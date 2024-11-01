<?php
// This base of this code is taken from WordPress 2.3
// It is used under the GNU GPLv2 license
//
// The source in this file is MODIFIED from the original version.

	global $wp_version;

	if ( !function_exists('fsockopen') )
		return false;

	$plugins = get_plugins();
	$active  = get_option( 'active_plugins' );
	$current = get_option( 'update_plugins' );

	$new_option = '';
	$new_option->last_checked = time();


    //TFH>>>
    $tinfoil_hat = get_option('tinfoil_hat');
    if($tinfoil_hat['plugin_update_disable'])
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

    $blog_charset = "; " . get_option('blog_charset');
    if($tinfoil_hat['send_charset_with_update_disable'])
        { $blog_charset = ""; }
    ///<<<TFH


	$plugin_changed = false;
	foreach ( $plugins as $file => $p ) {
		$new_option->checked[ $file ] = $p['Version'];

		if ( !isset( $current->checked[ $file ] ) ) {
			$plugin_changed = true;
			continue;
		}

		if ( $current->checked[ $file ] != $p['Version'] )
			$plugin_changed = true;
	}

	if (
		isset( $current->last_checked ) &&
		43200 > ( time() - $current->last_checked ) &&
		!$plugin_changed
	)
		return false;

	$to_send->plugins = $plugins;
	$to_send->active = $active;
	$send = serialize( $to_send );

	$request = 'plugins=' . urlencode( $send );
	$http_request  = "POST /plugins/update-check/1.0/ HTTP/1.0\r\n";
	$http_request .= "Host: api.wordpress.org\r\n";


    //TFH>>>
	$http_request .= "Content-Type: application/x-www-form-urlencoded" . $blog_charset . "\r\n";
    //<<<TFH


	$http_request .= "Content-Length: " . strlen($request) . "\r\n";


    //TFH>>>
	$http_request .= 'User-Agent: WordPress/' . $wp_version . $blog_url . "\r\n";
    //<<<TFH


    //TFH: everything from here on is kosher
	$http_request .= "\r\n";
	$http_request .= $request;

	$response = '';
	if( false != ( $fs = @fsockopen( 'api.wordpress.org', 80, $errno, $errstr, 3) ) && is_resource($fs) ) {
		fwrite($fs, $http_request);

		while ( !feof($fs) )
			$response .= fgets($fs, 1160); // One TCP-IP packet
		fclose($fs);
		$response = explode("\r\n\r\n", $response, 2);
	}

	$response = unserialize( $response[1] );

	if ( $response )
		$new_option->response = $response;

	update_option( 'update_plugins', $new_option );

?>
