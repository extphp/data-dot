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

    public function testItValidatesKeys()
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = $this->buildData();
        $dot = new Dot($data);

        $this->assertEquals('one', $dot->get('objects..name'));        
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

        $this->assertEquals(json_decode(json_encode($data), true), $dot->get());
    }

    public function testItCanSet()
    {
        $data = $this->buildData();

        $dot = new Dot($data);
        
        $dot->set('a.b.c', 'foo');

        $this->assertEquals('foo', $dot->get('a.b.c'));
        $this->assertEquals(['c' => 'foo'], $dot->get('a.b'));
    }

    public function testItCanUpdate()
    {
        $data = $this->buildData();

        $dot = new Dot($data);

        $dot->set('a.b.c', 'foo');
        $dot->set('a.b.c', 'bar');

        $this->assertEquals('bar', $dot->get('a.b.c'));
        $this->assertEquals(['c' => 'bar'], $dot->get('a.b'));
    }

    public function testItCanAppendToArray()
    {
        $data = $this->buildData();

        $dot = new Dot($data);

        $dot->set('a.b.c', 'foo');
        $dot->set('a.b.d', 'bar');

        $this->assertEquals('foo', $dot->get('a.b.c'));
        $this->assertEquals('bar', $dot->get('a.b.d'));
        $this->assertEquals(['c' => 'foo', 'd' => 'bar'], $dot->get('a.b'));
    }

    public function testItCanReplaceData()
    {
        $data = $this->buildData();

        $dot = new Dot($data);
        $dot->set(null, ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $dot->get());
    }

    public function testItCanDelete()
    {
        $data = $this->buildData();
        $dot = new Dot($data);

        $dot->delete('foo');
        $this->assertFalse($dot->has('foo'));
    }

    public function testItCanChangeSeparator()
    {
        $data = $this->buildData();
        $dot = new Dot($data);

        $dot->setSeparator('_');
        $this->assertEquals('second', $dot->get('objects_1_name'));

        $dot->setSeparator(' ');
        $this->assertEquals('second', $dot->get('    objects 1 name    '));
    }

    public function testItCanDot()
    {
        $data = $this->buildData();
        $dot = new Dot($data);

        $child = $dot->dot('objects');

        $this->assertInstanceOf(Dot::class, $child);
        $this->assertEquals('second', $child->get('1.name'));

        $clone = $dot->dot();
        $this->assertInstanceOf(Dot::class, $clone);
        $this->assertEquals('second', $clone->get('objects.1.name'));
    }

    /**
     * construct a complex data structure
     */
    protected function buildData()
    {
        return json_decode(file_get_contents(__DIR__ . '/data/basic.json'));
    }
}
