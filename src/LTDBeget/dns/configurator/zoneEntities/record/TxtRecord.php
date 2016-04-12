<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\zoneEntities\record;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class TxtRecord
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record
 */
class TxtRecord extends Record
{
    /**
     * @var String
     */
    private $txtData;

    /**
     * TxtRecord constructor.
     *
     * @param Node   $node
     * @param int    $ttl
     * @param string $txtData
     */
    public function __construct(Node $node, int $ttl, string $txtData)
    {
        $this->txtData = $txtData;
        parent::__construct($node, eRecordType::TXT(), $ttl);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        $spited_string      = str_split($this->getTxtData(), 255);
        $spited_with_quotes = array_map(function ($value) {
            return "\"$value\"";
        }, $spited_string);
        $char_sets          = implode("\n", $spited_with_quotes);

        return $this->getMainRecordPart() . "$char_sets";
    }

    /**
     * @return String
     */
    public function getTxtData() : string
    {
        return $this->txtData;
    }

    /**
     * @param string $txtData
     * @return TxtRecord
     */
    public function setTxtData(string $txtData) : TxtRecord
    {
        return $this->setAttribute("txtData", $txtData);
    }

    /**
     * @return bool
     */
    public function validate() : bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (strlen($this->getTxtData()) === 0) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::EMPTY_TXT(), "txtData"));
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
            "TXTDATA" => $this->getTxtData()
        ];
    }
}