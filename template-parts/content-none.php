<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cjw-brummen
 */

?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e('Niets gevonden', 'cjw-brummen'); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php
        if (is_home() && current_user_can('publish_posts')) :

            printf(
                '<p>' . wp_kses(
                    /* translators: 1: link to WP admin new post page. */
                    __('Klaar om je eerste bericht te publiceren? <a href="%1$s">Begin hier</a>.', 'cjw-brummen'),
                    [
                        'a' => [
                            'href' => [],
                        ],
                    ]
                ) . '</p>',
                esc_url(admin_url('post-new.php'))
            );
        elseif (is_search()) :
            ?>

			<p><?php esc_html_e('Sorry, er kwam niets overeen met je zoekopdracht. Probeer het nog eens met andere zoektermen.', 'cjw-brummen'); ?></p>
			<?php
            get_search_form();
        else :
            ?>

			<p><?php esc_html_e('We kunnen niet vinden wat je zoekt. Misschien helpt zoeken.', 'cjw-brummen'); ?></p>
			<?php
            get_search_form();

        endif;
?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
