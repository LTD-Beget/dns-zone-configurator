<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\deserializer;

use LTDBeget\dns\configurator\Zone;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class ArrayDeserializer
 *
 * @package LTDBeget\dns\configurator\deserializer
 */
class ArrayDeserializer
{
    /**
     * @param Zone  $zone
     * @param array $records
     * @return Zone
     */
    public static function deserialize(Zone $zone, array $records) : Zone
    {
        foreach ($records as $record) {
            self::appendRecord($zone, $record);
        }

        return $zone;
    }

    /**
     * Append result of parsing dnsZone file in zone object
     *
     * @param Zone  $zone
     * @param array $record result of parsing single record
     */
    protected static function appendRecord(Zone $zone, $record)
    {
        $node_mame      = $record['NAME'];
        $record_type    = eRecordType::get($record['TYPE']);
        $record_ttl     = $record['TTL'];
        $record_data    = $record['RDATA'];
        $node           = $zone->getNode($node_mame);
        $recordAppender = $node->getRecordAppender();

        switch ($record_type) {
            case eRecordType::A:
                $recordAppender->appendARecord(
                    $record_data['ADDRESS'],
                    $record_ttl);
                break;
            case eRecordType::AAAA:
                $recordAppender->appendAaaaRecord(
                    $record_data['ADDRESS'],
                    $record_ttl);
                break;
            case eRecordType::CNAME:
                $recordAppender->appendCNameRecord(
                    $record_data['CNAME'],
                    $record_ttl);
                break;
            case eRecordType::MX:
                $recordAppender->appendMxRecord(
                    $record_data['PREFERENCE'],
                    $record_data['EXCHANGE'],
                    $record_ttl);
                break;
            case eRecordType::NS:
                $recordAppender->appendNsRecord(
                    $record_data['NSDNAME'],
                    $record_ttl);
                break;
            case eRecordType::PTR:
                $recordAppender->appendPtrRecord(
                    $record_data['PTRDNAME'],
                    $record_ttl);
                break;
            case eRecordType::SOA:
                $recordAppender->appendSoaRecord(
                    $record_data['MNAME'],
                    $record_data['RNAME'],
                    $record_data['SERIAL'],
                    $record_data['REFRESH'],
                    $record_data['RETRY'],
                    $record_data['EXPIRE'],
                    $record_data['MINIMUM'],
                    $record_ttl
                );
                break;
            case eRecordType::SRV:
                $recordAppender->appendSrvRecord(
                    $record_data['PRIORITY'],
                    $record_data['WEIGHT'],
                    $record_data['PORT'],
                    $record_data['TARGET'],
                    $record_ttl
                );
                break;
            case eRecordType::TXT:
                $recordAppender->appendTxtRecord(
                    $record_data['TXTDATA'],
                    $record_ttl
                );
                break;
        }
    }

}