<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\deserializer;

use LTDBeget\dns\configurator\Zone;
use LTDBeget\dns\Tokenizer;

/**
 * Class PlainDeserializer
 *
 * @package LTDBeget\dns\configurator\deserializer
 */
class PlainDeserializer
{
    /**
     * @param Zone   $zone
     * @param string $data
     * @return Zone
     */
    public static function deserialize(Zone $zone, string $data) : Zone
    {
        return ArrayDeserializer::deserialize($zone, Tokenizer::tokenize($data));
    }
}