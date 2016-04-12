<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\zoneEntities;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\traits\RecordsIterateTrait;
use LTDBeget\dns\configurator\validators\CnameNumberCheck;
use LTDBeget\dns\configurator\validators\ConflictTypesValidator;
use LTDBeget\dns\configurator\validators\NodeNameValidator;
use LTDBeget\dns\configurator\Zone;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordNotification;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class Node
 *
 * @package LTDBeget\dns\configurator\zoneEntities
 */
class Node
{
    use RecordsIterateTrait;

    /**
     * @var Zone
     */
    private $zone;
    /**
     * @var string
     */
    private $name;
    /**
     * @var RecordAppender
     */
    private $recordAppender;
    /**
     * @var RecordsStore
     */
    private $recordsStore;
    /**
     * @var RecordsStore
     */
    private $removedRecordsStore;

    /**
     * @param Zone $zone
     * @param      $name
     */
    public function __construct(Zone $zone, string $name)
    {
        $this->zone                = $zone;
        $this->name                = mb_strtolower(trim($name));
        $this->recordAppender      = new RecordAppender($this);
        $this->recordsStore        = new RecordsStore();
        $this->removedRecordsStore = new RecordsStore();
    }

    /**
     * @return RecordAppender
     */
    public function getRecordAppender() : RecordAppender
    {
        return $this->recordAppender;
    }

    /**
     * @return Record[]
     */
    public function iterateRemoved()
    {
        foreach ($this->getRemovedRecordsStore()->iterate() as $record) {
            yield $record;
        }
    }

    /**
     * @return RecordsStore
     */
    protected function getRemovedRecordsStore()
    {
        return $this->removedRecordsStore;
    }

    /**
     * @param eRecordType|null $type
     * @return Record[]
     */
    public function iterateRecords(eRecordType $type = NULL)
    {
        foreach ($this->getRecordsStore()->iterate($type) as $record) {
            yield $record;
        }
    }

    /**
     * @return RecordsStore
     */
    protected function getRecordsStore() : RecordsStore
    {
        return $this->recordsStore;
    }

    /**
     * @internal
     * @param Record              $record
     * @param eRecordNotification $notification
     * @return Node
     */
    public function notify(Record $record, eRecordNotification $notification) : Node
    {
        switch ($notification) {
            case eRecordNotification::ADD:
                $this->recordsStore->append($record);
                break;
            case eRecordNotification::REMOVE:
                $this->recordsStore->remove($record);
                $this->removedRecordsStore->append($record);
                break;
            case eRecordNotification::CHANGE:
                $this->getRecordsStore()->change($record);
                break;
        }

        return $this;
    }

    public function sort()
    {
        $this->getRecordsStore()->sort();
    }

    /**
     * @internal
     * Full validate zone via build in validators
     * @return bool
     */
    public function validate() : bool
    {
        $errorsStore = $this->getZone()->getErrorsStore();

        if (!ConflictTypesValidator::validate($this)) {
            $errorsStore->add(ValidationError::makeNodeError($this, eErrorCode::CONFLICT_RECORD_TYPES_ERROR()));
        }

        if (!CnameNumberCheck::validate($this)) {
            $errorsStore->add(ValidationError::makeNodeError($this, eErrorCode::MULTIPLE_CNAME_ERROR()));
        }

        if (!NodeNameValidator::validate($this->getName())) {
            $errorsStore->add(ValidationError::makeNodeError($this, eErrorCode::WRONG_NODE_NAME()));
        }

        foreach ($this->iterateRecords() as $record) {
            /** @noinspection PhpInternalEntityUsedInspection */
            $record->validate();
        }

        return !$errorsStore->isHasErrors();
    }

    /**
     * @return Zone
     */
    public function getZone() : Zone
    {
        return $this->zone;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
}