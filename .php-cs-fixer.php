<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/Tests',
        __DIR__ . '/App',
        __DIR__ . '/Bootstrap',
    ])
    ->exclude('v1/Views')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
