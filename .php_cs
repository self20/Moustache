<?php

$header = <<<EOF
EOF;

$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor', 'Resources', 'bin', 'var', 'web', 'app'])
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRules([
        '@Symfony' => true,

        'array_syntax' => ['syntax' => 'short'],

        'declare_strict_types' => true, // Force strict types declaration in all files. Requires PHP >= 7.0.
        'ereg_to_preg' => true, // Replace deprecated ereg regular expression functions with preg.
        'no_useless_return' => true, // There should not be an empty return statement at the end of a function.
        'linebreak_after_opening_tag' => true, // Ensure there is no code on the same line as the PHP open tag.
        'ordered_imports' => true, // Ordering use statements.
        'ordered_class_elements' => true, // Orders the elements of classes/interfaces/traits.
        'no_php4_constructor' => true, // Convert PHP4-style constructors to __construct. Warning! This could change code behavior.
        'phpdoc_order' => true, // Annotations in phpdocs should be ordered so that param annotations come first, then throws annotations, then return annotations.
        'strict_param' => true, // Functions should be used with $strict param set to true.
        'strict_comparison' => true, // Comparisons should be strict.
    ])
    ->setFinder($finder)
;
