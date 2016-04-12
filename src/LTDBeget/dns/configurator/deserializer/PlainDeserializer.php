<?php
/**
 * @author: Viskov Sergey
 * @date: 05.04.16
 * @time: 1:12
 */

namespace LTDBeget\dns\configurator\deserializer;


use LTDBeget\dns\configurator\Zone;
use LTDBeget\dnsZoneParser\DnsZoneParser;

/**
 * Class PlainDeserializer
 * @package LTDBeget\dns\configurator\deserializer
 */
class PlainDeserializer
{
    /**
     * @param Zone $zone
     * @param string $data
     * @return Zone
     */
    public static function deserialize(Zone $zone, string $data) : Zone
    {
        return ArrayDeserializer::deserialize($zone, DnsZoneParser::parse($data));
    }
}