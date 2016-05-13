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
        if (in_array($hostname, ['@', '*'])) {
            return true;
        }

        if (strpos($hostname, '@') !== false && strlen($hostname) > 1) {
            return false;
        }

        $hostname = preg_replace('/^\*\./', '', $hostname); // wild card allowed in hostname

        $hostnameValidation = HostnameValidator::validate($hostname);

        if ($hostnameValidation) {
            return true;
        }
        
        $ipValidation = Ip4Validator::validate($hostname);

        if ($ipValidation) {
            return true;
        }

        return false;
    }
}
