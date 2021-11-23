<?php
/**
 * @package ebps-meetings
 *
 */

function ebps_get_upcoming_events() {
    // Dummy logic to return a couple of events.
    // These will be 'tribe-event' posts.

    $events = [ [ 'post_title' => "something"]
        ,        ['post_title' => 'another']
    ];
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
    $datetime = '2021-11-27';
    $month = 'Nov';
    $day = '27';
    $start_time = '2:00 pm';
    $end_time = '4:00 pm';
    $url = 'https://s.b/ebps/event/n-autumn-zoom-meeting/';
    $title = 'N: Autumn Meeting:  Victorian Ferneries';

    $html = '<div class="tribe-common-g-row tribe-events-widget-events-list__event-row">';
    $html .= '<div class="tribe-events-widget-events-list__event-date-tag tribe-common-g-col">';
    $html .= '<time class="tribe-events-widget-events-list__event-date-tag-datetime" datetime="2021-11-27">';
    $html .= '<span class="tribe-events-widget-events-list__event-date-tag-month">';
    $html .= $month;
    $html .= '</span>';
    $html .= '<span class="tribe-events-widget-events-list__event-date-tag-daynum tribe-common-h2 tribe-common-h4--min-medium">';
    $html .= $day;
    $html .= '</span>';
    $html .= '</time>';
    $html .= '</div>';
    $html .= '<div class="tribe-events-widget-events-list__event-wrapper tribe-common-g-col">';
    $html .= '<article class="tribe-events-widget-events-list__event post-34632 tribe_events type-tribe_events status-publish has-post-thumbnail hentry tribe_events_cat-meetings tribe_events_cat-national tribe_events_cat-zoom cat_meetings cat_national cat_zoom">';
    $html .= '<div class="tribe-events-widget-events-list__event-details">

				<header class="tribe-events-widget-events-list__event-header">
					<div class="tribe-events-widget-events-list__event-datetime-wrapper tribe-common-b2 tribe-common-b3--min-medium">
		<time class="tribe-events-widget-events-list__event-datetime" datetime="2021-11-27">
		<span class="tribe-event-date-start">2:00 pm</span> - <span class="tribe-event-time">4:00 pm</span>	</time>
	</div>
					<h3 class="tribe-events-widget-events-list__event-title tribe-common-h7">
	<a href="https://s.b/ebps/event/n-autumn-zoom-meeting/" 
	title="N: Autumn Meeting:  Victorian Ferneries" 
	rel="bookmark" class="tribe-events-widget-events-list__event-title-link tribe-common-anchor-thin">
    N: Autumn Meeting:  Victorian Ferneries	
    </a>
</h3>
				</header>


			</div>
		</article>
	</div>

</div>';
    return $html;
}