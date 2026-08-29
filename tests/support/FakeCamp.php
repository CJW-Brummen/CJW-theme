<?php

declare(strict_types=1);

/**
 * A stand-in for the plugin's summer camp service.
 *
 * Only the getters the theme actually calls, each returning whatever the test
 * handed the constructor. The point is to exercise the case the theme used to
 * get wrong: a plugin that is installed and running, with fields the organiser
 * has not filled in.
 */
final class FakeCamp
{
    /**
     * @param array<string, mixed> $values
     */
    public function __construct(private array $values = []) {}

    private function value(string $key, mixed $default): mixed
    {
        return array_key_exists($key, $this->values) ? $this->values[$key] : $default;
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        $raw = $this->value('start_date', null);

        return is_string($raw) && '' !== $raw ? new DateTimeImmutable($raw) : null;
    }

    public function getEndDate(): ?DateTimeImmutable
    {
        $raw = $this->value('end_date', null);

        return is_string($raw) && '' !== $raw ? new DateTimeImmutable($raw) : null;
    }

    public function getDateRangeText(): string
    {
        return (string) $this->value('date_range', '');
    }

    public function getHeroImageAlt(): string
    {
        return (string) $this->value('hero_image_alt', '');
    }

    public function getHeroOverlayOpacity(): int
    {
        return (int) $this->value('hero_overlay_opacity', 25);
    }

    /**
     * @return array<string, string>
     */
    public function getThemeColors(): array
    {
        return (array) $this->value('theme_colors', [
            'primary' => '#588c7e',
            'secondary' => '#f0ae73',
            'accent' => '#ed1c24',
            'hero_text' => '#ffffff',
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function getSecondaryHeroCta(): array
    {
        return (array) $this->value('secondary_cta', [ 'label' => 'Lees meer', 'url' => '#meer' ]);
    }

    /**
     * @return array<int, array{image_id: int, caption: string}>
     */
    public function getPolaroids(): array
    {
        return (array) $this->value('polaroids', []);
    }

    public function getThemeName(): string
    {
        return (string) $this->value('theme_name', '');
    }

    public function getThemeYear(): string
    {
        return (string) $this->value('theme_year', '');
    }
}
