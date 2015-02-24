<?php

namespace MarcW\Weaver\Tests;

use MarcW\Weaver\Weaver;

/**
 * WeaverTest
 *
 * @author Marc Weistroff <marc@weistroff.net>
 */
class WeaverTest extends \PHPUnit_Framework_TestCase
{
    public function testWeaver()
    {
        $weaver = new Weaver();
        $body = "<p>foo</p><p>bar</p>";
        $fragments = ['<img src="foobar" />'];
        $result = $weaver->weave($body, $fragments);
        $this->assertEquals('<p>foo</p><img src="foobar" /><p>bar</p>', $result);
    }

    /**
     * @dataProvider provideCreatePattern
     */
    public function testCreatePattern($blocks, $fragments, $pattern)
    {
        $this->assertEquals($pattern, Weaver::createPattern($blocks, $fragments));
    }

    public function provideCreatePattern()
    {
        return [
            [0, 3, '---'],
            [3, 0, '***'],
            [1, 1, '*-'],
            [1, 2, '*--'],
            [2, 1, '*-*'],
            [4, 1, '**-**'],
            [4, 3, '*-*-*-*'],
            [4, 4, '*-*-*-*-'],
            [5, 1, '**-***'],
            [5, 4, '*-*-*-*-*'],
            [7, 1, '***-****'],
            [8, 1, '****-****'],
            [8, 7, '*-*-*-*-*-*-*-*'],
            [8, 8, '*-*-*-*-*-*-*-*-'],
            [2, 14, '*-*-------------'],
        ];
    }
}
