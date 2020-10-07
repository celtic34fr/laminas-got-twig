<?php

namespace LaminasGOT_TWIG\TwigExtensions;

use LaminasGOT_TWIG\TwigExtensions\TokenParser\SwitchTokenParser;
use Twig\Extension\AbstractExtension;

class SwitchTwigExtension extends AbstractExtension
{

    public function getTokenParsers()
    {
        return array(
            new SwitchTokenParser(),
            // unaccepted token (fabpot) update maxgalbu to work with Twig >= 1.12 version CraftCMS
        );
    }

    public function getName() : string
    {
        return 'switch_extension';
    }
}