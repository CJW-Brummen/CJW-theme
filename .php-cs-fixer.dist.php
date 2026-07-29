<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor', 'node_modules', 'build', 'dist', 'coverage', 'assets', '.stubs'])
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PER-CS' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'single_space'],
        'blank_line_after_opening_tag' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => false,
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_quote' => true,
        'trailing_comma_in_multiline' => true,

        // WordPress templates mix PHP with inline HTML. These PER-CS rules only see
        // the PHP scopes, so they flatten template code to column 0 or re-indent it
        // with spaces on top of the HTML tabs. Indentation stays hand-managed here;
        // `no_trailing_whitespace` still trims real end-of-line whitespace.
        'indentation_type' => false,
        'statement_indentation' => false,

        // PER-CS wants `else`/`elseif` preceded by a single space (`} else {`). Templates
        // use the alternative syntax, where there is no closing brace, so the rule
        // collapses the newline before `else` and no_multiple_statements_per_line then
        // splits it back one indent level too deep. Both lists are spelled out because
        // configuring the rule drops the rest of @PER-CS's configuration for it: the
        // "followed by" list below is @PER-CS's verbatim, "preceded by" is @PER-CS's
        // minus else/elseif.
        'single_space_around_construct' => [
            'constructs_followed_by_a_single_space' => [
                'abstract', 'as', 'case', 'catch', 'class', 'const', 'const_import', 'do',
                'else', 'elseif', 'enum', 'final', 'finally', 'for', 'foreach', 'function',
                'function_import', 'if', 'insteadof', 'interface', 'match', 'named_argument',
                'namespace', 'new', 'private', 'protected', 'public', 'readonly', 'static',
                'switch', 'trait', 'try', 'type_colon', 'use', 'use_lambda', 'while',
            ],
            'constructs_preceded_by_a_single_space' => ['as', 'use_lambda'],
        ],

        // A `// phpcs:ignore ...` annotation right before a closing PHP tag makes the
        // space in front of that tag "trailing comment whitespace" to this rule, while
        // PHPCS's Squiz.PHP.EmbeddedPhp.SpacingBeforeClose requires exactly that space --
        // with both on, `composer format` and `composer phpcbf` undo each other.
        'no_trailing_whitespace_in_comment' => false,
    ])
    ->setFinder($finder);
