<?php
/*
 * This file should be placed in your WordPress upload folder for custom editing
 * Don't forget to flush rewrite rules if you have changed any permalinks names
 */
if(count(get_included_files()) ==1) die("404...");
define('SJS_MY_VERSION',            1);
define('SJS_POST_NAME',             'jobs');
define('SJS_TAX_AREA_NAME',         'area');
define('SJS_TAX_AREA_BASE_URL',     'by-area');
define('SJS_TAX_CAREER_NAME',       'career');
define('SJS_TAX_CAREER_BASE_URL',   'by-career');

$labels = array(
    'name'               => __('Jobs', 'stdev-job-seeker'),
    'singular_name'      => __('Job', 'stdev-job-seeker'),
    'add_new'            => __('Add new job', 'stdev-job-seeker'),
    'add_new_item'       => __('Add new job', 'stdev-job-seeker'),
    'edit_item'          => __('Edit job', 'stdev-job-seeker'),
    'new_item'           => __('New job', 'stdev-job-seeker'),
    'all_items'          => __('All jobs', 'stdev-job-seeker'),
    'view_item'          => __('View job', 'stdev-job-seeker'),
    'search_items'       => __('Search jobs', 'stdev-job-seeker'),
    'not_found'          => __('No jobs found', 'stdev-job-seeker'),
    'not_found_in_trash' => __('No jobs found in the trash', 'stdev-job-seeker'), 
    'parent_item_colon'  => '',
    'menu_name'          => __('Jobs', 'stdev-job-seeker'), //Display name in admin menu
);
$args = array(
    'labels'                => $labels,
    'description'           => 'Holds our jobs and job specific data',
    'public'                => true,
//    'publicly_queryable'    => true,    //Whether queries can be performed on the front end as part of parse_request()
//    'exclude_from_search'   => false,   //Whether to exclude posts with this post type from front end search results
    'menu_position'         => 20, //http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
    'menu_icon'             => 'dashicons-analytics',
    'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'), //'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats
    'has_archive'           => true,
    'taxonomies'            => array(SJS_POST_NAME),    
//    'query_var'             => '',      //Sets the query_var key for this post type - Default: true - set to $post_type 
/*    'rewrite'               => array(   'slug'          => '',          //Customize the permastruct slug. Defaults to the $post_type value. Should be translatable.
                                        'with_front'    => false,       //Should the permastruct be prepended with the front base.
                                        'feeds'         => true,        //Should a feed permastruct be built for this post type. Defaults to has_archive value. 
                                        'pages'         => true,        //Should the permastruct provide for pagination. Defaults to true 
                                        'ep_mask'       => EP_PERMALINK //const As of 3.4 Assign an endpoint mask for this post type
                                    )
    */
);
register_post_type( SJS_POST_NAME, $args );

register_taxonomy(SJS_TAX_AREA_NAME, SJS_POST_NAME, array(
    'public'                => true,    //Should this taxonomy be exposed in the admin UI.  - defaults to true
//    'show_ui'               => true,    //Whether to generate a default UI for managing this taxonomy - if not set, defaults to value of public argument. As of 3.5, setting this to false for attachment taxonomies will hide the UI. 
//    'show_in_nav_menus'     => true,    //true makes this taxonomy available for selection in navigation menus - if not set, defaults to value of public argument 
//    'show_tagcloud'         => true,    //Whether to allow the Tag Cloud widget to use this taxonomy - if not set, defaults to value of show_ui argument 
    'show_admin_column'     => true,    //Whether to allow automatic creation of taxonomy columns on associated post-types - defaults to false
    'hierarchical'          => true,    //Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags - defaults to false
    'query_var'             => SJS_TAX_AREA_NAME, //False to disable the query_var, set as string to use custom query_var instead of default which is $taxonomy, the taxonomy's "name". 
    
    'labels' => array(
        'name'              => __('Areas', 'stdev-job-seeker'),
        'singular_name'     => __('Area', 'stdev-job-seeker'),
        'search_items'      => __('Search areas', 'stdev-job-seeker'),
        'all_items'         => __('All areas', 'stdev-job-seeker'),
        'parent_item'       => __('Parent area', 'stdev-job-seeker'),
        'parent_item_colon' => __('Parent area:', 'stdev-job-seeker'),
        'edit_item'         => __('Edit area', 'stdev-job-seeker'),
        'update_item'       => __('Update area', 'stdev-job-seeker'),
        'add_new_item'      => __('Add new area', 'stdev-job-seeker'),
        'new_item_name'     => __('New area name', 'stdev-job-seeker'),
        'menu_name'         => __('Areas', 'stdev-job-seeker'),
    ),
    'rewrite' => array(
        'slug'          => SJS_TAX_AREA_BASE_URL,            //Used as pretty permalink text. ex: /tag/) - defaults to $taxonomy (taxonomy's name slug) 
        'with_front'    => true,                        //allowing permalinks to be prepended with front base. ex: "/locations/" - defaults to true
        'hierarchical'  => true,                        //Allow hierarchical urls. ex: "/locations/sweden/stockholm/" - defaults to false  
        'ep_mask'       => EP_NONE                      //Assign an endpoint mask for this taxonomy - defaults to EP_NONE.
    ),
));

register_taxonomy(SJS_TAX_CAREER_NAME, SJS_POST_NAME, array(
    'public'                => true,    //Should this taxonomy be exposed in the admin UI.  - defaults to true
//    'show_ui'               => true,    //Whether to generate a default UI for managing this taxonomy - if not set, defaults to value of public argument. As of 3.5, setting this to false for attachment taxonomies will hide the UI. 
//    'show_in_nav_menus'     => true,    //true makes this taxonomy available for selection in navigation menus - if not set, defaults to value of public argument 
//    'show_tagcloud'         => true,    //Whether to allow the Tag Cloud widget to use this taxonomy - if not set, defaults to value of show_ui argument 
    'show_admin_column'     => true,    //Whether to allow automatic creation of taxonomy columns on associated post-types - defaults to false
    'hierarchical'          => true,    //Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags - defaults to false
    'query_var'             => SJS_TAX_CAREER_NAME, //False to disable the query_var, set as string to use custom query_var instead of default which is $taxonomy, the taxonomy's "name". 
    
    'labels' => array(
        'name'              => __('Careers', 'stdev-job-seeker'),
        'singular_name'     => __('Career', 'stdev-job-seeker'),
        'search_items'      => __('Search careers', 'stdev-job-seeker'),
        'all_items'         => __('All careers', 'stdev-job-seeker'),
        'parent_item'       => __('Parent career', 'stdev-job-seeker'),
        'parent_item_colon' => __('Parent career:', 'stdev-job-seeker'),
        'edit_item'         => __('Edit career', 'stdev-job-seeker'),
        'update_item'       => __('Update career', 'stdev-job-seeker'),
        'add_new_item'      => __('Add new career', 'stdev-job-seeker'),
        'new_item_name'     => __('New career name', 'stdev-job-seeker'),
        'menu_name'         => __('Careers', 'stdev-job-seeker'),
    ),
    'rewrite' => array(
        'slug'          => SJS_TAX_CAREER_BASE_URL,     //Used as pretty permalink text. ex: /tag/) - defaults to $taxonomy (taxonomy's name slug) 
        'with_front'    => true,                        //allowing permalinks to be prepended with front base. ex: "/locations/" - defaults to true
        'hierarchical'  => true,                        //Allow hierarchical urls. ex: "/locations/sweden/stockholm/" - defaults to false  
        'ep_mask'       => EP_NONE                      //Assign an endpoint mask for this taxonomy - defaults to EP_NONE.
    ),
));

/**
 * Prints css in header on frontend
 */
function sjs_echo_css()
{
    $sjs_sets = get_option("stdev_job_seeker_blog_settings");
    ?>
    <style type="text/css">
        .sjs_list_ul{
            list-style: none;
            font-family:Helvetica, Arial, sans-serif;
            line-height:20px !important;            
        }
        
        .sjs_list_li{
          
        }
        ul.sjs_list_li li:before {
            margin-left:-13px;
            content: "\00BB";
        }
        
        .sjs_list_featured{
            float:right;
            margin:0 5px 0 0;
        }
        span.sjs_list_job_title {
            font-weight:bold;
            font-size:13px;
            margin-top:8px !important;
        }        
        .sjs_list_job_career{
            color:#999;
            font-size:10px;
            text-decoration:italic;
        }
        .sjs_list_job_area{
            color:#999;
            font-size:10px;
            text-decoration:italic;
        }
        .sjs_list_job_divider{
            clear:both;
            border-top:#d1d1d1 dotted thin;
            width:100%;
            height:2px;
            padding-bottom:10px;
            margin-top:-20px;
        }	
        
        .sjs_scroll_li{
            margin-bottom:-5px;
        }		
		
        #sjs_scroller{
            font-family:Helvetica, Arial, sans-serif;
            overflow:hidden;
            height:<?php echo $sjs_sets['scroller_css_height']; ?>px;
            vertical-align:top;
            line-height:13px !important;
        }

        ul#sjs_scroller{
            list-style: none;
        }

        ul#sjs_scroller li:before {
            margin-left:-13px;
            content: "\00BB";
        }

        span.sjs_scroll_job_title {
            font-weight:bold;
            font-size:13px;
            margin-top:8px !important;
        }
        .sjs_scroll_job_area{
            color:#999;
            font-size:9px;
            text-decoration:italic;
        }
        .sjs_scroll_job_divider{
            clear:both;
            border-top:#d1d1d1 dotted thin;
            width:100%;
            height:2px;
            padding-bottom:10px;
            margin-top:-20px;
        }	
    </style>
    <?php
}

/**
 * Get list of jobs
 * @param array $args - Arguments for WP query post
 * @return string - The list output
 */
function sjs_get_jobs($args)
{
    $sjs_sets = get_option("stdev_job_seeker_blog_settings");
    
    $query = sjs_query_jobs($args);
    ob_start();
    if ($query->have_posts())
    {
        //echo '<pre>'; var_dump($query->posts); echo '</pre>';
        ?>
        <ul class="sjs_list_ul">
            <?php
            foreach ($query->posts as $p)
            {
                $area_terms = wp_get_post_terms($p->ID, SJS_TAX_AREA_NAME);
                $career_terms = wp_get_post_terms($p->ID, SJS_TAX_CAREER_NAME);
                ?>
                <li class="sjs_list_li">
                    <?php
                    if (has_post_thumbnail($p->ID)):
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($p->ID), 'thumbnail');
                    ?>
                        <img src="<?php echo $image[0]; ?>" alt="featured_image" class="sjs_list_featured" />
                    <?php endif; ?>
                    <span class="sjs_list_job_title"><a href="<?php echo get_post_permalink($p->ID); ?>"><?php echo $p->post_title; ?></a></span>
                    <br />
                    <small class="sjs_list_job_career"><?php echo '<a href="'.get_term_link($career_terms[0]).'">'.(isset($career_terms[0]) ? $career_terms[0]->name.'</a>' : '' ); ?></small>
                    <small class="sjs_list_job_area">&nbsp;>>&nbsp;<?php echo '<a href="'.get_term_link($area_terms[0]).'">'.(isset($area_terms[0]) ? $area_terms[0]->name.'</a>' : '' ); ?></small>
                    <p>
                    <?php
                    if ($sjs_sets['use_excerpt'] == 1)
                        echo $p->post_excerpt;
                    else
                        echo wordwrap($p->post_content, $sjs_sets['max_length'], "\n", true);
                    ?>
                    </p>                    
                    <div class="sjs_list_job_divider"></div>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
        return ob_get_clean();
    }
}

/**
 * Get job list scroller
 * @param string $sanitized_title - A unique string with a-z 0-9 and -_ ONLY
 * @param array $args - Arguments for WP query post
 * @return string - The scroller output
 * You can determin if it was called by the widget with $args['is_widget']
 */
function sjs_get_scroller($sanitized_title, $args)
{
    $sjs_sets = get_option("stdev_job_seeker_blog_settings");
    
    $query = sjs_query_jobs($args);
    ob_start();
    if ($query->have_posts())
    {
        //echo '<pre>'; var_dump($query->posts); echo '</pre>';
        ?>
        <ul class="<?php echo $sanitized_title; ?>_ul" id="sjs_scroller">
            <?php
            foreach ($query->posts as $p)
            {
                $area_terms = wp_get_post_terms($p->ID, SJS_TAX_AREA_NAME);
                //$career_terms = wp_get_post_terms($p->ID, SJS_TAX_CAREER_NAME);
                ?>
                <li class="sjs_scroll_li">
                    <span class="sjs_scroll_job_title"><a href="<?php echo get_post_permalink($p->ID); ?>"><?php echo $p->post_title; ?></a></span>
                    <br />
                    <small class="sjs_scroll_job_area"><?php echo (isset($area_terms[0]) ? $area_terms[0]->name : '' ); ?></small>
                    <p>
                    <?php
                    if ($sjs_sets['use_excerpt'] == 1)
                        echo $p->post_excerpt;
                    else
                        echo wordwrap($p->post_content, $sjs_sets['scroller_max_length'], "\n", true);
                    ?>
                    </p>
                    <div class="sjs_scroll_job_divider"></div>
                </li>
                <?php
            }
            ?>
        </ul>
        <script type="text/javascript">//<![CDATA[
        jQuery(document).ready(function()
        { 
            var animate_time = <?php echo $args['animate_time']; ?>;
            var stop_time = animate_time + <?php echo $args['stop_time']; ?>;

            var current_job_index = 0;
            var job_heights = new Array();
            var jobs = 0;
            var total_height = 0;
            jQuery('ul[class="<?php echo $sanitized_title; ?>_ul"] li').each(function(index)
            {
                job_heights[index] = jQuery(this).outerHeight(true);
                jobs++;
                total_height += job_heights[index];
                //console.log(index + "=" + job_heights[index])
            });
            
            //console.log("Total: " + total_height + " inner height: " + jQuery('ul[class="<?php echo $sanitized_title; ?>_ul"]').innerHeight());
            
            function sjs_step_list()
            {
                //jQuery(".sjs_jobs_scroller").append(jQuery("li.sjs_job:first"));
                jQuery(".<?php echo $sanitized_title; ?>_ul").append(jQuery('ul[class="<?php echo $sanitized_title; ?>_ul"] li:first'));
                jQuery(".<?php echo $sanitized_title; ?>_ul").scrollTop(0);
                current_job_index++;
                if (current_job_index >= jobs)
                    current_job_index = 0;
            }

            if (total_height > parseInt(jQuery('ul[class="<?php echo $sanitized_title; ?>_ul"]').innerHeight()))
            {
                var scr_timer = setInterval(function()
                {
                    jQuery(".<?php echo $sanitized_title; ?>_ul").animate({ scrollTop: job_heights[current_job_index] }, animate_time, 'swing', sjs_step_list);
                }, stop_time);
            }
             
             
             
        });
        //]]></script>
        <?php
        return ob_get_clean();
    }    
}

?>