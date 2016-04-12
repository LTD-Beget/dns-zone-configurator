<?php
/**
 * @author: Viskov Sergey
 * @date: 05.04.16
 * @time: 0:48
 */

namespace LTDBeget\dns\enums;


use MabeEnum\Enum;

/**
 * Class eRecord
 * @package LTDBeget\dns\enums
 *
 * @method static eRecordType SOA()
 * @method static eRecordType A()
 * @method static eRecordType AAAA()
 * @method static eRecordType CNAME()
 * @method static eRecordType MX()
 * @method static eRecordType NS()
 * @method static eRecordType PTR()
 * @method static eRecordType TXT()
 * @method static eRecordType SRV()
 */
class eRecordType extends Enum
{
    const SOA   = "SOA";
    const A     = "A";
    const AAAA  = "AAAA";
    const CNAME = "CNAME";
    const MX    = "MX";
    const NS    = "NS";
    const PTR   = "PTR";
    const TXT   = "TXT";
    const SRV   = "SRV";
}