# ebps-meetings  
![banner](assets/ebps-meetings-banner-772x250.jpg)
* Contributors: bobbingwide, andrewleonard
* Donate link: https://ebps.org.uk/donate-to-the-bps/
* Tags: eBPS, Meetings, shortcode
* Requires at least: 5.8.2
* Tested up to: 5.8.2
* Stable tag: 0.2.0

eBPS meetings

## Description 
The eBPS meetings plugin provides the [ebps-meetings] shortcode to replace the Upcoming Events widget on the British Pteridological Society’s website. [ebps.org.uk](https://ebps.org.uk)

* Purpose: To improve the overall performance of the website.


## Installation 
1. Upload the contents of the ebps-meetings plugin to the `/wp-content/plugins/ebps-meetings' directory
1. Activate the ebps-meetings plugin through the 'Plugins' menu in WordPress
1. Add the [ebps-meetings] shortcode to your content or widgets.
1. Use the oik-unloader plugin or similar to deactivate the Events calendar on any page that doesn't need it.


## Frequently Asked Questions 

# What is this plugin for? 

To improve the overall performance of the website.


## Screenshots 
1. Upcoming Meetings - original widget
2. Upcoming Meetings - replacement shortcode as a widget

## Upgrade Notice 
# 0.2.0 
Update for improved styling and inline CSS.

# 0.1.0 
Update for improved performing through cacheing.

# 0.0.2 
Update for actual data load and formatting.

# 0.0.1 
Upgrade for the first version of the shortcode.

# 0.0.0 
First version. Does nothing yet.

## Changelog 
# 0.2.0 
* Changed: Move more logic to the includes file #1
* Changed: Enqueue CSS inline #2
* Changed: Set event heading size #2
* Changed: Wrap get_permalink() in ebps_get_permalink(). Fixes #3
* Changed: Attempt to style the ebps-meetings shortcode to match the widget #2
* Fixed: Correct day and hour display - no leading zeros #1
* Fixed: Add title attr to View Calendar link #1

# 0.1.0 
* Changed: Clears the cached output from [ebps-meetings] when an event is updated #1
* Changed: Move post type registration into includes file.
* Added: Add transient caching logic #1
* Changed: Register tribe_events if not already registered #1

# 0.0.2 
* Changed: Added logic to load events and format the output #1

# 0.0.1 
* Added: main plugin file with first version of the shortcode #1
* Changed: Remove h2 heading, Add View Calendar link #1
* Tested: With WordPress 5.8.2
* Tested: With PHP 8.0

# 0.0.0 
* Added: This readme.txt file
