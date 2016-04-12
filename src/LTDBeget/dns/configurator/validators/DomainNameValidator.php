<?php
/**
 * @author: Viskov Sergey
 * @date  : 31.07.15
 * @time  : 19:03
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class DomainNameValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class DomainNameValidator
{
    const PATTERN = '/^([a-z0-9-_]+\.)*[a-z0-9-_]+$/';

    /**
     * @param $value
     *
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
