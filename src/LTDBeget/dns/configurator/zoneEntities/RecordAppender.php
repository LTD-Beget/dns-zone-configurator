<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\zoneEntities;

use LTDBeget\dns\configurator\zoneEntities\record\AaaaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\ARecord;
use LTDBeget\dns\configurator\zoneEntities\record\CaaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\CnameRecord;
use LTDBeget\dns\configurator\zoneEntities\record\MxRecord;
use LTDBeget\dns\configurator\zoneEntities\record\NaptrRecord;
use LTDBeget\dns\configurator\zoneEntities\record\NsRecord;
use LTDBeget\dns\configurator\zoneEntities\record\PtrRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SoaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SrvRecord;
use LTDBeget\dns\configurator\zoneEntities\record\TxtRecord;

/**
 * Class RecordAppender
 *
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
     *
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
     * @param string   $address
     * @param int|null $ttl
     * @return ARecord
     */
    public function appendARecord(string $address, int $ttl = NULL) : ARecord
    {
        return new ARecord($this->node, $ttl ?? $this->defaultTtl, $address);
    }

    /**
     * @param string   $address
     * @param int|null $ttl
     * @return AaaaRecord
     */
    public function appendAaaaRecord(string $address, int $ttl = NULL) : AaaaRecord
    {
        return new AaaaRecord($this->node, $ttl ?? $this->defaultTtl, $address);
    }

    /**
     * @param string   $cname
     * @param int|null $ttl
     * @return CnameRecord
     */
    public function appendCNameRecord(string $cname, int $ttl = NULL) : CnameRecord
    {
        return new CnameRecord($this->node, $ttl ?? $this->defaultTtl, $cname);
    }

    /**
     * @param int      $preference
     * @param string   $exchange
     * @param int|null $ttl
     * @return MxRecord
     */
    public function appendMxRecord(int $preference, string $exchange, int $ttl = NULL) : MxRecord
    {
        return new MxRecord($this->node, $ttl ?? $this->defaultTtl, $preference, $exchange);
    }

    /**
     * @param string   $nsdName
     * @param int|null $ttl
     * @return NsRecord
     */
    public function appendNsRecord(string $nsdName, int $ttl = NULL) : NsRecord
    {
        return new NsRecord($this->node, $ttl ?? $this->defaultTtl, $nsdName);
    }

    /**
     * @param string   $ptrDName
     * @param int|null $ttl
     * @return PtrRecord
     */
    public function appendPtrRecord(string $ptrDName, int $ttl = NULL) : PtrRecord
    {
        return new PtrRecord($this->node, $ttl ?? $this->defaultTtl, $ptrDName);
    }

    /**
     * @param          $mName
     * @param          $rName
     * @param          $serial
     * @param          $refresh
     * @param          $retry
     * @param          $expire
     * @param          $minimum
     * @param int|null $ttl
     * @return SoaRecord
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
        int $ttl = NULL
    ) : SoaRecord
    {
        return new SoaRecord(
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
    }

    /**
     * @param int      $priority
     * @param int      $weight
     * @param int      $port
     * @param string   $target
     * @param int|null $ttl
     * @return SrvRecord
     */
    public function appendSrvRecord
    (
        int $priority,
        int $weight,
        int $port,
        string $target,
        int $ttl = NULL
    ) : SrvRecord
    {
        return new SrvRecord($this->node, $ttl ?? $this->defaultTtl, $priority, $weight, $port, $target);
    }

    /**
     * @param string   $txtData
     * @param int|null $ttl
     * @return TxtRecord
     */
    public function appendTxtRecord(string $txtData, int $ttl = NULL) : TxtRecord
    {
        return new TxtRecord($this->node, $ttl ?? $this->defaultTtl, $txtData);
    }

    /**
     * @param int      $flags
     * @param string   $tag
     * @param string   $value
     * @param int|NULL $ttl
     *
     * @return CaaRecord
     */
    public function appendCaaRecord(int $flags, string $tag, string $value, int $ttl = NULL) : CaaRecord
    {
        return new CaaRecord($this->node, $ttl ?? $this->defaultTtl, $flags, $tag, $value);
    }

    /**
     * @param int      $order
     * @param int      $preference
     * @param string   $flags
     * @param string   $services
     * @param string   $regexp
     * @param string   $replacement
     * @param int|NULL $ttl
     *
     * @return NaptrRecord
     */
    public function appendNaptrRecord(int $order, int $preference, string $flags, string $services,
                                      string $regexp, string $replacement, int $ttl = NULL) : NaptrRecord
    {
        return new NaptrRecord($this->node, $ttl ?? $this->defaultTtl,
            $order, $preference, $flags, $services, $regexp, $replacement);
    }
}
