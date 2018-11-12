<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ExtPHP\DataDot\Dot;

class DotTest extends TestCase
{
    public function testItCanGetData()
    {
        $data = $this->buildData();

        $dot = new Dot($data);

        $this->assertEquals('bar', $dot->get('foo'));
        $this->assertEquals('one', $dot->get('elements.0'));
        $this->assertEquals('second', $dot->get('objects.1.name'));
    }

    public function testItCanGetDefaults()
    {
        $data = $this->buildData();

        $dot = new Dot($data);

        $this->assertEquals('baz', $dot->get('foo.bar', 'baz'));
        $this->assertEquals('baz', $dot->get('foo.baz.bar', 'baz'));
        $this->assertEquals('default', $dot->get('elements.5', 'default'));
        $this->assertEquals('default', $dot->get('elements.key', 'default'));
        $this->assertEquals('default', $dot->get('objects.1.value', 'default'));
        $this->assertEquals(null, $dot->get('unavailable'));
    }

    public function testItCanReturnAllData()
    {
        $data = $this->buildData();

        $dot = new Dot($data);

        $this->assertEquals($data, $dot->get());
    }


    /**
     * construct a complex data structure
     */
    protected function buildData()
    {
        return json_decode(file_get_contents(__DIR__ . '/data/basic.json'));
    }
}
