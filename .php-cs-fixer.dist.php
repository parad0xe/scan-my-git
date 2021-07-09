<?php
require 'vendor/autoload.php';

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_trailing_comma_in_singleline_array' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'trim_array_spaces' => true,
        "braces" => [
            'position_after_functions_and_oop_constructs' => 'same',
            'allow_single_line_closure' => true,
            'position_after_functions_and_oop_constructs' => 'same'
        ],
        'constant_case' => true,
        'lowercase_keywords' => true,
        'lowercase_static_reference' => true,
        'cast_spaces' => true
    ])
    ->setFinder($finder)
    ->setUsingCache(false)
;
