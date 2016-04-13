<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class DnsZoneDomainNameValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class DnsZoneDomainNameValidator
{
    /**
     * @param $hostname
     * @return bool
     */
    public static function validate(string $hostname) : bool 
    {
        $hostname = preg_replace('/^\*\./', '', $hostname);
        if(in_array($hostname, ['@', '*', '.'])) {
            return true;
        }

        return OriginValidator::validate($hostname);
    }
}
