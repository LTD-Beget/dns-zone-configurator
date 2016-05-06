<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 3:10
 */

use LTDBeget\dns\configurator\Zone;

/**
 * Class ExampleTest
 */
class ExampleTest extends PHPUnit_Framework_TestCase
{
    public function testZone()
    {
        $data = file_get_contents(__DIR__ . '/../dns/zones/zone.conf');
        $zone = Zone::fromString('voksiv.ru.', $data);
        $zone->toArray();
        $zone = Zone::fromString('voksiv.ru.', (string) $zone);
        $zone->validate();
    }
}
