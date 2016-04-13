<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
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
     * @return bool
     */
    public static function validate(string $value) : bool 
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
}
