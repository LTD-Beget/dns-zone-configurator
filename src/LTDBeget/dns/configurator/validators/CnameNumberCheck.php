<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

use LTDBeget\dns\configurator\zoneEntities\Node;

/**
 * Class CnameNumberCheck
 *
 * @package beget\lib\dns\lib\validators
 */
class CnameNumberCheck
{
    /**
     * @param Node $node
     * @return bool
     */
    public static function validate(Node $node) : bool 
    {
        $records = [];
        foreach ($node->iterateCname() as $record) {
            $records[] = $record;
        }

        return count($records) <= 1;
    }
}