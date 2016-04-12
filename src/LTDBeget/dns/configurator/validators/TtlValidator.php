<?php
/**
 * @author: Viskov Sergey
 * @date  : 31.07.15
 * @time  : 16:36
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
    public static function validate(int $value)
    {
        return $value >= 10 && $value <= 86400;
    }
}
