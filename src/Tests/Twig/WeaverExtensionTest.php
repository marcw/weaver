<?php

namespace MarcW\Weaver\Tests\Twig;

use MarcW\Weaver\Twig\WeaverExtension;

/**
 * WeaverExtensionTest.
 *
 * @author Marc Weistroff <marc@weistroff.net> 
 */
class WeaverExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testTwigExtension()
    {
        $loader = new \Twig_Loader_Array([
            'index.html' => "{{ weave('<p>foobar</p><p>barfoo</p>', ['<img />']) }}",
        ]);
        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new WeaverExtension());

        $out = $twig->render('index.html');
        $this->assertEquals('<p>foobar</p><img /><p>barfoo</p>', $out);
    }
}
