<?php

/**
 * The verhuurpagina's arithmetic.
 *
 * Everything the page states about the inventory is derived: the totals under
 * the table, the scale the footprints are drawn at, and every answer the
 * "Past het?" helper gives. None of it is typed by anyone, which is the point --
 * and which means a mistake here is a wrong number presented with confidence
 * rather than a visible error. So the sums are pinned down.
 *
 * @package cjw-brummen
 */

declare(strict_types=1);

final class RentalTest extends CJW_Brummen_TestCase
{
    /**
     * One inventory record, in the shape cjw_brummen_rental_item() returns.
     *
     * @param array<string, mixed> $overrides Fields to set.
     * @return array<string, mixed>
     */
    private function item(array $overrides = []): array
    {
        $item = array_merge(
            [
                'id' => 1,
                'title' => 'Tent',
                'category' => 'groepstent',
                'count' => 0,
                'capacity' => 0,
                'capacity_max' => 0,
                'length' => 0,
                'width' => 0,
                'height_front' => 0,
                'height_middle' => 0,
                'note' => '',
                'image_id' => 0,
                'area' => 0.0,
            ],
            $overrides
        );

        $item['area'] = $item['length'] > 0 && $item['width'] > 0
            ? round(($item['length'] * $item['width']) / 10000, 1)
            : 0.0;
        $item['total'] = $item['count'] * $item['capacity'];
        $item['total_max'] = $item['capacity_max'] > 0 ? $item['count'] * $item['capacity_max'] : 0;

        return $item;
    }

    /**
     * The inventory as the live site publishes it.
     *
     * @return array<int, array<string, mixed>>
     */
    private function inventory(): array
    {
        return [
            $this->item([
                'id' => 1,
                'title' => 'Trippstein Super',
                'count' => 20,
                'capacity' => 8,
                'length' => 300,
                'width' => 440,
                'height_front' => 175,
                'height_middle' => 235,
            ]),
            $this->item([
                'id' => 2,
                'title' => 'Nepal Super',
                'count' => 4,
                'capacity' => 12,
                'length' => 460,
                'width' => 440,
                'height_front' => 175,
                'height_middle' => 235,
            ]),
            $this->item([
                'id' => 3,
                'title' => 'Grote partytent',
                'category' => 'partytent',
                'count' => 1,
                'length' => 1000,
                'width' => 400,
            ]),
            $this->item([
                'id' => 4,
                'title' => 'Partytent',
                'category' => 'partytent',
                'count' => 4,
                'length' => 300,
                'width' => 300,
            ]),
            $this->item([
                'id' => 5,
                'title' => 'Andere groepstenten',
                'count' => 0,
                'capacity' => 12,
                'capacity_max' => 42,
            ]),
            $this->item([
                'id' => 6,
                'title' => 'Slaapmatrasjes',
                'category' => 'slaapmateriaal',
                'count' => 130,
                'capacity' => 1,
            ]),
            $this->item([
                'id' => 7,
                'title' => 'Tafels en banken',
                'category' => 'overig',
                'count' => 14,
                'capacity' => 8,
                'capacity_max' => 10,
            ]),
        ];
    }

    /* Totals
    --------------------------------------------- */

    public function test_sleeping_places_are_the_sum_over_group_tents(): void
    {
        $totals = cjw_brummen_rental_totals($this->inventory());

        // 20 x 8 plus 4 x 12. The item with no aantal contributes nothing.
        $this->assertSame(208, $totals['sleeping']);
        $this->assertSame(24, $totals['tents']);
    }

    public function test_cover_is_the_sum_over_party_tents_only(): void
    {
        $totals = cjw_brummen_rental_totals($this->inventory());

        // 1 x 40 m2 plus 4 x 9 m2.
        $this->assertSame(76.0, $totals['cover']);
    }

    public function test_mattresses_count_but_do_not_join_the_sleeping_places(): void
    {
        $totals = cjw_brummen_rental_totals($this->inventory());

        $this->assertSame(130, $totals['mattresses']);
        $this->assertLessThan(
            $totals['sleeping'],
            $totals['mattresses'],
            'The gap between tent places and mattresses is a fact a hirer needs; if this ever'
            . ' inverts, the page is telling them the wrong way round.'
        );
    }

    public function test_bench_sets_are_not_counted_as_sleeping_places(): void
    {
        // "Overig" has a capacity of 8 per set, which is seats, not beds.
        $totals = cjw_brummen_rental_totals($this->inventory());

        $this->assertSame(208, $totals['sleeping']);
    }

    /* The shared drawing scale
    --------------------------------------------- */

    public function test_plan_span_is_the_largest_footprint_in_the_set(): void
    {
        $span = cjw_brummen_rental_plan_span(cjw_brummen_rental_drawable($this->inventory()));

        $this->assertSame(1000, $span['x']);
        $this->assertSame(500, $span['y'], 'A 440cm width rounds up to a whole metre.');
    }

    public function test_only_items_with_both_dimensions_are_drawable(): void
    {
        $drawable = cjw_brummen_rental_drawable($this->inventory());

        $this->assertCount(4, $drawable);
    }

    public function test_plan_span_is_zero_when_nothing_has_a_footprint(): void
    {
        $span = cjw_brummen_rental_plan_span([]);

        $this->assertSame(0, $span['x']);
        $this->assertSame(0, $span['y']);
    }

    /* What the helper may combine
    --------------------------------------------- */

    public function test_fit_stock_skips_tents_with_no_known_quantity(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());

        $titles = array_column($stock, 'title');

        $this->assertNotContains(
            'Andere groepstenten',
            $titles,
            '"Vraag naar de mogelijkheden" is not a quantity anyone can plan around.'
        );
    }

    public function test_fit_stock_skips_everything_that_is_not_a_group_tent(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());

        $this->assertSame(['Nepal Super', 'Trippstein Super'], array_column($stock, 'title'));
    }

    public function test_fit_stock_is_ordered_largest_tent_first(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());

        $this->assertSame(12, $stock[0]['capacity']);
        $this->assertSame(8, $stock[1]['capacity']);
    }

    public function test_ceiling_is_every_place_in_the_countable_tents(): void
    {
        $this->assertSame(
            208,
            cjw_brummen_rental_fit_ceiling(cjw_brummen_rental_fit_stock($this->inventory()))
        );
    }

    /* The answers
    --------------------------------------------- */

    public function test_thirty_people_get_the_fewest_tents_with_the_least_left_over(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());
        $answer = cjw_brummen_rental_fit($stock, 30);

        $this->assertNotNull($answer);
        $this->assertSame(3, $answer['tents']);
        $this->assertSame(32, $answer['places']);
        $this->assertSame(2, $answer['spare']);
    }

    public function test_an_exact_fit_reports_no_surplus(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());
        $answer = cjw_brummen_rental_fit($stock, 24);

        $this->assertNotNull($answer);
        $this->assertSame(0, $answer['spare']);
        $this->assertSame(2, $answer['tents']);
    }

    public function test_a_tie_on_tent_count_is_broken_by_the_smaller_surplus(): void
    {
        // 16 people fit in two Trippsteins exactly, or in a Nepal and a
        // Trippstein with four places going spare. Both are two tents.
        $stock = cjw_brummen_rental_fit_stock($this->inventory());
        $answer = cjw_brummen_rental_fit($stock, 16);

        $this->assertNotNull($answer);
        $this->assertSame(2, $answer['tents']);
        $this->assertSame(16, $answer['places']);
        $this->assertSame(0, $answer['spare']);
    }

    public function test_fewer_tents_beats_a_smaller_surplus(): void
    {
        // Nine people: one Nepal (one tent, three spare) rather than two
        // Trippsteins (two tents, seven spare).
        $stock = cjw_brummen_rental_fit_stock($this->inventory());
        $answer = cjw_brummen_rental_fit($stock, 9);

        $this->assertNotNull($answer);
        $this->assertSame(1, $answer['tents']);
        $this->assertSame(12, $answer['places']);
    }

    public function test_the_whole_stock_still_answers(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());
        $answer = cjw_brummen_rental_fit($stock, 208);

        $this->assertNotNull($answer);
        $this->assertSame(208, $answer['places']);
        $this->assertSame(24, $answer['tents']);
    }

    public function test_a_group_that_does_not_fit_gets_no_answer(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());

        $this->assertNull(cjw_brummen_rental_fit($stock, 209));
    }

    public function test_zero_and_negative_group_sizes_get_no_answer(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());

        $this->assertNull(cjw_brummen_rental_fit($stock, 0));
        $this->assertNull(cjw_brummen_rental_fit($stock, -5));
    }

    public function test_an_empty_stock_answers_nothing_rather_than_dividing_by_it(): void
    {
        $this->assertNull(cjw_brummen_rental_fit([], 10));
        $this->assertSame(0, cjw_brummen_rental_fit_ceiling([]));
        $this->assertSame([], cjw_brummen_rental_fit_table([]));
    }

    public function test_every_answer_holds_at_least_the_group_it_is_for(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());
        $table = cjw_brummen_rental_fit_table($stock);

        $this->assertCount(208, $table);

        foreach ($table as $people => $answer) {
            $this->assertGreaterThanOrEqual(
                $people,
                $answer['places'],
                "The helper offers {$answer['places']} places to a group of {$people}."
            );

            $places = 0;

            foreach ($answer['parts'] as $part) {
                $places += $part['places'];
            }

            $this->assertSame(
                $answer['places'],
                $places,
                "The tents listed for {$people} people do not add up to the total shown beside them."
            );
        }
    }

    public function test_no_answer_uses_more_tents_than_there_are(): void
    {
        $stock = cjw_brummen_rental_fit_stock($this->inventory());
        $available = [];

        foreach ($stock as $tent) {
            $available[ $tent['title'] ] = $tent['count'];
        }

        foreach (cjw_brummen_rental_fit_table($stock) as $people => $answer) {
            foreach ($answer['parts'] as $part) {
                $this->assertLessThanOrEqual(
                    $available[ $part['title'] ],
                    $part['units'],
                    "For {$people} people the helper offers {$part['units']} × {$part['title']},"
                    . ' and CJW does not own that many.'
                );
            }
        }
    }

    /* Labels
    --------------------------------------------- */

    public function test_whole_metres_lose_the_trailing_decimal(): void
    {
        $this->assertSame('3', cjw_brummen_rental_metres(300));
        $this->assertSame('10', cjw_brummen_rental_metres(1000));
    }

    public function test_part_metres_keep_a_dutch_decimal_comma(): void
    {
        $this->assertSame('4,4', cjw_brummen_rental_metres(440));
    }

    public function test_a_capacity_range_reads_as_a_range(): void
    {
        $item = $this->item(['capacity' => 12, 'capacity_max' => 42]);

        $this->assertSame('12 tot 42 personen', cjw_brummen_rental_capacity_label($item));
    }

    public function test_a_single_capacity_reads_as_one_number(): void
    {
        $item = $this->item(['capacity' => 8]);

        $this->assertSame('8 personen', cjw_brummen_rental_capacity_label($item));
    }

    public function test_an_item_measured_in_something_other_than_people_has_no_capacity_label(): void
    {
        $item = $this->item(['category' => 'partytent', 'length' => 300, 'width' => 300]);

        $this->assertSame('', cjw_brummen_rental_capacity_label($item));
    }

    public function test_the_size_label_includes_both_heights_when_both_are_set(): void
    {
        $item = $this->item([
            'length' => 300,
            'width' => 440,
            'height_front' => 175,
            'height_middle' => 235,
        ]);

        $this->assertSame('300 × 440 × 175/235 cm', cjw_brummen_rental_size_label($item));
    }

    public function test_the_size_label_is_empty_without_a_footprint(): void
    {
        $this->assertSame('', cjw_brummen_rental_size_label($this->item(['count' => 130])));
    }

    public function test_the_total_column_multiplies_stock_by_capacity(): void
    {
        $item = $this->item(['count' => 20, 'capacity' => 8]);

        $this->assertSame('160 personen', cjw_brummen_rental_total_label($item));
    }

    public function test_the_total_column_multiplies_a_range_at_both_ends(): void
    {
        $item = $this->item([
            'category' => 'overig',
            'count' => 14,
            'capacity' => 8,
            'capacity_max' => 10,
        ]);

        $this->assertSame('112–140 personen', cjw_brummen_rental_total_label($item));
    }

    public function test_the_total_column_uses_square_metres_for_party_tents(): void
    {
        $item = $this->item([
            'category' => 'partytent',
            'count' => 4,
            'length' => 300,
            'width' => 300,
        ]);

        $this->assertSame('36 m²', cjw_brummen_rental_total_label($item));
    }

    public function test_an_item_on_request_has_no_total(): void
    {
        $item = $this->item(['count' => 0, 'capacity' => 12, 'capacity_max' => 42]);

        $this->assertSame('—', cjw_brummen_rental_total_label($item));
    }
}
