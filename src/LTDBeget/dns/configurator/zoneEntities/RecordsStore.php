<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\zoneEntities;

use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class RecordsStore
 *
 * @package LTDBeget\dns\configurator\zoneEntities
 */
class RecordsStore
{
    /**
     * @var Record[]
     */
    protected $records = [];

    /**
     * @param Record $record
     * @return bool
     */
    public function isContain(Record $record) : bool
    {
        return isset($this->records[$record->getHash()]);
    }

    /**
     * @param Record $record
     * @return RecordsStore
     */
    public function remove(Record $record) : RecordsStore
    {
        unset($this->records[$record->getHash()]);

        return $this;
    }

    /**
     * @param Record $record
     * @return RecordsStore
     */
    public function change(Record $record) : RecordsStore
    {
        foreach ($this->records as $hash => $recordStored) {
            if ($recordStored->isEqual($record)) {
                unset($this->records[$hash]);
                $this->append($record);
                break;
            }
        }

        return $this;
    }

    /**
     * @param Record $record
     * @return RecordsStore
     */
    public function append(Record $record) : RecordsStore
    {
        $this->records[$record->getHash()] = $record;

        return $this;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->records);
    }

    /**
     * @param eRecordType $type
     * @return record\base\Record[]
     */
    public function iterate(eRecordType $type = NULL)
    {
        foreach ($this->records as $record) {
            if (is_null($type)) {
                yield $record;
            } elseif ($record->getType()->is($type)) {
                yield $record;
            }
        }
    }

    public function sort()
    {
        usort($this->records, function (Record $a, Record $b) {
            if ($a->getType()->is(eRecordType::SOA)) {
                return -1;
            } elseif ($b->getType()->is(eRecordType::SOA)) {
                return 1;
            }

            return strcmp($a->getType(), $b->getType());
        });
    }
}