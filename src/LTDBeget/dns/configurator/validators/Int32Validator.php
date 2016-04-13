<?php
/**
 * @author: echern@beget.ru
 * @date  : 4/12/16
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class Int32Validator
 *
 * @package beget\lib\dns\lib\validators
 */
class Int32Validator
{
    /**
     * @param int $value
     * @return bool
     */
    public static function validate(int $value) : bool 
    {
        return $value >= 0 && $value <= 4294967295;
    }
}
