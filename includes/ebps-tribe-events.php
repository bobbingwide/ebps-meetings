<?php
/**
 * @package ebps-meetings
 *
 */


/**
 * Loads up to 3 future tribe_events.
 *
 * Assumptions:
 * - eBPS meetings don't span multiple days
 * - We only need to see public events
 * - It doesn't matter if today's event has already happened
 *
 * @return
 */
function ebps_get_upcoming_events() {
    $today = wp_date( 'Y-m-d' );
    $args = [ 'post_type' => 'tribe_events'
        , 'meta_key' => '_EventStartDate'
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
    $datetime = wp_date('Y-m-d', $_EventStartDate );
    $month = wp_date( 'M', $_EventStartDate );
    $day = wp_date( 'j', $_EventStartDate );
    $start_time = wp_date('g:i a', $_EventStartDate );
    $end_time = wp_date( 'g:i a', $_EventEndDate );

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
    return true;
}

/**
 * Expands the [ebps-meetings] shortcode.
 *
 * @param $atts
 * @param $content
 * @param $tag
 * @return string
 */
function ebps_meetings_lazy_shortcode( $atts, $content, $tag ) {
    $enqueue_css = ebps_maybe_register_tribe_events();
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
 * @param $html
 */
function ebps_save_cached_meetings( $html ) {
    $secs = ebps_time_of_day_secs();
    $secs = 86400 - $secs;
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