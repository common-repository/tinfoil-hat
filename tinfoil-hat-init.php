<?php
/*

Copyright 2007 Travis Snoozy (ai2097@users.sourceforge.net)
Released under the terms of the GNU GPL v2

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

class TinfoilHatInit {

    function initialize_options() {
        $options = get_option('tinfoil_hat');
        $defaults = TinfoilHatInit::get_default_options();
        if(empty($options)) {
            $desc = __("Configures how Tinfoil Hat alters WordPress features");
            add_option('tinfoil_hat', $defaults, $desc);
        }
        else {
            foreach($defaults as $key => $value) {
                if(empty($options["$key"]))
                    { $options["$key"] = $value; }
            }
            update_option('tinfoil_hat', $options);
        }
    }

    function get_default_options() {
        $retval = array();
        $retval['wp_update_disable'] = false;
        $retval['plugin_update_disable'] = false;
        $retval['spoof_blog_url_enable'] = true;
        $retval['spoofed_blog_url'] = "http://remstate.com/projects/tinfoil-hat/";
        $retval['send_php_with_update_disable'] = false;
        $retval['send_locale_with_update_disable'] = false;
        $retval['send_charset_with_update_disable'] = false;
        $retval['admin_news_disable'] = true;
        return $retval;
    }
}

?>
