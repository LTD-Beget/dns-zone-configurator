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
     * @return RecordsStore
     */
    public function remove(Record $record) : RecordsStore
    {
        foreach ($this->records as $key => $recordStored) {
            if ($recordStored === $record) {
                unset($this->records[$key]);
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
        $this->records[] = $record;

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
     * @return  \Generator|record\base\Record[]
     */
    public function iterate(eRecordType $type = NULL) : \Generator
    {
        foreach ($this->records as $record) {
            if (NULL === $type) {
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