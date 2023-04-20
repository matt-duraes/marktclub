<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'node_modules',
        'resources',
        'views',
        'routes'
    ]);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12'       => true,
    'strict_param' => false,
    'array_syntax' => ['syntax' => 'short']
])->setFinder($finder);
