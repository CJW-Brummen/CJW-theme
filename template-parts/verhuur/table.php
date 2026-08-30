<?php

/**
 * The specificatietabel: the whole inventory on one axis.
 *
 * The cards above cover the tents, which is what most people come for. This is
 * everything -- mattresses and bench sets included -- in the one shape that
 * lets two items be compared: a row each, a column per fact, and the "Totaal"
 * column doing the multiplication the live page leaves to the reader.
 *
 * @package cjw-brummen
 */

$cjw_brummen_table_items = cjw_brummen_rental_items();

if ([] === $cjw_brummen_table_items) {
    return;
}

$cjw_brummen_table_totals = cjw_brummen_rental_totals($cjw_brummen_table_items);
?>

<section class="verhuur-tabel">
	<h2 class="verhuur-tabel__title"><?php esc_html_e('Alles op een rij', 'cjw-brummen'); ?></h2>

	<div class="verhuur-tabel__scroll">
		<table>
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e('Materiaal', 'cjw-brummen'); ?></th>
					<th scope="col"><?php esc_html_e('Aantal', 'cjw-brummen'); ?></th>
					<th scope="col"><?php esc_html_e('Voor', 'cjw-brummen'); ?></th>
					<th scope="col"><?php esc_html_e('Totaal', 'cjw-brummen'); ?></th>
					<th scope="col"><?php esc_html_e('Afmetingen', 'cjw-brummen'); ?></th>
					<th scope="col"><?php esc_html_e('Bijzonderheden', 'cjw-brummen'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($cjw_brummen_table_items as $cjw_brummen_row) : ?>
					<?php
                    $cjw_brummen_row_capacity = cjw_brummen_rental_capacity_label($cjw_brummen_row);
                    $cjw_brummen_row_size = cjw_brummen_rental_size_label($cjw_brummen_row);
                    ?>
					<tr>
						<th scope="row"><?php echo esc_html($cjw_brummen_row['title']); ?></th>
						<td class="verhuur-tabel__getal">
							<?php
                            echo $cjw_brummen_row['count'] > 0
                                ? esc_html(sprintf('%d×', (int) $cjw_brummen_row['count']))
                                : esc_html__('op aanvraag', 'cjw-brummen');
                            ?>
						</td>
						<td class="verhuur-tabel__getal"><?php echo esc_html('' !== $cjw_brummen_row_capacity ? $cjw_brummen_row_capacity : '—'); ?></td>
						<td class="verhuur-tabel__getal"><?php echo esc_html(cjw_brummen_rental_total_label($cjw_brummen_row)); ?></td>
						<td class="verhuur-tabel__getal"><?php echo esc_html('' !== $cjw_brummen_row_size ? $cjw_brummen_row_size : '—'); ?></td>
						<td><?php echo esc_html($cjw_brummen_row['note']); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<ul class="verhuur-tabel__totalen">
		<?php if ($cjw_brummen_table_totals['sleeping'] > 0) : ?>
			<li>
				<b><?php echo esc_html(number_format_i18n($cjw_brummen_table_totals['sleeping'])); ?></b>
				<span>
					<?php
                    printf(
                        /* translators: %d: number of group tents. */
                        esc_html(_n('slaapplaats in %d groepstent', 'slaapplaatsen in %d groepstenten', (int) $cjw_brummen_table_totals['tents'], 'cjw-brummen')),
                        (int) $cjw_brummen_table_totals['tents']
                    );
    ?>
				</span>
			</li>
		<?php endif; ?>

		<?php if ($cjw_brummen_table_totals['mattresses'] > 0) : ?>
			<li>
				<b><?php echo esc_html(number_format_i18n($cjw_brummen_table_totals['mattresses'])); ?></b>
				<span>
					<?php
                    if ($cjw_brummen_table_totals['mattresses'] < $cjw_brummen_table_totals['sleeping']) {
                        printf(
                            /* translators: %d: how many sleepers must bring their own mattress. */
                            esc_html__('matrassen — %d slapers minder dan er tentplaats is', 'cjw-brummen'),
                            (int) $cjw_brummen_table_totals['sleeping'] - (int) $cjw_brummen_table_totals['mattresses']
                        );
                    } else {
                        esc_html_e('matrassen', 'cjw-brummen');
                    }
    ?>
				</span>
			</li>
		<?php endif; ?>

		<?php if ($cjw_brummen_table_totals['cover'] > 0) : ?>
			<li>
				<b><?php echo esc_html(cjw_brummen_rental_area_label($cjw_brummen_table_totals['cover'])); ?></b>
				<span><?php esc_html_e('m² overkapping in partytenten', 'cjw-brummen'); ?></span>
			</li>
		<?php endif; ?>
	</ul>
</section>
