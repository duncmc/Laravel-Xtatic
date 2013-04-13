<?php

/**
 * Xtatic
 *
 *
 *
 * 
 * (c) September 2012 Art & Soul Communications Ltd
 *
 * @package		Xtatic
 * @category	Libraries 
 * @author		Duncan McMillan
 * @link		http://www.artandsoul.co.uk
 * @version 	1.0
 *
 */
class Xtatic {
	
	/**
	 * Slug
	 *
	 * URI slug which determines the page meta data to be used.
	 *
	 * @var	string 	$slug
	 */
	private static $slug;
	
	/**
	 * Page Meta
	 *
	 * The page meta data corresponding to the URI slug which
	 * is retrieved from the config file .
	 *
	 * @var	array 	$page_meta
	 */
	private static $page_meta;
	
	private static $content;
	
	private static $js_in_head = array();
	private static $js_in_tail = array();
	private static $jquery_ready = array();
	
	private static $depth = 1;
	
	// --------------------------------------------------------------------------
	
	/**
	 * Constructor
	 */
    public function __construct()
	{
		// It’ll never happen...
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Menu
	 *
	 * Assemble and return an HTML formatted list element based on the navigation 
	 * array specified by the root key passed in to the method.
	 *
	 * @access	public
	 * @param	string 		Key for root element - can be a dot separated path to a nested array in the config
	 * @param	int			Depth to which menu elements should be parsed
	 * @param	bool		Whether to include the root element in the resulting menu
	 * @param	string		HTML list type
	 * @return 	string		An HMTL formatted list
	 */
	public static function menu($root_key=NULL, $to_depth=NULL, $include_root=FALSE, $list_type='ul')
	{
		if ( is_null( $root_key ) ) {
			// No key supplied - use first member of the navigation array
			$root_key = array_search(reset( Config::get( 'xtatic::xtatic.navigation' ) ), Config::get( 'xtatic::xtatic.navigation' ) );
		}
		
		// Retrieve the corresponding menu elements from the config
		$menu_elements = Config::get( 'xtatic::xtatic.navigation.' . $root_key);
		
		// If the root element is required as a parent element in the menu we’ll restructure the array
		if ($include_root) $menu_elements = array($root_key => $menu_elements);
		
		return (is_null($to_depth) || $to_depth >= static::$depth) ? static::as_list($menu_elements, $list_type, $to_depth) : '';
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * As List
	 *
	 * Returns an HTML formatted list with links from an array of navigation keys
	 *
	 * @access	public
	 * @param	array 		Array of key values to the pages array
	 * @param	string		List tag
	 * @param	int			Depth to which menu elements should be parsed
	 * @return 	string		An HMTL formatted list
	 */
	private static function as_list($nav_elements, $tag=NULL, $to_depth=NULL)
	{
		// Ensure list type is valid
		$list_tag = in_array($tag, array('ul', 'ol', 'dl')) ? $tag : 'ul';
		
		$html = '<' . $list_tag . '>' . "\n";
		$html .= static::as_items($nav_elements, $list_tag, $to_depth);
		$html .= '</' . $list_tag . '>' . "\n";
		
		return $html;
		
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * As Items
	 * 
	 * Returns a series of HTML list items from an array and recursively parses elements with array values
	 *
	 * @access	private
	 * @param 	array 		Array of key values to the pages array
	 * @param	string		List tag
	 * @param	int			Depth to which menu elements should be parsed
	 * @return 	string		HTML formatted list elements
	 */
	private static function as_items($nav_elements, $list_tag, $to_depth)
	{
		$html = '';
		
		// Define the list item element tag
		switch ($list_tag)
		{
			case 'dl' :
				$item_tag = 'dt';
				break;
				
			case 'dt' :
			case 'dd' :
				$item_tag = 'dd';
				break;
			
			default :
				$item_tag = 'li';
		}
						
		// Iterate recursively across nav elements and parse into HTML
		foreach ( (array) $nav_elements as $key => $value)
		{
			// Determine values for the label and uri from pages array
			
			$is_live = TRUE;
			$link_classes = array();
			$link_attributes = array();
			
			switch(TRUE)
			{
				case is_numeric($key) :
					// Simple indexed element - value should correspond to a page
					$label = Config::get('xtatic::xtatic.pages.' . $value . '.label');
					$uri = $value;
					$is_live = Config::get('xtatic::xtatic.pages.' . $value . '.is_live');
					break;
					
				case array_key_exists($key, Config::get('xtatic::xtatic.pages') ) :
					// Element’s key corresponds to a page so assume element’s value is an array of subordinate elements 
					$label = Config::get('xtatic::xtatic.pages.' . $key . '.label');
					$uri = $key;
					$is_live = Config::get('xtatic::xtatic.pages.' . $key . '.is_live');
					break;

				case is_array($value) :
					// Element’s key is NOT a page but we have an array of values
					$label = $key;
					$uri = NULL;
					break;
					
				default :
					// Assume we have a simple label (key) and URI (value) pair
					$label = $key;
					// We’ll check for 'http' substring in a mo’, otherwise use as is
					$uri = $value;					
			}
			
			// Make sure URIs are correctly formatted
			switch (TRUE)
			{
				case $uri == Config::get('xtatic::xtatic.default_page') :
					$full_uri = URL::base();
					$link_classes[] = $uri;
					break;
					
				case substr($uri, 0, 4) == 'http' :
					$full_uri = $uri;
					
					// If there is class set on the config for external links we’ll grab it now
					if (Config::get('xtatic::xtatic.external_link_class')) $link_classes[] = Config::get('xtatic::xtatic.external_link_class');
					
					if ( Config::get('xtatic::xtatic.new_window_for_external_links') ) $link_attributes['target'] = '_blank';
					break;
					
				case substr($uri, 0, 6) == 'mailto' :
					$full_uri = HTML::email($uri);

					// If there is class set on the config for external links we’ll grab it now
					if (Config::get('xtatic::xtatic.mailto_link_class')) $link_classes[] = Config::get('xtatic::xtatic.mailto_link_class');

					if ( Config::get('xtatic::xtatic.new_window_for_external_links') ) $link_attributes['target'] = '_blank';
					break;
			
					
				default :
					$full_uri = url($uri);
					$link_classes[] = $uri;
			}
			
			// Only append HTML output if the page is LIVE
			if ( $is_live ) {
				
				// If the element’s URI slug matches the current URI slug then we may want the element to be inactive
				if ( $uri == static::$slug && Config::get('xtatic::xtatic.inactive_link_class') ) {
					$link_classes[] = Config::get('xtatic::xtatic.inactive_link_class');
				}
				
				if ( count($link_classes) ) {
					$link_attributes['class'] = implode( ' ', $link_classes );
				}
				
				// Open the list item, and if URI matches the slug we’ll class it as inactive
				$html .= '<' . $item_tag . static::array_to_attributes($link_attributes) . '>';
			
				// Add label with link if needed - this omits the <a> tag if the uri matches the current page slug
				$html .= ($uri && $uri != static::$slug) ? '<a href="' . $full_uri . '">' . $label . '</a>' : $label;
				
				// Add nested list if there are subordinate elements (unless this is a <dl>) and we haven’t gone out of our depth
				if (is_array($value) && $item_tag == 'li' && (is_null($to_depth) || static::$depth < $to_depth)) {
					// We’re going down a level so increment depth
					static::$depth ++;
					$html .= "\n" . static::as_list($value, $list_tag, $to_depth);
					// And we’re coming back up again
					static::$depth --;
				}
			
				// Close the list item
				$html .= '</' . $item_tag . '>' . "\n";
				
				// If there are subordinate elements and this IS a <dl> and we haven’t gone out of our depth then append them
				if (is_array($value) && $item_tag != 'li' && (is_null($to_depth) || static::$depth < $to_depth)) {
					
					// We’re going down a level so increment depth
					static::$depth ++;
					$html .= static::as_list($value, $item_tag);
					// And we’re coming back up again
					static::$depth --;
				}
			}
		}
		
		return $html;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Ancestors
	 *
	 * Looks for the supplied child key in the first (primary) navigation array
	 * and returns an array of ancestor page keys.
	 *
	 * @access	public
	 * @param 	string	Page key of junior element
	 * @param	bool	Whether to include the most senior root element as an ancestor
	 * @return 	array 	Indexed array of page keys in order of descending seniority
	 */
	public static function ancestors($slug=NULL, $include_root=FALSE)
	{
		// No slug provided? Let’s use the current page slug then...
		if ( is_null($slug) ) $slug = static::$slug;
		
		$navigation = Config::get( 'xtatic::xtatic.navigation');
		
		$root_key = array_search( reset( $navigation ), $navigation );
		$nav_elements = Config::get( 'xtatic::xtatic.navigation.' . $root_key );
		
		$containers = static::find_in_array($slug, $nav_elements, array($root_key => $nav_elements));
		
		if (!$include_root) array_shift($containers);
		
		return $containers;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Find In Array
	 *
	 * Recursively parse haystack array of page keys in search of supplied needle.
	 *
	 * @param	string	Page key we are looking for
	 * @param	array 	Array of page keys in which to search
	 * @param	array 	Array of ancestor page keys derived thus far
	 * @return 	array 	Array of ancestor page keys
	 */
	private static function find_in_array($needle, $haystack=array(), $containers=array())
	{
		foreach ($haystack as $key => $value)
		{
			switch (TRUE)
			{
				case ($value == $needle) :
					// BINGO! Add to array...
					$containers[$value] = array();
					// ...and send it back
					return $containers;
					break;
					
				case (!is_numeric($key) && $key == $needle) :
					// BINGO! Add to array...
					$containers[$key] = $value;
					// ...and send it back
					return $containers;
					break;
					
				case is_array($value) :
					// Key might be a valid member of the series...
					$containers[$key] = $value;
					
					$new_containers = static::find_in_array($needle, $value, $containers);
					if (count($new_containers) > count($containers))
						return $new_containers;
					
					// Still here? Then key wasn’t a valid member, so pop off...
					array_pop($containers);
					break;
					
				default :
					// Move along - there’s nothing to see here
			}
		}
				
		return $containers;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Siblings
	 *
	 * Test this with a deep sub menu!
	 */
	public static function siblings($slug=NULL)
	{		
		// Get array of ancestor slugs including the great-grandaddy root element
		$ancestors = static::ancestors($slug, TRUE);
		
		// Get rid of last element...
		array_pop($ancestors);
		
		// ...and return just the new last element
		return array_pop($ancestors);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Children
	 *
	 *
	 */
	public static function children($slug=NULL)
	{
		// No slug provided? Let’s use current page’s then...
		if (is_null($slug)) $slug = static::$slug;
		
		// Get array of ancestor slugs including the great-grandaddy root element
		$ancestors = static::ancestors($slug, TRUE);
		
		$children = array();
		
		if (array_key_exists($slug, $ancestors))
			$children = $ancestors[$slug];
		
		return $children;
	}
	
	// --------------------------------------------------------------------------
	
	public static function page_data( $slug=NULL )
	{
		if (!$slug) $slug = static::$slug;
		
		return static::$page_meta[$slug];
	}
	
	// --------------------------------------------------------------------------
	//
	// Public Assembly Methods
	//
	// --------------------------------------------------------------------------
	
	/**
	 * Add JavaScript
	 *
	 * Add literal JavaScript statements to be executed inline.
	 *
	 * @access	public
	 * @param	string		JavaScript statements
	 * @param	bool		Determines whether statements will be rendered the 
	 * 						document head or foot
	 * @return 	void
	 */
	public static function add_javascript( $js, $in_head=FALSE ) {
		
		if ($in_head) {
			static::$js_in_head[] = $js;
		} else {
			static::$js_in_tail[] = $js;
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Add JQuery
	 *
	 * Add literal JavaScript statements to be executed within a jQuery document
	 * ready method call.
	 *
	 * @access	public
	 * @param	string		JavaScript statements
	 * @return 	void
	 */
	public static function add_jquery($js)
	{
		static::$jquery_ready[] = $js;
	}
	
	// --------------------------------------------------------------------------
	//
	// Public Rendering Methods
	//
	// --------------------------------------------------------------------------
	
	/**
	 * Make
	 *
	 * Prime $content for retrieval in the view. Assemble and return the page view.
	 *
	 * @access	public
	 * @param	string		URL slug ti identify content
	 * @param	array 		Data which will be bound to the View
	 * @return 	mixed
	 */
	public static function make( $slug=NULL, $data=array() )
	{
		// If maintenance mode is on (defined in the config file) we’ll return 503
		if ( Config::get('xtatic::xtatic.maintenance_mode_on') ) {
			return Response::error('503');
		}
		
		// Determine the URI slug which will be the key to retrieving all our content
		static::$slug = ( $slug ) ? $slug : Config::get('xtatic::xtatic.default_page');
		
		// Retrieve the main content view - doing it here gives us the opportunity to
		// make any asset calls within the views and inject code into the document head
		static::$content = static::prime_the_content( $data );
		
		// Check that the slug provided corresponds to a page set on the config
		if ( array_key_exists( static::$slug, Config::get('xtatic::xtatic.pages' ) ) ) {
			
			// Pull the page data out of the config 
			static::$page_meta = Config::get('xtatic::xtatic.pages.' . static::$slug);
			
			// Pick up the view
			return View::make( Config::get('xtatic::xtatic.site_template'), $data );
			
		} else {
			// Requested page is not specified in the config so we’ll bail out...
			return Response::error('404');
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Head
	 *
	 * Assemble and return HTML head components - namely meta tags, CSS and
	 * JavaScript files - derived from the configuration file.
	 *
	 * @access	public
	 * @return 	string		HTML head components
	 */
	public static function head()
	{
		$html = '';
		
		// Register CSS assets from Xtatic config file
		foreach ( Config::get('xtatic::xtatic.css' ) as $css ) {
			
			$name = $css['name'];
			$source = $css['source'];
			$dependencies = !empty($css['dependencies']) ? $css['dependencies'] : array();
			$attributes = !empty($css['attributes']) ? $css['attributes'] : array();
			
			Asset::style( $name, $source, $dependencies, $attributes );
		}
		
		// Register JS assets from Xtatic config file
		foreach ( Config::get('xtatic::xtatic.js' ) as $js ) {
			
			$name = $js['name'];
			$source = $js['source'];
			$dependencies = !empty($js['dependencies']) ? $js['dependencies'] : array();
			$attributes = !empty($js['attributes']) ? $js['attributes'] : array();
			
			Asset::container( Config::get('xtatic::xtatic.header_asset_container') )->script( $name, $source, $dependencies, $attributes );
		}
		
		// Assemble <meta> tags
		switch ( TRUE ) {
			
			case ! Config::get('xtatic::xtatic.allow_search_engines_to_index' ) :
				$html .= static::meta_tag( array( 'robots' => 'noindex,nofollow' ) );
				break;
				
			case ! empty( static::$page_meta['meta_robots'] ) :
				$html .= static::meta_tag( array( 'robots' => static::$page_meta['meta_robots'] ) );
				break;
				
			default :
				//
		}
		
		if ( !empty( static::$page_meta['meta_description'] ) ) {
			$html .= static::meta_tag( array( 'description' => static::$page_meta['meta_description'] ) );
		}
		
		if ( !empty( static::$page_meta['meta_keywords'] ) ) {
			$html .= static::meta_tag( array( 'keywords' => static::$page_meta['meta_keywords'] ) );
		}
				
		// Get CSS styles
		$html .= Asset::styles();
		
		// Get JS scripts
		$html .= Asset::container( Config::get('xtatic::xtatic.header_asset_container') )->scripts();
		
		// Get Google Analytics tracking code
		$html .= static::javascript( static::google_analytics() );
		
		// Get inline JS
		$html .= static::javascript( static::$js_in_head );
		
		return $html;
	}
		
	// --------------------------------------------------------------------
	
	/**
	 * Tail
	 *
	 * Retrieve and return inline JavaScript to be rendered before the
	 * closing HTML body tag.
	 * 
	 * @access	public
	 * @return 	string		Inline JavaScript elements
	 */
	public static function tail()
	{
		$html = '';
		
		// Get JS scripts
		$html .= Asset::container( Config::get('xtatic::xtatic.footer_asset_container') )->scripts();
		
		// Get inline JS
		$html .= static::javascript( static::$js_in_tail );
		
		if (!empty( static::$jquery_ready ) ){
			
			$preamble = array( '(function($) {', '$(document).ready(function() {' );
			$postamble = array( '});', '})(jQuery);' );
			
			static::$jquery_ready = array_merge( $preamble, static::$jquery_ready, $postamble );
			
			$html .= static::javascript( static::$jquery_ready );
		}
		
		return $html;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Content
	 *
	 * Retrieve the content view which corresponds to the page URI slug.
	 *
	 * @access	public
	 * @return 	string
	 */
	public static function content()
	{
		return static::$content;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Get
	 *
	 * Retrieve ...
	 *
	 */
	public static function get( $attribute_name )
	{
		switch ( $attribute_name ) {
			
			case 'slug' :
				return static::$slug;
				break;
				
			case 'title' :
				return '<title>' . static::$page_meta['title'] . '</title>' . "\n";
				break;
				
			case 'site_owner' :
				return Config::get('xtatic::xtatic.site_owner');
				break;
				
			default :
				
				if ( array_key_exists( $attribute_name, static::$page_meta ) ) {
					return static::$page_meta[ $attribute_name ];
				}
		}
	}
	
	// --------------------------------------------------------------------------
	//
	// Rendering Methods (Private)
	//
	// --------------------------------------------------------------------------
	
	/**
	 * Prime The Content
	 *
	 * Render the main content view into the static::$content variable ready to be
	 * called for output in the main template view. This means we can parse the 
	 * content view before the HTML head is rendered, giving us the opportunity to
	 * inject asset calls that may be required on a per view basis.
	 * 
	 * @access	private
	 * @param	array 		Data to bind to the View
	 * @return 	string;
	 */
	private static function prime_the_content( $data=array() )
	{
		switch ( TRUE ) {
			
			// Slug has a corresponding content view
			case View::exists( Config::get('xtatic::xtatic.path_to_page_views') . static::$slug ) :
				return render( Config::get('xtatic::xtatic.path_to_page_views') . static::$slug, $data );
				break;
			
			// Slug has no corresponding page view but a fallback placeholder view exists
			case View::exists( Config::get('xtatic::xtatic.content_placeholder') ) :
				return render( Config::get('xtatic::xtatic.content_placeholder'), $data );
				break;
			
			// Slug has no corresponding page view and there is no fallback placeholder view
			default :
				return '';
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * JavaScript
	 * 
	 * Output literal JavaScript statements inside a <script> element.
	 *
	 * @access	private
	 * @param	array
	 * @return 	string	<script> element containing JavaScript statements
	 */
	private static function javascript( $js )
	{
		$html = '';
		
		if ( !empty( $js ) ) {
			
			$html .= '<script type="text/javascript">' . "\n";
			$html .= implode( "\n", (array) $js ) . "\n";
			$html .= '</script>' . "\n";
		}
		
		return $html;
	}
		
	// --------------------------------------------------------------------------
	
	/**
	 * Meta Tag
	 * 
	 * Returns an HTML <meta> tag with attributes derived from the supplied array’s
	 * key / value pairs.
	 * 
	 * @access	private
	 * @param	array
	 * @return 	string
	 */
	private static function meta_tag( $attributes )
	{
		return '<meta' . static::array_to_attributes( $attributes ) . '/>' . "\n";
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Google Analytics
	 * 
	 * Assemble the Google Analytics tracking code based on the values defined 
	 * in the config file.
	 *
	 * @access	private
	 * @return 	string		Google Analytics tracking code
	 */
	private static function google_analytics() {
		
		// Don’t try and track if no GA tracking ID is set
		if ( !(Config::get( 'xtatic::xtatic.google_analytics_id' )) ) return;
		
		// Don’t try and track if page is served locally
		if ( Request::env() == 'local' ) return;
		
		// Retrieve the GA ID(s) from the config, ensuring we have an array
		$ga_ids = (array) Config::get( 'xtatic::xtatic.google_analytics_id' );
				
		$ga_domain = Config::get( 'xtatic::xtatic.google_analytics_domain' );
		$multiple_domains = Config::get( 'xtatic::xtatic.google_analytics_multiple_tlds' );
		
		// Assemble the tracking code
		$tracking_code  = "\t" . 'var _gaq = _gaq || [];' . "\n";
		
		// Insert first GA tracking ID
		$tracking_code .= "\t" . '_gaq.push([\'_setAccount\', \'' . array_shift($ga_ids) . '\']);' . "\n";
		$tracking_code .= "\t" . '_gaq.push([\'_trackPageview\']);' . "\n";
		
		// If multiple GA tracking IDs have been specified then we need to append code for them too
		$id_counter = 1;
		while (count($ga_ids)) {
			$id_counter ++;
			$tracking_code .= "\t" . '_gaq.push([\'t' . $id_counter . '._setAccount\', \'' . array_shift($ga_ids) . '\']);' . "\n";
			$tracking_code .= "\t" . '_gaq.push([\'t' . $id_counter . '._trackPageview\']);' . "\n";
		}
		
		// Additional line if we are tracking multiple subdomains
		if ( $ga_domain ) {
			$tracking_code .= "\t" . '_gaq.push([\'_setDomainName\', \'' . $ga_domain . '\']);' . "\n";
		}
		
		// Additional line if we are tracking multiple top level domains
		if ( $multiple_domains ) {
			$tracking_code .= "\t" . '_gaq.push([\'_setAllowLinker\', true]);' . "\n";
		}
		
		$tracking_code .= "\n";
		
		$tracking_code .=   "\t" . '(function() {' . "\n";
		$tracking_code .= "\t\t" . 'var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;' . "\n";
		$tracking_code .= "\t\t" . 'ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';' . "\n";
		$tracking_code .= "\t\t" . 'var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);' . "\n";
		$tracking_code .=   "\t" . '})();' . "\n";
		
		return $tracking_code;
	}
	
	// --------------------------------------------------------------------------
	//
	// Private Utility Methods
	//
	// --------------------------------------------------------------------------
		
	/**
	 * Array To Attributes
	 *
	 * Return an HTML attribute string derived from the supplied array’s
	 * key / value pairs.
	 *
	 * @access	private
	 * @param	array 
	 * @return 	string
	 */
	private static function array_to_attributes( $attributes=array() )
	{
		$attribute_pairs = array();
		
		foreach ( (array) $attributes as $key => $value ) {
			$attribute_pairs[] = $key . '="' . $value . '"';
		}
		
		$attribute_string = implode( ' ', $attribute_pairs );
		
		if ( strlen( $attribute_string ) ) {
			$attribute_string = ' ' . $attribute_string . ' ';
		}
		
		return $attribute_string;
	}
}

/* End of file xtatic.php */
/* Location: ./application/third_party/xtatic/libraries/xtatic.php */