=== WordCycle ===
Contributors: eswhite 
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=NHHQZ3K6AY5BU&lc=US&item_name=Esther%20S%20White&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: slideshow, javascript, jquery, gallery, images
Requires at least: 2.5
Tested up to: 3.0.1
Stable tag: 1.1
	
WordCycle is a WordPress plugin that acts as a wrapper for the popular jQuery Cycle Plugin by Mike Alsup.

== Description ==

WordCycle is a WordPress plugin that acts as a wrapper for the popular [jQuery Cycle Plugin][1] by Mike Alsup. Use the [slideshow] shortcode to insert a jQuery Cycle slideshow into your WordPress post or page.  

No JavaScript necessary! Customize the slideshow using the shortcode options and by modifying your theme's stylesheet.  The plugin also adds a custom template tag wordcycle_slideshow().

WordCycle is developed by [Esther S White][2]

 [1]: http://malsup.com/jquery/cycle/
 [2]: http://blog.estherswhite.net
	
== Installation ==

Extract the zip file and drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the WordCycle from Plugins page.

Use [slideshow] shortcode in your Posts & Pages to add Cycle slideshows or use the wordcycle_slideshow() template tag in your template files.

== Usage and Examples ==  
      
Uploaded images are called attachments in the WordPresss database. If WordCycle is installed, you can use the [slideshow] shortcode to create a slideshow of all the attachments associated with a post. You can also use the custom template tag wordcycle_slideshow()  in your WordPress template files.

The slideshow advances automatically and is minimally styled. The slideshow uses the Link, Title, and Caption attributes of the WordPress Image Uploader/Gallery, as well as the gallery sorting options.
      
To add the slideshow to a post, insert this code:  [slideshow]  

More examples available on the plugin website.

== Options & Defaults ==  
      
* id 		    // post id: #, current (default)  
* order         // slide order: ASC (default), DESC, RAND  
* orderby       // slide order by: menu_order ID (default), see list in codex: http://codex.wordpress.org/Template_Tags/get_posts  
* size	        // image size: thumbnail, small, medium (default), large, full-size  
* speed	        // effect speed: slow (default), normal, fast  
* fx            // slideshow effect (view the [jQuery Cycle Plugin Effects Browser][4] for a complete list)  
* timeout	    // time on each slide: miliseconds: 8000 (default)  
* pause         // pause on mouse-over: 0 (default) or 1
* next          // selector of element to use as click trigger for next slide : null (default) or DOM element ID
* prev	        // selector of element to use as click trigger for previous slide : null (default) or DOM element ID
* include       // include image by ID
* exclude       // exclude image by ID
* link 	        // image link: file, attachment
* align	        // slideshow position: left, right, none (default)  
* float (deprecated)	        // slideshow position: left, right, none (default)  

 [4]: http://malsup.com/jquery/cycle/browser.html
 
== Changelog ==

= 1.1 =
* Updated jquery.cycle.js to v2.86
* Changed CSS to not include border around images
* Updated float option, now called "align", no longer uses <style>

= 1 =
* Added custom template tag wordcycle_slideshow()

= 0.1.7 =
* Updated slideshow markup to use $post->ID as element ID for easier CSS styling.

= 0.1.6 =
* Added support for include and exclude based on Gallery Shortcode; suggested and implemented by Mathew Simo (@matthewsimo)

= 0.1.5 =
* Fixed bug in v0.1.4 which broke slideshows.
* Added style .wordcycle { overflow:hidden } for improved page view before JS loads

= 0.1.4 =
* Revised implementation of jQuery.noConflict()

= 0.1.3 =
* Abstracted creation of slideshow (print_wordcycle()) from shortcode function (shortcode_cycle())
* Added random # to slideshow ID to allow multiple slideshows on a page
* Moved scripts and styles to wp_footer

= 0.1.2 =
* Fixed readme.txt formatting, updated readme.txt

= 0.1.1 =
* Introduced "next" and "prev" attributes.

= 0.1 =
* WordCycle first released.