<?php
/**
 * cagrimmett-2026: styles, a month-grouped post index, and longer list pages.
 */

add_action( 'after_setup_theme', function () {
	add_editor_style( 'style.css' );
} );

add_action( 'wp_enqueue_scripts', function () {
	// After global styles so the dark-scheme token overrides win.
	wp_enqueue_style( 'cagrimmett-2026', get_stylesheet_uri(), array( 'global-styles' ), wp_get_theme()->get( 'Version' ) );
} );

/**
 * Title-only lists are short per item, so show more of them per page.
 */
add_action( 'pre_get_posts', function ( $query ) {
	if ( is_admin() || ! $query->is_main_query() || $query->is_feed() ) {
		return;
	}
	if ( $query->is_archive() || $query->is_search() ) {
		// Likes and notes show their content, so they get shorter pages than title lists.
		$query->set( 'posts_per_page', $query->is_post_type_archive( array( 'indieblocks_like', 'indieblocks_note' ) ) ? 30 : 60 );
	}
} );

/**
 * [cg_post_index] lists posts one per line, grouped by month.
 *
 * With count="N" it runs its own query for the latest N posts (homepage);
 * count="all" lists every post under year headings, with a jump list of years on top (the blog index page).
 * Without it, it renders the main query and its pagination (blog, archives, search).
 * heading="3" sets the month heading level.
 */
add_shortcode( 'cg_post_index', function ( $atts ) {
	global $wp_query;

	$atts  = shortcode_atts( array( 'count' => 0, 'heading' => 2 ), $atts );
	$count = 'all' === $atts['count'] ? -1 : absint( $atts['count'] );
	$tag   = 'h' . min( 6, max( 2, absint( $atts['heading'] ) ) );
	$query = $count
		? new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => $count, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) )
		: $wp_query;

	if ( ! $query->have_posts() ) {
		return '<p>Nothing here yet.</p>';
	}

	$all   = -1 === $count;
	$mtag  = $all ? 'h' . min( 6, (int) substr( $tag, 1 ) + 1 ) : $tag;
	$years = array();
	$month = '';
	$out   = '';
	while ( $query->have_posts() ) {
		$query->the_post();

		$this_month = get_the_date( 'F Y' );
		if ( $this_month !== $month ) {
			$out .= $month ? '</ul>' : '';
			$year = get_the_date( 'Y' );
			if ( $all && ! in_array( $year, $years, true ) ) {
				$years[] = $year;
				$out    .= sprintf( '<%1$s class="cg-year" id="y%2$s">%2$s</%1$s>', $tag, esc_html( $year ) );
			}
			$out  .= sprintf( '<%1$s class="cg-month">%2$s</%1$s><ul class="cg-posts">', $mtag, esc_html( $all ? get_the_date( 'F' ) : $this_month ) );
			$month = $this_month;
		}

		$cats  = get_the_category();
		$title = get_the_title();
		$out  .= sprintf(
			'<li><a href="%s"><span class="cg-t">%s</span><span class="cg-lead" aria-hidden="true"></span>%s<time class="cg-d" datetime="%s">%s</time></a></li>',
			esc_url( get_permalink() ),
			'' !== $title ? $title : esc_html__( '(untitled)', 'cagrimmett-2026' ),
			$cats ? '<span class="cg-cat">' . esc_html( $cats[0]->name ) . '</span>' : '',
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date( 'M j' ) )
		);
	}
	$out .= '</ul>';

	if ( $all ) {
		$toc = '';
		foreach ( $years as $year ) {
			$toc .= sprintf( '<li><a href="#y%1$s">%1$s</a></li>', esc_html( $year ) );
		}
		$out = '<nav class="cg-years" aria-label="Jump to year"><ul>' . $toc . '</ul></nav>' . $out;
	}

	if ( $count ) {
		wp_reset_postdata();
	} else {
		$query->rewind_posts();
		$out .= get_the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => 'Newer', 'next_text' => 'Older', 'class' => 'cg-pagination' ) );
	}

	return $out;
} );
