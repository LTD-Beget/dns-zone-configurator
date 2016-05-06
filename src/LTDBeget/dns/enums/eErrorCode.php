<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\enums;

use MabeEnum\Enum;

/**
 * Class ErrorCode
 *
 * @package beget\lib\dns\lib\errors
 * @method static eErrorCode WRONG_ORIGIN()
 * @method static eErrorCode WRONG_NODE_NAME()
 * @method static eErrorCode CONFLICT_RECORD_TYPES_ERROR()
 * @method static eErrorCode SOA_ERROR()
 * @method static eErrorCode WRONG_NS_IN_ROOT()
 * @method static eErrorCode SOA_RECORD_NOT_IN_ROOT()
 * @method static eErrorCode MULTIPLE_CNAME_ERROR()
 * @method static eErrorCode NO_NS_IN_ROOT()
 * @method static eErrorCode WRONG_TTL()
 * @method static eErrorCode WRONG_IP_V4()
 * @method static eErrorCode WRONG_IP_V6()
 * @method static eErrorCode WRONG_DOMAIN_NAME()
 * @method static eErrorCode WRONG_INT16()
 * @method static eErrorCode WRONG_PTR_NAME()
 * @method static eErrorCode EMPTY_TXT()
 * @method static eErrorCode WRONG_INT32()
 */
class eErrorCode extends Enum
{
    /**
     * Error codes for DNS validation errors.
     * Change current codes are forbidden.
     * Only add new.
     */
    const WRONG_ORIGIN                = 1;
    const WRONG_NODE_NAME             = 2;
    const CONFLICT_RECORD_TYPES_ERROR = 3;
    const SOA_ERROR                   = 4;
    const WRONG_NS_IN_ROOT            = 5;
    const SOA_RECORD_NOT_IN_ROOT      = 6;
    const MULTIPLE_CNAME_ERROR        = 7;
    const NO_NS_IN_ROOT               = 8;
    const WRONG_TTL                   = 9;
    const WRONG_IP_V4                 = 10;
    const WRONG_IP_V6                 = 11;
    const WRONG_DOMAIN_NAME           = 12;
    const WRONG_INT16                 = 13;
    const WRONG_PTR_NAME              = 14;
    const EMPTY_TXT                   = 15;
    const WRONG_INT32                 = 16;

    /**
     * Preset text for known error codes
     *
     * @var array
     */
    protected static $textForCode = [
        self::WRONG_ORIGIN                => 'Wrong origin value.',
        self::WRONG_NODE_NAME             => 'Wrong node name value',
        self::CONFLICT_RECORD_TYPES_ERROR => 'Conflict types records in node (A,NS,CNAME)',
        self::SOA_ERROR                   => 'Multiple SOA or no SOA',
        self::WRONG_NS_IN_ROOT            => 'Wrong ns records in root',
        self::SOA_RECORD_NOT_IN_ROOT      => 'SOA record not in root',
        self::MULTIPLE_CNAME_ERROR        => 'Multiple cname record',
        self::NO_NS_IN_ROOT               => 'No NS records in root',
        self::WRONG_TTL                   => 'Wrong value for TTL',
        self::WRONG_IP_V4                 => 'Value must be IP v4',
        self::WRONG_IP_V6                 => 'Value must be IP v6',
        self::WRONG_DOMAIN_NAME           => 'Value must be correct domain name',
        self::WRONG_INT16                 => 'Value must be int 16 bit',
        self::WRONG_PTR_NAME              => 'Wrong PTR record name',
        self::EMPTY_TXT                   => 'Empty txt record',
        self::WRONG_INT32                 => 'Value must be int 32 bit',
    ];

    /**
     * @return string
     */
    public function getText() : string
    {
        return self::$textForCode[(int) $this->getValue()];
    }
}