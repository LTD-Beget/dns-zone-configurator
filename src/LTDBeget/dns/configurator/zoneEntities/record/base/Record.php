<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\zoneEntities\record\base;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\validators\TtlValidator;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordNotification;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class Record
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record\base
 */
abstract class Record
{
    /**
     * @var Node
     */
    private $node;
    /**
     * eRecord $type
     */
    private $type;
    /**
     * @var Int
     */
    private $ttl;
    /**
     * @var Bool
     */
    private $isRemoved = false;
    /**
     * @var string
     */
    private $hash;

    /**
     * Record constructor.
     *
     * @param Node        $node
     * @param eRecordType $type
     * @param int         $ttl
     */
    public function __construct(Node $node, eRecordType $type, int $ttl)
    {
        $this->node = $node;
        $this->type = $type;
        $this->ttl  = $ttl;

        $this->refreshHash();
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->getNode()->notify($this, eRecordNotification::ADD());
    }

    /**
     * rebuild hash of record
     */
    protected function refreshHash()
    {
        $this->hash = md5(((string) $this) . $this->getNode()->getZone()->getOrigin());
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Marks this record as needed to remove
     */
    public function remove()
    {
        $this->isRemoved = true;
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->getNode()->notify($this, eRecordNotification::REMOVE());
    }

    /**
     * @return Bool
     */
    public function isRemoved()
    {
        return $this->isRemoved;
    }

    /**
     * @param Record $record
     * @return bool
     */
    public function isEqual(Record $record) : bool
    {
        return $record->getHash() === $this->getHash();
    }

    /**
     * Full hash of record (must be unique)
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'NAME'  => $this->getNode()->getName(),
            'TTL'   => $this->getTtl(),
            'TYPE'  => (string) $this->getType(),
            'RDATA' => $this->recordDataToArray()
        ];
    }

    /**
     * @return Int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param int $value
     */
    public function setTtl(int $value)
    {
        $this->setAttribute('ttl', $value);
    }

    /**
     * @return eRecordType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    abstract protected function recordDataToArray() : array;

    /**
     * @param $name
     * @param $value
     * @return Record
     */
    protected function setAttribute($name, $value) : self
    {
        $this->{$name} = $value;
        $this->refreshHash();

        return $this;
    }

    /**
     * @return string
     */
    abstract public function __toString() : string;

    /**
     * @internal
     * @return bool
     */
    public function validate() : bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (!TtlValidator::validate($this->getTtl())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_TTL(), 'ttl'));
        }

        return !$errorStorage->isHasErrors();
    }

    /**
     * @return string
     */
    protected function getMainRecordPart() : string
    {
        return "{$this->getNode()->getName()} {$this->getTtl()} IN {$this->getType()}";
    }
}