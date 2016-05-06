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
 * Class MxRecord
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record
 */
class MxRecord extends Record
{
    /**
     * @var Int
     */
    protected $preference;
    /**
     * @var String
     */
    protected $exchange;

    /**
     * MxRecord constructor.
     *
     * @param Node   $node
     * @param int    $ttl
     * @param int    $preference
     * @param string $exchange
     */
    public function __construct(Node $node, $ttl, int $preference, string $exchange)
    {
        $this->preference = $preference;
        $this->exchange   = $exchange;
        parent::__construct($node, eRecordType::MX(), $ttl);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getMainRecordPart() . " {$this->getPreference()} {$this->getExchange()}";
    }

    /**
     * @return Int
     */
    public function getPreference() : int
    {
        return $this->preference;
    }

    /**
     * @param int $preference
     * @return MxRecord
     */
    public function setPreference(int $preference) : MxRecord
    {
        return $this->setAttribute('preference', $preference);
    }

    /**
     * @return String
     */
    public function getExchange() : string
    {
        return $this->exchange;
    }

    /**
     * @param string $exchange
     * @return MxRecord
     */
    public function setExchange(string $exchange) : MxRecord
    {
        return $this->setAttribute('exchange', $exchange);
    }

    /**
     * @internal
     * @return bool
     */
    public function validate() : bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (!Int16Validator::validate($this->getPreference())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_INT16(), 'preference'));
        }

        if (!DnsZoneDomainNameValidator::validate($this->getExchange())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_DOMAIN_NAME(), 'exchange'));
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
            'EXCHANGE'   => $this->getExchange(),
            'PREFERENCE' => $this->getPreference()
        ];
    }
}