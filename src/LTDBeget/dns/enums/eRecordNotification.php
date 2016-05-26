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
 * @method static eRecordNotification ADD()
 * @method static eRecordNotification REMOVE()
 */
class eRecordNotification extends Enum
{
    const ADD    = 'ADD';
    const REMOVE = 'REMOVE';
}