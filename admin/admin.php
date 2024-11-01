<?php
/**** Admin meny ****/
add_action('admin_menu', 'stdev_job_seeker_plugin_menu');
function stdev_job_seeker_plugin_menu()
{
    global $g_sjs_capability, $g_sjs_plug_url;
//    add_submenu_page('edit.php?post_type='.SJS_POST_NAME, 'Stormtribe Custom Posts', __('Information', 'stdev-job-seeker'), 'publish_posts', 'stdev_job_seeker_menu_info', 'stdev_job_seeker_admin_menu_info');
//    add_submenu_page('edit.php?post_type='.SJS_POST_NAME, 'Stormtribe Custom Posts', __('Settings', 'stdev-job-seeker'), 'manage_options', 'stdev_job_seeker_menu_settings', 'stdev_job_seeker_admin_menu_settings');
    add_menu_page('Stormtribe Job Seeker', __('Stormtribe Job Seeker','stdev-job-seeker'), $g_sjs_capability, 'stdev_job_seeker_menu_main', 'stdev_job_seeker_admin_menu_info', $g_sjs_plug_url.'assets/admin_menu_icon.png');
    $mypage = add_submenu_page('stdev_job_seeker_menu_main', 'Stormtribe Job Seeker', __('Information', 'stdev-job-seeker'), $g_sjs_capability, 'stdev_job_seeker_menu_main', 'stdev_job_seeker_admin_menu_info');
//        add_action("admin_print_scripts-$mypage", 'stdev_job_seeker_admin_head');
    $mypage = add_submenu_page('stdev_job_seeker_menu_main', 'Stormtribe Job Seeker', __('Settings', 'stdev-job-seeker'), 'manage_options', 'stdev_job_seeker_menu_settings', 'stdev_job_seeker_admin_menu_settings');
//        add_action("admin_print_scripts-$mypage", 'stdev_job_seeker_admin_head');
    

    
}
/*
function stdev_job_seeker_admin_head()
{
//    global $g_sjs_plug_url;
//    wp_deregister_script('chosen');
//    wp_enqueue_script('chosen', $g_sjs_plug_url.'assets/chosen/chosen.jquery.min.js', array('jquery'), '1.1.0', false);
//    wp_enqueue_style('chosen', $g_sjs_plug_url.'assets/chosen/chosen.min.css',false,'1.1.0','all');
}
*/

function stdev_job_seeker_admin_menu_info()
{
    global $g_sjs_plug_dir, $g_sjs_plug_url, $g_sjs_capability;    
    if (!current_user_can($g_sjs_capability)) { wp_die( __('You do not have sufficient permissions to access this page.') ); }
    ?>
    <div id="sjsa_logo"><a href="http://www.stormtribemarket.com" target="_blank"><img src="<?php echo $g_sjs_plug_url; ?>assets/stma_logo.png" alt="stma_logo" /></a></div>
    
    <?php
    global $g_sjs_version, $g_sjs_upload_dir;
    $errors = '';
    $sjs_sets = get_site_option("stdev_job_seeker_settings");
    if ($g_sjs_version !== $sjs_sets['version'])
        $errors .= '<div class="error"><p>'.sprintf(__('Please reactivate this plugin. Version on these files is v%s. Version installed v%s.', 'stdev-job-seeker'), $g_sjs_version, $sjs_sets['version']).'</p></div>';
    if (version_compare('3.5', get_bloginfo('version')) > 0)
        $errors .=  '<div class="error"><p>'.sprintf(__('Please update WordPress to use this plugin correct. Version installed v%s.', 'stdev-job-seeker'), get_bloginfo('version')).'</p></div>';
    if (version_compare('5.2.2', PHP_VERSION) > 0)
        $errors .=  '<div class="error"><p>'.sprintf(__('Please update PHP to use this plugin correct. Version installed v%s.', 'stdev-job-seeker'), PHP_VERSION).'</p></div>';
    if (file_exists($g_sjs_upload_dir.'my_job_seeker.php') == false)
        $errors .=  '<div class="error"><p>'.sprintf(__('Your configuration file does not exists. Copy (%s) to (%s)', 'stdev-job-seeker'),$g_sjs_plug_dir.'my_job_seeker.php', $g_sjs_upload_dir.'my_job_seeker.php').'</p></div>';
    ?>
    <div class="sjsa_error_container">
        <?php if (!empty($errors)) { echo $errors; } else { echo '<div class="updated"><p>'.__('No errors found with this plugin&hellip;', 'stdev-job-seeker').'</p></div>'; } ?>
    </div><br />
        
    <h1><?php _e('Thanks for using Stormtribe Job Seeker','stdev-job-seeker'); echo ' v'.$g_sjs_version; ?></h1>
    <br />
    <i><?php _e('Use this product on your own risk','stdev-job-seeker'); ?></i><br />
    <br />
    
    <div id="sjsa_readme">
        <?php
        include 'readme.php';
        ?>
    </div>    
    
    <div class="sjsa_settings_form">
    <table class="sjsa_settings_table">
        <tr valign="top"><td>Server info</td><td><?php echo $_SERVER['SERVER_SOFTWARE'].' PHP/'.phpversion().' @ '.date('Y-m-d H:i:s'); ?><p class="description"></p></td></tr>
    </table>
    </div>
    
    <?php

}



function stdev_job_seeker_admin_menu_settings()
{
    if (!current_user_can('manage_options')) { wp_die( __('You do not have sufficient permissions to access this page.') ); }

    echo C_stdev_admin_Notices::get_messages_div(false);
    
    if (isset($_POST['btn_save']))
        C_stdev_admin_Notices::add_notice(__('Saved&hellip;','stdev-job-seeker'));
    
    global $g_sjs_plug_dir;
    include_once $g_sjs_plug_dir.'includes/stdev-forms.class.php';
    
    ?>
    <form method="post" action="">
    <?php
    global $pagenow;
    if ($pagenow == 'admin.php' && $_GET['page'] == 'stdev_job_seeker_menu_settings')
    {
        if (isset($_GET['tab']))
            $current_tab = $_GET['tab'];
        else
            $current_tab = 'general';

        $tabs = array( 'general'    => __('General', 'stdev-job-seeker'), 
                       'taxonomy'   => __('Taxonomy', 'stdev-job-seeker'),
                       'site_admin' => __('Globals', 'stdev-job-seeker')
                        );
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name )
        {
            if ( $tab == $current_tab )
                echo "<a class='nav-tab nav-tab-active' href='?page=stdev_job_seeker_menu_settings&tab=$tab'>$name</a>";
            else
                echo "<a class='nav-tab' href='?page=stdev_job_seeker_menu_settings&tab=$tab'>$name</a>";
        }
        echo '</h2>';
        
        
        switch ($current_tab)
        {
            case 'general':
                stdev_job_seeker_admin_menu_settings_general();
                break;
            case 'taxonomy':
                stdev_job_seeker_admin_menu_settings_taxonomy();
                break;
            case 'site_admin':
                stdev_job_seeker_admin_menu_settings_site_admin();
                break;
        }
    }

    ?>
    </form>
    
    <script type="text/javascript">//<![CDATA[
    jQuery(document).ready(function()
    { 
        if (jQuery.isFunction(jQuery.fn.chosen))
        {
            jQuery(".chosen-select").chosen({no_results_text: "'.__('No results match','stdev-job-seeker').'"}); 
            jQuery(".chosen-single").chosen({disable_search_threshold: 10});
        }
    });
    //]]></script>
    <?php

    C_stdev_admin_Notices::prepare_messages();
}

function stdev_job_seeker_admin_menu_settings_general()
{
    $sjs_sets = get_option("stdev_job_seeker_blog_settings");
    if (isset($_POST['btn_save']))
    {
        if (isset($_POST['USE_EXCERPT']))
            $sjs_sets['use_excerpt'] = 1;
        else
            $sjs_sets['use_excerpt'] = 0;

        if (isset($_POST['MAX_LENGTH']) && is_numeric($_POST['MAX_LENGTH']))
            $sjs_sets['max_length'] = (int)$_POST['MAX_LENGTH'];
        else
            $sjs_sets['max_length'] = 160;

        if (isset($_POST['USE_BY_AREA']))
            $sjs_sets['use_by_area'] = 1;
        else
            $sjs_sets['use_by_area'] = 0;

        if (isset($_POST['USE_BY_CAREER']))
            $sjs_sets['use_by_career'] = 1;
        else
            $sjs_sets['use_by_career'] = 0;
        
        if (isset($_POST['MAX_JOBS']) && is_numeric($_POST['MAX_JOBS']))
            $sjs_sets['max_jobs'] = (int)$_POST['MAX_JOBS'];
        else
            $sjs_sets['max_jobs'] = -1;
        
        if (isset($_POST['SCROLLER_MAX_LENGTH']) && is_numeric($_POST['SCROLLER_MAX_LENGTH']))
            $sjs_sets['scroller_max_length'] = (int)$_POST['SCROLLER_MAX_LENGTH'];
        else
            $sjs_sets['scroller_max_length'] = 90;
        
        if (isset($_POST['ANIMATE_TIME']) && is_numeric($_POST['ANIMATE_TIME']))
            $sjs_sets['scroller_animate_time'] = (int)$_POST['ANIMATE_TIME'];
        else
            $sjs_sets['scroller_animate_time'] = 2500;

        if (isset($_POST['STOP_TIME']) && is_numeric($_POST['STOP_TIME']))
            $sjs_sets['scroller_stop_time'] = (int)$_POST['STOP_TIME'];
        else
            $sjs_sets['scroller_stop_time'] = 3000;
        
        if (isset($_POST['SCROLLER_CSS_HEIGHT']) && is_numeric($_POST['SCROLLER_CSS_HEIGHT']))
            $sjs_sets['scroller_css_height'] = (int)$_POST['SCROLLER_CSS_HEIGHT'];
        else
            $sjs_sets['scroller_css_height'] = -1;        
        
        update_option('stdev_job_seeker_blog_settings', $sjs_sets);
    }
    
    $Cforms = new C_stdev_forms();
    $elements = array(
        "general_title" => array(
            'type' => 'title',
            'title' => __('General', 'stdev-job-seeker'),
            'h_size' => 3
            ),
        "USE_EXCERPT" => array(
            'type' => 'checkbox',
            'title' => __('Use excerpt', 'stdev-job-seeker'),
            'label' => __('Use excerpt text instead of full text', 'stdev-job-seeker'),
            'desc' => '',
            'value' => $sjs_sets['use_excerpt'],
            ),
        "MAX_LENGTH" => array(
            'type' => 'text',
            'title' => __('Max length', 'stdev-job-seeker'),
            'desc' => __('Max length of text when using full text', 'stdev-job-seeker'),
            'value' => $sjs_sets['max_length'],
            'default' => 160,
            'meta' => 'maxlength="8"'
            ),
        "USE_BY_AREA" => array(
            'type' => 'checkbox',
            'title' => __('By area', 'stdev-job-seeker'),
            'label' => __('Display jobs in current area based on "page/post name" compared to area slug name', 'stdev-job-seeker'),
            'desc' => '',
            'value' => $sjs_sets['use_by_area'],
            ),
        "USE_BY_CAREER" => array(
            'type' => 'checkbox',
            'title' => __('By career', 'stdev-job-seeker'),
            'label' => __('Display jobs in current career based on "page/post name" compared to career slug name', 'stdev-job-seeker'),
            'desc' => '',
            'value' => $sjs_sets['use_by_career'],
            ),
        "scroller_title" => array(
            'type' => 'title',
            'title' => __('Scroller', 'stdev-job-seeker'),
            'h_size' => 3
            ),       
        "MAX_JOBS" => array(
            'type' => 'text',
            'title' => __('Jobs', 'stdev-job-seeker'),
            'desc' => __('Default number of jobs to display (-1 = unlimited)', 'stdev-job-seeker'),
            'value' => $sjs_sets['max_jobs'],
            'default' => -1,
            'meta' => 'maxlength="8"'
            ),
        "SCROLLER_MAX_LENGTH" => array(
            'type' => 'text',
            'title' => __('Max length', 'stdev-job-seeker'),
            'desc' => __('Max length of text when using full text', 'stdev-job-seeker'),
            'value' => $sjs_sets['scroller_max_length'],
            'default' => 90,
            'meta' => 'maxlength="8"'
            ),
        "ANIMATE_TIME" => array(
            'type' => 'text',
            'title' => __('Animation time', 'stdev-job-seeker'),
            'desc' => __('Default animation time, in milliseconds (1000ms = 1 second)', 'stdev-job-seeker'),
            'value' => $sjs_sets['scroller_animate_time'],
            'default' => 2500,
            'meta' => 'maxlength="8"'
            ),
        "STOP_TIME" => array(
            'type' => 'text',
            'title' => __('Stop time', 'stdev-job-seeker'),
            'desc' => __('Default stop time between animations, in milliseconds', 'stdev-job-seeker'),
            'value' => $sjs_sets['scroller_stop_time'],
            'default' => 3000,
            'meta' => 'maxlength="8"'
            ),
        "SCROLLER_CSS_HEIGHT" => array(
            'type' => 'text',
            'title' => __('Height', 'stdev-job-seeker'),
            'desc' => __('CSS height of the list, in pixels', 'stdev-job-seeker'),
            'value' => $sjs_sets['scroller_css_height'],
            'default' => 150,
            'meta' => 'maxlength="8"'
            ),

        
        
        "btn_save" => array(
            'type' => 'submit',
            'title' => '',
            'desc' => '',
            'value' => __('Save','stdev-job-seeker'),
            'meta' => 'class="button-primary"'
            )        
    );
    ?>
    
    <div class="sjsa_settings_form">
        <table>
            <?php echo $Cforms->get_generated_form($elements); ?>
        </table>
    </div>
    <?php
}

function stdev_job_seeker_admin_menu_settings_site_admin()
{
    if (!current_user_can('activate_plugins')) { wp_die( __('You do not have sufficient permissions to access this page.')); }
    
    $sjs_sets = get_site_option( "stdev_job_seeker_settings");
    if (isset($_POST['btn_save']))
    {
        if(isset($_POST['CAPABILITY']))
            $sjs_sets['capability'] = $_POST['CAPABILITY'];
        
        update_site_option("stdev_job_seeker_settings", $sjs_sets);
        /*
        if(isset($_POST['USE_CRON']))
            update_option('stdev_job_seeker_use_cron', "1");
        else
            update_option('stdev_job_seeker_use_cron', "0"); */
    }
    else if (isset($_POST['btn_flush_rewrite']))
    {
        flush_rewrite_rules(true);
        C_stdev_admin_Notices::add_notice(__('Updated rewrite rules&hellip;', 'stdev-job-seeker'));
    }
    else if (isset($_POST['btn_flush_rewrite_soft']))
    {
        flush_rewrite_rules(false);
        C_stdev_admin_Notices::add_notice(__('Updated rewrite rules&hellip;', 'stdev-job-seeker'));
    }
    else if (isset($_POST['btn_update_my_php_file']))
    {
        global $g_sjs_upload_dir, $g_sjs_plug_dir;
        if (copy($g_sjs_plug_dir.'/my_job_seeker.php', $g_sjs_upload_dir.'/my_job_seeker.php'))
            C_stdev_admin_Notices::add_notice(__('Copy succeeded&hellip;','stdev-job-seeker'));
        else
            C_stdev_admin_Notices::add_notice(__('Copy failed','stdev-job-seeker'));
        
    }
    
    global $wp_roles;
    $capabilites_list = array();
    foreach ($wp_roles->roles as $role => $more)
    {
        $capabilites_list[$role]['label'] = translate_user_role($more['name']);
        $capabilites_list[$role]['options'] = array();
        //asort($more['capabilities'], SORT_ASC);
        foreach ($more['capabilities'] as $name => $value)
        {
            $capabilites_list[$role]['options'] += array($name => $name);
            
        }
        
    }
    
    $Cforms = new C_stdev_forms('0.4.0');
    $elements = array(
        "other_title" => array(
            'type' => 'title',
            'title' => __('General site settings', 'stdev-job-seeker'),
            'h_size' => 3
            ),
        "CAPABILITY" => array(
            'type' => 'select_optgroup',
            'title' => __('Capability', 'stdev-job-seeker'),
            'desc' => __('Select a required user capability for adding jobs', 'stdev-job-seeker'),
            'options' => $capabilites_list,
            'value' => $sjs_sets['capability'],
            'default' => 'publish_posts',
            'meta' => 'class="chosen-single search" style="width:350px;"'
            ),
        "btn_update_my_php_file" => array(
            'type' => 'submit',
            'title' => __('Restore file','stdev-job-seeker'),
            'desc' => __('Copy original my_job_seeker.php file to upload directory','stdev-job-seeker'),
            'value' => __('Copy','stdev-job-seeker'),
            'meta' => 'class="button-secondary"'
            ),           
        "btn_flush_rewrite" => array(
            'type' => 'submit',
            'title' => __('Flush rewrite','stdev-job-seeker'),
            'desc' => __('Perform WordPress flush rewrite rules (hard)','stdev-job-seeker').' <a href="//codex.wordpress.org/Function_Reference/flush_rewrite_rules" target="_BLANK">'.__('Read more','stdev-job-seeker').'</a>',
            'value' => __('Flush now','stdev-job-seeker'),
            'meta' => 'class="button-secondary"'
            ),
        "btn_flush_rewrite_soft" => array(
            'type' => 'submit',
            'title' => __('Flush rewrite','stdev-job-seeker'),
            'desc' => __('Perform WordPress flush rewrite rules (soft)','stdev-job-seeker'),
            'value' => __('Flush now','stdev-job-seeker'),
            'meta' => 'class="button-secondary"'
            ),        
                
        "btn_save" => array(
            'type' => 'submit',
            'title' => __('Save','stdev-job-seeker'),
            'desc' => '',
            'value' => __('Save','stdev-job-seeker'),
            'meta' => 'class="button-primary"'
            )        
    );
    

    
    ?>

    <div class="sjsa_settings_form">
        <table>
            <?php echo $Cforms->get_generated_form($elements); ?>
        </table>
    </div>
    <?php
}

function stdev_job_seeker_admin_menu_settings_taxonomy()
{
    echo sprintf(__('All none empty taxonomies associated with %s', 'stdev-job-seeker'), SJS_TAX_AREA_NAME).':<br />';
    
    $terms = get_terms(SJS_TAX_AREA_NAME, array('hide_empty=0'));
    if ( !empty( $terms ) && !is_wp_error( $terms ) )
    {
        $count = count($terms);
        $i=0;
        $term_list = '<p class="my_term-archive">';
        foreach ($terms as $term)
        {
            $i++;
            $term_list .= '<a href="' . get_term_link( $term ) . '" target="_BLANK" title="' . sprintf(__('View all jobs filed under %s', 'stdev-job-seeker'), $term->name) . '">' . $term->name . '</a>';
            if ($count != $i)
                $term_list .= ' &middot; ';
            else
                $term_list .= '</p>';

        }
        echo $term_list.'<br />';
    }
    else
        _('No taxonomies found&hellip;', 'stdev-job-seeker');
    
    echo sprintf(__('All none empty taxonomies associated with %s', 'stdev-job-seeker'), SJS_TAX_CAREER_NAME).':<br />';
    
    $terms = get_terms(SJS_TAX_CAREER_NAME, array('hide_empty=0'));
    if ( !empty( $terms ) && !is_wp_error( $terms ) )
    {
        $count = count($terms);
        $i=0;
        $term_list = '<p class="my_term-archive">';
        foreach ($terms as $term)
        {
            $i++;
            $term_list .= '<a href="' . get_term_link( $term ) . '" target="_BLANK" title="' . sprintf(__('View all jobs filed under %s', 'stdev-job-seeker'), $term->name) . '">' . $term->name . '</a>';
            if ($count != $i)
                $term_list .= ' &middot; ';
            else
                $term_list .= '</p>';

        }
        echo $term_list.'<br />';
    }
    else
        _('No taxonomies found&hellip;', 'stdev-job-seeker');
    
}

//Admin notices by Stormtribe Development. v1
if (!class_exists('C_stdev_admin_Notices'))
{
    class C_stdev_admin_Notices
    {
        static $errors;
        static $notices;

        static function add_error($error)
        {
            C_stdev_admin_Notices::$errors[] = addslashes($error); //str_replace("'", "\'", $error_msg);
        }
        static function add_notice($notice)
        {
            C_stdev_admin_Notices::$notices[] = addslashes($notice); //str_replace("'", "\'", $error_msg);
        }
        static function has_errors()
        {
            if (count(C_stdev_admin_Notices::$errors) > 0)
                return true;
            else
                return false;
        }
        static function has_notices()
        {
            if (count(C_stdev_admin_Notices::$notices) > 0)
                return true;
            else
                return false;
        }           
        static function get_errors()
        {
            if (count(C_stdev_admin_Notices::$errors) > 0)
            {
                return C_stdev_admin_Notices::$errors;
            }
            return '';
        }
        static function get_notices()
        {
            if (count(C_stdev_admin_Notices::$notices) > 0)
            {
                return C_stdev_admin_Notices::$notices;
            }
            return '';
        }  
        static function prepare_messages()
        {
            $out = '';
            if (count(C_stdev_admin_Notices::$errors) > 0)
            {
                foreach(C_stdev_admin_Notices::$errors as $e)
                {
                    $out .= '<div class="error stdev_admin_notices_fade_e"><p>'.$e.'</p></div>';
                }
            }
            if (count(C_stdev_admin_Notices::$notices) > 0)
            {
                foreach(C_stdev_admin_Notices::$notices as $n)
                {
                    $out .= '<div class="updated stdev_admin_notices_fade_n"><p>'.$n.'</p></div>';
                }
            }
            
            ob_start();
            ?>
            <script type="text/javascript">//<![CDATA[
            jQuery(document).ready(function()
            { 
                jQuery(".stdev_admin_notices_container").html('<?php echo $out; ?>'); 
                jQuery(".stdev_admin_notices_fade_n").fadeOut(8000);
                //jQuery(".stdev_admin_notices_fade_e").fadeOut(10000);
            });
            //]]></script>
            <?php
            echo ob_get_clean();
        }
        static function get_messages_div($skip_end_div = false)
        {
            if ($skip_end_div)
                return '<div class="stdev_admin_notices_container">';
            else
                return '<div class="stdev_admin_notices_container"></div>';
        }        
        
        
     
    }
}
?>