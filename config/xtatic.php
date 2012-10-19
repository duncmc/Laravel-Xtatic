<?php

/**
 * Xtatic Configuration
 *
 * Global configuration settings for the website.
 * 
 * (c) September 2012 Art & Soul Communications Ltd
 *
 * @package		Xtatic
 * @category	Configs
 * @author		Duncan McMillan
 * @link		http://www.artandsoul.co.uk
 * @version 	1.0
 *
 */

return array(

	/*
	|--------------------------------------------------------------------------
	| Site Owner
	|--------------------------------------------------------------------------
	|
	| The name of the site owner - provides easy access in views, such as in a
	| copyright statement in the page footer, title tags, etc.
	|
	*/

	'site_owner' => 'The Site Owner',
	
	/*
	|--------------------------------------------------------------------------
	| Maintenance Mode On
	|--------------------------------------------------------------------------
	|
	| When set to TRUE any attempt to browse the site will return a 503 error.
	| To handle this nicely make sure that there is a file at views/error/503.php.
	|
	*/
	
	'maintenance_mode_on' => FALSE,
	
	/*
	|--------------------------------------------------------------------------
	| Allow Search Engines To Index
	|--------------------------------------------------------------------------
	|
	| Determines whether search engines have permission to index the site. If set to 
	| FALSE your site will include <meta name='robots' content='noindex,nofollow' />
	| in the head.
	|
	| When set to TRUE the head will not include the robots meta tag, but this
	| can be overridden on indvidual pages by setting the page 'meta_robots'
	| attribute on the corresponding element of the 'pages' configuration further
	| down this file.
	|
	*/
	
	'allow_search_engines_to_index' => TRUE,
	
	/*
	|--------------------------------------------------------------------------
	| Google Analytics ID
	|--------------------------------------------------------------------------
	|
	| The unique Google Analytics web property ID provided by the Google Analytics
	| tool. If an ID or array of IDs is specified and the site is in a 'production' 
	| environment then Xtatic will render the Google Analytics JavaScript in the view.
	| To see how to define your site’s environment see the Laravel documentation.
	|
	| If specified as an array then GA code will be inserted to support multiple IDs.
	|
	*/
	
	'google_analytics_id' => '', // e.g. UA-1234567-1
	
	/*
	|--------------------------------------------------------------------------
	| Google Analytics Domain
	|--------------------------------------------------------------------------
	|
	| Specifies the primary top level domain used on the Google Analytics 
	| account. This is only required if multiple sub domains are to be tracked.
	| e.g. mydomain.com, www.mydomain.com, support.mydomain.com, etc.
	|
	*/
	
	'google_analytics_domain' => NULL, // e.g. 'mydomain.com'
	
	/*
	|--------------------------------------------------------------------------
	| Google Analytics Multiple TLDs
	|--------------------------------------------------------------------------
	| 
	| Specify whether mulitple domains are tracked in Google Analytics
	| e.g. mydomain.com, mydomain.co.uk, my-domain.com, etc.
	|
	*/
	
	'google_analytics_multiple_tlds' => FALSE,

	/*
	|--------------------------------------------------------------------------
	| Default Page
	|--------------------------------------------------------------------------
	|
	| The URI slug which will be considered as the default home or index page.
	|
	*/

	'default_page' => 'home',

	/*
	|--------------------------------------------------------------------------
	| Navigation
	|--------------------------------------------------------------------------
	|
	| Nested arrays of navigation elements which determine the site’s navigation
	| structure. Read the Xtatic documentation for more on how to define this item.
	|
	*/

	'navigation' => array(
		
		'primary-navigation' => array(
			'home',
			'about-us',
			'products' => array(
				'widgets',
				'doohickeys',
				'thingummy-jigs',
			),
			'My Blog' => 'http://www.my-blog.com',
			'get-in-touch',
		),

		'footer-navigation' => array(
			'home', 'about-us', 'products', 'get-in-touch',
		),
		
	),
	
	/*
	|--------------------------------------------------------------------------
	| Pages
	|--------------------------------------------------------------------------
	|
	| Array of meta data for each of the site’s pages. The array keys correspond
	| to the URI slug of the selected page. Read the Xtatic documentation for
	| more on how to define this item.
	|
	*/

	'pages' => array(
		
		## Top Level Pages ##

		// Home
		'home' => array(
			'title' 			=> 'Welcome | Xtatic',
			'meta_description'	=> 'Summary of the page in 160 characters or fewer',
			'meta_keywords'		=> '',
			'meta_robots'		=> '',
			'label'				=> 'Home',
			'is_live'			=> TRUE,
		),

		// About Us
		'about-us' => array(
			'title' 			=> 'About Us | Xtatic',
			'meta_description'	=> 'Summary of the page in 160 characters or fewer',
			'meta_keywords'		=> '',
			'meta_robots'		=> '',
			'label'				=> 'About Us',
			'is_live'			=> TRUE,
		),

		// Products
		'products' => array(
			'title' 			=> 'Our Products | Xtatic',
			'meta_description'	=> 'Summary of the page in 160 characters or fewer',
			'meta_keywords'		=> '',
			'meta_robots'		=> '',
			'label'				=> 'Products',
			'is_live'			=> TRUE,
		),

		// Get In Touch
		'get-in-touch' => array(
			'title' 			=> 'Get In Touch | Xtatic',
			'meta_description'	=> 'Summary of the page in 160 characters or fewer',
			'meta_keywords'		=> '',
			'meta_robots'		=> '',
			'label'				=> 'Get In Touch',
			'is_live'			=> TRUE,
		),

		## Second Level Pages - Products ##

		// Widgets
		'widgets' => array(
			'title' 			=> 'Widgets | Xtatic',
			'meta_description'	=> 'Summary of the page in 160 characters or fewer',
			'meta_keywords'		=> '',
			'meta_robots'		=> '',
			'label'				=> 'Widgets',
			'is_live'			=> TRUE,
		),

		// Doohickeys
		'doohickeys' => array(
			'title' 			=> 'Doohickeys | Xtatic',
			'meta_description'	=> 'Summary of the page in 160 characters or fewer',
			'meta_keywords'		=> '',
			'meta_robots'		=> '',
			'label'				=> 'Doohickeys',
			'is_live'			=> TRUE,
		),

		// Thingummy Jigs
		'thingummy-jigs' => array(
			'title' 			=> 'Thingummy Jigs | Xtatic',
			'meta_description'	=> 'Summary of the page in 160 characters or fewer',
			'meta_keywords'		=> '',
			'meta_robots'		=> '',
			'label'				=> 'Thingummy Jigs',
			'is_live'			=> TRUE,
		),
	),
	
	/*
	|--------------------------------------------------------------------------
	| CSS
	|--------------------------------------------------------------------------
	|
	| Array of CSS stylesheets to apply globally across the site. Each stylesheet
	| file is defined as an array whose keys correspond to the parameters 
	| required by Laravel’s Asset::style() method - see Laravel documentation.
	|
	*/
	
	'css' => array(
		array(
			'name' => 'main-style',
			'source' => 'css/main.css',
		//	'dependencies' => array(),
		//	'attributes' => array(),
		),
	),
	
	/*
	|--------------------------------------------------------------------------
	| JavaScript
	|--------------------------------------------------------------------------
	|
	| Array of JavaScript files to load globally across the site. Each JS
	| file is defined as an array whose keys correspond to the parameters 
	| required by Laravel’s Asset::script() method - see Laravel documentation.
	|
	*/
	
	'js' => array(
		array(
			'name' => 'jquery',
			'source' => 'js/libs/jquery-1.8.2.min.js',
		//	'dependencies' => array(),
		//	'attributes' => array(),
		),
		array(
			'name' => 'modernizr',
			'source' => 'js/libs/modernizr.js',
		//	'dependencies' => array(),
		//	'attributes' => array(),
		),
	),
	
	/*
	|--------------------------------------------------------------------------
	| New Window For External Links
	|--------------------------------------------------------------------------
	|
	| If TRUE then any external links in Xtatic-generated menus will open in a
	| new window (via the target="_blank" attribute).
	|
	*/

	'new_window_for_external_links' => TRUE,

	/*
	|--------------------------------------------------------------------------
	| External Link Class
	|--------------------------------------------------------------------------
	|
	| A CSS class name to apply to menu items which have external links.
	| Set as NULL if no additional class is required. Make sure this doesn’t
	| clash with the name of any key defined on the pages array.
	|
	*/

	'external_link_class' => 'external-link',

	/*
	|--------------------------------------------------------------------------
	| Inactive Link Class
	|--------------------------------------------------------------------------
	|
	| A CSS class name to apply to menu items which contain an inactive link
	| (i.e. it corresponds to the current page). Set as NULL if no additional
	| class is required. Make sure this doesn’t clash with the name of any key
	| defined on the pages array.
	|
	*/

	'inactive_link_class' => 'inactive-link',
	
	/*
	|--------------------------------------------------------------------------
	| Mailto Link Class
	|--------------------------------------------------------------------------
	|
	| A CSS class name to apply to menu items which contain an email link.
	| Set as NULL if no additional class is required. Make sure this doesn’t
	| clash with the name of any key defined on the pages array.
	|
	*/

	'mailto_link_class' => 'email-link',

	/*
	|--------------------------------------------------------------------------
	| Site Template
	|--------------------------------------------------------------------------
	|
	| The main view template used throughout the site, defined using Laravel’s
	| dot-separated path syntax. 
	|
	*/
	
	'site_template' => 'xtatic::templates.layout-template',
	
	/*
	|--------------------------------------------------------------------------
	| Path To Page Views
	|--------------------------------------------------------------------------
	|
	| The path to the directory containing the content subviews for the site’s pages, 
	| defined using Laravel’s dot-separated path syntax.
	|
	| Include the TRAILING DOT!
	|
	*/
	
	'path_to_page_views' => 'xtatic::content.',
	
	/*
	|--------------------------------------------------------------------------
	| Content Placeholder
	|--------------------------------------------------------------------------
	|
	| The view file which may be used to render placeholder content for a live
	| page which has no corresponding view.
	|
	*/

	'content_placeholder' => 'xtatic::common.no-content',
	
	/*
	|--------------------------------------------------------------------------
	| Header Asset Container
	|--------------------------------------------------------------------------
	|
	| The name of the Laravel asset container where scripts that are required in 
	| the HTML head are registered. 
	|
	*/
	
	'header_asset_container' => 'header',
	
	/*
	|--------------------------------------------------------------------------
	| Footer Asset Container
	|--------------------------------------------------------------------------
	|
	| The name of the Laravel asset container where scripts that are required 
	| before the closing HTML body tag are registered. 
	|
	*/
	
	'footer_asset_container' => 'footer',
	
);
/* End of file xtatic.php */
/* Location: ./application/third_party/xtatic/config/xtatic.php */