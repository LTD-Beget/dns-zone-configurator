<?php
/**
 * @author: Viskov Sergey
 * @date  : 31.07.15
 * @time  : 18:46
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class Ip4Validator
 *
 * @package beget\lib\dns\lib\validators
 */
class Ip4Validator
{
    /**
     * @param $value
     *
     * @return bool
     */
    public static function validate(string $value)
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }
}
