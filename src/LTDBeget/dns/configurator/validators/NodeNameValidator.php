<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class NodeNameValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class NodeNameValidator
{
    /**
     * @param $value
     * @return bool
     */
    public static function validate($value)
    {
        if ($value === '*') {
            return true;
        }

        return DomainNameValidator::validate($value);
    }
}
