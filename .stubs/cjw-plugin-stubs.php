<?php

/**
 * PHPStan stubs for the cjw plugin's public API.
 *
 * The theme reads all camp data from the cjw-common / cjw-summer-camp plugins, but
 * those live in a separate repository and are not present when the theme is analysed
 * on its own (see .github/workflows/quality.yml). Without these signatures PHPStan
 * reports every `$camp->getX()` call as `class.notFound`.
 *
 * This file is only scanned by PHPStan (`scanFiles` in phpstan.neon.dist); it is never
 * loaded at runtime and is excluded from analysis, PHPCS and PHP-CS-Fixer.
 *
 * Only the signatures matter, so the service is declared `abstract` and its methods have
 * no bodies. Empty bodies behind a declared return type make editors (Intelephense,
 * PhpStorm) report "not all code paths return a value" on every getter, and Intelephense
 * has no inline suppression for it. If the theme ever needs `new CJW_Summer_Camp_Service`,
 * drop `abstract` here and give each method a `throw` body instead.
 *
 * Keep in sync with the plugin: wp-content/plugins/cjw/inc/shared/class-camp-type.php
 * and wp-content/plugins/cjw/inc/shared/class-summer-camp-service.php.
 *
 * @package cjw-brummen
 */

/**
 * Camp type enumeration.
 */
enum CampType: string
{
    case WEEKEND = 'weekend';
    case WEEK = 'week';
}

/**
 * Returns the shared summer camp service.
 */
function cjw_summer_camp(): CJW_Summer_Camp_Service
{
    throw new LogicException('Stub only; the real implementation lives in the cjw plugin.');
}

/**
 * Service class for handling Summer Camp data and logic.
 */
abstract class CJW_Summer_Camp_Service
{
    /**
     * The top-level WordPress option key for summer camp settings.
     */
    public const string OPTION_KEY = 'cjw_summer_camp_settings';

    /**
     * Number of polaroid slots on the website's photo wall.
     */
    public const int POLAROID_SLOTS = 5;

    /**
     * Initializes the service and loads current settings.
     */
    public function __construct()
    {
    }

    /**
     * Returns the start date of the summer camp.
     *
     * @return DateTimeImmutable|null
     */
    abstract public function getStartDate(): ?DateTimeImmutable;

    /**
     * Returns the end date of the summer camp.
     *
     * @return DateTimeImmutable|null
     */
    abstract public function getEndDate(): ?DateTimeImmutable;

    /**
     * Returns the registration deadline.
     *
     * @return DateTimeImmutable|null
     */
    abstract public function getRegistrationDeadline(): ?DateTimeImmutable;

    /**
     * Checks if the registration is currently open based on status and deadline.
     *
     * @return bool
     */
    abstract public function isRegistrationOpen(): bool;

    /**
     * Retrieves the text shown when registration is closed.
     *
     * @return string
     */
    abstract public function getRegistrationClosedText(): string;

    /**
     * Retrieves the generated private link for late registration requests.
     *
     * @param string|null $baseUrl Optional base URL of the page that contains the registration wizard.
     *
     * @return string
     */
    abstract public function getLateRegistrationLink(?string $baseUrl = null): string;

    /**
     * The address of the page with the registration form, or '' when there is none.
     *
     * @return string
     */
    abstract public function getRegistrationPageUrl(): string;

    /**
     * Retrieves the private late-registration token.
     *
     * @return string
     */
    abstract public function getLateRegistrationToken(): string;

    /**
     * Checks whether a submitted late-registration token matches the stored token.
     *
     * @param string|null $token Submitted token.
     *
     * @return bool
     */
    abstract public function isLateRegistrationTokenValid(?string $token): bool;

    /**
     * Retrieves the price for a specific visit type, considering early_bird discounts.
     *
     * @param CampType $type The type of visit ('week' or 'weekend').
     *
     * @return int
     */
    abstract public function getPrice(CampType $type): int;

    /**
     * Checks if the current date falls within the early_bird discount period.
     *
     * @return bool
     */
    abstract public function isEarlyBird(): bool;

    /**
     * Retrieves the introduction text for the summer camp.
     *
     * @return string
     */
    abstract public function getIntroText(): string;

    /**
     * Returns a human-readable camp date range.
     *
     * @return string
     */
    abstract public function getDateRangeText(): string;

    /**
     * Returns the camp age range shown on the website (e.g. "6 t/m 17 jaar").
     *
     * @return string
     */
    abstract public function getCampAgeRange(): string;

    /**
     * Returns the camp location shown on the website (e.g. "Landgoed Brockhausen, Stokkum").
     *
     * @return string
     */
    abstract public function getCampLocation(): string;

    /**
     * Returns the public theme/campaign name for the current camp year.
     *
     * @return string
     */
    abstract public function getThemeName(): string;

    /**
     * Returns the configured theme year.
     *
     * @return string
     */
    abstract public function getThemeYear(): string;

    /**
     * Returns all values needed by the theme hero.
     *
     * @return array
     */
    abstract public function getHeroConfig(): array;

    /**
     * Returns the hero title with a theme-name fallback.
     *
     * @return string
     */
    abstract public function getHeroTitle(): string;

    /**
     * Returns the hero subtitle, falling back to a camp date range when possible.
     *
     * @return string
     */
    abstract public function getHeroSubtitle(): string;

    /**
     * Returns the configured hero image attachment ID.
     *
     * @return int
     */
    abstract public function getHeroImageId(): int;

    /**
     * Returns the configured hero image URL.
     *
     * @param string $size WordPress image size.
     *
     * @return string
     */
    abstract public function getHeroImageUrl(string $size = 'full'): string;

    /**
     * Returns the hero image alternative text.
     *
     * @return string
     */
    abstract public function getHeroImageAlt(): string;

    /**
     * Returns the primary hero call-to-action.
     *
     * @return array
     */
    abstract public function getPrimaryHeroCta(): array;

    /**
     * Returns the secondary hero call-to-action.
     *
     * @return array
     */
    abstract public function getSecondaryHeroCta(): array;

    /**
     * Returns the configured public theme colors.
     *
     * @return array
     */
    abstract public function getThemeColors(): array;

    /**
     * Returns the configured image overlay opacity as a percentage.
     *
     * @return int
     */
    abstract public function getHeroOverlayOpacity(): int;

    /**
     * Returns the public description of the yearly theme (website teaser).
     *
     * @return string
     */
    abstract public function getThemeDescription(): string;

    /**
     * Returns the attachment ID of the website's introduction photo.
     *
     * @return int
     */
    abstract public function getIntroImageId(): int;

    /**
     * Returns the page ID behind the "Zomerkamp" card on the front page.
     *
     * @return int
     */
    abstract public function getCardZomerkampPageId(): int;

    /**
     * Returns the page ID behind the "Praktische info" card on the front page.
     *
     * @return int
     */
    abstract public function getCardInfoPageId(): int;

    /**
     * Returns the configured photo-wall polaroids (slots with an image).
     *
     * @return array<int, array{image_id: int, caption: string}>
     */
    abstract public function getPolaroids(): array;

    /**
     * Returns the public contact e-mail address.
     *
     * @return string
     */
    abstract public function getContactEmail(): string;

    /**
     * Returns the configured social profile URLs.
     *
     * @return array{facebook: string, instagram: string}
     */
    abstract public function getSocialLinks(): array;

    /**
     * Returns the title of the front-page introduction section.
     *
     * @return string
     */
    abstract public function getHomeIntroTitle(): string;

    /**
     * Returns the text of the front-page introduction section.
     *
     * @return string
     */
    abstract public function getHomeIntroText(): string;

    /**
     * Returns the title of the "Zomerkamp" card on the front page.
     *
     * @return string
     */
    abstract public function getCardZomerkampTitle(): string;

    /**
     * Returns the text of the "Zomerkamp" card on the front page.
     *
     * @return string
     */
    abstract public function getCardZomerkampText(): string;

    /**
     * Returns the title of the "Praktische info" card on the front page.
     *
     * @return string
     */
    abstract public function getCardInfoTitle(): string;

    /**
     * Returns the text of the "Praktische info" card on the front page.
     *
     * @return string
     */
    abstract public function getCardInfoText(): string;

    /**
     * Returns the title of the sign-up card on the front page.
     *
     * @return string
     */
    abstract public function getCardSignupTitle(): string;

    /**
     * Returns the text of the sign-up card on the front page.
     *
     * May contain the literal token {jaar}, replaced by the theme with the camp year.
     *
     * @return string
     */
    abstract public function getCardSignupText(): string;

    /**
     * Returns the title of the photo-wall section.
     *
     * @return string
     */
    abstract public function getPhotosTitle(): string;

    /**
     * Returns the lead text of the photo-wall section.
     *
     * @return string
     */
    abstract public function getPhotosLead(): string;

    /**
     * Returns the title of the sponsors section.
     *
     * @return string
     */
    abstract public function getSponsorsTitle(): string;

    /**
     * Returns the lead text of the sponsors section.
     *
     * @return string
     */
    abstract public function getSponsorsLead(): string;

    /**
     * Returns the call-to-action text of the sponsors section.
     *
     * @return string
     */
    abstract public function getSponsorsCtaText(): string;

    /**
     * Returns the "about" text in the website footer.
     *
     * @return string
     */
    abstract public function getFooterAbout(): string;

    /**
     * Returns the organisation text in the website footer.
     *
     * @return string
     */
    abstract public function getFooterOrg(): string;

    /**
     * Returns the title of the call-to-action band.
     *
     * @return string
     */
    abstract public function getCtaTitle(): string;

    /**
     * Returns the text of the call-to-action band.
     *
     * @return string
     */
    abstract public function getCtaText(): string;

    /**
     * Retrieves the list of drive dates (ritten).
     *
     * @return array
     */
    abstract public function getDriveDates(): array;

}
