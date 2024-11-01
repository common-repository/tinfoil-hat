=== Plugin Name ===
Contributors: quandary
Donate link: http://remstate.com/donate/
Tags: admin, manage, security, privacy
Requires at least: 2.1
Tested up to: 2.3
Stable tag: 1.0

Tinfoil Hat provides extra privacy configuration for your blog.

== Description ==

WordPress sends different and varied types of information hither and thither
across the Internet. While this is innocent and innocuous from the developer
standpoint, there is the feeling that anyone *not* wanting some or all of this
information sent around needs a "tinfoil hat." This, in addition to the desire
to keep configuration clean and simple, means that there are very limited
options for information privacy control with WordPress out-of-the-box.

Some people have privacy or security needs that are above that of the normal
population. Some folks are simply sensitive about what information is shuttled
where. Tinfoil Hat aims to provide a comprehensive set of information control
options, so that you can have a higher degree of control over how and if
WordPress sends data to other services.


== Installation ==

This section describes how to install the plugin and get it working.

1. **Do not allow outbound connections on the server where you install
wordpress.** Tinfoil Hat cannot change WordPress' behavior until the plugin is
installed and configured.
1. Upload the tinfoil-hat zip file to the `/wp-content/plugins/` directory
1. Unzip the tinfoil-hat zip file (you may then delete the zip file)
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure Tinfoil Hat to your needs, through the Options -> TH Privacy panel
1. Allow WordPress outbound connections to the Internet.

You can set up WordPress on a local machine, with no Internet access, and then
dump the MySQL database once WordPress and Tinfoil Hat are configured. You can
then upload the database dump to your production MySQL server, upload the
WordPress files from the local installation to your production web server,
update the wp-config.php file, and go.

== Frequently Asked Questions ==

= Dude, are you really this paranoid? =

I prefer to say I have a hobby in creative thinking. :)


= Isn't Tinfoil Hat kind of a derogatory name to use? =

It's more of a tongue-in-cheek reaction to "somebody can write a plugin" and
"you're wearing a tinfoil hat" comments I've seen regarding WordPress. Real
users haven't the time or knowledge to write a plugin to add extra privacy
features to WordPress, and I feel that expecting them to do so is unreasonable.
In some cases, Tinfoil Hat *does* offer some "paranoid" options, but only
because I can't possibly predict what piece or pieces of information could
possibly be important in every single scenario.


= Is the government controlling you through fillings in your teeth? =

No.


= Why don't you trust WordPress? If you knew them, Matt et. al. are really nice! =

I'm sure they are. But millions of folks *don't* know these guys. In any case,
it's the users' business what, if any, data is collected on them. Tinfoil Hat is
about personal choice in information control, not distrust. Shrinkwrap software
vendors can't know the security or privacy needs of *all* their users; something
completely innocuous 99.99% of the time might be totally inappropriate for that
other .01%.


= What if Tinfoil Hat doesn't cover a privacy concern I have? =

Let me know about it via the contact form at http://remstate.com/contact. I'll
do my best to accomodate feature requests, especially if somebody points out a
beacon-like feature that I missed (I'm sure I missed at least one ;).


= Why isn't there support for WordPress 2.0 or 1.5? =

The dashboard feature in 2.0 and 1.5 is hard-coded into the dashboard. I can't
remove it with a plugin -- the closest I can get is to try and spoof out the
Magpie cache so that nothing is ever actually fetched. Sorry folks, but the best
way to deal with 2.0 and earlier is to hack wp-admin/index.php.

== Screenshots ==

== Privacy Concerns? ==

If you have a privacy concern that you feel is not addressed by Tinfoil Hat,
please e-mail the author at http://remstate.com/contact/.
