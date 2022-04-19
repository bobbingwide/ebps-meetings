<?php
/**
 * @package ebps-meetings
 *
 */


/**
 * Loads up to 3 future tribe_events.
 *
 * Assumptions:
 * - We only need to see public events
 * - Look for events that haven't yet ended.
 *
 * @return array zero to three tribe_events posts
 */
function ebps_get_upcoming_events() {
    $today = wp_date( 'Y-m-d H:i:s' );
    $args = [ 'post_type' => 'tribe_events'
        , 'meta_key' => '_EventEndDate'
        , 'meta_value' => $today
        , 'meta_compare' => '>='
        , 'numberposts' => 3
        , 'orderby' => 'meta_value'
        , 'order' => 'ASC'
    ];
    $events = get_posts( $args );
    return $events;
}

/**
 * Returns the formatted upcoming events.
 *
 * @param array $events
 * @return string
 */
function ebps_meetings_upcoming_events( $events ) {
    $html = '<div class="tribe-events-widget-events-list__events">';
    foreach ($events as $event) {
        $html .= ebps_meetings_upcoming_event($event);
    }
    $html .= '</div>';
    return $html;
}

/**
 * Return the HTML for a single event.
 *
 * This code just reproduces the output of the events calendar.
 * - The HTML is the same; saves changing the CSS for the moment.
 * - The values for the variables are hardcoded.
 * - Not all of the variable values are being replaced.
 *
 * @param $event
 * @return string
 */
function ebps_meetings_upcoming_event ($event ) {
    $_EventStartDate = ebps_get_event_date( $event );
    $_EventEndDate = ebps_get_event_date( $event, '_EventEndDate');
    ebps_first_event_end_time( $_EventEndDate );
    $datetime = wp_date('Y-m-d', $_EventStartDate );
    $month = wp_date( 'M', $_EventStartDate );
    $day = wp_date( 'j', $_EventStartDate );
    $timezone = new DateTimeZone('+00:00' );
    $start_time = wp_date('g:i a', $_EventStartDate, $timezone );
    $end_time = wp_date( 'g:i a', $_EventEndDate, $timezone );

    $html = '<div class="tribe-common-g-row tribe-events-widget-events-list__event-row">';
    $html .= '<div class="tribe-events-widget-events-list__event-date-tag tribe-common-g-col">';
    $html .= '<time class="tribe-events-widget-events-list__event-date-tag-datetime" datetime="' . $datetime . '">';
    $html .= '<span class="tribe-events-widget-events-list__event-date-tag-month">';
    $html .= $month;
    $html .= '</span>';
    $html .= '<span class="tribe-events-widget-events-list__event-date-tag-daynum tribe-common-h2 tribe-common-h4--min-medium">';
    $html .= $day;
    $html .= '</span>';
    $html .= '</time>';
    $html .= '</div>';
    $html .= '<div class="tribe-events-widget-events-list__event-wrapper tribe-common-g-col">';
    $html .= '<article class="tribe-events-widget-events-list__event post-' . $event->ID . ' tribe_events type-tribe_events status-publish has-post-thumbnail hentry tribe_events_cat-meetings tribe_events_cat-national tribe_events_cat-zoom cat_meetings cat_national cat_zoom">';
    $html .= '<div class="tribe-events-widget-events-list__event-details">';
    $html .= '<header class="tribe-events-widget-events-list__event-header">';
    $html .= '<div class="tribe-events-widget-events-list__event-datetime-wrapper tribe-common-b2 tribe-common-b3--min-medium">';
    $html .= '<time class="tribe-events-widget-events-list__event-datetime" datetime="' . $datetime . '">';
    $html .= '<span class="tribe-event-date-start">' . $start_time . '</span> - <span class="tribe-event-time">' . $end_time . '</span>	</time>';
    $html .= '</div>';
    $html .= '<h3 class="tribe-events-widget-events-list__event-title tribe-common-h7">';
    $html .= '<a href="' . ebps_get_permalink( $event->ID ) .'" ';
	$html .= 'title="' . $event->post_title . '" ';
	$html .= 'rel="bookmark" class="tribe-events-widget-events-list__event-title-link tribe-common-anchor-thin">';
	$html .= $event->post_title;
	$html .= '</a>';
	$html .= '</h3>';
	$html .= '</header>';
	$html .= '</div>';
	$html .= '</article>';
	$html .= '</div>';
	$html .= '</div>';
    return $html;
}

function ebps_get_event_date( $event, $meta_key='_EventStartDate' ) {
    $_EventDate = get_post_meta( $event->ID, $meta_key, true);
    $datetime = strtotime( $_EventDate);
    return $datetime;
}

/**
 * Sets/gets the end time of the first event.
 *
 * @param integer| null $end_time
 * @return int|mixed current value.
 */
function ebps_first_event_end_time( $end_time=null ) {
    static $first_event_end_time = 0;
    if ( null !== $end_time && 0 === $first_event_end_time ) {
        $first_event_end_time = $end_time;
    }
    return $first_event_end_time;
}

/**
 * Registers the post type if it's not already registered.
 *
 * We could have hooked into `registered_post_type` and checked the post types that have been registered
 * but that's less efficient than waiting until other plugins have done their bit
 * then checking if the post type is already registered.
 * If it's not been registered then register it.
 * Note: The values were obtained by seeing what the parameters are to `tribe_events_register_event_type_args`.
 *
 * @return bool true if we registered the tribe_events post type, false if it's already registered.
 */
function ebps_maybe_register_tribe_events() {
    if ( post_type_exists( 'tribe_events') ) {
        return false;
    }
    $post_type_args = [
        'rewrite' => [ 'slug' => "event", 'with_front' => false ]
        , 'menu_position' => 6
        , 'supports' => [ 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields', 'comments', 'revisions']
        //     [taxonomies] => Array        [0] => (string) "post_tag"
        //       [capability_type] => Array
        //
        //        [0] => (string) "tribe_event"
        //        [1] => (string) "tribe_events"
        , 'map_meta_cap' => true
        , 'has_archive' => true
        , 'menu_icon' => "dashicons-calendar"
        , 'show_in_rest' => true
        , 'labels' => [
            'name' => "Events"
            , 'singular_name' => "Event"
            , 'add_new' => "Add New"
            , 'add_new_item' => "Add New Event"
            , 'edit_item' => "Edit Event"
            , 'new_item' => "New Event"
            , 'view_item' => "View Event"
            , 'search_items' => "Search Events"
            , 'not_found' => "No events found"
            , 'not_found_in_trash' => "No events found in Trash"
            , 'item_published' => "Event published."
            , 'item_published_privately' => "Event published privately."
            , 'item_reverted_to_draft' => "Event reverted to draft."
            , 'item_scheduled' => "Event scheduled."
            , 'item_updated' => "Event updated."
            , 'item_link' => "Event Link"
            , 'item_link_description' => "A link to a particular Event."
        ]
    ];
    register_post_type( 'tribe_events', $post_type_args );
    register_taxonomy_for_object_type( 'tribe_events_cat', 'tribe_events');
    return true;
}

function ebps_maybe_register_tribe_events_taxonomy() {
	if ( taxonomy_exists( 'tribe_events_cat') ) {
		return false;
	}
	$args = [ 'label' => "Event Categories"
	 /* , 'labels' => stdClass Object

	'name' => "Event Categories"
	, 'singular_name' => "Event Category"
	, 'search_items' => "Search Event Categories"
	, 'popular_items] => (NULL)
	, 'all_items' => "All Event Categories"
	, 'parent_item' => "Parent Event Category"
	, 'parent_item_colon' => "Parent Event Category:"
	, 'edit_item' => "Edit Event Category"
	, 'view_item' => "View Category"
	, 'update_item' => "Update Event Category"
	, 'add_new_item' => "Add New Event Category"
	, 'new_item_name' => "New Event Category Name"
	, 'separate_items_with_commas] => (NULL)
	, 'add_or_remove_items] => (NULL)
	, 'choose_from_most_used] => (NULL)
	, 'not_found' => "No categories found."
	, 'no_terms' => "No categories"
	, 'filter_by_item' => "Filter by category"
	, 'items_list_navigation' => "Categories list navigation"
	, 'items_list' => "Categories list"
	, 'most_used' => "Most Used"
	, 'back_to_items' => "&larr; Go to Categories"
	, 'item_link' => "Event Category Link"
	, 'item_link_description' => "A link to a particular Event category."
	, 'menu_name' => "Event Categories"
	, 'name_admin_bar' => "Event Category"
	, 'archives' => "All Event Categories"
 */
	, 'description' => "ebps-meetings registered taxonomy"
	, 'public' => true
    , 'publicly_queryable' => true
    , 'hierarchical' => true
    , 'show_ui' => true
    , 'show_in_menu' => true
    , 'show_in_nav_menus' => true
    , 'show_tagcloud' => true
    , 'show_in_quick_edit' => true
    , 'show_admin_column' => false
	, 'rewrite' => [ 'with_front' => true
				, 'hierarchical' => true
            , 'ep_mask' => 0
            , 'slug' => "events/category"
			]
	, 'query_var' => "tribe_events_cat"
	, 'update_count_callback' => ""
	, 'show_in_rest' => true
    ];
	register_taxonomy( 'tribe_events_cat', null, $args );
}

/**
 * Expands the [ebps-meetings] shortcode.
 *
 * @param array $atts array of shortcode attributes
 * @param string $content nested content
 * @param string $tag shortcode
 * @return string the expanded shortcode
 */
function ebps_meetings_lazy_shortcode( $atts, $content, $tag ) {
	ebps_enqueue_css();
    $html = ebps_get_cached_meetings();
    if ( false === $html ) {
        $html = ebps_meetings_render_html( $atts, $content, $tag );
        ebps_save_cached_meetings($html);
    }
    return $html;
}

function ebps_meetings_render_html( $atts, $content, $tag ) {
    $events = ebps_get_upcoming_events();
    $html = '<div class="tribe-common tribe-events tribe-events-view tribe-events-view--widget-events-list tribe-events-widget">';
    $html .= ebps_meetings_upcoming_events($events);
    $html .= '</div>';
    $html .= ebps_meetings_view_calendar_link();
    return $html;
}

/**
 * Returns the link to view all events.
 *
 * @return string
 */
function ebps_meetings_view_calendar_link() {
    $html = '<a href="';
    $html .= home_url('events');
    $html .= '" title="View more meetings">View Calendar</a>';
    return $html;
}

/**
 * Returns the cached output from [ebps-meetings]
 *
 * @return mixed cached HTML or false
 */
function ebps_get_cached_meetings() {
    $html = get_transient( 'ebps-meetings' );
    return $html;
}

/**
 * Caches the output from [ebps-meetings]
 *
 * The cache should get cleared at the end of the first event.
 * If there are no future events clear the cached daily at midnight.
 *
 *
 * @param $html
 */
function ebps_save_cached_meetings( $html ) {

    $first_event_end_time = ebps_first_event_end_time();
    $secs_to_end_of_first_event = $first_event_end_time - time();

    if ( $secs_to_end_of_first_event > 0 ) {
        $secs = $secs_to_end_of_first_event;
    } else {
        $secs = ebps_time_of_day_secs();
        $secs_to_end_of_day = 86400 - $secs;
        $secs = $secs_to_end_of_day;
    }
    $result = set_transient( 'ebps-meetings', $html, $secs );
}

/**
 * Returns the current time of the days in seconds.
 *
 * @return float|int
 */
function ebps_time_of_day_secs() {
    extract( localtime( time(), true ));
    $secs = ((($tm_hour * 60) + $tm_min) * 60) + $tm_sec;
    return $secs;
}

/**
 * Returns the permalink for an event.
 *
 * @param $event_id
 * @return false|string|WP_Error
 */
function ebps_get_permalink( $event_id ) {
    $permalink = get_permalink( $event_id );
    return $permalink;
}

/**
 * Enqueues the CSS inline.
 */
function ebps_enqueue_css() {
    //wp_enqueue_style( 'ebps-meetings', plugins_url( '/css/ebps-meetings.css', __FILE__ ), []  );
    $inline_css = file_get_contents( plugin_dir_path( __DIR__ ) . '/css/ebps-meetings.css');
    wp_register_style( 'ebps-meetings', false );
    wp_enqueue_style( 'ebps-meetings' );
    wp_add_inline_style( 'ebps-meetings', $inline_css );
}