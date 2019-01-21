<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\enums;

use MabeEnum\Enum;

/**
 * Class eRecord
 *
 * @package LTDBeget\dns\enums
 * @method static eRecordType SOA()
 * @method static eRecordType A()
 * @method static eRecordType AAAA()
 * @method static eRecordType CNAME()
 * @method static eRecordType MX()
 * @method static eRecordType NS()
 * @method static eRecordType PTR()
 * @method static eRecordType TXT()
 * @method static eRecordType SRV()
 * @method static eRecordType CAA()
 * @method static eRecordType NAPTR()
 */
class eRecordType extends Enum
{
    const SOA   = 'SOA';
    const A     = 'A';
    const AAAA  = 'AAAA';
    const CNAME = 'CNAME';
    const MX    = 'MX';
    const NS    = 'NS';
    const PTR   = 'PTR';
    const TXT   = 'TXT';
    const SRV   = 'SRV';
    const CAA   = 'CAA';
    const NAPTR = 'NAPTR';
}