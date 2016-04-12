<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class DomainNameValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class DomainNameValidator
{
    const PATTERN = '/^([a-z0-9-_]+\.)*[a-z0-9-_]+\.?$/i';
    
    /**
     * @param $value
     * @return bool
     */
    public static function validate(string $value)
    {
        if ($value === "@") {
            return true;
        }

        $value = preg_replace('/\.$/', '', $value);

        if (preg_match(self::PATTERN, $value)) {
            return true;
        }

        return false;
    }
}
