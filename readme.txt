=== Optimizer Pro ===
Contributors: @OptimizerPro 
Plugin Name: Optimizer Pro 
Plugin URI: http://optimizerpro.com/
Tags: split testing, analytics, stats, optimizer pro, OptimizerPro

World's easiest to use A/B, Split and Multivariate testing tool. 

== Description ==
This plugin will allow you to automatically insert the Optimizer Pro tracking code. Just enter your OptimizerPro Account ID from http://optimizerpro.com/get_tracking_code.php

== Installation ==
Wordpress : Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

WordpressMu : Same as above 

== Frequently Asked Questions ==
= I can't see any code added to my header or footer when I view my page source =
Your theme needs to have the header and footer actions in place before the `</head>` and before the `</body>`

= If I use this plugin, do I need to enter any other code on my website? =
No, this plugin is sufficient by itself

== Screenshots ==
1. Settings page (Asynchronous Code)
2. Settings page (Synchronous Code)

== ChangeLog ==
= 1.0 =
* First Version

== Upgrade Notice ==

== Configuration ==

Enter your ID in the field marked 'YOUR OptimizerPro ACCOUNT ID'

== Adding to your template ==

header code :
`<?php wp_head();?>`

footer code : 
`<?php wp_footer();?>`