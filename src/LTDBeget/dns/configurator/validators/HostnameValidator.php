<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

use Zend\Validator\Hostname;

/**
 * Class HostnameValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class HostnameValidator
{
    /**
     * @var HostnameValidator
     */
    static private $instance;

    /**
     * @var Hostname
     */
    private        $validator;

    /**
     * DomainNameValidator constructor.
     */
    private function __construct() {
        $this->validator = new Hostname([
            'allow'         => Hostname::ALLOW_DNS | Hostname::ALLOW_LOCAL,
            'useIdnCheck'   => false,
            'useTldCheck'   => false
        ]);
    }

    /**
     * @param $hostname
     * @return bool
     */
    public static function validate(string $hostname) : bool
    {
        /** @see http://stackoverflow.com/questions/2180465/can-domain-name-subdomains-have-an-underscore-in-it */
        $hostname = preg_replace('/^_/', '', $hostname);
        $hostname = preg_replace('/\._/', '.', $hostname);

        /** @see https://www.ietf.org/rfc/rfc1033.txt */
        /** @see https://www.ietf.org/rfc/rfc1912.txt */
        $hostname = str_replace('_', '-', $hostname);

        foreach (explode('.', $hostname) as $hostPiece) {
            /**
             * @see https://www.ietf.org/rfc/rfc3696.txt
             * Each DNS label must not exceed 63 characters and should consist of any combination of alphabetic characters
             */
            if (strlen($hostPiece) > 63)
            {
                return false;
            }
        }

        return self::getInstance()->validator->isValid($hostname);
    }

    /**
     * @return HostnameValidator
     */
    static private function getInstance()
    {
        return self::$instance ?? self::$instance = new static();
    }
}
