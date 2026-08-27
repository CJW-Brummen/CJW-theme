<?php

declare(strict_types=1);

/**
 * Base class for tests that talk to a real WordPress.
 *
 * Deliberately the same shape as the plugin's, including the expectDeprecated()
 * override that upstream has not made PHPUnit 10+ compatible. The two
 * repositories share a toolchain; a developer moving between them should not
 * have to learn two harnesses.
 */
abstract class CJW_IntegrationTestCase extends WP_UnitTestCase
{
    public function set_up(): void
    {
        parent::set_up();

        // The object cache lives in memory for the whole process, outside the
        // transaction the parent rolls back.
        wp_cache_flush();

        $_GET = [];
        $_POST = [];
        $_REQUEST = [];
        $_COOKIE = [];
    }

    /**
     * Re-implements WordPress's deprecation expectations for modern PHPUnit.
     *
     * WP_UnitTestCase_Base::expectDeprecated() reads @expectedDeprecated and
     * @expectedIncorrectUsage out of a doc block using
     * PHPUnit\Util\Test::parseTestMethodAnnotations() and $this->getName(),
     * both removed in PHPUnit 10. The upstream suite has not caught up, so
     * every test errored before reaching its first assertion.
     *
     * The doc block is read here instead, off the reflection of the test
     * method, so the annotations keep working -- and, more importantly, so do
     * the hooks below, which are what turn a _deprecated_function() or
     * _doing_it_wrong() notice into a failed test rather than silence.
     */
    public function expectDeprecated(): void
    {
        foreach ($this->annotated('expectedDeprecated') as $value) {
            $this->expected_deprecated[] = $value;
        }

        foreach ($this->annotated('expectedIncorrectUsage') as $value) {
            $this->expected_doing_it_wrong[] = $value;
        }

        foreach ([
            'deprecated_function_run' => 3,
            'deprecated_argument_run' => 3,
            'deprecated_class_run' => 3,
            'deprecated_file_included' => 4,
            'deprecated_hook_run' => 4,
        ] as $hook => $args) {
            add_action($hook, [ $this, 'deprecated_function_run' ], 10, $args);
        }

        add_action('doing_it_wrong_run', [ $this, 'doing_it_wrong_run' ], 10, 3);

        // The notice is recorded, not printed: the assertion at tear-down is
        // what reports it, and PHP's own error output would only be noise.
        foreach ([
            'deprecated_function_trigger_error',
            'deprecated_argument_trigger_error',
            'deprecated_class_trigger_error',
            'deprecated_file_trigger_error',
            'deprecated_hook_trigger_error',
            'doing_it_wrong_trigger_error',
        ] as $hook) {
            add_action($hook, '__return_false');
        }
    }

    /**
     * Values of one annotation on the running test method and its class.
     *
     * @param string $annotation Annotation name, without the @.
     *
     * @return array<int, string>
     */
    private function annotated(string $annotation): array
    {
        $found = [];
        $pattern = '/@' . preg_quote($annotation, '/') . '\s+(.+)/';

        $blocks = [ (new ReflectionClass(static::class))->getDocComment() ];

        try {
            $blocks[] = (new ReflectionMethod(static::class, $this->name()))->getDocComment();
        } catch (ReflectionException) {
            // A data-provider generated name with no method behind it.
        }

        foreach ($blocks as $block) {
            if (! is_string($block)) {
                continue;
            }

            if (preg_match_all($pattern, $block, $matches)) {
                foreach ($matches[1] as $value) {
                    $found[] = trim($value);
                }
            }
        }

        return $found;
    }

    /**
     * An administrator, logged in.
     */
    protected function actAsAdministrator(): int
    {
        $id = self::factory()->user->create([ 'role' => 'administrator' ]);
        wp_set_current_user($id);

        return $id;
    }
}
