<?php
/*
Plugin Name: Stormtribe Job Seeker
Plugin URI: http://www.stormtribemarket.com
Description: List jobs on your site
Version: 1.0.1
Author: Stormtribe Development
Author URI: http://www.stormtribemarket.com
License: GPLv2
*/

global $g_sjs_version;
$g_sjs_version = "1.0.1";

global $g_sjs_capability;
global $g_sjs_plug_basename, $g_sjs_plug_dir, $g_sjs_plug_url;
global $g_sjs_upload_dir;

register_activation_hook(__FILE__, 'stdev_job_seeker_install_hook');
function stdev_job_seeker_install_hook()
{
    include_once '__install.php';
    stdev_job_seeker_install();
}
/*
register_deactivation_hook( __FILE__, 'stdev_job_seeker_deactivate_hook' );
function stdev_job_seeker_deactivate_hook()
{
    
}*/

add_action( 'init', 'stdev_job_seeker_init' );
function stdev_job_seeker_init()
{
    global $g_sjs_plug_basename; $g_sjs_plug_basename = plugin_basename(dirname( __FILE__ ));
    global $g_sjs_plug_dir; $g_sjs_plug_dir = plugin_dir_path( __FILE__ );
    global $g_sjs_plug_url; $g_sjs_plug_url = plugins_url(null, __FILE__) . '/';
    
    
    global $g_sjs_upload_dir;
    $upload_dir = wp_upload_dir();
    $g_sjs_upload_dir = $upload_dir['basedir'].'/';
    
    load_plugin_textdomain('stdev-job-seeker', false, $g_sjs_plug_basename.'/languages/');
    
    $sjs_sets = get_site_option('stdev_job_seeker_settings');
    global $g_sjs_capability; $g_sjs_capability = $sjs_sets['capability'];
    //include 'my_job_seeker.php';
    include $g_sjs_upload_dir.'my_job_seeker.php';
    
    global $g_sjs_version;
    if (is_admin())
    {
        wp_enqueue_style('stdev-job-seeker-admin', $g_sjs_plug_url.'assets/stdev-job-seeker-admin.css', false, $g_sjs_version, 'all');
        
        include_once 'admin/admin.php';
    }
    else
    {
        add_action('wp_head', 'sjs_echo_css');
    }
    
}

/**
 * Do a query for jobs
 * @param array $args - Arguments, ex: array('posts' => 5)
 * @return class WP_Query
 */
function sjs_query_jobs(&$args)
{
    $sjs_sets = get_option("stdev_job_seeker_blog_settings");

    //Get jobs only in one area based on page/post name
    if ($sjs_sets['use_by_area'] == 1)
    {
        global $post;
        if (!empty($post->post_name))
        {
            $term = term_exists($post->post_name, SJS_TAX_AREA_NAME);
            if ($term !== 0 && $term !== null)
                $args[SJS_TAX_AREA_NAME] = $post->post_name;
        }
    }
    //END get job by area
    //Get jobs only in one career based on page/post name
    if ($sjs_sets['use_by_career'] == 1)
    {
        global $post;
        if (!empty($post->post_name))
        {
            $term = term_exists($post->post_name, SJS_TAX_CAREER_NAME);
            if ($term !== 0 && $term !== null)
                $args[SJS_TAX_CAREER_NAME] = $post->post_name;
        }
    }
    //END get job by career        
    
    $temp_args = shortcode_atts(array(
        'type' => SJS_POST_NAME,
        'posts' => $sjs_sets['max_jobs'],
        'orderby' => 'date',
        'order' => 'desc',
        SJS_TAX_AREA_NAME => '',
        SJS_TAX_CAREER_NAME => '',
        'category' => '',
        'animate_time' => $sjs_sets['scroller_animate_time'],
        'stop_time' => $sjs_sets['scroller_stop_time'],
    ), $args );    

    $args = $temp_args;
    
    $options = array(
        'post_type' => $args['type'],
        'posts_per_page' => $args['posts'],
        'orderby' => $args['orderby'],
        'order' => $args['order'],
        SJS_TAX_AREA_NAME => $args['area'],
        SJS_TAX_CAREER_NAME => $args['career'],
        'category_name' => $args['category'],
    );
    $query = new WP_Query($options);
    return $query;
}


function sjs_shortcode_list_jobs($attr)
{
    return sjs_get_jobs($attr);
}
add_shortcode('sjs_list_jobs', 'sjs_shortcode_list_jobs');


function sjs_shortcode_job_scroller($attr)
{
    return sjs_get_scroller('shortcode', $attr);
}
add_shortcode('sjs_job_scroller', 'sjs_shortcode_job_scroller');




class C_sjs_widget extends WP_Widget
{
	function __construct()
    {
		parent::__construct(
			'sjs_scroller_widget',
			__('Job Seeker', 'stdev-job-seeker'),
			array('description' => __('Adds a scrolling jobs list', 'stdev-job-seeker'))
		);
	}

	public function widget($args, $instance)
    {
		$title = apply_filters('widget_title', $instance['title']);
        $san_title = sanitize_title($instance['san_title']);
        $args['is_widget'] = true;
        $args['posts'] = $instance['posts'];
        $args['orderby'] = $instance['orderby'];
        $args['order'] = $instance['order'];
        $args['animate_time'] = $instance['animate_time'];
        $args['stop_time'] = $instance['stop_time'];
        
		echo $args['before_widget'];
		if (!empty($title))
			echo $args['before_title'] . $title . $args['after_title'];
		echo sjs_get_scroller($san_title, $args);
		echo $args['after_widget'];
	}

	public function form($instance)
    {
        $sjs_sets = get_option("stdev_job_seeker_blog_settings");
        
		if (isset($instance['title']))
			$title = $instance['title'];
		else
			$title = __('New title', 'stdev-job-seeker');

		if (isset($instance['posts']))
			$jobs = $instance['posts'];
		else
			$jobs = 10;        

		if (isset($instance['orderby']))
			$orderby = $instance['orderby'];
		else
			$orderby = 'date';        
		if (isset($instance['order']))
			$order = $instance['order'];
		else
			$order = 'desc';        
        
        
		if (isset($instance['animate_time']))
			$animate_time = $instance['animate_time'];
		else
			$animate_time = $sjs_sets['scroller_animate_time'];
        
		if (isset($instance['stop_time']))
			$stop_time = $instance['stop_time'];
		else
			$stop_time = $sjs_sets['scroller_stop_time'];        
        
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'stdev-job-seeker'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('posts'); ?>"><?php _e('Number of jobs', 'stdev-job-seeker'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" type="text" value="<?php echo esc_attr($jobs); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order by', 'stdev-job-seeker'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" type="text" value="<?php echo esc_attr($orderby); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'stdev-job-seeker'); ?>:</label> 
        <select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
            <option value="asc" <?php echo ($order === 'asc' ? ' checked' : ''); ?>><?php _e('Ascending', 'stdev-job-seeker'); ?></option>
            <option value="desc" <?php echo ($order === 'desc' ? ' checked' : ''); ?>><?php _e('Descending', 'stdev-job-seeker'); ?></option>
        </select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('animate_time'); ?>"><?php _e('Animation time', 'stdev-job-seeker'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id('animate_time'); ?>" name="<?php echo $this->get_field_name('animate_time'); ?>" type="text" value="<?php echo esc_attr($animate_time); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('stop_time'); ?>"><?php _e('Stop time', 'stdev-job-seeker'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id('stop_time'); ?>" name="<?php echo $this->get_field_name('stop_time'); ?>" type="text" value="<?php echo esc_attr($stop_time); ?>">
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance )
    {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        
        if (empty($instance['title']))
            $instance['san_title'] = sanitize_title(uniqid());
        else
            $instance['san_title'] = sanitize_title($instance['title']);

        if (!is_numeric($new_instance['posts']))
    		$instance['posts'] = 10;
        else
            $instance['posts'] = (int)trim(($new_instance['posts']));
        
        if (!empty($instance['orderby']))
            $instance['orderby'] = sanitize_title($instance['orderby']);
        else
            $instance['orderby'] = 'date';
        if (!empty($instance['order']))
            $instance['order'] = sanitize_title($instance['order']);
        else
            $instance['order'] = 'desc';
        
        
        if (!is_numeric($new_instance['animate_time']))
    		$instance['animate_time'] = 2500;
        else
            $instance['animate_time'] = (int)trim(($new_instance['animate_time']));

        if (!is_numeric($new_instance['stop_time']))
    		$instance['stop_time'] = 3000;
        else
            $instance['stop_time'] = (int)trim(($new_instance['stop_time']));
        
		return $instance;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("C_sjs_widget");'));
?>