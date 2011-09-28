<?php 
/*
Plugin Name: FlexoPostManager Posts
Description: FlexoPostManager n posts from the desired category
Version: 0.0001
*/

class FlexoPostManager {


public static function FlexoPostManager_category_post($_args){
	/*	=Params
	------------------------------------------------------------------------------*//*
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
	------------------------------------------------------------------------------*//**/
	global $post;
	
	
	//vzimane na func za vizualizaciq
	$tfunc		=	'FlexoPostManager::format_post';
	
	if(isset($_args['template_func'])):
		$_tfunc	=	$_args['template_func'];
		if(function_exists($_tfunc))
			$tfunc	=	$_tfunc;
	endif;
	
	
	//vizmane na parametri
	$category = $_args['category'];
	$br_post 	=	$_args['count'] ? $_args['count'] : 10;
	$return 	=	$_args['return'] == true ? true :false;
	$title_first	=	$_args['title_first'] == true ? true :false;
	$_args['is_next']	= false;	

	
	if($h_num == 0 )$h_num = 3;
	
	$args	=	array();
	$args['category_name']	=	$category;
	$args['posts_per_page']			=	$br_post;
	global $query 	;

	$rez 		=	"";


	if(is_array($_args)){
		if($_args['order_by_postmeta']):
			global $wpdb;
			global $post;
			$postmeta_key		=	$_args['order_by_postmeta']['key'];
			$postmeta_order	=	$_args['order_by_postmeta']['order'];
			
	
				$querystr = "
				SELECT wposts.* , wpostmeta.`meta_value` as `".$postmeta_key."`
				FROM $wpdb->posts wposts
					LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
					LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
					LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
					LEFT JOIN $wpdb->terms ON ($wpdb->terms.term_id  = $wpdb->term_taxonomy.term_id)
				WHERE wpostmeta.meta_key = '".$postmeta_key."' 
					AND $wpdb->term_taxonomy.taxonomy = 'category'
					AND $wpdb->terms.slug LIKE '".$category."'
					AND wposts.post_date < NOW()
				ORDER BY `".$postmeta_key."` ".$postmeta_order."
				LIMIT 0,".$br_post."
				";		
			 $pageposts = $wpdb->get_results($querystr, OBJECT);		
			 //	echo '<pre>'; print_r ($querystr);echo'</pre>';
				if ($pageposts):
	 			global $post;
		 		foreach ($pageposts as $post):
			 		setup_postdata($post);		 
		 			$rez .= $tfunc($_args);
		 			$_args['is_next']	=	true;
		 		endforeach;
			endif;
		else:
				
			$args	=	array();
			$args['category_name']	=	$category;
			$args['posts_per_page']			=	$br_post;
			$query = new WP_Query( $args );
		
			while ( $query->have_posts() ) : $query->the_post();
				$rez .= $tfunc($_args);
				$_args['is_next']	=	true;
			endwhile;			
		endif;
	}
	wp_reset_query();
	
	if($return == true):
		return $rez;
	else:
		echo $rez;
	endif;
}



public static function format_post($_args){
	$title_first	=	$_args['title_first'] == true ? true :false;
	$h_num		=	$_args['h_num'] ? intval($_args['h_num']) : 3;
	$is_next	=	$_args['is_next'] ? true :false;
	$category = $_args['category'];


	if(isset($_args['master_class'])):
		$master_class	=	$_args['master_class'];
	else:
		$master_class	=	'post-preview';
	endif;

	$rez = "";
	//$vars = otm::vars();
	$_title	=	get_the_title();
	$_description	=	get_the_excerpt();
	$title	=	"<h".$h_num.">".'<a href="'.get_permalink().'?c='.$category.'" class="preview-title" title="'.$_title.'" >'.$_title."</a>"."</h".$h_num.">";
	$rez .= '<div class="'.$master_class.' is-'.($is_next ? 'next' : 'first').'">';
	if($title_first):
		$rez .= $title;
		//$rez .= "<div class='clear' style='padding-bottom:4px;'></div>";
	endif;
	if( has_post_thumbnail( $post->ID ) &&
						( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
						$image[1] >= HEADER_IMAGE_WIDTH ) :
					// Houston, we have a new header image!
					$rez .= '<a href="'.get_permalink().'" title="'.get_the_title().'" alt="'.get_the_title().'">'.get_the_post_thumbnail( $post->ID )."</a>";
	endif;
						
	if(!$title_first)	$rez .= $title;
	//$rez .= "<h".$header_number.">".'<a href="'.get_permalink().'" title="'.get_the_title().'" >'.get_the_title()."</a>"."</h".$header_number.">";
 	/*$rez .= $vars['pic'];*/
 	$rez .= "<p>";
 	$rez .= $_description;
 	//$rez .= '<a href="'.get_permalink().'" class="read_more">Прочети още</a>';
 	$rez .= "</p>";
	$rez .= '</div>';
	return $rez;
}




public static function format_post_as_picture($_args){
	global $post;
	$title_first	=	$_args['title_first'] == true ? true :false;
	$h_num		=	$_args['h_num'] ? intval($_args['h_num']) : 3;
	$is_next	=	$_args['is_next'] ? true :false;

	if(isset($_args['master_class'])):
		$master_class	=	$_args['master_class'];
	else:
		$master_class	=	'post-preview';
	endif;
	
	$rez = "";

	/*
	echo "<pre>";
	print_r($vars);
	echo "</pre>";
	*/
	
	$_title	=	 get_the_title();
	$rez .= '<div class="'.$master_class.' is-'.($is_next ? 'next' : 'first').'">';
	if($title_first):
		$rez .= $title;
		//$rez .= "<div class='clear' style='padding-bottom:4px;'></div>";
	endif;//echo FlexoPostManager::catch_first_image();
		if (has_post_thumbnail()) {
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail_name');
    echo $thumb[0]; 
					// Houston, we have a new header image!		
					$rez .= '<a href="'.$thumb.'" title="'.get_the_title().'" alt="'.get_the_title().'" rel="lightbox['.$master_class.']">'.get_the_post_thumbnail( $post->ID )."</a>";
					//$rez .= get_the_post_thumbnail( $post->ID )."";
		}				
	$rez .= '</div>';
	return $rez;
}


public static	function catch_first_image() {
 global $post, $posts;
 $first_img = '';
 ob_start();
 ob_end_clean();
 $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
 $first_img = $matches [1] [0];

 //if(empty($first_img)){
 //$first_img = " //"http://........./noimage.jpg"; // Сложете път до примерна картинка, в случай, че статията Ви няма добавена снимка.
 //}
 return $first_img;
}



public static function FlexoPostManager_post($_args) {
	
	$category				=		$_args['category_name'];
	$br_post				=		$_args['posts_per_page'];
	$header_number 	=		$_args['h_num'];
	$h							=		$_args['height'];
	$w							=		$_args['width'];
 
	
	global $post;
	$args	=	array();
	$args['category_name']	=	$category;
	$args['posts_per_page']			=	$br_post;
	$query = new WP_Query( $args );

	while ( $query->have_posts() ) : $query->the_post();

		echo '<div class="post-preview">';
		if( has_post_thumbnail( $post->ID ) &&
							(  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo '<a href="'.get_permalink().'" title="'.get_the_title().'" alt="'.get_the_title().'">'.get_the_post_thumbnail( $post->ID )."</a>";
		endif;
					
						$vars = otm::vars();
		echo "<h".$header_number.">".'<a href="'.get_permalink().'" title="'.get_the_title().'" >'.get_the_title()."</a>"."</h".$header_number.">";
	 	  $pic=FlexoPostManager::catch_first_image(); 
	 	  if($pic)
	 	 	 echo "<img src='".$pic."' height='".$h."' width='".$w."' />";
	 	
	 	the_excerpt();
	 	echo '<a href="'.get_permalink().'" class="read_more">Прочети още</a>';
		echo '</div>';
	endwhile;	 	
wp_reset_query();	

}

public static function FlexoPostManager_post_custom_text($category,$br_post , $header_number = "3") {
	global $post;
	$args	=	array();
	$args['category_name']	=	$category;
	$args['posts_per_page']			=	$br_post;
	
	$query = new WP_Query( $args );

	while ( $query->have_posts() ) : $query->the_post();

		echo '<div class="post-preview">';
		if( has_post_thumbnail( $post->ID ) &&
							(  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo '<a href="'.get_permalink().'" title="'.get_the_title().'" alt="'.get_the_title().'">'.get_the_post_thumbnail( $post->ID )."</a>";
		endif;
						
		$vars = rondova::vars();
		//print_r ($vars);
	 	 $h=222;$w=222;
	 	  $pic=FlexoPostManager::catch_first_image(); 
	 	  if($pic)
	 	 	 echo "<img  src='".$pic."' height='".$h."' width='".$w."' />";
	 	 	 echo '<div class="cont">';
	 			echo "<h".$header_number.">".'<a href="'.get_permalink().'" title="'.get_the_title().'" >'.get_the_title()."</a>"."</h".$header_number.">";
							
	 							echo $vars['text_disc'];
	 
	 	echo '<div class="readm"><a href="'.get_permalink().'" class="read_more">научи повече</a></div>';
	 	echo '</div>';
	 	
		echo '</div>';
	endwhile;	 	
wp_reset_query();
}




public static function FlexoPostManager_post_custom($category,$br_post , $header_number = "3") {
	global $post;
	$args	=	array();
	$args['category_name']	=	$category;
	$args['posts_per_page']			=	$br_post;
	
	$query = new WP_Query( $args );

	while ( $query->have_posts() ) : $query->the_post();

		echo '<div class="post-preview">';
		if( has_post_thumbnail( $post->ID ) &&
							(  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo '<a href="'.get_permalink().'" title="'.get_the_title().'" alt="'.get_the_title().'">'.get_the_post_thumbnail( $post->ID )."</a>";
		endif;
						
		$vars= rondova::vars();
	 	 $h=100;$w=180;
	 	 	 echo "<img  src='".$vars['pic']."' height='".$h."' width='".$w."' />";
	 	 	 echo '<div class="cont">';
	 			echo "<h".$header_number.">".'<a href="'.get_permalink().'" title="'.get_the_title().'" >'.get_the_title()."</a>"."</h".$header_number.">";
							
						
								echo '<p>'.$vars['pictext'].'</p>';
			
	 
	 	echo '<div class="readm"><a href="'.get_permalink().'" class="read_more">научи повече</a></div>';
	 	echo '</div>';
	 	
		echo '</div>';
	endwhile;	 	
wp_reset_query();
}
} //class FlexoPostManager
	
	
	/* =Izkarvane na n-broi publikacii ot dadena kategoriq
------------------------------------------------------------ 
public static function FlexoPostManager_category_post($category,$br_post , $header_number = "3") {
	global $post;
	$args	=	array();
	$args['category_name']	=	$category;
	$args['post_count']			=	$br_post;
	
	$query = new WP_Query( $args );

	while ( $query->have_posts() ) : $query->the_post();

		echo '<div class="post-preview">';
		if( has_post_thumbnail( $post->ID ) &&
							(  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo '<a href="'.get_permalink().'" title="'.get_the_title().'" alt="'.get_the_title().'">'.get_the_post_thumbnail( $post->ID )."</a>";
		endif;
						
		$vars = otm::vars();
		echo "<h".$header_number.">".'<a href="'.get_permalink().'" title="'.get_the_title().'" >'.get_the_title()."</a>"."</h".$header_number.">";
	 	 $h=60;$w=90;
	 	  $pic=FlexoPostManager::catch_first_image(); 
	 	  if($pic)
	 	 	 echo "<img src='".$pic."' height='".$h."' width='".$w."' />";
	 	
	 	the_excerpt();
	 	echo '<a href="'.get_permalink().'" class="read_more">Прочети още</a>';
		echo '</div>';
	endwhile;	 	
wp_reset_query();
}

public static function get_category_post($category,$br_post) {
}


public static	function catch_first_image() {
 global $post, $posts;
 $first_img = '';
 ob_start();
 ob_end_clean();
 $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
 $first_img = $matches [1] [0];

 //if(empty($first_img)){
 //$first_img = " //"http://........./noimage.jpg"; // Сложете път до примерна картинка, в случай, че статията Ви няма добавена снимка.
 //}
 return $first_img;
}
	
} //class FlexoPostManager

*/
?>