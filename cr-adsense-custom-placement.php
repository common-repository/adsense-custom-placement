<?php
/*
Plugin Name: Adsense Custom Placement
Plugin URI: http://alihussain.me/adsense-custom-placement/
Description: This plugin will help you customize adsense placement
Version: 0.1
Author: Ali Hussain
Author URI: http://alihussain.me/

/*  Copyright 2011 Ali hussain  (email : contact@alihussain.me)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


add_action('admin_menu', 'cr_adsense_cp_config_admin');
function cr_adsense_cp_config_admin()
{
	add_submenu_page( 'options-general.php', '[CR]Adsense CP', '[CR]Adsense CP', 8, 'cr_adsense_cp_config_admin_form', 'cr_adsense_cp_config_admin_form');
}

add_action('wp_print_styles', 'add_my_stylesheet');
function add_my_stylesheet() {
        $myStyleUrl = WP_PLUGIN_URL . '/cr-adsense-custom-placement/style.css';
        $myStyleFile = WP_PLUGIN_DIR . '/cr-adsense-custom-placement/style.css';
        if ( file_exists($myStyleFile) ) {
            wp_register_style('myStyleSheets', $myStyleUrl);
            wp_enqueue_style( 'myStyleSheets');
        }
    }
	
add_action('admin_head', 'cr_adsense_cp_admin_head');
function cr_adsense_cp_admin_head()
{
?>
<script language="javascript" type="text/javascript">
jQuery(document).ready( function(){
	jQuery( '#cr_adsense_cp_add_block' ).click( function(){

		jQuery( '#cr_adsense_cp_counter' ).val(parseInt(jQuery( '#cr_adsense_cp_counter' ).val()) + 1);

		var htmlRow = "<tr><td valign='top'>";
htmlRow += "<select name='cr_adsense_cp_configurations[" + jQuery( '#cr_adsense_cp_counter' ).val() + "][option]'>";
htmlRow += "  <option value='above post'>Above Post Content</option>";
htmlRow += "  <option value='below post'>Below Post Content</option>";
htmlRow += "  <option value='above page'>Above Page Content</option>";
htmlRow += "  <option value='below page'>Below Page Content</option>";
htmlRow += "  <option value='above title post'>Above Post Title</option>";
htmlRow += "  <option value='below title post'>Below Post Title</option>";
htmlRow += "</select>";
htmlRow += "</td><td valign='top'>";
htmlRow += "<input type='text' size='2' name='cr_adsense_cp_configurations[" + jQuery( '#cr_adsense_cp_counter' ).val() + "][post_count]' value='' />";
htmlRow += "</td><td valign='top'>";
htmlRow += "<textarea name='cr_adsense_cp_configurations[" + jQuery( '#cr_adsense_cp_counter' ).val() + "][adsense_code]' rows='10' cols='50'>";
htmlRow += "</textarea>";
htmlRow += "</td>";
htmlRow += "</tr>";

		jQuery( '#blocks' ).append("<table>" + htmlRow + "</table>");
		
		
	});
});
</script>
<?php
}


function cr_adsense_cp_config_admin_form()
{
	
	
?>
<div class="wrap">
<h2>[CR]Adsense Custom Placement</h2>
<p>Here you can set your custom placement for adsense.</p>
<h3>FEATURES</h3>
<ul>
<li>1. Easy "Copy & Paste" your ads code for embedding AdSense in your WordPress posts</li>
<li>2. Set the number and types of ads for page</li>
<li>3. Select the section of your blog where to insert each ad (homepage, posts, pages)</li>
<li>4. All settings configured through WordPress Options interface (no knowledge of plugins or PHP required)</li>
<li>5. Easily test different ad formats and positions modifying your existing ads</li>
<li>6. Advertisements can be insterted in the sidebar [seperate feature]</li>
</ul>
<form method="post" action="options.php">
<?php
wp_nonce_field('update-options');
$configs = get_option('cr_adsense_cp_configurations', array());
$counter = isset( $configs['counter'] ) ? $configs['counter'] : 0;

//print_r( $configs );
?>



<h4>Adsense Placement(s)</h4>
<input type="hidden" id="cr_adsense_cp_counter" name="cr_adsense_cp_configurations[counter]" value="<?php echo $counter; ?>" />
<div id="blocks">
<?php

$default_block = array(
	'option' => '',
	'post_count' => '',
	'adsense_code' => '',
	'block_note' => '',
);
for($idx = 0; $idx <= $counter; $idx++)
{
	$block = isset( $configs[ $idx ] ) ? $configs[ $idx ] : $default_block;
?>
<table><tr><td valign="top">
<select name="cr_adsense_cp_configurations[<?php echo $idx; ?>][option]">
  <option value="above post" <?php echo ($block['option'] == 'above post') ? 'selected=""' : ''; ?>>Above Post Content</option>
  <option value="below post" <?php echo ($block['option'] == 'below post') ? 'selected=""' : ''; ?>>Below Post Content</option>
  <option value="above page" <?php echo ($block['option'] == 'above page') ? 'selected=""' : ''; ?>>Above Page Content</option>
  <option value="below page" <?php echo ($block['option'] == 'below page') ? 'selected=""' : ''; ?>>Below Page Content</option>
  <option value="above title post" <?php echo ($block['option'] == 'above title post') ? 'selected=""' : ''; ?>>Above Post Title</option>
  <option value="below title post" <?php echo ($block['option'] == 'below title post') ? 'selected=""' : ''; ?>>Below Post Content</option>
</select>
</td><td valign="top">
<input type="text" size="2" name="cr_adsense_cp_configurations[<?php echo $idx; ?>][post_count]" value="<?php echo $block['post_count']; ?>" />
</td><td valign="top">
<textarea name="cr_adsense_cp_configurations[<?php echo $idx; ?>][adsense_code]" rows="10" cols="50">
<?php echo $block['adsense_code']; ?>
</textarea>
</td></tr></table>
<?php
}
?>
</div>

<p class="submit">
<input id="cr_adsense_cp_add_block" type="button" class="button-primary" value="<?php _e('Add Block') ?>" />
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="cr_adsense_cp_configurations" />
</p>

</form>
</div>
<?php
}


add_filter('the_excerpt', 'cr_adsense_cp_filter_the_content', 1);
add_filter('the_content', 'cr_adsense_cp_filter_the_content', 1);
function cr_adsense_cp_filter_the_content($content)
{
	global $wp_query;
	
	//echo "<!--\n". print_r($wp_query, true)."\n-->";
	//echo "\$wp_query->current_post: $wp_query->current_post<br />";
	$configs = get_option('cr_adsense_cp_configurations', array());
	$counter = isset( $configs['counter'] ) ? $configs['counter'] : 0;
	
	$default_block = array(
		'option' => '',
		'post_count' => '',
		'adsense_code' => '',
		'block_note' => '',
	);
	
	$current_post = $wp_query->current_post + 1;
	$post_type = $wp_query->posts[ $wp_query->current_post ]->post_type;
	
	$switch_type = '';
	if( in_array( $post_type, array('post', 'page') ) )
	{
		$switch_type = $post_type;
	}

	for($idx = 0; $idx <= $counter; $idx++)
	{
		$block = isset( $configs[ $idx ] ) ? $configs[ $idx ] : $default_block;
		
		//print_r($block);
		if('above ' . $switch_type == $block[ 'option' ])
		{
			if($current_post == $block[ 'post_count' ])
			{
				$content = $block[ 'adsense_code' ] . $content;
			}
		}
		else if('below ' . $switch_type == $block[ 'option' ])
		{
			if($current_post == $block[ 'post_count' ])
			{
				$content .= $block[ 'adsense_code' ];
			}
		}
	}
	
	return $content;
}


add_filter('the_title', 'cr_adsense_cp_filter_the_title', 1);
function cr_adsense_cp_filter_the_title($content)
{
	global $wp_query;
	//echo "\$wp_query->current_post: $wp_query->current_post<br />";
	$configs = get_option('cr_adsense_cp_configurations', array());
	$counter = isset( $configs['counter'] ) ? $configs['counter'] : 0;
	
	$default_block = array(
		'option' => '',
		'post_count' => '',
		'adsense_code' => '',
		'block_note' => '',
	);
	
	$current_post = $wp_query->current_post + 1;
	$post_type = $wp_query->posts[ $wp_query->current_post ]->post_type;
	
	$switch_type = '';
	if( in_array( $post_type, array('post', 'page') ) )
	{
		$switch_type = $post_type;
	}

	for($idx = 0; $idx <= $counter; $idx++)
	{
		$block = isset( $configs[ $idx ] ) ? $configs[ $idx ] : $default_block;
		$adsense_code = $block[ 'adsense_code' ];
		
		//print_r($block);
		if('above title ' . $switch_type == $block[ 'option' ])
		{
			if($current_post == $block[ 'post_count' ])
			{
				if(trim( $adsense_code ) == '') continue;
				if( !is_single() )
					$content = "</a>" . $adsense_code . "<a href='" . get_permalink( $wp_query->posts[ $wp_query->current_post ]->ID ) . "'>" . $content . "</a>";
				else
					$content = $adsense_code . $content;
			}
		}
		else if('below title ' . $switch_type == $block[ 'option' ])
		{
			if(trim( $adsense_code ) == '') continue;
			if($current_post == $block[ 'post_count' ])
			{
				if( !is_single() )
					$content .= "</a>" . $adsense_code;
				else
					$content .= $adsense_code;
			}
		}
	}
	
	return $content;
}

class CR_Adsense_CP extends WP_Widget {
	function CR_Adsense_CP() {
		parent::WP_Widget('', $name = '[CR]Adsense CP');
	}
	
	function widget($args, $instance)
	{
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
			
			echo $instance['adsense_code'];
			
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['adsense_code'] = strip_tags($new_instance['adsense_code']);
		return $instance;
	}
	
	function form( $instance )
	{
		global $wpdb;
		$title = esc_attr($instance['title']);
		$adsense_code = esc_attr($instance['adsense_code']);
		?>
				<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('adsense_code'); ?>"><?php _e('Adsense Code:'); ?> 
				<textarea name="<?php echo $this->get_field_name('adsense_code'); ?>">
				<?php echo $adsense_code; ?>
				</textarea>
		<?php
	}
}
//register_widget('CR_Adsense_CP');

add_action('widgets_init', create_function('', 'return register_widget("CR_Adsense_CP");'));

function cr_adsense_cp_loop_API( $location = '', $echo = true)
{
	$configs = get_option('cr_adsense_cp_configurations', array());
	$counter = isset( $configs['counter'] ) ? $configs['counter'] : 0;
	
	$default_block = array(
		'option' => '',
		'post_count' => '',
		'adsense_code' => '',
		'block_note' => '',
	);
	
	for($idx = 0; $idx <= $counter; $idx++)
	{
		$block = isset( $configs[ $idx ] ) ? $configs[ $idx ] : $default_block;
		
		if($location == $block[ 'option' ])
		{
			$adsense_code .= $block[ 'adsense_code' ];
		}
	}
	
	if( $echo )
	{
		echo $adsense_code;
	}
	else
	{
		return $adsense_code;
	}
}
