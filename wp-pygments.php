<?php
/*
 * You can read the tutorial here: http://davidwalsh.name/pygments-php-wordpress
 *
 * Plugin Name: WP Pygments
 * Plugin URI: http://wellingguzman.com/wp-pygments
 * Description: Turn your <pre> code to pygments.
 * Version: 0.1
 * Author: Welling Guzman
 * Author URI: http://wellingguzman.com
 * License: MEH
*/

define( 'MB_WPP_BASE', dirname(__FILE__) );
define( 'MB_WPP_EXPIRATION', 60 * 60 * 24 * 30 );

add_action( 'save_post', 'mb_pygments_save_post' );
add_filter( 'the_content', 'mb_pygments_content_filter' );
register_uninstall_hook(__FILE__, 'mb_wp_pygments_uninstall');

function mb_pygments_save_post( $post_id )
{
	if ( wp_is_post_revision( $post_id ) )
		return;
		
	$content = get_post_field( 'post_content', $post_id );
	
	mb_pygments_content_filter( $content, TRUE );
}

function mb_pygments_content_filter( $content )
{
	// Check if there is cached data
	if ( FALSE !== ( $cached_post = get_post_cache() ) && !post_cache_needs_update() )
		return $cached_post['content'];
	
	clear_post_cache();
	
	$content = preg_replace_callback('/<pre(\s?class\="(.*?)")?[^>]?.*?>.*?<code>(.*?)<\/code>.*?<\/pre>/sim', 'mb_pygments_convert_code', $content);
	
	// OR
	/*
		
	libxml_use_internal_errors(true);
	$dom = new DOMDocument();
	$dom->loadHTML($content);
	$pres = $dom->getElementsByTagName('pre');
	foreach ($pres as $pre) {
		$class = $pre->attributes->getNamedItem('class')->nodeValue;
		$code = $pre->nodeValue;
		
		$args = array(
			2 => $class,
			3 => $code
		);
		
		$new_code = mb_pygments_convert_code($args);
		
		$new_pre = $dom->createDocumentFragment();
		$new_pre->appendXML($new_code);
		$pre->parentNode->replaceChild($new_pre, $pre);
	}
	
	$content = $dom->saveHTML();
	*/
		
	save_post_cache( $content );
	
	return $content;
}


function mb_pygments_convert_code( $matches )
{
	$pygments_build	= MB_WPP_BASE . '/pygments/build.py';
	$source_code		= isset($matches[3])?$matches[3]:'';
	$class_name 		= isset($matches[2])?$matches[2]:'';
	
	// Creates a temporary filename
	$temp_file 			= tempnam(sys_get_temp_dir(), 'MB_Pygments_');
	
	
	// Populate temporary file
	$filehandle = fopen($temp_file, "wb");
	fwrite($filehandle, html_entity_decode($source_code, ENT_COMPAT, 'UTF-8') );
	fclose($filehandle);
	
	
	// Creates pygments command
	$language		= $class_name?$class_name:'guess';
	$command 		= sprintf('python %s %s %s', $pygments_build, $language, $temp_file);
	

	// Executes the command
	$retVal = -1;
	exec( $command, $output, $retVal );
	unlink($temp_file);
	
	// Returns Source Code
	$format = '<div class="highlight highlight-%s"><pre><code>%s</code></pre></div>';
	
	if ( $retVal == 0 )
		$source_code = implode("\n", $output);
		
	$highlighted_code = sprintf($format, $language, $source_code);
	
	return $highlighted_code;
}

// Cache Functions

function get_post_cache_transient()
{
	global $post;
	
	$post_id = $post->ID;
	$transient = 'post_' . $post_id . '_content';
	
	return $transient;
}

function get_post_cache()
{
	$cached_post = get_transient( get_post_cache_transient() );
	
	return $cached_post;
}

function post_cache_needs_update()
{
	global $post;
	
	$cached_post = get_post_cache();
	if ( strtotime($post->post_modified) > strtotime($cached_post['updated']) )
		return TRUE;
			
	return FALSE;
}

function save_post_cache($content)
{
	global $post;
		
	$expiration = MB_WPP_EXPIRATION;
	$value = array( 'content'=>$content, 'updated'=>$post->post_modified );
	set_transient( get_post_cache_transient(), $value, $expiration );
}

function clear_post_cache()
{	
	delete_transient( get_post_cache_transient() );
}

function mb_wp_pygments_uninstall() {
	global $wpdb;
	
	$wpdb->query( "DELETE FROM `wp_options` WHERE option_name LIKE '_transient_post_%_content' " );
}

?>