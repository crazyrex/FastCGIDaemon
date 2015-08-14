<?php

namespace PHPFastCGI\Test\FastCGIDaemon;

use PHPFastCGI\FastCGIDaemon\ApplicationFactory;

/**
 * Tests the daemon.
 */
class ApplicationFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the factory can create a Symfony console application
     */
    public function testCreateApplication()
    {
        $applicationFactory = new ApplicationFactory;

        $name        = 'foo';
        $description = 'bar';

        $application = $applicationFactory->createApplication(function () { }, $name, $description);

        $this->assertInstanceOf('Symfony\\Component\\Console\\Application', $application);
        $this->assertTrue($application->has($name));
        $this->assertEquals($description, $application->get($name)->getDescription());
    }
}