<?php
/**
 * cagrimmett-com: styles, a month-grouped post index, and longer list pages.
 */

add_action( 'after_setup_theme', function () {
	add_editor_style( 'style.css' );
} );

add_action( 'wp_enqueue_scripts', function () {
	// After global styles so the dark-scheme token overrides win.
	wp_enqueue_style( 'cagrimmett-com', get_stylesheet_uri(), array( 'global-styles' ), wp_get_theme()->get( 'Version' ) );
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
			'' !== $title ? $title : esc_html__( '(untitled)', 'cagrimmett-com' ),
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

/*
 * Reading page: turns each "year heading, count line, bulleted list" run into a book list
 * with a year jump list on top. Bullets stay plain "Title by Author (year) ★ - note" in the editor.
 */
add_filter( 'the_content', function ( $content ) {
	if ( ! is_page( 'reading' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$toc = '';
	$out = preg_replace_callback(
		'~<h2[^>]*>\s*(\d{4})\s*</h2>\s*(?:<p[^>]*>(.*?)</p>\s*)?<ul[^>]*>(.*?)</ul>~s',
		function ( $m ) use ( &$toc ) {
			preg_match_all( '~<li[^>]*>(.*?)</li>~s', $m[3], $items );
			$rows  = '';
			$stars = 0;
			foreach ( $items[1] as $li ) {
				$li   = preg_replace( '~</?a\b[^>]*>~i', '', $li ); // Titles only; links in the list are not shown.
				$li   = str_replace( array( '&nbsp;', "\xC2\xA0" ), ' ', $li );
				$star = false !== strpos( $li, '★' );
				$li   = trim( str_replace( '★', '', $li ) );
				$stars += (int) $star;
				$by   = '';
				$note = '';
				if ( preg_match( '~^(.*?)\sby\s+(.*?)\s*(?:\((\d{4})\))?(?:\s(?:-|–|&#8211;)\s+(.*))?$~su', $li, $p ) ) {
					$li   = $p[1];
					$by   = '<span class="cg-by"><span>' . $p[2] . '</span>' . ( empty( $p[3] ) ? '' : '<span>' . $p[3] . '</span>' ) . '</span>';
					$note = empty( $p[4] ) ? '' : '<span class="cg-note">' . $p[4] . '</span>';
				}
				$rows .= ( $star ? '<li class="is-star">' : '<li>' ) . ( $star ? '<span class="cg-star" role="img" aria-label="Recommended">★</span>' : '' ) . '<span class="cg-t">' . $li . '</span> ' . $by . $note . '</li>';
			}

			// Keep any hand-written split ("23 non-fiction, 10 fiction") after the computed count.
			$split = isset( $m[2] ) ? trim( wp_strip_all_tags( preg_replace( '~^.*?<br\s*/?>~s', '', $m[2], 1, $n ) ) ) : '';
			$count = sprintf( _n( '%d book', '%d books', count( $items[1] ), 'cagrimmett-com' ), count( $items[1] ) );
			$toc  .= sprintf( '<li><a href="#y%1$s">%1$s</a></li>', $m[1] );

			return sprintf(
				'<section class="cg-reading"><h2 class="cg-year" id="y%1$s">%1$s</h2><p class="cg-count">%2$s</p><ul class="cg-books">%3$s</ul></section>',
				$m[1],
				esc_html( $count . ( ! empty( $n ) && '' !== $split ? ' · ' . $split : '' ) . ( $stars ? ' · ' . $stars . ' starred' : '' ) ),
				$rows
			);
		},
		$content,
		-1,
		$sections
	);

	if ( ! $sections ) {
		return $content;
	}

	// The checkbox filters with CSS :has(), no script.
	$nav = '<div class="cg-reading-tools"><nav class="cg-years" aria-label="Jump to year"><ul>' . $toc . '</ul></nav>'
		. '<label class="cg-starred"><input type="checkbox"> ' . esc_html__( 'Starred only', 'cagrimmett-com' ) . '</label></div>';
	return preg_replace( '~<section class="cg-reading">~', $nav . '$0', $out, 1 );
}, 12 );
