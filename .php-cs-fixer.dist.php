<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'files',
        'public',
        'node_modules'
    ])
    ->notName('*Route.php')
    ->contains('/^\<\?php/');

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12'       => true,
    'strict_param' => false,
    'array_syntax' => ['syntax' => 'short']
])->setFinder($finder);
