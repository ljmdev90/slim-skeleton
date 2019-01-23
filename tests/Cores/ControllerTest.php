<?php

namespace Application\Tests\Cores;

use PHPUnit\Framework\TestCase;
use Application\Cores\Controller;
use Slim\App;

class ControllerTest extends TestCase
{

    private $controller = null;

    public function setUp()
    {
        $app = new App();
        $container = $app->getContainer();
        $this->controller = $this->getMockForAbstractClass('Application\Cores\Controller', [$container]);
    }

    public function testControllerHasAttributeContainer()
    {
        $this->assertAttributeInstanceOf('Slim\Container', 'container', $this->controller);
    }
}