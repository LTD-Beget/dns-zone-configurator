<?php
/**
 * @author: echern@beget.ru
 * @date  :   02.11.15
 */

namespace LTDBeget\dns\configurator\validators;


/**
 * Class Int16Validator
 *
 * @package beget\lib\dns\lib\validators
 */
class Int16Validator
{
    /**
     * @param $value
     *
     * @return bool
     */
    public static function validate(int $value)
    {
        return $value >= 0 && $value <= 65535;
    }
}
