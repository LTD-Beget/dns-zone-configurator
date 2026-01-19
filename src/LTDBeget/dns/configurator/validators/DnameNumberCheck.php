<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

use LTDBeget\dns\configurator\zoneEntities\Node;

/**
 * Class DnameNumberCheck
 *
 * @package beget\lib\dns\lib\validators
 */
class DnameNumberCheck
{
    /**
     * @param Node $node
     * @return bool
     */
    public static function validate(Node $node) : bool 
    {
        $records = [];
        foreach ($node->iterateDname() as $record) {
            $records[] = $record;
        }

        return count($records) <= 1;
    }
}