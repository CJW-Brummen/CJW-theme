<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cjw-brummen
 */

$cjw_brummen_contact_email = cjw_brummen_contact_email();
$cjw_brummen_socials = cjw_brummen_social_links();
$cjw_brummen_facebook = $cjw_brummen_socials['facebook'];
$cjw_brummen_instagram = $cjw_brummen_socials['instagram'];
$cjw_brummen_footer_copy = cjw_brummen_footer_copy();
?>

	<div class="site-footer-treeline" aria-hidden="true">
		<svg viewBox="0 0 1440 120" preserveAspectRatio="none" focusable="false"><path fill="var(--forest)" d="M0 120 L0 78 L30 40 L52 74 L70 26 L96 70 L118 44 L138 76 L160 22 L186 72 L210 48 L232 78 L258 30 L282 72 L306 50 L326 78 L352 24 L378 70 L400 44 L422 76 L448 34 L472 72 L494 52 L516 80 L540 26 L566 72 L590 46 L612 76 L636 30 L662 70 L686 50 L708 78 L732 22 L758 72 L782 44 L804 76 L830 32 L854 70 L878 52 L900 80 L924 26 L950 72 L974 46 L996 76 L1020 30 L1046 70 L1070 50 L1092 78 L1116 24 L1142 72 L1166 44 L1188 76 L1214 34 L1238 70 L1262 52 L1284 80 L1308 26 L1334 72 L1358 46 L1380 76 L1404 36 L1428 70 L1440 60 L1440 120 Z"></path></svg>
	</div>

	<footer id="contact" class="site-footer">
		<div class="site-footer__inner">
			<div class="site-footer__about">
				<div class="site-footer__brandrow">
					<svg class="site-footer__flame" width="44" height="52" viewBox="0 0 48 56" aria-hidden="true" focusable="false"><path d="M14 50 l20 6 M12 56 l22 -4" stroke="#f0ae73" stroke-width="4" stroke-linecap="round"></path><path d="M14 46 Q 16 22 24 10 Q 28 24 34 20 Q 40 34 32 46 Q 22 52 14 46" fill="none" stroke="#f0ae73" stroke-width="3.5" stroke-linejoin="round"></path><path d="M22 44 Q 22 32 26 26 Q 30 34 28 42" fill="none" stroke="#ffce8a" stroke-width="3" stroke-linecap="round"></path></svg>
					<span class="site-footer__brand"><?php bloginfo('name'); ?></span>
				</div>
				<p><?php echo esc_html($cjw_brummen_footer_copy['about']); ?></p>
			</div>

			<nav class="site-footer__nav" aria-label="<?php esc_attr_e('Footermenu', 'cjw-brummen'); ?>">
				<span class="site-footer__heading"><?php esc_html_e('Snel naar', 'cjw-brummen'); ?></span>
				<?php
                wp_nav_menu(
                    [
                        'theme_location' => 'menu-1',
                        'container' => '',
                        'depth' => 1,
                        'menu_class' => 'menu site-footer__menu',
                        'fallback_cb' => 'cjw_brummen_menu_fallback',
                    ]
                );
?>
			</nav>

			<div class="site-footer__contact">
				<span class="site-footer__heading"><?php esc_html_e('Contact', 'cjw-brummen'); ?></span>
				<?php if ($cjw_brummen_contact_email) : ?>
					<a class="site-footer__email" href="mailto:<?php echo esc_attr($cjw_brummen_contact_email); ?>"><?php echo esc_html($cjw_brummen_contact_email); ?></a>
				<?php endif; ?>
				<span class="site-footer__org"><?php echo esc_html($cjw_brummen_footer_copy['org']); ?></span>
				<?php if ($cjw_brummen_facebook || $cjw_brummen_instagram) : ?>
					<div class="site-footer__social">
						<?php if ($cjw_brummen_facebook) : ?>
							<a href="<?php echo esc_url($cjw_brummen_facebook); ?>" aria-label="<?php esc_attr_e('Facebook', 'cjw-brummen'); ?>"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
						<?php endif; ?>
						<?php if ($cjw_brummen_instagram) : ?>
							<a href="<?php echo esc_url($cjw_brummen_instagram); ?>" aria-label="<?php esc_attr_e('Instagram', 'cjw-brummen'); ?>"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="2" y="2" width="20" height="20" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><line x1="17.5" y1="6.5" x2="17.5" y2="6.5"></line></svg></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<p class="site-footer__colophon">
			<?php
            /* translators: 1: current year, 2: site name. */
            printf(esc_html__('© %1$s %2$s · gemaakt met modder aan onze schoenen', 'cjw-brummen'), esc_html(wp_date('Y')), esc_html(get_bloginfo('name')));
?>
		</p>
	</footer><!-- #contact -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
