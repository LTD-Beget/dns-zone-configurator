<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\errors;

use LTDBeget\dns\configurator\Zone;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;

/**
 * Class ValidationError
 *
 * @package LTDBeget\dns\configurator\errors
 */
class ValidationError
{
    /**
     * @var Zone
     */
    private $zone;
    /**
     * @var Node
     */
    private $node;
    /**
     * @var Record
     */
    private $record;
    /**
     * @var eErrorCode
     */
    private $errorCode;
    /**
     * @var String
     */
    private $checkedAttribute;

    /**
     * closed. Make instance only via static methods
     */
    private function __construct()
    {
    }

    /**
     * @param Zone       $zone
     * @param eErrorCode $errorCode
     * @return ValidationError
     */
    public static function makeZoneError(Zone $zone, eErrorCode $errorCode) : ValidationError
    {
        $error            = new self;
        $error->zone      = $zone;
        $error->errorCode = $errorCode;

        return $error;
    }

    /**
     * @param Node       $node
     * @param eErrorCode $errorCode
     * @return ValidationError
     */
    public static function makeNodeError(Node $node, eErrorCode $errorCode) : ValidationError
    {
        $error            = new self;
        $error->zone      = $node->getZone();
        $error->node      = $node;
        $error->errorCode = $errorCode;

        return $error;
    }

    /**
     * @param Record     $record
     * @param eErrorCode $errorCode
     * @param            $checked_atribute
     * @return ValidationError
     */
    public static function makeRecordError(Record $record, eErrorCode $errorCode, $checked_atribute) : ValidationError
    {
        $error                   = new self;
        $error->zone             = $record->getNode()->getZone();
        $error->node             = $record->getNode();
        $error->record           = $record;
        $error->errorCode        = $errorCode;
        $error->checkedAttribute = $checked_atribute;

        return $error;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $errorArray              = [];
        $errorArray['code']      = $this->errorCode->getValue();
        $errorArray['errorText'] = $this->errorCode->getText();
        $errorArray['origin']    = $this->zone->getOrigin();

        if (NULL !== $this->node) {
            $errorArray['node'] = $this->node->getName();
        }

        if (NULL !== $this->record) {
            $errorArray['recordData']       = $this->record->toArray();
            $errorArray['checkedAttribute'] = $this->checkedAttribute;
        }

        return $errorArray;
    }

    /**
     * @return Zone
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @return Node
     */
    public function getNode() : Node
    {
        if(! $this->isHasNode()) {
            throw new \RuntimeException('ValidationError has no Node');
        }
        return $this->node;
    }

    /**
     * @return bool
     */
    public function isHasNode() : bool
    {
        return NULL !== $this->node;
    }

    /**
     * @return Record
     */
    public function getRecord() : Record
    {
        if(! $this->isHasRecord()) {
            throw new \RuntimeException('ValidationError has no Record');
        }
        return $this->record;
    }

    /**
     * @return bool
     */
    public function isHasRecord() : bool
    {
        return NULL !== $this->record;
    }

    /**
     * @return eErrorCode
     */
    public function getErrorCode(): eErrorCode
    {
        return $this->errorCode;
    }
}