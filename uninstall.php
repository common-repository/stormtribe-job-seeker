<?php
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

delete_site_option("stdev_job_seeker_settings");
delete_option("stdev_job_seeker_blog_settings");
?>