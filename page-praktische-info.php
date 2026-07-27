<?php
/**
 * The template for the "Praktische info" page (slug: praktische-info).
 *
 * Follows the "CJW Pagina Template" design: kicker with the page title,
 * the design headline in the sage band, the page content from the editor,
 * and an aside with the photo blob and the "Goed om te weten" facts card.
 *
 * @package cjw-brummen
 */

get_header();

$cjw_brummen_prices = cjw_brummen_camp_prices();
$cjw_brummen_cta = cjw_brummen_signup_cta();

while (have_posts()) :
    the_post();

    $cjw_brummen_lead = has_excerpt()
        ? get_the_excerpt()
        : __('Alles wat jij (en je ouders) willen weten voordat het kamp begint.', 'cjw-brummen');
    ?>

	<main id="primary" class="site-main">

		<header class="page-band">
			<svg class="page-band__doodle" width="150" height="110" viewBox="0 0 40 56" aria-hidden="true" focusable="false"><path d="M20 4 L8 22 H14 L5 38 H13 L4 52 H36 L27 38 H35 L26 22 H32 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path></svg>
			<div class="page-band__inner">
				<span class="page-band__kicker"><?php the_title(); ?></span>
				<h1 class="page-band__title"><?php esc_html_e('Goed voorbereid het bos in', 'cjw-brummen'); ?></h1>
				<p class="page-band__lead"><?php echo esc_html($cjw_brummen_lead); ?></p>
			</div>
			<svg class="page-band__wave" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="var(--paper)" d="M0 60 Q 60 20 140 44 T 300 40 T 470 52 T 640 30 T 810 50 T 980 34 T 1150 52 T 1320 36 T 1440 48 L1440 90 L0 90 Z"></path></svg>
		</header>

		<div class="page-layout">
			<article id="post-<?php the_ID(); ?>" <?php post_class('page-layout__article'); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>

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
					<p><strong><?php esc_html_e('Leeftijd:', 'cjw-brummen'); ?></strong> <?php esc_html_e('6 t/m 17 jaar', 'cjw-brummen'); ?></p>
					<p><strong><?php esc_html_e('Locatie:', 'cjw-brummen'); ?></strong> <?php esc_html_e('Landgoed Brockhausen, Stokkum', 'cjw-brummen'); ?></p>
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
		</div>

		<section class="page-cta">
			<div class="page-cta__box">
				<div>
					<h2 class="page-cta__title"><?php esc_html_e('Zin gekregen?', 'cjw-brummen'); ?></h2>
					<p class="page-cta__text"><?php esc_html_e('Vol = vol, dus wacht niet te lang.', 'cjw-brummen'); ?></p>
				</div>
				<a class="btn btn--hero" href="<?php echo esc_url($cjw_brummen_cta['url']); ?>"><?php echo esc_html($cjw_brummen_cta['label']); ?></a>
			</div>
		</section>

	</main><!-- #primary -->

	<?php
endwhile;

get_footer();
