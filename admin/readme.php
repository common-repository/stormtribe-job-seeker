<h2>Installation</h2>
<b>1.</b> Go through the plugin settings to get it work as you want, here in the administration area.<br />
<br />
<b>2.</b> Do a flush rewrite rules on the setting page if you have changed the custom post type or taxonomies. Or if you have problem with permalinks.<br />
<br />
<b>You can customize many things by editing </b><i><?php global $g_sjs_upload_dir; echo $g_sjs_upload_dir.'my_job_seeker.php'; ?></i><br />
<b>That file will never be overwritten on updates by this plugin.</b>
<br />
<h2>Usage</h2>
In <a href="<?php echo get_admin_url(null, 'widgets.php'); ?>"><b>Appearance->Widgets</b></a> you can add Job Seeker widget to your sidebar.<br />
<br />
With <a href="//codex.wordpress.org/Shortcode" target="_BLANK">shortcodes</a>:<br />
<b>[sjs_job_scroller]</b> - Show a rolling list of jobs. Supports common args, for example: <b>[sjs_job_scroller posts="5" animate_time="1000" stop_time="4000"]</b><br />
<b>[sjs_list_jobs]</b> - Show a list of jobs. Supports common args, for example: <b>[sjs_list_jobs posts="5" orderby="title" order="asc"]</b><br />
<br />
With <a href="//codex.wordpress.org/Pages#Page_Templates" target="_BLANK">templates</a>:<br />
<pre>
<&#63;php 
if (function_exists('sjs_get_scroller'))
{
    $args = array('posts' => 5, 'orderby' => 'title', 'order' => 'asc', 'stop_time' => 5000);
    echo sjs_get_scroller('my-list-1', $args);
}

if (function_exists('sjs_get_jobs'))
{
    $args = array('posts' => 5, 'orderby' => 'date', 'order' => 'desc');
    echo sjs_get_jobs($args);
}
&#63;>
</pre>
<h2>Support</h2>
<a href="http://stormtribemarket.com/forum/" target="_blank"><b>Visit our Support Forum</b></a> - We provide support through our forum at Stormtribe Market. <br />
Please check there for answers or put your questions there.
<h2>Help & Customization</h2>
We provide professional help with customization of the functionality and the theme after your wishes.
<br />Please mail <a href="mailto:info@stormtribemarket.com">info@stormtribemarket.com</a> for further discussions.
<br />
<h2>Upgrade:</h2>
1. Deactivate the plugin.<br />
2. Unzip the new plugin to your wp-content/plugins/ directory. And replace every file.<br />
3. Activate the plugin in wp-admin.<br />
4. Check information page for any warnings.<br />
<br />