<?php

namespace igorw;

class GetInTest extends \PHPUnit_Framework_TestCase
{
    /** @dataProvider provideGetIn */
    function testGetIn($expected, $array, $keys, $default = null)
    {
        $this->assertSame($expected, get_in($array, $keys, $default));
    }

    function provideGetIn()
    {
        $single = array('key' => 'value');
        $nested = array('foo' => array('bar' => array('baz' => 'value')));
        $list   = array(array('name' => 'foo'));

        return array(
            array('value', $single, array('key'), 'default'),
            array(array('bar' => array('baz' => 'value')), $nested, array('foo'), 'default'),
            array(array('baz' => 'value'), $nested, array('foo', 'bar'), 'default'),
            array('value', $nested, array('foo', 'bar', 'baz'), 'default'),
            array('default', $nested, array('foo', 'bar', 'bang'), 'default'),
            array('default', $nested, array('non_existent'), 'default'),
            array(null, $nested, array('non_existent')),
            array($nested, $nested, array(), 'default'),
            array($nested, $nested, array()),
            array('foo', $list, array(0, 'name')),
            array(null, array('foo' => null), array('foo'), 'err'),
            array(null, array('foo' => null), array('foo', 'bar')),
            array('default', $single, array('foo', 'value'), 'default'),
        );
    }

    /** @dataProvider provideUpdateIn */
    function testUpdateIn($expected, $array, $keys, $fn, array $args = array())
    {
        $this->assertSame($expected, call_user_func_array('igorw\update_in', array_merge(array($array, $keys, $fn), $args)));
    }

    function provideUpdateIn()
    {
        $nested = array('foo' => array('bar' => array('baz' => 40)));
        $single = array('key' => 'value');

        $add = function ($a, $b) { return $a + $b; };
        $identity = function ($x) { return $x; };

        return array(
            array(array('foo' => array('bar' => array('baz' => 42))), $nested, array('foo', 'bar', 'baz'), $add, array(2)),
            array(array('foo' => array('bar' => array('baz' => 40))), $nested, array('foo', 'bar', 'baz'), $identity),
            array(array('foo' => array('bar' => array('baz' => 40))), $nested, array(), $identity),
            array(array('key' => 'value'), $single, array(), $identity),
            array(array('foo' => null), array('foo' => null), array('foo'), $identity),
        );
    }

    /**
     * @dataProvider provideInvalidUpdateIn
     * @expectedException InvalidArgumentException
     */
    function testInvalidUpdateIn($expected, $array, $keys, $fn, array $args = array())
    {
        $this->assertSame($expected, call_user_func_array('igorw\update_in', array_merge(array($array, $keys, $fn), $args)));
    }

    function provideInvalidUpdateIn()
    {
        $nested = array('foo' => array('bar' => array('baz' => 40)));

        $identity = function ($x) { return $x; };

        return array(
            array(array('foo' => array('bar' => array('baz' => 40))), $nested, array('non_existent'), $identity),
            array(array('foo' => array('bar' => array('baz' => 40))), $nested, array('non', 'existent'), $identity),
            array(array('foo' => array('bar' => array('baz' => 40))), $nested, array('foo', 'bar', 'baz', 'qux'), $identity),
        );
    }

    /** @dataProvider provideAssocIn */
    function testAssocIn($expected, $array, $keys, $value)
    {
        $this->assertSame($expected, assoc_in($array, $keys, $value));
    }

    function provideAssocIn()
    {
        $nested = array('foo' => array('bar' => array('baz' => 'value')));
        $single = array('key' => 'value');
        $empty  = array();

        return array(
            array(array('foo' => array('bar' => array('baz' => 'new value'))), $nested, array('foo', 'bar', 'baz'), 'new value'),
            array(array('key' => 'value'), $single, array(), 'new value'),
            array(array('foo' => array('bar' => 'new value')), $empty, array('foo', 'bar'), 'new value'),
            array(array('foo' => 'new value'), array('foo' => null), array('foo'), 'new value'),
            array(array('foo' => array('bar' => 'new value')), array('foo' => null), array('foo', 'bar'), 'new value'),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUpdateInRequiresCallback()
    {
        update_in(array(), array(), new \stdClass);
    }
}
