=== flexo-posts-manager===

Plugin Name: flexo-posts-manager
Contributors: flexostudio
Tags:posts,category
Author: flexostudio
Description:
Version: 0.0001 
Stable tag:0.0001
Requires at least:3.0
Tested up to: 3.0

показва избран от вас брой публикации от избрана категория

== Description ==
Избирате от коя категория и колко на брой публикации искате да се показват, като се извежад 
името, картинката и част от съдържанието на публикацията.

== Installation ==
1.	Download.
2.	Unzip.
3.	Upload to the plugins directory.
4.	Activate the plugin.
5.	Have a nice work.

== How to use ==
С FlexoPostManager::FlexoPostManager_category_post($_args); където $_args е масив от вида 	
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

== Screenshots ==