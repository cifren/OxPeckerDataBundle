<?php

namespace Earls\OxPeckerDataBundle\Tests\Defintion;

use Earls\OxPeckerDataBundle\Definition\Context;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $item = new Context();
        $args = array('test' => 'test');
        $item->setArgs($args);
        $this->assertEquals($args, $item->getArgs());
    }
}
