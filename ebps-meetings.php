<?php
/**
 * Plugin Name:     eBPS Meetings
 * Plugin URI:      http://github.com/bobbingwide/ebps-meetings
 * Description:     To replace the Upcoming Events widget
 * Author:          bobbingwide
 * Author URI:      https://bobbingwide.com/about-bobbing-wide
 * Text Domain:     ebps-meetings
 * Domain Path:     /languages
 * Version:         0.0.1
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html

Copyright 2021 Bobbing Wide (email : herb@bobbingwide.com )

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2,
as published by the Free Software Foundation.

You may NOT assume that you can use any other version of the GPL.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

The license for this software can likely be found here:
http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package         ebps-meetings
 */

function ebps_loaded() {
    add_action( 'init', 'ebps_init' );
}

function ebps_init() {
    add_shortcode( 'ebps-meetings', 'ebps_meetings_shortcode' );
}

function ebps_meetings_shortcode( $atts, $content, $tag ) {
    require_once __DIR__ . '/includes/ebps-tribe-events.php';
    $events = ebps_get_upcoming_events();
    $html = '<div class="tribe-common tribe-events tribe-events-view tribe-events-view--widget-events-list tribe-events-widget">';
    $html .= ebps_meetings_upcoming_events( $events );
    $html .= '</div>';
    $html .= ebps_meetings_view_calendar_link();
    return $html;
}

function ebps_meetings_view_calendar_link() {
    $html = '<a href="';
    $html .= home_url('events');
    $html .= '">View Calendar</a>';
    return $html;
}

ebps_loaded();