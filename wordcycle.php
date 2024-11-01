<?php
/**
 * @package WordCycle
 * @author Esther S White
 * @version 1.1
 */
/*
Plugin Name: WordCycle
Plugin URI: http://blog.estherswhite.net/wordcycle
Description: Simple and lightweight slideshow plugin that integrates with the WordPress image upload & galleries. WordCycle is a wrapper for the popular <a href="http://malsup.com/jquery/cycle/">jQuery Cycle Plugin</a> by Mike Alsup. Use the [slideshow] shortcode to insert a Cycle slideshow into your WordPress post or page.
Author: Esther S White
Version: 1.1
Author URI: http://blog.estherswhite.net
*/
/*
 MIT License/GNU License
	http://www.opensource.org/licenses/mit-license.php
	http://www.gnu.org/licenses/gpl.html
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so.
*/

if (!class_exists("wordcycle_plugin")){
	function wordcycle_slideshow($id = null, $order = 'ASC', $orderby = 'menu_order ID', $size = 'medium', $speed = 'slow', $fx = 'fade', $timeout = '8000', $pause = '0', $next = null, $prev = null, $include = null, $exclude = null, $link = null, $align = null) {
		
		global $post; 
		if (!$id) $id = $post->ID;
		
		add_action('wp_head', array('wordcycle_plugin', 'add_cycle'));
		add_action('wp_footer', array('wordcycle_plugin', 'wordcycle_scripts'));
		
		echo wordcycle_plugin::print_wordcycle($id, $order, $orderby, $size, $include, $exclude, $speed, $align, $fx, $timeout, $pause, $next, $prev, $link);
	}
	
	class wordcycle_plugin {
		/*======================
		Loads CSS & JS
		=======================*/
		function add_cycle() {
			echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/wordcycle/css/wordcycle.css" />' . "\n";
			if (function_exists('wp_enqueue_script')) {
				wp_enqueue_script('jquery'); 
				wp_enqueue_script('cycle', get_bloginfo('wpurl') . '/wp-content/plugins/wordcycle/js/jquery.cycle.js', 'jquery'); 
			}
		} //End add_cycle()
				
		function wordcycle_scripts() {
			global $wpfoot;
			echo $wpfoot;
		}
		
		/*======================
		Creates Shortcode for Cycle, based on Gallery Shortcode (/wp-includes/media.php)
		=======================*/
		function shortcode_cycle($attr = null, $content = null) {
			global $post;
			
			// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
			if ( isset( $attr['orderby'] ) ) {
				$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
				if ( !$attr['orderby'] )
					unset( $attr['orderby'] );
			}
		
			// Set defaults for shortcode values		
			extract(shortcode_atts(array(
				'order'      => 'ASC',
				'orderby'    => 'menu_order ID',
				'id'         => $post->ID,
				'size'       => 'medium',
				'include'	 => '',
				'exclude'	 => '',
				'align'		 => '',
				'speed'		 => 'slow',
				'fx'		 => 'fade',
				'timeout'	 => '8000',
				'pause'		 => '0',
				'next'		 => 'null',
				'prev'		 => 'null',
			), $attr));
			
			if ($attr['float']) $align = $attr['float']; // for backwards compatibility -- float option replaced with align in v1.1

			return wordcycle_plugin::print_wordcycle($id, $order, $orderby, $size, $include, $exclude, $speed, $align, $fx, $timeout, $pause, $next, $prev, $attr['link']);
			
		} // End shortcode_cycle()
		
		/*======================
		Returns WordCycle Slideshow and Footer Script
		=======================*/
		function print_wordcycle($id, $order, $orderby, $size, $include, $exclude, $speed, $align, $fx, $timeout=NULL, $pause=NULL, $next=NULL, $prev=NULL,$linktype=NULL){
			
			global $post;
		
			$id = intval($id);
			
			/* Logic for include/exclude options, contributed by Mathew Simo based on Gallery Shortcode /wp-includes/media.php */
			if ( !empty($include) ) {
				$include = preg_replace( '/[^0-9,]+/', '', $include );
				$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		
				$attachments = array();
				foreach ( $_attachments as $key => $val ) {
					$attachments[$val->ID] = $_attachments[$key];
				}
			} elseif ( !empty($exclude) ) {
				$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
				$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
			} else {
				$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
			}		
			
			if ( empty($attachments) )
				return '';
		
			if ( is_feed() ) {
				$output = "\n";
				foreach ( $attachments as $id => $attachment )
					$output .= wp_get_attachment_link($id, $size, true) . "\n";
				return $output;
			}
			
			global $wpfoot;
			$wpfoot .= "<script type='text/javascript'>
						var \$j = jQuery.noConflict(); 
						
						var \$wd = 0;
						
						\$j(function(){ 
							\$j('#slideshow-".$id."').cycle({
								speed: '".$speed."',
								fx: '".$fx."',
								timeout: ".$timeout.",
								pause: ".$pause.",
								next: '".$next."',
								prev: '".$prev."'
							});
						});
						</script>";
			
			$output = "<div class='wordcycle " . $align ."' id='slideshow-". $id . "'>";
			$i = 0;
			foreach ( $attachments as $id => $attachment ) {
				if ( isset($linktype) && 'file' == $linktype) { $link = wp_get_attachment_link($id, $size, false, false); }
				elseif ( isset($linktype) && 'attachment' == $linktype) { $link = wp_get_attachment_link($id, $size, true, false); }
				else { $link = wp_get_attachment_image($id, $size, false); }
		
				$output .= "<div class='cycle-item' id='post-".$id."'>";
				$output .= $link;
				if ( trim($attachment->post_excerpt) ) {
					$output .= "
						<div class='cycle-caption'>
						{$attachment->post_excerpt}
						</div>";
				}
				$output .= "</div>";
			}
		
			$output .= "
				</div>\n";
		
			return $output;
		} // End print_wordcycle()
	}
} // End Class wordcycle_plugin

if (class_exists("wordcycle_plugin")) {
	$wordcycle = new wordcycle_plugin();
}

if(isset($wordcycle)) {
	//Actions
	add_action('wp_head', array(&$wordcycle, 'add_cycle'), 1);
	add_action('wp_footer', array(&$wordcycle, 'wordcycle_scripts'), 1);
	
	//Filters
	add_shortcode('slideshow', array(&$wordcycle, 'shortcode_cycle'));
}

?>