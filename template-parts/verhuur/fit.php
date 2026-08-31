<?php

/**
 * "Past het?" -- the one question a hirer arrives with.
 *
 * A school camp organiser knows exactly one number: how many children. This
 * turns it into a combination of tents.
 *
 * It is a real GET form, answered here in PHP. js/verhuur.js only reads the
 * same answers back out of a lookup table as you type, so the page never has an
 * input that does nothing when a script fails to load.
 *
 * Rendered by the cjw/verhuur-past-het block, which passes its heading in as
 * $args['titel']. An empty heading renders no heading at all, for an editor who
 * would rather write their own above the block.
 *
 * @package cjw-brummen
 */

$cjw_brummen_fit_title = isset($args['titel']) ? (string) $args['titel'] : 'Past het?';

$cjw_brummen_fit_items = cjw_brummen_rental_items();
$cjw_brummen_fit_stock = cjw_brummen_rental_fit_stock($cjw_brummen_fit_items);

if ([] === $cjw_brummen_fit_stock) {
    return;
}

$cjw_brummen_fit_totals = cjw_brummen_rental_totals($cjw_brummen_fit_items);
$cjw_brummen_fit_ceiling = cjw_brummen_rental_fit_ceiling($cjw_brummen_fit_stock);

// A group size to answer with, read straight off the query string. It changes
// nothing and is echoed only after absint(), so there is nothing for a nonce to
// protect -- this is a search box, not a submission.
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$cjw_brummen_fit_people = isset($_GET['personen']) ? absint(wp_unslash($_GET['personen'])) : 0;

$cjw_brummen_fit_answer = $cjw_brummen_fit_people > 0
    ? cjw_brummen_rental_fit($cjw_brummen_fit_stock, $cjw_brummen_fit_people)
    : null;
?>

<section class="verhuur-fit">
	<?php if ('' !== $cjw_brummen_fit_title) : ?>
		<h2 class="verhuur-fit__title"><?php echo esc_html($cjw_brummen_fit_title); ?></h2>
	<?php endif; ?>

	<form class="verhuur-fit__form" method="get" action="<?php echo esc_url(get_permalink()); ?>">
		<label for="verhuur-personen"><?php esc_html_e('Voor hoeveel personen zoek je onderdak?', 'cjw-brummen'); ?></label>
		<input
			type="number"
			id="verhuur-personen"
			name="personen"
			min="1"
			step="1"
			inputmode="numeric"
			value="<?php echo esc_attr($cjw_brummen_fit_people > 0 ? (string) $cjw_brummen_fit_people : ''); ?>"
		>
		<button type="submit" class="btn verhuur-fit__submit"><?php esc_html_e('Reken uit', 'cjw-brummen'); ?></button>
	</form>

	<div class="verhuur-fit__answer" id="verhuur-antwoord" aria-live="polite">
		<?php if (null !== $cjw_brummen_fit_answer) : ?>
			<?php foreach ($cjw_brummen_fit_answer['parts'] as $cjw_brummen_fit_part) : ?>
				<div class="verhuur-fit__line">
					<b><?php echo esc_html(sprintf('%d×', $cjw_brummen_fit_part['units'])); ?></b>
					<span>
						<?php
                        printf(
                            /* translators: 1: tent name, 2: number of places those tents hold. */
                            esc_html__('%1$s — %2$d plaatsen', 'cjw-brummen'),
                            esc_html($cjw_brummen_fit_part['title']),
                            (int) $cjw_brummen_fit_part['places']
                        );
    ?>
					</span>
				</div>
			<?php endforeach; ?>
			<div class="verhuur-fit__line">
				<b><?php echo esc_html((string) $cjw_brummen_fit_answer['places']); ?></b>
				<span>
					<?php
                    if ($cjw_brummen_fit_answer['spare'] > 0) {
                        printf(
                            /* translators: %d: number of unused places. */
                            esc_html__('plaatsen in totaal, %d over', 'cjw-brummen'),
                            (int) $cjw_brummen_fit_answer['spare']
                        );
                    } else {
                        esc_html_e('plaatsen in totaal, precies genoeg', 'cjw-brummen');
                    }
    ?>
				</span>
			</div>
		<?php elseif ($cjw_brummen_fit_people > $cjw_brummen_fit_ceiling) : ?>
			<div class="verhuur-fit__line">
				<b><?php echo esc_html((string) $cjw_brummen_fit_ceiling); ?></b>
				<span>
					<?php esc_html_e('plaatsen is alles wat er in de groepstenten past. Vraag naar de mogelijkheden voor grotere groepen.', 'cjw-brummen'); ?>
				</span>
			</div>
		<?php else : ?>
			<div class="verhuur-fit__line">
				<b>—</b>
				<span><?php esc_html_e('Vul een aantal personen in.', 'cjw-brummen'); ?></span>
			</div>
		<?php endif; ?>
	</div>

	<p class="verhuur-fit__notice" id="verhuur-let-op">
		<?php
        if (
            null !== $cjw_brummen_fit_answer
            && $cjw_brummen_fit_totals['mattresses'] > 0
            && $cjw_brummen_fit_people > $cjw_brummen_fit_totals['mattresses']
        ) {
            printf(
                /* translators: 1: number of mattresses, 2: group size, 3: how many short. */
                esc_html__('Let op: we hebben %1$d matrassen. Voor %2$d personen neem je er zelf %3$d mee.', 'cjw-brummen'),
                (int) $cjw_brummen_fit_totals['mattresses'],
                (int) $cjw_brummen_fit_people,
                (int) $cjw_brummen_fit_people - (int) $cjw_brummen_fit_totals['mattresses']
            );
        }
?>
	</p>
</section>
