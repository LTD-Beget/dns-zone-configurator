<?php

namespace LTDBeget\dns\configurator\zoneEntities\record;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\validators\Ip4Validator;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class ARecord
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record
 */
class ARecord extends Record
{
    /**
     * @var string
     */
    protected $address;

    /**
     * ARecord constructor.
     *
     * @param Node   $node
     * @param int    $ttl
     * @param string $address
     */
    public function __construct(Node $node, $ttl, string $address)
    {
        $this->address = $address;
        parent::__construct($node, eRecordType::A(), $ttl);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getMainRecordPart() . ' ' . $this->getAddress();
    }

    /**
     * @return string
     */
    public function getAddress() : string
    {
        return $this->address;
    }

    /**
     * @param $address
     * @return static
     */
    public function setAddress($address)
    {
        return $this->setAttribute('address', $address);
    }

    /**
     * @internal
     * @return bool
     */
    public function validate() : bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (!Ip4Validator::validate($this->getAddress())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_IP_V4(), 'address'));
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
            'ADDRESS' => $this->getAddress()
        ];
    }
}