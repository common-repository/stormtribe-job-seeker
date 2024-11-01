<?php
function stdev_job_seeker_install()
{
//    ob_start();
    global $g_sjs_version;
    
    if (!get_site_option( "stdev_job_seeker_settings"))
    {
        $sjs_sets = array(  'version'       => $g_sjs_version,
                            'capability'    => 'publish_posts'
                            );
        add_site_option("stdev_job_seeker_settings", $sjs_sets);
    }
    else
    {
        $sjs_sets = get_site_option("stdev_job_seeker_settings");
        $sjs_sets['version'] = $g_sjs_version;
        update_site_option("stdev_job_seeker_settings", $sjs_sets);
    }

    if (!get_option("stdev_job_seeker_blog_settings"))
    {
        $sjs_blog_sets = array( 'scroller_animate_time'     => 2500,
                                'scroller_stop_time'        => 3000,
                                'scroller_max_length'       => 90,
                                'scroller_css_height'       => 150,
                                'max_jobs'                  => -1,
                                'use_excerpt'               => 1,
                                'max_length'                => 160,
                                'use_by_area'               => 0,
                                'use_by_career'             => 0
                                );
        add_option("stdev_job_seeker_blog_settings", $sjs_blog_sets);
    }
    else
    {
        //$sjs_blog_sets = get_option("stdev_job_seeker_blog_settings");
        //update_option("stdev_job_seeker_blog_settings", $sjs_blog_sets);
    }

    $upload_dir = wp_upload_dir();
    $settings_file = $upload_dir['basedir'] . '/my_job_seeker.php';
    if (file_exists($settings_file) == false)
    {
        copy(dirname(__FILE__).'/my_job_seeker.php', $settings_file);
    }
    
    include $settings_file;
    flush_rewrite_rules();
    
//    exit(ob_get_contents());
}
?>