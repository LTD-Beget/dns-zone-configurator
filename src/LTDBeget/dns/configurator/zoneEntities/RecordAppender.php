<?php
/**
 * @author: Viskov Sergey
 * @date: 05.04.16
 * @time: 1:34
 */

namespace LTDBeget\dns\configurator\zoneEntities;


use LTDBeget\dns\configurator\zoneEntities\record\AaaaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\ARecord;
use LTDBeget\dns\configurator\zoneEntities\record\CnameRecord;
use LTDBeget\dns\configurator\zoneEntities\record\MxRecord;
use LTDBeget\dns\configurator\zoneEntities\record\NsRecord;
use LTDBeget\dns\configurator\zoneEntities\record\PtrRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SoaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SrvRecord;
use LTDBeget\dns\configurator\zoneEntities\record\TxtRecord;

/**
 * Class RecordAppender
 * @package LTDBeget\dns\configurator\zoneEntities
 */
class RecordAppender
{
    /**
     * @var Node
     */
    protected $node;

    /**
     * Default value for ttl,
     * that will be set if no ttl given
     * @var int
     */
    protected $defaultTtl = 600;

    /**
     * @param Node $node
     */
    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    /**
     * @param string $address
     * @param int|null $ttl
     * @return RecordAppender
     */
    public function appendARecord(string $address, int $ttl = null) : RecordAppender
    {
        new ARecord($this->node, $ttl ?? $this->defaultTtl, $address);

        return $this;
    }

    /**
     * @param string $address
     * @param int|null $ttl
     * @return RecordAppender
     */
    public function appendAaaaRecord(string $address, int $ttl = null) : RecordAppender
    {
        new AaaaRecord($this->node, $ttl ?? $this->defaultTtl, $address);

        return $this;
    }

    /**
     * @param string $cname
     * @param int|null $ttl
     * @return RecordAppender
     */
    public function appendCNameRecord(string $cname, int $ttl = null) : RecordAppender
    {
        new CnameRecord($this->node, $ttl ?? $this->defaultTtl, $cname);

        return $this;
    }

    /**
     * @param int $preference
     * @param string $exchange
     * @param int|null $ttl
     * @return RecordAppender
     */
    public function appendMxRecord(int $preference, string $exchange, int $ttl = null) : RecordAppender
    {
        new MxRecord($this->node, $ttl ?? $this->defaultTtl, $preference, $exchange);

        return $this;
    }

    /**
     * @param string $nsdName
     * @param int|null $ttl
     * @return RecordAppender
     */
    public function appendNsRecord(string $nsdName, int $ttl = null) : RecordAppender
    {
        new NsRecord($this->node, $ttl ?? $this->defaultTtl, $nsdName);

        return $this;
    }

    /**
     * @param string $ptrDName
     * @param int|null $ttl
     * @return RecordAppender
     */
    public function appendPtrRecord(string $ptrDName, int $ttl = null) : RecordAppender
    {
        new PtrRecord($this->node, $ttl ?? $this->defaultTtl, $ptrDName);

        return $this;
    }

    /**
     * @param $mName
     * @param $rName
     * @param $serial
     * @param $refresh
     * @param $retry
     * @param $expire
     * @param $minimum
     * @param int|null $ttl
     * @return RecordAppender
     */
    public function appendSoaRecord
    (
        string $mName,
        string $rName,
        int $serial,
        int $refresh,
        int $retry,
        int $expire,
        int $minimum,
        int $ttl = null
    ) : RecordAppender
    {
        new SoaRecord(
            $this->node,
            $ttl ?? $this->defaultTtl,
            $mName,
            $rName,
            $serial,
            $refresh,
            $retry,
            $expire,
            $minimum
        );

        return $this;
    }

    /**
     * @param int $priority
     * @param int $weight
     * @param int $port
     * @param string $target
     * @param int|null $ttl
     * @return RecordAppender
     */
    public function appendSrvRecord
    (
        int $priority,
        int $weight,
        int $port,
        string $target,
        int $ttl = null
    ) : RecordAppender
    {
        new SrvRecord($this->node, $ttl ?? $this->defaultTtl, $priority, $weight, $port, $target);

        return $this;
    }

    /**
     * @param string $txtData
     * @param int|null $ttl
     * @return RecordAppender
     */
    public function appendTxtRecord(string $txtData, int $ttl = null) : RecordAppender
    {
        new TxtRecord($this->node, $ttl ?? $this->defaultTtl, $txtData);

        return $this;
    }
}