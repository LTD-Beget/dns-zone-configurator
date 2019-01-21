<?php

namespace LTDBeget\dns\configurator\zoneEntities\record;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\validators\DnsZoneDomainNameValidator;
use LTDBeget\dns\configurator\validators\Int16Validator;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class NaptrRecord
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record
 */
class NaptrRecord extends Record
{
    /**
     * @var Int
     */
    protected $order;
    /**
     * @var Int
     */
    protected $preference;
    /**
     * @var int
     */
    protected $flags;
    /**
     * @var string
     */
    protected $services;

    /**
     * @var string
     */
    protected $regexp;

    /**
     * @var string
     */
    protected $replacement;

    /**
     * NaptrRecord constructor.
     *
     * @param Node   $node
     * @param int    $ttl
     * @param int    $order
     * @param int    $preference
     * @param string $flags
     * @param string $services
     * @param string $regexp
     * @param string $replacement
     */
    public function __construct(Node $node, int $ttl,
                                int $order, int $preference, string $flags, string $services,
                                string $regexp, string $replacement)
    {
        $this->order       = $order;
        $this->preference  = $preference;
        $this->flags       = $flags;
        $this->services    = $services;
        $this->regexp      = $regexp;
        $this->replacement = $replacement;
        parent::__construct($node, eRecordType::NAPTR(), $ttl);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getMainRecordPart() . ' ' .
            implode(' ', [
                $this->getOrder(),
                $this->getPreference(),
                $this->getFlags(),
                $this->getServices(),
                $this->getRegexp(),
                $this->getReplacement()
            ]);
    }

    /**
     * @return Int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Int $order
     *
     * @return Record
     */
    public function setOrder(Int $order)
    {
        return $this->setAttribute('order', $order);
    }

    /**
     * @return Int
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * @param Int $preference
     *
     * @return Record
     */
    public function setPreference(Int $preference)
    {
        return $this->setAttribute('preference', $preference);
    }

    /**
     * @return string
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param string $flags
     *
     * @return Record
     */
    public function setFlags(string $flags)
    {
        return $this->setAttribute('flags', $flags);
    }

    /**
     * @return string
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param string $services
     *
     * @return Record
     */
    public function setServices(string $services)
    {
        return $this->setAttribute('services', $services);
    }

    /**
     * @return string
     */
    public function getRegexp()
    {
        return $this->regexp;
    }

    /**
     * @param string $regexp
     *
     * @return Record
     */
    public function setRegexp(string $regexp)
    {
        return $this->setAttribute('regexp', $regexp);
    }

    /**
     * @return string
     */
    public function getReplacement()
    {
        return $this->replacement;
    }

    /**
     * @param string $replacement
     *
     * @return Record
     */
    public function setReplacement(string $replacement)
    {
        return $this->setAttribute('replacement', $replacement);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (!DnsZoneDomainNameValidator::validate($this->getReplacement())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_DOMAIN_NAME(), 'replacement'));
        }

        $integerAttributes = [
            'order'      => $this->getOrder(),
            'preference' => $this->getPreference(),
        ];

        foreach ($integerAttributes as $atr => $value) {
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
    protected function recordDataToArray(): array
    {
        return [
            'ORDER'       => $this->getOrder(),
            'PREFERENCE'  => $this->getPreference(),
            'FLAGS'       => $this->getFlags(),
            'SERVICES'    => $this->getServices(),
            'REGEXP'      => $this->getRegexp(),
            'REPLACEMENT' => $this->getReplacement(),
        ];
    }
}