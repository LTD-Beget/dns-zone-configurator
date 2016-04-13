<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

use LTDBeget\dns\configurator\zoneEntities\Node;

/**
 * Class SoaNumberCheck
 *
 * @package beget\lib\dns\lib\validators
 */
class SoaNumberCheck
{
    /**
     * @param Node $node
     * @return bool
     */
    public static function validate(Node $node) : bool 
    {
        $records = [];
        foreach ($node->iterateSoa() as $record) {
            $records[] = $record;
        }

        return count($records) === 1;
    }
}