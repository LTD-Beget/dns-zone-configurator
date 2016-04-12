<?php
/**
 * @author: Viskov Sergey
 * @date: 05.04.16
 * @time: 0:45
 */

namespace LTDBeget\dns\configurator\zoneEntities;


use BadMethodCallException;
use InvalidArgumentException;
use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\validators\CnameNumberCheck;
use LTDBeget\dns\configurator\validators\ConflictTypesValidator;
use LTDBeget\dns\configurator\validators\NodeNameValidator;
use LTDBeget\dns\configurator\Zone;
use LTDBeget\dns\configurator\zoneEntities\record\AaaaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\ARecord;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\configurator\zoneEntities\record\CnameRecord;
use LTDBeget\dns\configurator\zoneEntities\record\MxRecord;
use LTDBeget\dns\configurator\zoneEntities\record\NsRecord;
use LTDBeget\dns\configurator\zoneEntities\record\PtrRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SoaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SrvRecord;
use LTDBeget\dns\configurator\zoneEntities\record\TxtRecord;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordNotification;
use LTDBeget\dns\enums\eRecordType;

/**
 *
 * Class Node
 * @package LTDBeget\dns\configurator\zoneEntities
 *
 * @method ARecord iterateA()
 * @method AaaaRecord iterateAaaa()
 * @method CnameRecord iterateCname()
 * @method MxRecord iterateMx()
 * @method NsRecord iterateNs()
 * @method PtrRecord iteratePtr()
 * @method SoaRecord iterateSoa()
 * @method SrvRecord iterateSrv()
 * @method TxtRecord iterateTxt()
 */
class Node
{
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
     * @param $name
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
     * @internal
     * @param $name
     * @param $arguments
     * @return Record[]
     */
    public function __call($name, $arguments)
    {
        try {
            $type = eRecordType::get(mb_strtoupper(str_replace("iterate", "", $name)));

            return $this->iterateRecords($type);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException("Method {$name} not found");
        }
    }

    /**
     * @param eRecordType|null $type
     * @return Record[]
     */
    public function iterateRecords(eRecordType $type = null)
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
     * @param Record $record
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