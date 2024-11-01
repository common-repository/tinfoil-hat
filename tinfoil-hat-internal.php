<?php
/*

Copyright 2007 Travis Snoozy (ai2097@users.sourceforge.net)
Released under the terms of the GNU GPL v2

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

load_plugin_textdomain('tinfoil_hat', 'wp-content/plugins/tinfoil-hat');

class TinfoilHatInternal {

    function make_checked($var) {
        return empty($var) ? "" : "checked='checked'";
    }

    function activate() {
        require_once("tinfoil-hat-init.php");
        TinfoilHatInit::initialize_options();
    }

    function compat_hooks() {
        global $wp_version;

        if(!empty($wp_version)) {
            // Before 1.5
            if(version_compare("1.5", $wp_version) > 0)
                { exit(__("Tinfoil Hat requires at least WordPress 1.5 to operate.", "tinfoil_hat")); }
            // 1.5 to 2.0
            else if(version_compare("2.0", $wp_version) > 0) {

                // 1.5.x does not have a plugin activation action. Emulate it.
                $active_plugins = get_option("active_plugins");
                if($_GET['activate'] == "true" &&
                   in_array("tinfoil-hat/tinfoil-hat.php", $active_plugins)) {
                    // Not perfect; runs whenever any plugin is activated, and
                    // In Series is active. Still fairly infrequent, though. :)
                    TinfoilHatInternal::activate();
                }
            }
            // 2.0 or later
            else {
                add_action('activate_tinfoil-hat/tinfoil-hat.php', array('TinfoilHatInternal','activate'));
            }
        }
        else
            { exit(__("Could not get WordPress version.", "tinfoil_hat")); }
    }

    function add_admin_panels() {
        global $wp_version;

        $level = "manage_options";
        // Use a level number if running in pre-2.0
        if(version_compare("2.0",$wp_version) > 0)
            { $level = 8; }
        add_options_page(__('Tinfoil Hat Configuration', 'tinfoil_hat'), __('TH Privacy', 'tinfoil_hat'), $level, 'tinfoil-hat-config', array('TinfoilHatInternal', 'display_admin_panels'));
    }

    function display_admin_panels() {
        require_once('tinfoil-hat-config.php');
    }

    function wp_version_check() {
        if(function_exists('wp_version_check')) {
            require_once('tinfoil-hat-wp-version-check.php');
        }
    }

    function wp_update_plugins() {
        // if wp_version_check is there, this is present. This may not show up
        // in our scope.
        if(function_exists('wp_version_check')) {
            require_once('tinfoil-hat-wp-update-plugins.php');
        }
    }

    function unhook_index_js() {
        remove_action('admin_head', 'index_js');
    }

    function index_js() {
        if(!function_exists('index_js'))
            { return; }
        $options = get_option('tinfoil_hat');
        if(!$options['admin_news_disable'])
            { index_js(); }
    }
}

add_action('admin_menu', array('TinfoilHatInternal', 'add_admin_panels'));
if(function_exists('wp_version_check')) {
  remove_action('init', 'wp_version_check');
  add_action('init', array('TinfoilHatInternal', 'wp_version_check'));
  remove_action( 'load-plugins.php', 'wp_update_plugins' );
  add_action('load-plugins.php', array('TinfoilHatInternal', 'wp_update_plugins'));
}

add_action('admin_print_scripts', array('TinfoilHatInternal', 'unhook_index_js'));
add_action('admin_head', array('TinfoilHatInternal', 'index_js'));
TinfoilHatInternal::compat_hooks();

?>
