<?php

namespace MarcW\Weaver\Twig;

use MarcW\Weaver\Weaver;

class WeaverExtension extends \Twig_Extension
{
    private $weaver;

    public function __construct()
    {
        $weaver = new Weaver();
    }

    public function getFunctions()
    {
        return [
            new \Twig_Simple_Function('weave', array($this->weaver, 'weave'))
        ];
    }

    public function getName()
    {
        return 'weaver_extension';
    }
}
