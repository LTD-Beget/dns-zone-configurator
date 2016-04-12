<?php
/**
 * @author: Viskov Sergey
 * @date: 05.04.16
 * @time: 3:45
 */

namespace LTDBeget\dns\enums;

use MabeEnum\Enum;

/**
 * Class eRecordNotification
 * @package LTDBeget\dns\enums
 *
 * @method static eRecordNotification ADD()
 * @method static eRecordNotification REMOVE()
 * @method static eRecordNotification CHANGE()
 */
class eRecordNotification extends Enum
{
    const ADD    = "ADD";
    const REMOVE = "REMOVE";
    const CHANGE = "CHANGE";
}