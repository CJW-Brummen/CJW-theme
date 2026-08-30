<?php

/**
 * The rental inventory, read from the cjw plugin's `verhuurmateriaal` records.
 *
 * The verhuurpagina shows the same inventory three ways -- the "Past het?"
 * helper, cards with a to-scale footprint, and one specification table -- and
 * all three are rendered from the array this file assembles. Every derived
 * figure on the page (208 slaapplaatsen, 76 m2 overkapping, the scale the
 * footprints are drawn at) is a sum over these records rather than a number
 * anybody types, so the three views cannot drift apart from each other or from
 * the list they sit under.
 *
 * As everywhere else in this theme, the plugin is the source of truth and its
 * absence is not an error: with no plugin there are no records, and the page
 * template renders its prose and nothing else.
 *
 * @package cjw-brummen
 */

/**
 * Where the assembled inventory is cached.
 */
if (! defined('CJW_BRUMMEN_RENTAL_TRANSIENT')) {
    define('CJW_BRUMMEN_RENTAL_TRANSIENT', 'cjw_brummen_verhuur');
}

/**
 * The rental post type's name, repeated here so the theme can ask whether it
 * exists without loading the plugin.
 */
if (! defined('CJW_BRUMMEN_RENTAL_POST_TYPE')) {
    define('CJW_BRUMMEN_RENTAL_POST_TYPE', 'verhuurmateriaal');
}

/**
 * Every item CJW hires out, in the order set on the admin screen.
 *
 * Cached like the sponsor wall, and for the same reason: a query plus a
 * thumbnail and eight meta lookups per item, for data that changes a handful of
 * times a year. The cache is cleared whenever an item is saved, trashed or
 * reordered; the day-long expiry only covers a change made without any of those
 * firing, such as a direct database edit.
 *
 * @return array<int, array{
 *     id: int, title: string, category: string, count: int, capacity: int,
 *     capacity_max: int, length: int, width: int, height_front: int,
 *     height_middle: int, note: string, image_id: int, area: float, total: int,
 *     total_max: int
 * }>
 */
function cjw_brummen_rental_items()
{
    if (! post_type_exists(CJW_BRUMMEN_RENTAL_POST_TYPE)) {
        return [];
    }

    $cached = get_transient(CJW_BRUMMEN_RENTAL_TRANSIENT);

    if (is_array($cached)) {
        return $cached;
    }

    $query = new WP_Query(
        [
            'post_type' => CJW_BRUMMEN_RENTAL_POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => 50,
            'orderby' => 'menu_order title',
            'order' => 'ASC',
            'no_found_rows' => true,
            'update_post_term_cache' => false,
        ]
    );

    $items = [];

    foreach ($query->posts as $cjw_brummen_rental_post) {
        $items[] = cjw_brummen_rental_item($cjw_brummen_rental_post);
    }

    set_transient(CJW_BRUMMEN_RENTAL_TRANSIENT, $items, DAY_IN_SECONDS);

    return $items;
}

/**
 * Normalises one item post into the shape the templates read.
 *
 * @param WP_Post $post Rental item.
 * @return array{
 *     id: int, title: string, category: string, count: int, capacity: int,
 *     capacity_max: int, length: int, width: int, height_front: int,
 *     height_middle: int, note: string, image_id: int, area: float, total: int,
 *     total_max: int
 * }
 */
function cjw_brummen_rental_item($post)
{
    $id = (int) $post->ID;

    $count = cjw_brummen_rental_number($id, 'cjw_verhuur_aantal');
    $capacity = cjw_brummen_rental_number($id, 'cjw_verhuur_capaciteit');
    $capacity_max = cjw_brummen_rental_number($id, 'cjw_verhuur_capaciteit_tot');
    $length = cjw_brummen_rental_number($id, 'cjw_verhuur_lengte');
    $width = cjw_brummen_rental_number($id, 'cjw_verhuur_breedte');

    $note = get_post_meta($id, 'cjw_verhuur_toelichting', true);
    $category = get_post_meta($id, 'cjw_verhuur_categorie', true);
    $category = is_string($category) ? $category : '';

    if (! in_array($category, [ 'groepstent', 'partytent', 'slaapmateriaal', 'overig' ], true)) {
        $category = 'overig';
    }

    // The upper bound only means anything when it is above the lower one; a
    // stray "8 tot 8" would otherwise print as a range with one value in it.
    if ($capacity_max <= $capacity) {
        $capacity_max = 0;
    }

    return [
        'id' => $id,
        'title' => get_the_title($post),
        'category' => $category,
        'count' => $count,
        'capacity' => $capacity,
        'capacity_max' => $capacity_max,
        'length' => $length,
        'width' => $width,
        'height_front' => cjw_brummen_rental_number($id, 'cjw_verhuur_hoogte_voor'),
        'height_middle' => cjw_brummen_rental_number($id, 'cjw_verhuur_hoogte_midden'),
        'note' => is_string($note) ? $note : '',
        'image_id' => (int) get_post_thumbnail_id($post),
        'area' => $length > 0 && $width > 0 ? round(($length * $width) / 10000, 1) : 0.0,
        'total' => $count * $capacity,
        'total_max' => $capacity_max > 0 ? $count * $capacity_max : 0,
    ];
}

/**
 * Reads one numeric meta field off an item, clamped to a whole number >= 0.
 *
 * Meta comes back as a string, and these values are multiplied and drawn with:
 * an unclamped read turns a typo into a footprint rectangle drawn inside out,
 * or a capacity that concatenates instead of adding.
 *
 * @param int $post_id Item id.
 * @param string $key Meta key.
 * @return int
 */
function cjw_brummen_rental_number($post_id, $key)
{
    $value = get_post_meta($post_id, $key, true);

    return is_numeric($value) ? max(0, (int) $value) : 0;
}

/**
 * The figures the page states about the inventory as a whole.
 *
 * Each one is a sum over a category whose total means something: group tents
 * add up to sleeping places, party tents to square metres of cover, sleeping
 * material to a plain count. "Overig" has no total, because 130 mattresses plus
 * 14 table sets is not a number anybody wants.
 *
 * @param array<int, array<string, mixed>> $items Inventory.
 * @return array{sleeping: int, tents: int, mattresses: int, cover: float}
 */
function cjw_brummen_rental_totals($items)
{
    $totals = [
        'sleeping' => 0,
        'tents' => 0,
        'mattresses' => 0,
        'cover' => 0.0,
    ];

    foreach ($items as $item) {
        if ('groepstent' === $item['category']) {
            $totals['sleeping'] += (int) $item['count'] * (int) $item['capacity'];
            $totals['tents'] += (int) $item['count'];
        }

        if ('partytent' === $item['category']) {
            $totals['cover'] += (int) $item['count'] * (float) $item['area'];
        }

        if ('slaapmateriaal' === $item['category']) {
            $totals['mattresses'] += (int) $item['count'];
        }
    }

    $totals['cover'] = round($totals['cover'], 1);

    return $totals;
}

/**
 * The items that get a footprint drawing: anything with a length and a width.
 *
 * @param array<int, array<string, mixed>> $items Inventory.
 * @return array<int, array<string, mixed>>
 */
function cjw_brummen_rental_drawable($items)
{
    $drawable = [];

    foreach ($items as $item) {
        if ((int) $item['length'] > 0 && (int) $item['width'] > 0) {
            $drawable[] = $item;
        }
    }

    return $drawable;
}

/**
 * The shared scale every footprint is drawn at, in centimetres.
 *
 * One scale for all of them is the whole point of the drawings: four rectangles
 * on one grid answer "which tent do I need" in a way four dimension strings
 * cannot. So the span is the largest span in the set, rounded up to a whole
 * metre, and every plan uses it -- a 3 x 3 party tent really does render as a
 * ninth of the big one.
 *
 * @param array<int, array<string, mixed>> $items Drawable items.
 * @return array{x: int, y: int}
 */
function cjw_brummen_rental_plan_span($items)
{
    $x = 0;
    $y = 0;

    foreach ($items as $item) {
        $x = max($x, (int) $item['length']);
        $y = max($y, (int) $item['width']);
    }

    return [
        'x' => $x > 0 ? (int) ceil($x / 100) * 100 : 0,
        'y' => $y > 0 ? (int) ceil($y / 100) * 100 : 0,
    ];
}

/**
 * Centimetres as metres, the way a Dutch sentence writes them.
 *
 * 300 becomes "3" and 440 becomes "4,4": a trailing ",0" on a whole number of
 * metres reads as a measurement someone took, rather than a size a tent comes in.
 *
 * @param int $centimetres Length in cm.
 * @return string
 */
function cjw_brummen_rental_metres($centimetres)
{
    $centimetres = (int) $centimetres;
    $decimals = 0 === $centimetres % 100 ? 0 : 1;

    return number_format_i18n($centimetres / 100, $decimals);
}

/**
 * "8 personen", or "8 tot 10 personen" for an item with a range.
 *
 * Answers an empty string when personen do not apply -- a party tent is
 * measured in square metres and a stack of tables in sets.
 *
 * @param array<string, mixed> $item One inventory item.
 * @return string
 */
function cjw_brummen_rental_capacity_label($item)
{
    $from = (int) $item['capacity'];
    $to = (int) $item['capacity_max'];

    if ($from < 1) {
        return '';
    }

    if ($to > $from) {
        return sprintf(
            /* translators: 1: lower bound, 2: upper bound. */
            _n('%1$d tot %2$d persoon', '%1$d tot %2$d personen', $to, 'cjw-brummen'),
            $from,
            $to
        );
    }

    return sprintf(
        /* translators: %d: number of people. */
        _n('%d persoon', '%d personen', $from, 'cjw-brummen'),
        $from
    );
}

/**
 * Square metres, without a trailing ",0" on a whole number.
 *
 * @param float $area Area in square metres.
 * @return string
 */
function cjw_brummen_rental_area_label($area)
{
    $area = (float) $area;
    $decimals = abs($area - round($area)) < 0.05 ? 0 : 1;

    return number_format_i18n($area, $decimals);
}

/**
 * What the whole stock of one item adds up to: "160 personen", "40 m2", or "-".
 *
 * The live page prints "Trippstein Super (20x)" and "voor 8 personen" in two
 * different sentences and never multiplies them, so the reader does. This is
 * that multiplication, done once, in the column beside the two numbers it came
 * from.
 *
 * @param array<string, mixed> $item One inventory item.
 * @return string
 */
function cjw_brummen_rental_total_label($item)
{
    $count = (int) $item['count'];

    if ($count < 1) {
        return '—';
    }

    if ('partytent' === $item['category'] && (float) $item['area'] > 0) {
        return sprintf(
            /* translators: %s: total square metres of cover. */
            __('%s m²', 'cjw-brummen'),
            cjw_brummen_rental_area_label($count * (float) $item['area'])
        );
    }

    $total = (int) $item['total'];

    if ($total < 1) {
        return '—';
    }

    if ((int) $item['total_max'] > $total) {
        return sprintf(
            /* translators: 1: lower bound, 2: upper bound. */
            __('%1$d–%2$d personen', 'cjw-brummen'),
            $total,
            (int) $item['total_max']
        );
    }

    return sprintf(
        /* translators: %d: number of people. */
        _n('%d persoon', '%d personen', $total, 'cjw-brummen'),
        $total
    );
}

/**
 * "300 x 440 x 175/235 cm", built from whichever of the four sizes are set.
 *
 * @param array<string, mixed> $item One inventory item.
 * @return string
 */
function cjw_brummen_rental_size_label($item)
{
    $length = (int) $item['length'];
    $width = (int) $item['width'];
    $front = (int) $item['height_front'];
    $middle = (int) $item['height_middle'];

    if ($length < 1 || $width < 1) {
        return '';
    }

    $size = sprintf('%1$d × %2$d', $length, $width);

    if ($front > 0 && $middle > 0) {
        $size .= sprintf(' × %1$d/%2$d', $front, $middle);
    } elseif ($front > 0 || $middle > 0) {
        $size .= sprintf(' × %d', max($front, $middle));
    }

    return $size . ' cm';
}

/**
 * The group tents the "Past het?" helper is allowed to combine.
 *
 * A tent counts when CJW knows how many it has and how many fit in one. The
 * live site's "diverse groepstenten voor 12 tot 42 personen, vraag naar de
 * mogelijkheden" is exactly the case that must not count: an item with no
 * aantal is listed on the page, and the helper says to ask about it instead of
 * quietly pretending it does not exist.
 *
 * @param array<int, array<string, mixed>> $items Inventory.
 * @return array<int, array{title: string, capacity: int, count: int}>
 */
function cjw_brummen_rental_fit_stock($items)
{
    $stock = [];

    foreach ($items as $item) {
        if ('groepstent' !== $item['category']) {
            continue;
        }

        if ((int) $item['count'] < 1 || (int) $item['capacity'] < 1) {
            continue;
        }

        $stock[] = [
            'title' => (string) $item['title'],
            'capacity' => (int) $item['capacity'],
            'count' => (int) $item['count'],
        ];
    }

    // Largest first, so a combination reads the way somebody would say it out
    // loud: two big tents and a small one, not a list in admin order.
    usort(
        $stock,
        static function ($left, $right) {
            return $right['capacity'] <=> $left['capacity'];
        }
    );

    return $stock;
}

/**
 * How many people fit in the group tents altogether.
 *
 * @param array<int, array{title: string, capacity: int, count: int}> $stock Countable tents.
 * @return int
 */
function cjw_brummen_rental_fit_ceiling($stock)
{
    $ceiling = 0;

    foreach ($stock as $tent) {
        $ceiling += $tent['capacity'] * $tent['count'];
    }

    return $ceiling;
}

/**
 * Every combination of tents that is worth considering, as places => plan.
 *
 * A bounded-knapsack pass over the stock, run once: for each kind of tent, take
 * one to all of them on top of every total reached so far, and keep the cheapest
 * way to reach each total. Everything the helper answers is read out of this
 * map, so the search runs a single time per page rather than once per possible
 * group size.
 *
 * @param array<int, array{title: string, capacity: int, count: int}> $stock Countable tents.
 * @return array<int, array{tents: int, used: array<int, int>}>
 */
function cjw_brummen_rental_fit_plans($stock)
{
    $ceiling = cjw_brummen_rental_fit_ceiling($stock);
    // Assigned rather than declared as one nested literal: phpcs and
    // php-cs-fixer format that literal two different ways and rewrite each
    // other's version on every run.
    $plans = [];
    $plans[0] = [
        'tents' => 0,
        'used' => [],
    ];

    foreach ($stock as $index => $tent) {
        $next = $plans;

        foreach ($plans as $places => $plan) {
            for ($units = 1; $units <= $tent['count']; $units++) {
                $reach = $places + ($tent['capacity'] * $units);

                if ($reach > $ceiling) {
                    break;
                }

                $tents = $plan['tents'] + $units;

                if (isset($next[ $reach ]) && $next[ $reach ]['tents'] <= $tents) {
                    continue;
                }

                $used = $plan['used'];
                $used[ $index ] = $units;
                $next[ $reach ] = [
                    'tents' => $tents,
                    'used' => $used,
                ];
            }
        }

        $plans = $next;
    }

    return $plans;
}

/**
 * The fewest tents that sleep a given number of people.
 *
 * Fewest tents first, and among equal counts the one with the least surplus, so
 * 30 people get two Nepals and one Trippstein (three tents, 32 places) rather
 * than four Trippsteins (four tents, 32 places) or three Nepals (three tents,
 * 36 places).
 *
 * Answers null when the group does not fit at all -- the caller then says to ask
 * about the other tents rather than printing an impossible combination.
 *
 * @param array<int, array{title: string, capacity: int, count: int}> $stock Countable tents.
 * @param array<int, array{tents: int, used: array<int, int>}> $plans Result of cjw_brummen_rental_fit_plans().
 * @param int $people Group size.
 * @return array{places: int, spare: int, tents: int, parts: array<int, array{title: string, units: int, places: int}>}|null
 */
function cjw_brummen_rental_answer($stock, $plans, $people)
{
    $people = (int) $people;

    if ($people < 1 || [] === $stock) {
        return null;
    }

    $choice = null;
    $chosen_places = 0;

    foreach ($plans as $places => $plan) {
        if ($places < $people) {
            continue;
        }

        if (
            null === $choice
            || $plan['tents'] < $choice['tents']
            || ($plan['tents'] === $choice['tents'] && $places < $chosen_places)
        ) {
            $choice = $plan;
            $chosen_places = $places;
        }
    }

    if (null === $choice) {
        return null;
    }

    $parts = [];

    foreach ($choice['used'] as $index => $units) {
        $parts[] = [
            'title' => $stock[ $index ]['title'],
            'units' => $units,
            'places' => $units * $stock[ $index ]['capacity'],
        ];
    }

    return [
        'places' => $chosen_places,
        'spare' => $chosen_places - $people,
        'tents' => $choice['tents'],
        'parts' => $parts,
    ];
}

/**
 * One answer, for a caller that has no plans map to hand.
 *
 * @param array<int, array{title: string, capacity: int, count: int}> $stock Countable tents.
 * @param int $people Group size.
 * @return array{places: int, spare: int, tents: int, parts: array<int, array{title: string, units: int, places: int}>}|null
 */
function cjw_brummen_rental_fit($stock, $people)
{
    return cjw_brummen_rental_answer($stock, cjw_brummen_rental_fit_plans($stock), $people);
}

/**
 * Every answer the helper can give, for the browser to look up.
 *
 * The arithmetic lives in PHP only. Working it out again in JavaScript would be
 * the same rules written twice, and two copies of a rule are two rules; instead
 * the page ships the finished answers and the script does nothing but read one
 * out as you type. It is a few kilobytes for an inventory this size.
 *
 * @param array<int, array{title: string, capacity: int, count: int}> $stock Countable tents.
 * @return array<int, array{places: int, spare: int, tents: int, parts: array<int, array{title: string, units: int, places: int}>}>
 */
function cjw_brummen_rental_fit_table($stock)
{
    $plans = cjw_brummen_rental_fit_plans($stock);

    // A guard, not a limit anyone will meet: the inventory is two dozen tents.
    // It stops a mistyped aantal from building a million-row lookup table.
    $ceiling = min(cjw_brummen_rental_fit_ceiling($stock), 1000);

    $table = [];

    for ($people = 1; $people <= $ceiling; $people++) {
        $answer = cjw_brummen_rental_answer($stock, $plans, $people);

        if (null !== $answer) {
            $table[ $people ] = $answer;
        }
    }

    return $table;
}

/**
 * Forgets the cached inventory.
 *
 * @return void
 */
function cjw_brummen_flush_rental_cache()
{
    delete_transient(CJW_BRUMMEN_RENTAL_TRANSIENT);
}

/**
 * Loads the "Past het?" script, on any page that has the helper block on it.
 *
 * The form works without it: it is a GET form that the template answers
 * server-side. The script only saves the round trip.
 *
 * Hooked up in functions.php, not here: this file stays a set of pure helpers
 * so the fast test suite can load it with no WordPress underneath, exactly as
 * inc/summer-camp.php does.
 *
 * @return void
 */
function cjw_brummen_rental_scripts()
{
    // Keyed off the block, not the page template: the helper is a block now,
    // so it can sit on any page, and the verhuurpagina can be composed without
    // it. Loading the answer table on a page that has no form to answer would
    // be a few kilobytes for nothing.
    if (! is_singular() || ! has_block('cjw/verhuur-past-het')) {
        return;
    }

    $items = cjw_brummen_rental_items();
    $stock = cjw_brummen_rental_fit_stock($items);

    if ([] === $stock) {
        return;
    }

    wp_enqueue_script(
        'cjw-brummen-verhuur',
        get_template_directory_uri() . '/js/verhuur.js',
        [],
        CJW_BRUMMEN_VERSION,
        true
    );

    wp_localize_script(
        'cjw-brummen-verhuur',
        'cjwVerhuur',
        [
            'answers' => cjw_brummen_rental_fit_table($stock),
            'ceiling' => cjw_brummen_rental_fit_ceiling($stock),
            'mattresses' => cjw_brummen_rental_totals($items)['mattresses'],
            'strings' => [
                'places' => __('plaatsen in totaal', 'cjw-brummen'),
                'exact' => __('plaatsen in totaal, precies genoeg', 'cjw-brummen'),
                /* translators: %d: number of unused places. */
                'spare' => __('plaatsen in totaal, %d over', 'cjw-brummen'),
                /* translators: 1: tent name, 2: number of places those tents hold. */
                'part' => __('%1$s — %2$d plaatsen', 'cjw-brummen'),
                'tooMany' => __('plaatsen is alles wat er in de groepstenten past. Vraag naar de mogelijkheden voor grotere groepen.', 'cjw-brummen'),
                /* translators: 1: number of mattresses, 2: group size, 3: how many short. */
                'mattressWarning' => __('Let op: we hebben %1$d matrassen. Voor %2$d personen neem je er zelf %3$d mee.', 'cjw-brummen'),
                'empty' => __('Vul een aantal personen in.', 'cjw-brummen'),
            ],
        ]
    );
}
