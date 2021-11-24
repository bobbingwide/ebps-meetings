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
    $day = wp_date( 'd', $_EventStartDate );
    $start_time = wp_date('h:i a', $_EventStartDate );
    $end_time = wp_date( 'h:i a', $_EventEndDate );

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
    $html .= '<a href="' . get_permalink( $event->ID ) .'" ';
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