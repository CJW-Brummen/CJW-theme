<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <main>
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cjw-brummen
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'cjw-brummen'); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-header__inner">
			<a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
				<?php if (has_custom_logo()) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<svg class="site-brand__mark" width="46" height="46" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M6 40 L24 8 L42 40 Z" fill="none" stroke="var(--sage)" stroke-width="3.5" stroke-linejoin="round"></path><path d="M19 40 L24 25 L29 40" fill="none" stroke="var(--apricot)" stroke-width="3.5" stroke-linejoin="round"></path></svg>
				<?php endif; ?>
				<span class="site-brand__text">
					<span class="site-brand__title"><?php bloginfo('name'); ?></span>
					<?php
                    $cjw_brummen_description = get_bloginfo('description', 'display');
if ($cjw_brummen_description || is_customize_preview()) :
    ?>
						<span class="site-brand__tagline"><?php echo $cjw_brummen_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
				</span>
			</a>

			<nav id="site-navigation" class="site-nav" aria-label="<?php esc_attr_e('Hoofdmenu', 'cjw-brummen'); ?>">
				<?php
    wp_nav_menu(
        [
            'theme_location' => 'menu-1',
            'container' => false,
            'depth' => 1,
            'menu_class' => 'menu site-nav__menu',
            'fallback_cb' => 'cjw_brummen_menu_fallback',
        ]
    );
?>
				<?php $cjw_brummen_cta = cjw_brummen_signup_cta(); ?>
				<a class="btn site-nav__cta" href="<?php echo esc_url($cjw_brummen_cta['url']); ?>"><?php echo esc_html($cjw_brummen_cta['label']); ?></a>
			</nav>

			<button class="menu-toggle" aria-controls="menu-drawer" aria-expanded="false" data-label-open="<?php esc_attr_e('Menu', 'cjw-brummen'); ?>" data-label-close="<?php esc_attr_e('Sluit', 'cjw-brummen'); ?>">
				<span class="menu-toggle__label"><?php esc_html_e('Menu', 'cjw-brummen'); ?></span>
			</button>
		</div>
	</header><!-- #masthead -->

	<div id="menu-drawer" class="menu-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Mobiel menu', 'cjw-brummen'); ?>" inert>
		<button class="menu-drawer__close" aria-label="<?php esc_attr_e('Sluit menu', 'cjw-brummen'); ?>">&times;</button>
		<nav class="menu-drawer__nav" aria-label="<?php esc_attr_e('Mobiel menu', 'cjw-brummen'); ?>">
			<?php
            wp_nav_menu(
                [
                    'theme_location' => 'menu-1',
                    'container' => false,
                    'depth' => 1,
                    'menu_class' => 'menu menu-drawer__menu',
                    'fallback_cb' => 'cjw_brummen_menu_fallback',
                ]
            );
?>
		</nav>
		<?php $cjw_brummen_drawer_cta = cjw_brummen_signup_cta(); ?>
		<a class="btn menu-drawer__cta" href="<?php echo esc_url($cjw_brummen_drawer_cta['url']); ?>"><?php echo esc_html($cjw_brummen_drawer_cta['label']); ?></a>
	</div><!-- #menu-drawer -->
