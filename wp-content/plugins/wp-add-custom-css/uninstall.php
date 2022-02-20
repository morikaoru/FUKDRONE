<?php
// exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$option_name = 'wpacc_settings';
delete_option($option_name);
delete_site_option($option_name);
delete_post_meta_by_key('_single_add_custom_css');
?>
