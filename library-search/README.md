=== Library Search ===
Plugin Name: Library Search
Description: A plugin to search books in the library.
Version: 1.0
Author: Mr. Aniket Bhawkar
Author URI: https://www.linkedin.com/in/aniket-bhawkar-38908021/
License: GPL2

== Description ==

A few notes about the sections above:

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `library-search.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `library-search.zip`
2. Extract the `library-search` directory to your computer
3. Upload the `library-search` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

= Usage =

1. Custom Post Type "Books" will be generated after the plugin activation
2. Add the books in the backend
3. Use the shortcode [library_search] to access the search functionality in the frontend.

= Example =

<?php
	echo do_shortcode('[library_search]');
?>