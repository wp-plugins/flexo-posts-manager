=== flexo-posts-manager===

Plugin Name: flexo-posts-manager
Contributors: flexostudio
Tags:posts,category
Author: Grigor Grigorov, Mariela Stefanova, Flexo Studio Team
Plugin URI: http://www.flexostudio.com/wordpress-plugins-flexo-utils.html
Description:
Version: 1.0001 
Stable tag:1.0001
Requires at least:3.0.5
Tested up to: 3.0.5

shows your chosen number of publications in the selected category

== Description ==
Choose from which category and how many posts you want to show, as is shown
name and picture of the contents of this publication.
see: http://www.flexostudio.com/
== Installation ==
1.	Download.
2.	Unzip.
3.	Upload to the plugins directory.
4.	Activate the plugin.
5.	Have a nice work.

== How to use ==
1. From the Administration -> settings go to post manager options enter data, generate code copy and paste the desired place in post
2. FlexoPostManager::category_post($_args); where $ _args is an array of type
	$args	=	array(
		'category' => 'pictures',
		'count'		=>	5,
		'h_num'		=> 	3,
		'master_class'	=>	'post-preview',
		'return'	=> 	false,
		'title_first'	=> true,
		'template_func'		=> 'format_post_as_picture',
		'order_by_postmeta'	=> array(
			'key'	=> 'fb_score',
			'order'	=>	'DESC'
		 ),
	);
you can put this wherever you want in header, in sidebar .....

== Screenshots ==
-
== Frequently Asked Questions ==
-
== Changelog ==
-
== Upgrade Notice ==
-