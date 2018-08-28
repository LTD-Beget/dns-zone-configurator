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

    public function testNodeNames()
    {
        $data = file_get_contents(__DIR__ . '/../dns/zones/zone_node_names.conf');
        $zone = Zone::fromString('voksiv.ru.', $data);
        $content = (string) $zone; // check for side effects on __toString()

        $expected = [
            '@',
            'www',
            '*',
            '123',
            '46.20.191.35',
            'null',
        ];
        PHPUnit_Framework_Assert::assertSame($expected, $zone->getNodeNames());
    }

    public function testNodeNamesNumeric()
    {
        $data = file_get_contents(__DIR__ . '/../dns/zones/zone_node_names_numeric.conf');
        $zone = Zone::fromString('voksiv.ru.', $data);
        $content = (string) $zone; // check for side effects on __toString()

        $expected = [
            '10',
            '2',
            '5',
        ];
        PHPUnit_Framework_Assert::assertSame($expected, $zone->getNodeNames());
    }
}
