<?php

namespace MarcW\Weaver\Tests;

use MarcW\Weaver\Weaver;
use Icap\HtmlDiff\HtmlDiff;
use Mihaeu\HtmlFormatter;

/**
 * WeaverTest
 *
 * @author Marc Weistroff <marc@weistroff.net>
 */
class WeaverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideTestWeaver
     */
    public function testWeaver($body, array $fragments, $expected)
    {
        $weaver = new Weaver();
        $result = $weaver->weave($body, $fragments);
        $diff = new HtmlDiff($expected, $result, true);
        $out = $diff->outputDiff();
        //file_put_contents("result", HtmlFormatter::format($result));
        //file_put_contents("expected", HtmlFormatter::format($expected));
        $mod = $out->getModifications();
        $this->assertEquals(0, $mod['added']);
        $this->assertEquals(0, $mod['removed']);
        $this->assertEquals(0, $mod['changed']);
    }

    public function provideTestWeaver()
    {
        $files = glob(__DIR__.'/data/*.test');

        $tests = [];
        $part = '';
        foreach ($files as $file) {
            $test = ['body' => '', 'fragments' => [], 'result' => ''];
            $fh = fopen($file, 'r');
            while (($line = fgets($fh)) !== false) {
                $clean = trim($line);
                if ($clean == "--body--" || $clean == "--fragments--" || $clean == "--result--") {
                    $part = $clean;
                    continue;
                }
                if ($part == '--body--') {
                    $test['body'] .= $line;
                } else if ($part == '--fragments--') {
                    $test['fragments'][] = $line;
                } else if ($part == '--result--') {
                    $test['result'] .= $line;
                } else {
                    throw new \LogicException("The test file is malformed. It must contains a --body--, a --fragments--, and a --result-- section");
                }
            }

            $tests[] = [$test['body'], $test['fragments'], $test['result']];
            fclose($fh);
        }

        return $tests;
    }
}
