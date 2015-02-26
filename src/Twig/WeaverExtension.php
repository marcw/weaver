<?php

namespace MarcW\Weaver\Twig;

use MarcW\Weaver\Weaver;

class WeaverExtension extends \Twig_Extension
{
    private $weaver;

    public function __construct()
    {
        $this->weaver = new Weaver();
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('weave', [$this->weaver, 'weave'], array('is_safe' => ['html']))
        ];
    }

    public function getName()
    {
        return 'weaver_extension';
    }
}
