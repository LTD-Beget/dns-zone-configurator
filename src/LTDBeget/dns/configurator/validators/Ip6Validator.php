<?php
/**
 * @author: Viskov Sergey
 * @date  : 31.07.15
 * @time  : 18:59
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class Ip6Validator
 *
 * @package beget\lib\dns\lib\validators
 */
class Ip6Validator
{
    /**
     * @param $value
     *
     * @return bool
     */
    public static function validate(string $value)
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
}
