<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:31 PM
 */

namespace LTDBeget\dns\configurator\traits;

use BadMethodCallException;
use InvalidArgumentException;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class RecordsIterateTrait
 *
 * @package LTDBeget\dns\configurator\tarits
 */
trait RecordsIterateTrait
{
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
     * @param eRecordType $type
     * @return mixed
     */
    abstract public function iterateRecords(eRecordType $type);
}