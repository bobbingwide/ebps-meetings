<?php
/**
 * Plugin Name:     eBPS Meetings
 * Plugin URI:      http://github.com/bobbingwide/ebps-meetings
 * Description:     To replace the Upcoming Events widget
 * Author:          bobbingwide
 * Author URI:      https://bobbingwide.com/about-bobbing-wide
 * Text Domain:     ebps-meetings
 * Domain Path:     /languages
 * Version:         0.0.2
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
}

function ebps_init() {
    add_shortcode( 'ebps-meetings', 'ebps_meetings_shortcode' );
    //ebps_maybe_register_tribe_events();
}

/**
 * Registers the post type if it's not already registered.
 *
 * We could have hooked into `registered_post_type` and checked the post types that have been registered
 * but that's less efficient than waiting until other plugins have done their bit
 * then checking if the post type is already registered.
 * If it's not been registered then register it.
 * Note: The values were obtained by seeing what the parameters are to `tribe_events_register_event_type_args`.
 */
function ebps_maybe_register_tribe_events() {
    if ( post_type_exists( 'tribe_events') ) {
        return;
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
}

function ebps_meetings_shortcode( $atts, $content, $tag ) {
    ebps_maybe_register_tribe_events();
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