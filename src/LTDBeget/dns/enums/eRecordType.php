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
 * @method static static SOA()
 * @method static static A()
 * @method static static AAAA()
 * @method static static CNAME()
 * @method static static MX()
 * @method static static NS()
 * @method static static PTR()
 * @method static static TXT()
 * @method static static SRV()
 * @method static static CAA()
 * @method static static NAPTR()
 * @method string getValue()
 * @psalm-immutable
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