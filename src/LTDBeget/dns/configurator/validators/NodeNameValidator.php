<?php
/**
 * @author: Viskov Sergey
 * @date  : 29.07.15
 * @time  : 19:30
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class NodeNameValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class NodeNameValidator
{
    const RESOURCE_RECORD_NAME = "/^([a-z0-9-_]+\.)*[a-z0-9-_]+\.?$/i";

    /**
     * @param $value
     * @return bool
     */
    public static function validate($value)
    {
        if ($value === '@') {
            return true;
        }

        if ($value === '*') {
            return true;
        }

        $value = preg_replace('/^\*\./', '', $value);

        if (preg_match(self::RESOURCE_RECORD_NAME, $value)) {
            return true;
        }

        return false;
    }
}
