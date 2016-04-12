<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 6:25 PM
 */

namespace LTDBeget\dns\configurator\validators;
use LTDBeget\dns\configurator\zoneEntities\Node;

/**
 * Class NoSoaRecordValidator
 *
 * @package LTDBeget\dns\configurator\validators
 */
class NoSoaRecordValidator
{
    /**
     * @param Node $node
     * @return bool
     */
    public static function validate(Node $node)
    {
        $records = [];
        foreach ($node->iterateSoa() as $record) {
            $records[] = $record;
        }

        return count($records) === 0;
    }
}