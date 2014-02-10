=== Eventissimo ===
Contributors: Digitalissimo
Tags: events, event, calendar, facebook, shortcode, widget
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UXV6CWADKJQ5A
Requires at least: 3.4
Tested up to: 3.4
Stable tag: 1.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create and organize events into your site.
Your events also automatically automatically created on Facebook.
Import your Facebook Events.

== Description ==
Now into your site Wordpress you can create and organize your events.

*   Create events
*   Events only date or repeat
*   Gallery of the events
*   Also generate your events on Facebook
*   Post Events automatically on Facebook
*   View Calendar of your events

Multilanguage: English, Italian

Use Widget for view events

= SHORTCODE Calendar =
*  `[eventissimo type='CALENDAR' backcolorHEX='[#069c88]' fontcolorHEX='[#FFFFFF]']` :  backcolorHEX is backcolor of the event's title. Default is #069c88 for  backcolor and #FFFFFF for color text

= SHORTCODE List or Block Event =
*  `[eventissimo type='[LIST|BLOCK]' date='true' ]`: you view date of events
* `[eventissimo type='[LIST|BLOCK]' limit='#' ]`: you type a number for limit list event, default 10
*  `[eventissimo type='[LIST|BLOCK]' paginate='true' ]`: you view events with pagination (events per page defined with limit number, if not defined number is 10).
*  `[eventissimo type='[LIST|BLOCK]' view='[OLD|NEXT]']`: you defined past events or next events, Default is NEXT
*  `[eventissimo type='[LIST|BLOCK]' defined='TODAY|MONTH']`: you defined today events or all events of current month. MONTH combined with view NEXT lets you see only next events.

= SHORTCODE SlideShow =
*  `[eventissimo type='CYCLE' view='[OLD|NEXT]' defined='TODAY|MONTH']`

= Facebook =
To automate the creation of an event on facebook you have to register as a developer on facebook, create an APP and to recove id and privat key of APP.
NEWS: Import your events of Facebook!!!

= Note: Single Template =
If you would change single template of events copy pages/events-template-single.php into your template and custom it.

== Screenshots ==
1. Create Facebook Post 
 
2. Create Event Facebook 

== Installation ==
1. Upload `eventissimo` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
= 1.0.0 =
* Create plugin
= 1.0.1 =
* Correct Bug Bootstrap
= 1.1.1 =
* Correct Bugs css and Facebook
= 1.2 =
* Correct Bugs Link Events, added Slideshow events
= 1.3 =
* Added Single Events custom
= 1.3.5 =
* Import your Facebook events