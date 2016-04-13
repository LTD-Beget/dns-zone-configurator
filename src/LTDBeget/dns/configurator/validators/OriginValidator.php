<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

use Zend\Validator\Hostname;

/**
 * Class OriginValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class OriginValidator
{
    /**
     * @var OriginValidator
     */
    static private $instance = null;

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
     * @return OriginValidator
     */
    static private function getInstance() {
        return self::$instance ?? self::$instance = new static();
    }

    /**
     * @param $hostname
     * @return bool
     */
    public static function validate(string $hostname) : bool
    {
        return self::getInstance()->validator->isValid($hostname);
    }
}
