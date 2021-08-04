<?php

namespace Uptime;

use Uptime\System\SystemTable;

/**
 * @group system
 */
class SystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider             commandProvider
     * @expectedException        Uptime\System\UnsupportedSystemException
     * @expectedExceptionMessage Unknown" is not supported
     */
    public function testUnsupportedSystemException($method)
    {
        (new System('Unknown'))->{ $method }();
    }

    public function commandProvider()
    {
        return [
            ['getBoottime'],
            [ 'getUptime' ]
        ];
    }

    /**
     * @test
     * @dataProvider systemIdentifierProvider
     */
    public function tetsCurrentSystem($system_identifier)
    {
        if ($system_identifier !== PHP_OS) {
            $this->markTestSkipped("Skipped {$system_identifier} related test.");
        }
        $system = new System($system_identifier);
        $this->assertInstanceOf('\Uptime\Uptime', $system->getUptime());
        $this->assertInstanceOf('\Uptime\Boottime', $system->getBoottime());
        $this->assertGreaterThan(0, $system->getUptime()->getRaw());
        $this->assertGreaterThan(0, $system->getBoottime()->getRaw());
        $this->assertInternalType('float', uptime( $system_identifier ));
        $this->assertTimestamp(boottime( $system_identifier ));
    }

    public function systemIdentifierProvider()
    {
        $systems = array_keys(SystemTable::getMap());
        array_walk($systems, function (&$group) { $group =  [$group]; });

        return $systems;
    }

    public function testCreateFromContext()
    {
        $this->assertEquals(PHP_OS, System::createFromContext()->getSystem());
    }

    protected function assertTimestamp($timestamp)
    {
        $this->assertTrue(
            ((int) $timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX)
        );
    }
}
