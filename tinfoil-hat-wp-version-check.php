<?php
// This base of this code is taken from WordPress 2.3
// It is used under the GNU GPLv2 license
//
// The source in this file is MODIFIED from the original version.

    if ( !function_exists('fsockopen') || strpos($_SERVER['PHP_SELF'], 'install.php') !== false || defined('WP_INSTALLING') )
        return;

    global $wp_version;

    $current = get_option( 'update_core' );


    //TFH>>>
    $tinfoil_hat = get_option('tinfoil_hat');
    if($tinfoil_hat['wp_update_disable'])
        { return; }
    $blog_url = "; ";
    if($tinfoil_hat['spoof_blog_url_enable']) {
        if(empty($tinfoil_hat['spoofed_blog_url']))
            { $blog_url = ""; }
        else
            { $blog_url .= $tinfoil_hat['spoofed_blog_url']; }
    }
    else 
        { $blog_url .= get_bloginfo('url'); }

    $php_version = "&php=" . phpversion();
    if($tinfoil_hat['send_php_with_update_disable'])
        { $php_version = ""; }

    $locale = "&locale=" . get_locale();
    if($tinfoil_hat['send_locale_with_update_disable'])
        { $locale = ""; }

    $blog_charset = "; " . get_option('blog_charset');
    if($tinfoil_hat['send_charset_with_update_disable'])
        { $blog_charset = ""; }
    ///<<<TFH

    if (
        isset( $current->last_checked ) &&
        43200 > ( time() - $current->last_checked ) &&
        $current->version_checked == $wp_version
    )
        return false;

    $new_option = '';
    $new_option->last_checked = time(); // this gets set whether we get a response or not, so if something is down or misconfigured it won't delay the page load for more than 3 seconds, twice a day
    $new_option->version_checked = $wp_version;

    //TFH>>>
    $http_request  = "GET /core/version-check/1.0/?version=$wp_version" . $php_version . $locale . " HTTP/1.0\r\n";
    //<<<TFH


    $http_request .= "Host: api.wordpress.org\r\n";


    //TFH>>>
    $http_request .= 'Content-Type: application/x-www-form-urlencoded' . $blog_charset . "\r\n";
    $http_request .= 'User-Agent: WordPress/' . $wp_version . $blog_url . "\r\n";
    //<<<TFH


    $http_request .= "\r\n";

    //TFH: everything past here is kosher.
    $response = '';
    if ( false !== ( $fs = @fsockopen( 'api.wordpress.org', 80, $errno, $errstr, 3 ) ) && is_resource($fs) ) {
        fwrite( $fs, $http_request );
        while ( !feof( $fs ) )
            $response .= fgets( $fs, 1160 ); // One TCP-IP packet
        fclose( $fs );

        $response = explode("\r\n\r\n", $response, 2);
        $body = trim( $response[1] );
        $body = str_replace(array("\r\n", "\r"), "\n", $body);

        $returns = explode("\n", $body);

        $new_option->response = $returns[0];
        if ( isset( $returns[1] ) )
            $new_option->url = $returns[1];
    }
    update_option( 'update_core', $new_option );
?>
