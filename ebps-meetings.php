<?php
/**
 * Plugin Name:     eBPS Meetings
 * Plugin URI:      https://github.com/bobbingwide/ebps-meetings
 * Description:     To replace the Upcoming Events widget
 * Author:          bobbingwide
 * Author URI:      https://bobbingwide.com/about-bobbing-wide
 * Text Domain:     ebps-meetings
 * Domain Path:     /languages
 * Version:         0.2.0
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
    add_action( 'init', 'ebps_init', 100 );
    add_action( 'tribe_events_event_save', 'ebps_tribe_events_event_save', 10, 3 );
}

function ebps_init() {
    add_shortcode( 'ebps-meetings', 'ebps_meetings_shortcode' );
}

/**
 * Implements `tribe_events_event_save` action.
 *
 * Clears the cached output for the [ebps-meetings] shortcode.
 *
 * @param $event_id
 * @param $data
 * @param $event
 */
function ebps_tribe_events_event_save( $event_id, $data, $event ) {
    delete_transient( 'ebps-meetings');
}

/**
 * Implements the [ebps-meetings] shortcode.
 *
 * @param $atts
 * @param $content
 * @param $tag
 * @return string
 */
function ebps_meetings_shortcode( $atts, $content, $tag ) {
    require_once __DIR__ . '/includes/ebps-tribe-events.php';
    $html = ebps_meetings_lazy_shortcode($atts, $content, $tag);
    return $html;
}

ebps_loaded();