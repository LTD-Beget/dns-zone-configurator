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
                    (string) $record_data['ADDRESS'],
                    (int) $record_ttl);
                break;
            case eRecordType::AAAA:
                $recordAppender->appendAaaaRecord(
                    (string) $record_data['ADDRESS'],
                    (int) $record_ttl);
                break;
            case eRecordType::CNAME:
                $recordAppender->appendCNameRecord(
                    (string) $record_data['CNAME'],
                    (int) $record_ttl);
                break;
            case eRecordType::MX:
                $recordAppender->appendMxRecord(
                    (int) $record_data['PREFERENCE'],
                    (string) $record_data['EXCHANGE'],
                    (int) $record_ttl);
                break;
            case eRecordType::NS:
                $recordAppender->appendNsRecord(
                    (string) $record_data['NSDNAME'],
                    (int) $record_ttl);
                break;
            case eRecordType::PTR:
                $recordAppender->appendPtrRecord(
                    (string) $record_data['PTRDNAME'],
                    (int) $record_ttl);
                break;
            case eRecordType::SOA:
                $recordAppender->appendSoaRecord(
                    (string) $record_data['MNAME'],
                    (string) $record_data['RNAME'],
                    (int) $record_data['SERIAL'],
                    (int) $record_data['REFRESH'],
                    (int) $record_data['RETRY'],
                    (int) $record_data['EXPIRE'],
                    (int) $record_data['MINIMUM'],
                    (int) $record_ttl
                );
                break;
            case eRecordType::SRV:
                $recordAppender->appendSrvRecord(
                    (int) $record_data['PRIORITY'],
                    (int) $record_data['WEIGHT'],
                    (int) $record_data['PORT'],
                    (string) $record_data['TARGET'],
                    (int) $record_ttl
                );
                break;
            case eRecordType::TXT:
                $recordAppender->appendTxtRecord(
                    (string) $record_data['TXTDATA'],
                    (int) $record_ttl
                );
                break;
            case eRecordType::CAA:
                $recordAppender->appendCaaRecord(
                    (int) $record_data['FLAGS'],
                    (string) $record_data['TAG'],
                    (string) $record_data['VALUE'],
                    (int) $record_ttl
                );
                break;
            case eRecordType::NAPTR:
                $recordAppender->appendNaptrRecord(
                    (int) $record_data['ORDER'],
                    (int) $record_data['PREFERENCE'],
                    (string) $record_data['FLAGS'],
                    (string) $record_data['SERVICES'],
                    (string) $record_data['REGEXP'],
                    (string) $record_data['REPLACEMENT'],
                    (int) $record_ttl
                );
                break;
        }
    }

}