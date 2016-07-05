<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\validators;

use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class ConflictTypesValidator
 *
 * @package beget\lib\dns\lib\validators
 */
class ConflictTypesValidator
{
    /**
     * @param Node $node
     * @return bool
     */
    public static function validate(Node $node) : bool 
    {
        $conflictRecordsTypes = [];
        $allRecords           = [];
        foreach ($node->iterateRecords() as $record) {
            if (in_array((string) $record->getType(), [
                eRecordType::CNAME,
                eRecordType::A,
                eRecordType::NS
            ])) {
                $conflictRecordsTypes[] = (string) $record->getType();
            }
            $allRecords[] = (string) $record->getType();

        }
        $conflictRecordsTypes  = array_unique($conflictRecordsTypes);
        $conflictRecordsAmount = count($conflictRecordsTypes);

        if (count($allRecords) > 1 && in_array(eRecordType::CNAME, $allRecords)) {
            return false;
        }

        return $node->getName() === '@' ? $conflictRecordsAmount <= 2 : $conflictRecordsAmount <= 1;
    }
}