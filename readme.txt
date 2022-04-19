=== ebps-meetings  ===
Contributors: bobbingwide, andrewleonard
Donate link: https://ebps.org.uk/donate-to-the-bps/
Tags: eBPS, Meetings, shortcode
Requires at least: 5.8.2
Tested up to: 5.9.3
Stable tag: 0.5.1

eBPS meetings

== Description ==
The eBPS meetings plugin provides the [ebps-meetings] shortcode to replace the Events list widget
on the British Pteridological Society’s website. [ebps.org.uk](https://ebps.org.uk)

Purpose: To improve the overall performance of the website.

Use in conjunction with the oik-unloader plugin to deactivate The Events Calendar plugin on demand.


== Installation ==
1. Upload the contents of the ebps-meetings plugin to the `/wp-content/plugins/ebps-meetings' directory
1. Activate the ebps-meetings plugin through the 'Plugins' menu in WordPress
1. Add the [ebps-meetings] shortcode to your content or primary sidebar widget area as a Custom HTML widget.
1. Remove the Events list widget from the primary sidebar.
1. Use the oik-unloader plugin, or similar, to deactivate The Events Calendar plugin on any page that doesn't need it.


== Frequently Asked Questions ==

= What is this plugin for? =

To improve the overall performance of the website.


== Screenshots ==
1. Upcoming Meetings - original widget
2. Upcoming Meetings - replacement shortcode as a widget

== Upgrade Notice ==
= 0.5.1 =
Upgrade for corrected font-weight property

= 0.5.0 =
Update for BST support. 

= 0.4.1 =
Update to improve usability in Custom HTML widgets 

= 0 4.0 =
Update to enable the Events Category items in the main menu

= 0.3.0 =
Update for improved styling and improved caching.

= 0.2.0 =
Update for improved styling and inline CSS.

= 0.1.0 = 
Update for improved performing through caching.

= 0.0.2 =
Update for actual data load and formatting.

= 0.0.1 =
Upgrade for the first version of the shortcode.

= 0.0.0 = 
First version. Does nothing yet.

== Changelog ==
= 0.5.1 =
* Fixed: Correct styling for widget links #2

= 0.5.0 =
* Fixed: Cater for BST #6
* Fixed: Improve link styling for block widgets #2
* Tested: With WordPress 5.9.3
* Tested: With PHP 8.0 

= 0.4.1 =
* Fixed: Ensure do_shortcode() is run for the widget_text filter #5
* Changed: Style View Calendar link as green for text or custom widget #2

= 0.4.0 = 
* Changed: Register tribe_events and tribe_events_cat on init #4

= 0.3.0 = 
* Changed: Fetch events with end date after the current time #1  
* Changed: Cache data until the current event has ended #1
* Changed: Always enqueue the CSS #2. 
* Changed: Change title for View Calendar link #1
* Changed: Improve styling of the View Calendar link #2

= 0.2.0 =
* Changed: Move more logic to the includes file #1
* Changed: Enqueue CSS inline #2
* Changed: Set event heading size #2
* Changed: Wrap get_permalink() in ebps_get_permalink(). Fixes #3
* Changed: Attempt to style the ebps-meetings shortcode to match the widget #2
* Fixed: Correct day and hour display - no leading zeros #1
* Fixed: Add title attr to View Calendar link #1

= 0.1.0 =
* Changed: Clears the cached output from [ebps-meetings] when an event is updated #1
* Changed: Move post type registration into includes file. 
* Added: Add transient caching logic #1
* Changed: Register tribe_events if not already registered #1

= 0.0.2 = 
* Changed: Added logic to load events and format the output #1

= 0.0.1 =
* Added: main plugin file with first version of the shortcode #1
* Changed: Remove h2 heading, Add View Calendar link #1
* Tested: With WordPress 5.8.2
* Tested: With PHP 8.0

= 0.0.0 = 
* Added: This readme.txt file