<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\zoneEntities\record;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\validators\Ip6Validator;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class AaaaRecord
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record
 */
class AaaaRecord extends Record
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
        parent::__construct($node, eRecordType::AAAA(), $ttl);
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
     * @return AaaaRecord
     */
    public function setAddress(string $address) : AaaaRecord
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

        if (!Ip6Validator::validate($this->getAddress())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_IP_V6(), 'address'));
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