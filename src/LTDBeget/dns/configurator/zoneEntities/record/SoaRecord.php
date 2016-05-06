<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\zoneEntities\record;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\validators\Int32Validator;
use LTDBeget\dns\configurator\validators\SoaNotInRootValidator;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class SoaRecord
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record
 */
class SoaRecord extends Record
{
    /**
     * @var String
     */
    protected $mName;
    /**
     * @var String
     */
    protected $rName;
    /**
     * @var Int
     */
    protected $serial;
    /**
     * @var Int
     */
    protected $refresh;
    /**
     * @var Int
     */
    protected $retry;
    /**
     * @var Int
     */
    protected $expire;
    /**
     * @var Int
     */
    protected $minimum;

    /**
     * SoaRecord constructor.
     *
     * @param Node   $node
     * @param int    $ttl
     * @param string $mName
     * @param string $rName
     * @param int    $serial
     * @param int    $refresh
     * @param int    $retry
     * @param int    $expire
     * @param int    $minimum
     */
    public function __construct
    (
        Node $node,
        int $ttl,
        string $mName,
        string $rName,
        int $serial,
        int $refresh,
        int $retry,
        int $expire,
        int $minimum
    )
    {
        $this->mName   = $mName;
        $this->rName   = $rName;
        $this->serial  = $serial;
        $this->refresh = $refresh;
        $this->retry   = $retry;
        $this->expire  = $expire;
        $this->minimum = $minimum;
        parent::__construct($node, eRecordType::SOA(), $ttl);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getMainRecordPart() . ' ' . implode(' ', [
            $this->getMName(),
            $this->getRName(),
            $this->getSerial(),
            $this->getRefresh(),
            $this->getRetry(),
            $this->getExpire(),
            $this->getMinimum()
        ]);
    }

    /**
     * @return string
     */
    public function getMName() : string
    {
        return $this->mName;
    }

    /**
     * @param string $mName
     * @return SoaRecord
     */
    public function setMName(string $mName) : SoaRecord
    {
        return $this->setAttribute('mName', $mName);
    }

    /**
     * @return string
     */
    public function getRName() : string
    {
        return $this->rName;
    }

    /**
     * @param string $rName
     * @return SoaRecord
     */
    public function setRName(string $rName) : SoaRecord
    {
        return $this->setAttribute('rName', $rName);
    }

    /**
     * @return int
     */
    public function getSerial() : int
    {
        return $this->serial;
    }

    /**
     * @param int $serial
     * @return SoaRecord
     */
    public function setSerial(int $serial) : SoaRecord
    {
        return $this->setAttribute('serial', $serial);
    }

    /**
     * @return int
     */
    public function getRefresh() : int
    {
        return $this->refresh;
    }

    /**
     * @param int $refresh
     * @return SoaRecord
     */
    public function setRefresh(int $refresh) : SoaRecord
    {
        return $this->setAttribute('refresh', $refresh);
    }

    /**
     * @return int
     */
    public function getRetry() : int
    {
        return $this->retry;
    }

    /**
     * @param int $retry
     * @return SoaRecord
     */
    public function setRetry(int $retry) : SoaRecord
    {
        return $this->setAttribute('retry', $retry);
    }

    /**
     * @return int
     */
    public function getExpire() : int
    {
        return $this->expire;
    }

    /**
     * @param int $expire
     * @return SoaRecord
     */
    public function setExpire(int $expire) : SoaRecord
    {
        return $this->setAttribute('expire', $expire);
    }

    /**
     * @return int
     */
    public function getMinimum() : int
    {
        return $this->minimum;
    }

    /**
     * @param int $minimum
     * @return SoaRecord
     */
    public function setMinimum(int $minimum) : SoaRecord
    {
        return $this->setAttribute('minimum', $minimum);
    }

    /**
     * @return bool
     */
    public function validate() : bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (!SoaNotInRootValidator::validate($this)) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::SOA_RECORD_NOT_IN_ROOT(), 'name'));
        }

        $attributes = [
            'serial'  => $this->getSerial(),
            'refresh' => $this->getRefresh(),
            'retry'   => $this->getRetry(),
            'expire'  => $this->getExpire(),
            'minimum' => $this->getMinimum()
        ];

        foreach ($attributes as $atr => $value) {
            if (!Int32Validator::validate($value)) {
                $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_INT32(), $atr));
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
            'MNAME'   => $this->getMName(),
            'RNAME'   => $this->getRName(),
            'SERIAL'  => $this->getSerial(),
            'REFRESH' => $this->getRefresh(),
            'RETRY'   => $this->getRetry(),
            'EXPIRE'  => $this->getExpire(),
            'MINIMUM' => $this->getMinimum()
        ];
    }
}