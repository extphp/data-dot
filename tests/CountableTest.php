<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ExtPHP\DataDot\Dot;

class CountableTest extends TestCase
{
    public function testItInstantiates()
    {
        $dot = new Dot();

        $this->assertInstanceOf(\Countable::class, $dot);
    }

    public function testItCanCount()
    {
        $data = $this->buildData();

        $dot = new Dot($data);

        $this->assertCount(3, $dot);

        $dot->set('alpha.beta', 'gamma');
        $this->assertCount(4, $dot);
    }

    /**
     * construct a complex data structure
     */
    protected function buildData()
    {
        return json_decode(file_get_contents(__DIR__ . '/data/basic.json'));
    }
}
