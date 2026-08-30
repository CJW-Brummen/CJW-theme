<?php

/**
 * The kaartenrek: one card per tent, with its footprint drawn to scale.
 *
 * The live site gives every tent a dimension string -- "300 x 440 x 175/235 cm
 * (l x b x h voor/midden)" -- and leaves the reader to picture it. Four
 * rectangles on one shared grid answer "will it fit on my field" by looking
 * instead, and they cost nothing but the numbers CJW already publishes, which
 * matters here: the media library holds a photograph of one of these items.
 *
 * Every plan uses the same scale (see cjw_brummen_rental_plan_span()), so a 3x3
 * party tent really does render as a ninth of the big one.
 *
 * Rendered by the cjw/verhuur-kaarten block, which passes its heading in as
 * $args['titel'].
 *
 * @package cjw-brummen
 */

$cjw_brummen_cards_title = isset($args['titel']) ? (string) $args['titel'] : 'Wat verhuren we zoal?';

$cjw_brummen_cards = [];

foreach (cjw_brummen_rental_items() as $cjw_brummen_card_item) {
    if (in_array($cjw_brummen_card_item['category'], [ 'groepstent', 'partytent' ], true)) {
        $cjw_brummen_cards[] = $cjw_brummen_card_item;
    }
}

if ([] === $cjw_brummen_cards) {
    return;
}

$cjw_brummen_span = cjw_brummen_rental_plan_span(cjw_brummen_rental_drawable($cjw_brummen_cards));
$cjw_brummen_has_plans = $cjw_brummen_span['x'] > 0 && $cjw_brummen_span['y'] > 0;

// The plan sits above a ruler as long as the widest item in the set, so the
// empty half of a small tent's grid reads as scale rather than as a gap.
$cjw_brummen_plan_width = $cjw_brummen_span['x'] + 40;
$cjw_brummen_plan_height = $cjw_brummen_span['y'] + 40;
$cjw_brummen_view_height = $cjw_brummen_plan_height + 110;
$cjw_brummen_label_size = max(28, (int) round($cjw_brummen_span['x'] / 12));
$cjw_brummen_ruler_size = max(18, (int) round($cjw_brummen_span['x'] / 24));
?>

<section class="verhuur-rek">
	<?php if ('' !== $cjw_brummen_cards_title) : ?>
		<h2 class="verhuur-rek__title"><?php echo esc_html($cjw_brummen_cards_title); ?></h2>
	<?php endif; ?>

	<?php if ($cjw_brummen_has_plans) : ?>
		<svg width="0" height="0" aria-hidden="true" focusable="false" class="verhuur-plan-defs">
			<defs>
				<pattern id="cjw-verhuur-grid" width="100" height="100" patternUnits="userSpaceOnUse">
					<path d="M100 0 L0 0 0 100" fill="none" stroke="currentColor" stroke-opacity="0.22" stroke-width="2" />
				</pattern>
			</defs>
		</svg>
	<?php endif; ?>

	<div class="verhuur-rek__grid">
		<?php foreach ($cjw_brummen_cards as $cjw_brummen_card) : ?>
			<?php
            $cjw_brummen_capacity = cjw_brummen_rental_capacity_label($cjw_brummen_card);
            $cjw_brummen_size = cjw_brummen_rental_size_label($cjw_brummen_card);
            $cjw_brummen_drawable = $cjw_brummen_card['length'] > 0 && $cjw_brummen_card['width'] > 0;
            ?>
			<article class="verhuur-kaart verhuur-kaart--<?php echo esc_attr($cjw_brummen_card['category']); ?>">
				<?php if ($cjw_brummen_card['count'] > 0) : ?>
					<span class="verhuur-kaart__aantal">
						<?php
                        printf(
                            /* translators: %d: how many of this item CJW owns. */
                            esc_html__('%d× beschikbaar', 'cjw-brummen'),
                            (int) $cjw_brummen_card['count']
                        );
    ?>
					</span>
				<?php else : ?>
					<span class="verhuur-kaart__aantal verhuur-kaart__aantal--vraag">
						<?php esc_html_e('op aanvraag', 'cjw-brummen'); ?>
					</span>
				<?php endif; ?>

				<h3 class="verhuur-kaart__naam"><?php echo esc_html($cjw_brummen_card['title']); ?></h3>

				<?php if ('' !== $cjw_brummen_capacity) : ?>
					<p class="verhuur-kaart__voor">
						<b>
							<?php
                            // The range belongs in the big number: "12" set large with
                            // "tot 42 personen" small beside it reads as a capacity of 12.
                            echo esc_html(
                                $cjw_brummen_card['capacity_max'] > 0
                                    ? sprintf('%1$d–%2$d', (int) $cjw_brummen_card['capacity'], (int) $cjw_brummen_card['capacity_max'])
                                    : (string) $cjw_brummen_card['capacity']
                            );
    ?>
						</b>
						<span><?php esc_html_e('personen', 'cjw-brummen'); ?></span>
					</p>
				<?php elseif ($cjw_brummen_card['area'] > 0) : ?>
					<p class="verhuur-kaart__voor">
						<b><?php echo esc_html(cjw_brummen_rental_area_label($cjw_brummen_card['area'])); ?></b>
						<span><?php esc_html_e('m² overkapping', 'cjw-brummen'); ?></span>
					</p>
				<?php endif; ?>

				<?php if ($cjw_brummen_has_plans && $cjw_brummen_drawable) : ?>
					<svg
						class="verhuur-plan"
						viewBox="0 0 <?php echo esc_attr((string) $cjw_brummen_plan_width); ?> <?php echo esc_attr((string) $cjw_brummen_view_height); ?>"
						role="img"
						aria-label="
						<?php
                        printf(
                            /* translators: 1: item name, 2: length in cm, 3: width in cm. */
                            esc_attr__('Plattegrond van %1$s, %2$d bij %3$d centimeter', 'cjw-brummen'),
                            esc_attr($cjw_brummen_card['title']),
                            (int) $cjw_brummen_card['length'],
                            (int) $cjw_brummen_card['width']
                        );
    ?>
						"
					>
						<rect x="0" y="0" width="<?php echo esc_attr((string) $cjw_brummen_plan_width); ?>" height="<?php echo esc_attr((string) $cjw_brummen_plan_height); ?>" fill="url(#cjw-verhuur-grid)" />
						<rect
							class="verhuur-plan__vlak"
							x="20"
							y="20"
							width="<?php echo esc_attr((string) $cjw_brummen_card['length']); ?>"
							height="<?php echo esc_attr((string) $cjw_brummen_card['width']); ?>"
							rx="10"
						/>
						<text
							class="verhuur-plan__maat"
							x="<?php echo esc_attr((string) (20 + ((int) $cjw_brummen_card['length'] / 2))); ?>"
							y="<?php echo esc_attr((string) (20 + ((int) $cjw_brummen_card['width'] / 2) + ($cjw_brummen_label_size / 3))); ?>"
							text-anchor="middle"
							font-size="<?php echo esc_attr((string) $cjw_brummen_label_size); ?>"
						>
                        <?php
                            printf(
                                /* translators: 1: length in metres, 2: width in metres. */
                                esc_html__('%1$s × %2$s m', 'cjw-brummen'),
                                esc_html(cjw_brummen_rental_metres($cjw_brummen_card['length'])),
                                esc_html(cjw_brummen_rental_metres($cjw_brummen_card['width']))
                            );
    ?>
    </text>
						<g class="verhuur-plan__lat">
							<path d="M20 <?php echo esc_attr((string) ($cjw_brummen_plan_height + 40)); ?> H<?php echo esc_attr((string) (20 + $cjw_brummen_span['x'])); ?>" />
							<?php for ($cjw_brummen_tick = 0; $cjw_brummen_tick <= $cjw_brummen_span['x']; $cjw_brummen_tick += 100) : ?>
								<path d="M<?php echo esc_attr((string) (20 + $cjw_brummen_tick)); ?> <?php echo esc_attr((string) ($cjw_brummen_plan_height + 30)); ?> V<?php echo esc_attr((string) ($cjw_brummen_plan_height + 50)); ?>" />
							<?php endfor; ?>
						</g>
						<text
							class="verhuur-plan__schaal"
							x="<?php echo esc_attr((string) (20 + ($cjw_brummen_span['x'] / 2))); ?>"
							y="<?php echo esc_attr((string) ($cjw_brummen_plan_height + 95)); ?>"
							text-anchor="middle"
							font-size="<?php echo esc_attr((string) $cjw_brummen_ruler_size); ?>"
						>
                        <?php
                            printf(
                                /* translators: %s: the width of the plan grid, in metres. */
                                esc_html__('%s meter', 'cjw-brummen'),
                                esc_html(cjw_brummen_rental_metres($cjw_brummen_span['x']))
                            );
    ?>
    </text>
					</svg>
				<?php endif; ?>

				<?php if ('' !== $cjw_brummen_size) : ?>
					<p class="verhuur-kaart__maat"><?php echo esc_html($cjw_brummen_size); ?></p>
				<?php endif; ?>

				<?php if ('' !== $cjw_brummen_card['note']) : ?>
					<p class="verhuur-kaart__op"><?php echo esc_html($cjw_brummen_card['note']); ?></p>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>

	<?php if ($cjw_brummen_has_plans) : ?>
		<p class="verhuur-rek__legenda"><?php esc_html_e('Elk vakje in de plattegrond is 1 × 1 meter.', 'cjw-brummen'); ?></p>
	<?php endif; ?>
</section>
