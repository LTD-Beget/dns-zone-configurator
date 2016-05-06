<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\zoneEntities\record;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\validators\DnsZoneDomainNameValidator;
use LTDBeget\dns\configurator\validators\Int16Validator;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class SrvRecord
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record
 */
class SrvRecord extends Record
{
    /**
     * @var Int
     */
    protected $priority;
    /**
     * @var Int
     */
    protected $weight;
    /**
     * @var int
     */
    protected $port;
    /**
     * @var String
     */
    protected $target;

    /**
     * SrvRecord constructor.
     *
     * @param Node   $node
     * @param int    $ttl
     * @param int    $priority
     * @param int    $weight
     * @param int    $port
     * @param string $target
     */
    public function __construct(Node $node, int $ttl, int $priority, int $weight, int $port, string $target)
    {
        $this->priority = $priority;
        $this->weight   = $weight;
        $this->port     = $port;
        $this->target   = $target;
        parent::__construct($node, eRecordType::SRV(), $ttl);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getMainRecordPart() . ' ' .
        implode(' ', [
            $this->getPriority(),
            $this->getWeight(),
            $this->getPort(),
            $this->getTarget()
        ]);
    }

    /**
     * @return Int
     */
    public function getPriority() : int
    {
        return $this->priority;
    }

    /**
     * @param $priority
     * @return SrvRecord
     */
    public function setPriority(int $priority) : SrvRecord
    {
        return $this->setAttribute('priority', $priority);
    }

    /**
     * @return Int
     */
    public function getWeight() : int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     * @return SrvRecord
     */
    public function setWeight(int $weight) : SrvRecord
    {
        return $this->setAttribute('weight', $weight);
    }

    /**
     * @return int
     */
    public function getPort() : int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return SrvRecord
     */
    public function setPort(int $port) : SrvRecord
    {
        return $this->setAttribute('port', $port);
    }

    /**
     * @return String
     */
    public function getTarget() : string
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return SrvRecord
     */
    public function setTarget(string $target) : SrvRecord
    {
        return $this->setAttribute('target', $target);
    }

    /**
     * @return bool
     */
    public function validate() : bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (!DnsZoneDomainNameValidator::validate($this->getTarget())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_DOMAIN_NAME(), 'name'));
        }

        $attributes = [
            'priority' => $this->getPriority(),
            'weight'   => $this->getWeight(),
            'port'     => $this->getPort()
        ];

        foreach ($attributes as $atr => $value) {
            if (!Int16Validator::validate($value)) {
                $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_INT16(), $atr));
            }
        }

        /** @noinspection PhpInternalEntityUsedInspection */
        return parent::validate();
    }

    /**
     * @return array
     */
    protected function recordDataToArray() : array
    {
        return [
            'PRIORITY' => $this->getPriority(),
            'WEIGHT'   => $this->getWeight(),
            'PORT'     => $this->getPort(),
            'TARGET'   => $this->getTarget()
        ];
    }
}