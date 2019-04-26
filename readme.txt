=== ElegantCRM ===
Contributors: nerdaryan
Donate link: https://wp.cafe/
Tags: crm, customer, wp.cafe
Requires at least: 4.2
Tested up to: 4.9
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A Simple Lead Gen Form Plugin

== Description ==

Shortcode:

`[elegant_customer_form]`

Fields slugs:

- name
- email
- phone
- budget
- message

Some arguments of fields can be customized from shortcode.

- Label
- maxlength
- cols
- rows

For instance to customize label of name fields:

`[elegant_customer_form name_label="Your name" name_maxlength="50"]`

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/1.0/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0.0 =

* Initial development and release.
