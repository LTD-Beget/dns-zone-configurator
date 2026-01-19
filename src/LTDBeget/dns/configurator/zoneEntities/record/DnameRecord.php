<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\zoneEntities\record;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\validators\DnsZoneDomainNameValidator;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class CnameRecord
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record
 */
class DnameRecord extends Record
{
    /**
     * @var string
     */
    protected $dname;

    /**
     * CnameRecord constructor.
     *
     * @param Node   $node
     * @param int    $ttl
     * @param string $dname
     */
    public function __construct(Node $node, $ttl, string $dname)
    {
        $this->dname = $dname;
        parent::__construct($node, eRecordType::DNAME(), $ttl);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getMainRecordPart() . ' ' . $this->getDname();
    }

    /**
     * @return string
     */
    public function getDname() : string
    {
        return $this->dname;
    }

    /**
     * @param $dname
     * @return DnameRecord
     */
    public function setDname($dname) : DnameRecord
    {
        return $this->setAttribute('dname', $dname);
    }

    /**
     * @internal
     * @return bool
     */
    public function validate() : bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (!DnsZoneDomainNameValidator::validate($this->getDname())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_DOMAIN_NAME(), 'dname'));
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
            'DNAME' => $this->getDname()
        ];
    }
}