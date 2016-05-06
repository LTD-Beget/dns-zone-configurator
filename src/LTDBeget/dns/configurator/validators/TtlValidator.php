<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class TtlValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class TtlValidator
{
    /**
     * @param int $value
     * @return bool
     */
    public static function validate(int $value) : bool 
    {
        return $value >= 0 && $value <= 2147483647;
    }
}
