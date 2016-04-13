<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

/**
 * Class PtrValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class PtrValidator
{
    const IP4_HOST_RE = '/\.in-addr\.arpa\.$/';
    const IP6_FULL_RE = '/^([0-9a-f]\.){32}ip6\.arpa\.$/';

    /**
     * @param string $value
     * @return bool
     */
    public static function validate(string $value) : bool 
    {
        if (preg_match(self::IP4_HOST_RE, $value)) {
            $flip_ip = preg_replace(self::IP4_HOST_RE, '', $value);
            $ip4     = implode('.', array_reverse(explode('.', $flip_ip)));
            if (filter_var($ip4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                return true;
            }
        } elseif (preg_match(self::IP6_FULL_RE, $value)) {
            return true;
        }

        return false;
    }
}
