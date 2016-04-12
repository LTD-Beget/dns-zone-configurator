<?php
use LTDBeget\dns\configurator\Zone;

/**
 * @author: Viskov Sergey
 * @date: 12.04.16
 * @time: 3:10
 */
class ExampleTest extends PHPUnit_Framework_TestCase
{
    public function testZone()
    {
        $data = file_get_contents(__DIR__ . "/../dns/zones/zone.conf");
        $zone = Zone::fromString("voksiv.ru.", $data);
        $zone->toArray();
        $zone->__toString();
        $zone->validate();
    }
}
