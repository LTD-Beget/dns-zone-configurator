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
        $hostname = preg_replace('/^\*\./', '', $hostname); // wild card allowed in hostname

        /** @see http://stackoverflow.com/questions/2180465/can-domain-name-subdomains-have-an-underscore-in-it */
        $hostname = preg_replace('/^_/', '', $hostname);
        $hostname = preg_replace('/\._/', '.', $hostname);

        /** @see https://www.ietf.org/rfc/rfc1033.txt */
        /** @see https://www.ietf.org/rfc/rfc1912.txt */
        $hostname = preg_replace('/_/', '-', $hostname);

        if(in_array($hostname, ['@', '*', '.'])) {
            return true;
        }

        return HostnameValidator::validate($hostname);
    }
}
