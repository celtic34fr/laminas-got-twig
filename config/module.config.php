<?php

namespace LaminasGOT;

use LaminasGOT_TWIG\Service\Factory\LF3GotServicesFactory;
use LaminasGOT\Service\LF3GotServices;

return [
    'service_manager' => [
        'factories' => [
            LF3GotServices::class => LF3GotServicesFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];