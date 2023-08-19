<?php

declare(strict_types=1);

/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:2.16.0|configurator
 * you can change this configuration by importing this file.
 */

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@DoctrineAnnotation' => true,
        'concat_space' => ['spacing' => 'one'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'declare_strict_types' => true,
        'phpdoc_summary' => false,
        'native_function_invocation' => ['include' => ['@internal'], 'scope' => 'namespaced', 'strict' => true],
        'self_accessor' => false,
        'ordered_traits' => false,
        'types_spaces' => ['space' => 'single'],
        'class_definition' => ['single_line' => false],
        'phpdoc_align' => ['align' => 'left'],
    ])
    ->setUsingCache(false)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in(__DIR__)
    );