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

������� ������ �� ��� ���� ���������� �� ������� ���������

== Description ==
�������� �� ��� ��������� � ����� �� ���� ���������� ������ �� �� ��������, ���� �� ������� 
�����, ���������� � ���� �� ������������ �� ������������.

== Installation ==
1.	Download.
2.	Unzip.
3.	Upload to the plugins directory.
4.	Activate the plugin.
5.	Have a nice work.

== How to use ==
� FlexoPostManager::FlexoPostManager_category_post($_args); ������ $_args � ����� �� ���� 	
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