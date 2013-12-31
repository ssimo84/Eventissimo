=== Eventissimo ===
Contributors: Digitalissimo
Tags: events, event, calendar, facebook
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UXV6CWADKJQ5A
Requires at least: 3.4
Tested up to: 3.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create and organize events into your site.
Your events also automatically automatically created on Facebook.

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

= Shortcode =
*[eventissimo type='[CALENDAR|LIST|BLOCK]']*
*[eventissimo type='[LIST|BLOCK]' date='true' ]*: you view date of events
*[eventissimo type='[LIST|BLOCK]' limit='#' ]*: you type a number for limit list event, default 10
*[eventissimo type='[LIST|BLOCK]' paginate='true' ]*: you view events with pagination (events per page defined with limit number, if not defined number is 10).
*[eventissimo type='[LIST|BLOCK]' view='[OLD|NEXT]']*: you defined past events or next events, Default is NEXT
*[eventissimo type='[LIST|BLOCK]' defined='TODAY|MONTH']*: you defined today events or all events of current month. MONTH combined with view NEXT lets you see only next events.

= Facebook =
To automate the creation of an event on facebook you have to register as a developer on facebook, create an APP and to recove id and privat key of APP.

      

== Installation ==
1. Upload `eventissimo` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
= 1.0.0 =
* Create plugin


== Upgrade Notice ==
= 0.2 =
* A change since the previous version.
* Another change.

= 0.1 =
* Initial release.