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
class CnameRecord extends Record
{
    /**
     * @var string
     */
    protected $cname;

    /**
     * CnameRecord constructor.
     *
     * @param Node   $node
     * @param int    $ttl
     * @param string $cname
     */
    public function __construct(Node $node, $ttl, string $cname)
    {
        $this->cname = $cname;
        parent::__construct($node, eRecordType::CNAME(), $ttl);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getMainRecordPart() . ' ' . $this->getCname();
    }

    /**
     * @return string
     */
    public function getCname() : string
    {
        return $this->cname;
    }

    /**
     * @param $cname
     * @return CnameRecord
     */
    public function setCname($cname) : CnameRecord
    {
        return $this->setAttribute('cname', $cname);
    }

    /**
     * @internal
     * @return bool
     */
    public function validate() : bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (!DnsZoneDomainNameValidator::validate($this->getCname())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_DOMAIN_NAME(), 'cname'));
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
            'CNAME' => $this->getCname()
        ];
    }
}