<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:31 PM
 */

namespace LTDBeget\dns\configurator\traits;

use BadMethodCallException;
use InvalidArgumentException;
use LTDBeget\dns\configurator\zoneEntities\record\AaaaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\ARecord;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\configurator\zoneEntities\record\CnameRecord;
use LTDBeget\dns\configurator\zoneEntities\record\CaaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\MxRecord;
use LTDBeget\dns\configurator\zoneEntities\record\NsRecord;
use LTDBeget\dns\configurator\zoneEntities\record\PtrRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SoaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SrvRecord;
use LTDBeget\dns\configurator\zoneEntities\record\TxtRecord;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class RecordsIterateTrait
 *
 * @package LTDBeget\dns\configurator\tarits
 * @method ARecord[] iterateA()
 * @method AaaaRecord[] iterateAaaa()
 * @method CnameRecord[] iterateCname()
 * @method MxRecord[] iterateMx()
 * @method NsRecord[] iterateNs()
 * @method PtrRecord[] iteratePtr()
 * @method SoaRecord[] iterateSoa()
 * @method SrvRecord[] iterateSrv()
 * @method TxtRecord[] iterateTxt()
 * @method CaaRecord[] iterateCaa()
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
            $type = eRecordType::get(mb_strtoupper(str_replace('iterate', '', $name)));

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