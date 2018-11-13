<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ExtPHP\DataDot\Dot;

class ArrayAccessTest extends TestCase
{
    public function testItInstantiates()
    {
        $dot = new Dot();

        $this->assertInstanceOf(\ArrayAccess::class, $dot);
    }

    public function testItCanGetData()
    {
        $data = $this->buildData();

        $dot = new Dot($data);

        $this->assertEquals('bar', $dot['foo']);
        $this->assertEquals('one', $dot['elements.0']);
        $this->assertEquals('one', $dot['elements'][0]);
        $this->assertEquals('second', $dot['objects.1.name']);
        $this->assertEquals('second', $dot['objects.1']['name']);
        $this->assertEquals('second', $dot['objects'][1]['name']);

        $this->assertNull($dot['unset']['path']);
    }
    /**
     * construct a complex data structure
     */
    protected function buildData()
    {
        return json_decode(file_get_contents(__DIR__ . '/data/basic.json'));
    }
}
