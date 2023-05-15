<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\enums;

use MabeEnum\Enum;

/**
 * Class eRecordNotification
 *
 * @package LTDBeget\dns\enums
 * @method static static ADD()
 * @method static static REMOVE()
 * @method string getValue()
 * @psalm-immutable
 */
class eRecordNotification extends Enum
{
    const ADD    = 'ADD';
    const REMOVE = 'REMOVE';
}