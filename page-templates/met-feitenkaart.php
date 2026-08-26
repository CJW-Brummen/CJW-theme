<?php
/**
 * Template Name: Pagina met feitenkaart
 *
 * Follows the "CJW Pagina Template" design: the "CJW Zomerkamp" kicker,
 * the page title in the sage band, the page content from the editor, and
 * an aside with the photo blob and the "Goed om te weten" facts card fed
 * by the cjw-summer-camp plugin (source of truth).
 *
 * @package cjw-brummen
 */

get_header();

$cjw_brummen_prices = cjw_brummen_camp_prices();
$cjw_brummen_facts = cjw_brummen_camp_facts();
$cjw_brummen_cta_band = cjw_brummen_cta_band();
$cjw_brummen_cta = cjw_brummen_signup_cta();
$cjw_brummen_cta_class = $cjw_brummen_cta['open'] ? 'btn btn--hero' : 'btn btn--hero btn--muted';
$cjw_brummen_cta_label = $cjw_brummen_cta['open'] ? $cjw_brummen_cta['label'] : __('Inschrijving gesloten', 'cjw-brummen');

while (have_posts()) :
    the_post();
    ?>

	<main id="primary" class="site-main">

		<header class="page-band">
			<svg class="page-band__doodle" width="150" height="110" viewBox="0 0 40 56" aria-hidden="true" focusable="false"><path d="M20 4 L8 22 H14 L5 38 H13 L4 52 H36 L27 38 H35 L26 22 H32 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path></svg>
			<div class="page-band__inner">
				<span class="page-band__kicker"><?php esc_html_e('CJW Zomerkamp', 'cjw-brummen'); ?></span>
				<h1 class="page-band__title"><?php the_title(); ?></h1>
				<?php if (has_excerpt()) : ?>
					<p class="page-band__lead"><?php echo esc_html(get_the_excerpt()); ?></p>
				<?php endif; ?>
			</div>
			<svg class="page-band__wave" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="var(--paper)" d="M0 60 Q 60 20 140 44 T 300 40 T 470 52 T 640 30 T 810 50 T 980 34 T 1150 52 T 1320 36 T 1440 48 L1440 90 L0 90 Z"></path></svg>
		</header>

		<div class="page-layout">
			<aside class="page-layout__aside">
				<?php if (has_post_thumbnail()) : ?>
					<div class="page-blob">
						<div class="page-blob__frame" aria-hidden="true"></div>
						<div class="page-blob__media"><?php the_post_thumbnail('large'); ?></div>
					</div>
				<?php endif; ?>

				<div class="page-facts">
					<h2 class="page-facts__title"><?php esc_html_e('Goed om te weten', 'cjw-brummen'); ?></h2>
					<p><strong><?php esc_html_e('Data:', 'cjw-brummen'); ?></strong> <?php echo esc_html(cjw_brummen_hero_badge_text()); ?></p>
					<?php if ($cjw_brummen_facts['age']) : ?>
						<p><strong><?php esc_html_e('Leeftijd:', 'cjw-brummen'); ?></strong> <?php echo esc_html($cjw_brummen_facts['age']); ?></p>
					<?php endif; ?>
					<?php if ($cjw_brummen_facts['location']) : ?>
						<p><strong><?php esc_html_e('Locatie:', 'cjw-brummen'); ?></strong> <?php echo esc_html($cjw_brummen_facts['location']); ?></p>
					<?php endif; ?>
					<?php if ($cjw_brummen_prices) : ?>
						<p><strong><?php esc_html_e('Kosten:', 'cjw-brummen'); ?></strong>
							<?php
                            printf(
                                /* translators: 1: week price, 2: weekend price. */
                                esc_html__('€ %1$s (week) · € %2$s (weekend)', 'cjw-brummen'),
                                esc_html(number_format_i18n($cjw_brummen_prices['week'])),
                                esc_html(number_format_i18n($cjw_brummen_prices['weekend']))
                            );
    ?>
						</p>
					<?php endif; ?>
				</div>
			</aside>

			<article id="post-<?php the_ID(); ?>" <?php post_class('page-layout__article'); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		</div>

		<section class="page-cta">
			<div class="page-cta__box">
				<div>
					<h2 class="page-cta__title"><?php echo esc_html($cjw_brummen_cta_band['title']); ?></h2>
					<p class="page-cta__text"><?php echo esc_html($cjw_brummen_cta_band['text']); ?></p>
				</div>
				<a class="<?php echo esc_attr($cjw_brummen_cta_class); ?>" href="<?php echo esc_url($cjw_brummen_cta['url']); ?>"><?php echo esc_html($cjw_brummen_cta_label); ?></a>
			</div>
		</section>

	</main><!-- #primary -->

	<?php
endwhile;

get_footer();
