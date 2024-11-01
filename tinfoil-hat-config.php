<?php
/*

Copyright 2007 Travis Snoozy (ai2097@users.sourceforge.net)
Released under the terms of the GNU GPL v2

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

$options = get_option("tinfoil_hat");
$option_key = "tinfoil-hat_set-options";
$nonce = "";

if(function_exists("wp_create_nonce"))
    { $nonce = wp_create_nonce($option_key); }

if($_POST['tinfoil_hat'] &&
   $_POST['tinfoil_hat']['submit']) {
    check_admin_referer($option_key);
    foreach($options as $key => $value) {
        $options["$key"] = stripslashes($_POST['tinfoil_hat']["$key"]);
    }
    update_option("tinfoil_hat", $options);
}

if(function_exists("attribute_escape")) {
    $ref = attribute_escape($_SERVER['REQUEST_URI']);
    if(wp_get_original_referer()) {
        $original_ref = attribute_escape(stripslashes(wp_get_original_referer()));
    }
}

$default_wp_update_disable = TinfoilHatInternal::make_checked($options['wp_update_disable']);
$default_plugin_update_disable = TinfoilHatInternal::make_checked($options['plugin_update_disable']);
$default_spoof_blog_url_enable = TinfoilHatInternal::make_checked($options['spoof_blog_url_enable']);
$default_send_php_with_update_disable = TinfoilHatInternal::make_checked($options['send_php_with_update_disable']);
$default_send_locale_with_update_disable = TinfoilHatInternal::make_checked($options['send_locale_with_update_disable']);
$default_send_charset_with_update_disable = TinfoilHatInternal::make_checked($options['send_charset_with_update_disable']);
$default_spoofed_blog_url = htmlspecialchars($options['spoofed_blog_url']);
$default_admin_news_disable = TinfoilHatInternal::make_checked($options['admin_news_disable']);
$disable_wp_update_label = __("Disable built-in checks for WordPress updates: ", "tinfoil_hat");
$disable_plugin_update_label = __("Disable built-in checks for plugin updates: ", "tinfoil_hat");
$enable_blog_url_spoofing_label = __("Enable URL spoofing for built-in update checks: ", "tinfoil_hat");
$spoofed_blog_url_label = __("Spoof this URL during the built-in update checks: ", "tinfoil_hat");
$send_php_with_update_label = __("Disable sending PHP version with updates: ", "tinfoil_hat");
$send_locale_with_update_label = __("Disable sending PHP locale with updates: ", "tinfoil_hat");
$send_charset_with_update_label = __("Disable sending blog charset with updates: ", "tinfoil_hat");
$disable_admin_news_label = __("Disable admin news fetching: ", "tinfoil_hat");
$tinfoil_hat_submit_button = __("Update Options &raquo;", "tinfoil_hat");
$tinfoil_hat_options_header = __("Tinfoil Hat Configuration");
$update_options_title = __("WordPress Update Options", "tinfoil_hat");
$admin_news_title = __("Wordpress Dashboard News", "tinfoil_hat");
$update_options_instructions = __("
<p>
  This section controls whether WordPress is allowed to check for updates, and
  what information is sent when update checks are made. As of WordPress 2.3, two
  types of update checks are made &mdash; checks for new versions of WordPress,
  and checks for new versions of plugins. This plugin allows you to disable and
  control both types of checks, but if WordPress was installed in a location
  where it could make outgoing connections, your information has likely already
  been sent at least once already.
</p>
<p>
  Note that almost all information is sent by default with this plugin. PHP
  versions are sent in HTTP headers by default in many configurations;
  <em>if</em> you are with a site that is explicitly set up to not transmit PHP
  versions, <em>then</em> it makes sense not to send it. Transmitting PHP locale
  allows WordPress update checks to return links that point to localized
  releases; if you don't want localized links, or don't want to leak your
  locale, <em>then</em> it makes sense to not send it. Transmitting your blog's
  character set allows WordPress to return links with properly-encoded
  characters; you probably always want to send this.
</p>
<p>
  Spoofing your blog URL is recommended if you run an internal, access-protected
  or otherwise non-public blog. The Internet-addressable IP of your site will
  still be transmitted to the update service (naturally). Spoofing with the
  default URL will generally indicate that your are running this plugin.
  Spoofing with a different, constant, unique (versus other service users) URL
  will still identify update requests that originate from this specific
  WordPress installation. Spoofing with valid URLs will be at least detectable
  in most cases, since the DNS address in the URL can have a forward-lookup
  performed on it, and the IP address compared to the IP adress where the update
  request came from.
</p>
<p>
  Disabling update checks is not recommended, unless you <em>really</em> stay on
  top of WordPress and/or plugin security issues.
</p>
", "tinfoil_hat");
$admin_news_instructions = __("
<p>
  When you log in to the dashboard and allow Javascript to run, various RSS
  feeds are retrieved to add current information to some areas. If you don't
  ever read the news, and/or you simply don't want to ping the world every
  single time you log in to your installation of WordPRess, you can disable this
  behavior.
</p>
", "tinfoil_hat");

$wordpress_update_config = "
    <fieldset class='options'>
      <legend>{$update_options_title}</legend>
      {$update_options_instructions}
      <p><label>{$disable_wp_update_label}<input type='checkbox' name='tinfoil_hat[wp_update_disable]' {$default_wp_update_disable} /></label></p>
      <p><label>{$disable_plugin_update_label}<input type='checkbox' name='tinfoil_hat[plugin_update_disable]' {$default_plugin_update_disable} /></label></p>
      <p><label>{$send_php_with_update_label}<input type='checkbox' name='tinfoil_hat[send_php_with_update_disable]' {$default_send_php_with_update_disable} /></label></p>
      <p><label>{$send_locale_with_update_label}<input type='checkbox' name='tinfoil_hat[send_locale_with_update_disable]' {$default_send_locale_with_update_disable} /></label></p>
      <p><label>{$send_charset_with_update_label}<input type='checkbox' name='tinfoil_hat[send_charset_with_update_disable]' {$default_send_charset_with_update_disable} /></label></p>
      <p><label>{$enable_blog_url_spoofing_label}<input type='checkbox' name='tinfoil_hat[spoof_blog_url_enable]' {$default_spoof_blog_url_enable} /></label></p>
      <p><label>{$spoofed_blog_url_label}<input type='text' name='tinfoil_hat[spoofed_blog_url]' value='{$default_spoofed_blog_url}' /></label></p>
    </fieldset>
";

// Don't have an update config section if this version of WordPress doesn't have
// the update plumbing.
if(!function_exists('wp_version_check'))
    { $wordpress_update_config = ""; }

$output = "
<div class='wrap'>
  <h2>{$tinfoil_hat_options_header}</h2>
  <form method='post' action=''>
    <input type='hidden' name='_wpnonce' value='{$nonce}' />
    <input type='hidden' name='_wp_http_referer' value='{$ref}' />
    <input type='hidden' name='_wp_http_orifinal_referer' value='{$original_ref}' />
    <p class='submit'>
      <input type='submit' name='tinfoil_hat[submit]' value='{$tinfoil_hat_submit_button}' />
    </p>
{$wordpress_update_config}
    <fieldset class='options'>
      <legend>{$admin_news_title}</legend>
      {$admin_news_instructions}
      <p><label>{$disable_admin_news_label}<input type='checkbox' name='tinfoil_hat[admin_news_disable]' {$default_admin_news_disable} /></label></p>
    </fieldset>
    <p class='submit'>
      <input type='submit' name='tinfoil_hat[submit]' value='{$tinfoil_hat_submit_button}' />
    </p>
  </form>
</div>
";

echo $output;

?>
