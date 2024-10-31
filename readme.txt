=== Posts in Map ===

Contributors: lucdecri
Donate link: http://www.decristofano.it
Tags: google maps, jquery, javascript, googlemaps, gmap, gmap3, maps. geolocation
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 0.3


Add short code [gmap] to insert in post a google map with advanced features and place geolocalized post in this map

== Description ==
Add short code [gmap] to insert in post a google map with advanced features (overlays, clusters, callbacks, events...) and place geolocalized post (with custom fields) in this map

To view post in map, you use this custom field :
* geolocation : set here GPS coordinates for post
* marker : set here url for marker icon. (optional)

To view map in a post, you use this short code ;
[gmap width=w height=h border=b posttype=p taxonomy=t term=r maxpost=m centermap=c animate=a]

where 
* w,h are size of map (default is 500px and 300px)
* b is css-like style for map border (default is brown solid line)
* p is post-type (default is post)
* r is a term for taxonomy t to filter post (default is all post, without taxonomy filter)
* m is max number of post view in map
* c il gps coordinates of centre of map
* a is 0 for not animated marker, 1 for drop marker, 2 for bouncing marker

For more information visit http://gmap3.net/

Javascript by Jean-Baptiste DEMONTE.

== Installation ==
1. Upload `post-in-map` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. add custom field 'geolocation' and 'marker' in post that you want view in maps
4. add short code in post to view map

Its recommended to flush the cache after upgrading.

== Frequently Asked Questions == 
None at this moment.

== Coming soon ==
- custom field for post to use area instead marker
- custom field to use address in geolocalized post instead coordinates
- short code to view map bottom custom field geolocation

== Screenshots ==
coming soon


== Changelog ==
= 0.3 =
* add icon property
* bugfix
= 0.2 =
* add shortcode
* add post geolocation reading

= 0.1 =
* only include js