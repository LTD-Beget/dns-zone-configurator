<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/11/16
 * @time  : 7:15 PM
 */

namespace LTDBeget\dns\configurator\validators;

use LTDBeget\dns\configurator\Zone;


/**
 * Class SoaNumberCheck
 *
 * @package beget\lib\dns\lib\validators
 */
class SoaNumberCheck
{
    /**
     * @param Zone $zone
     * @return bool
     */
    public static function validate(Zone $zone)
    {
        $records = [];
        foreach ($zone->iterateSoa() as $record) {
            $records[] = $record;
        }

        return count($records) === 1;
    }
}