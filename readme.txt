=== MJCaroussel ===
Contributors: Geekoun
Donate link: http://www.morgan-jourdin.fr/
Tags: Caroussel, gallery
Requires at least: 4.0
Tested up to: 4.8
Stable tag: 4.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


== Description ==

MjCaroussel is a simple plugin to create photo galleries.
Use a shortcode [mjcaroussel id="2"] in editor.
The ID is the id_term or the ID gallery.

== Installation ==

1. Go to your admin area and select Plugins -> Add new from the menu.
2. Click on install.
3. Click on active.
4. A new menu mjCaroussel is appeared in the Dashboard menu.

== Frequently Asked Questions ==

= How to find the ID_term or ID Gallery ? =

Go to your edit page and click to MjCaroussel button in the editor for to add shortcode gallery. (look screenshot-3.png and screenshot-4.png)

= How to override the plugin ? =

- You can add a class extends in the functions.php (in your theme)
- You can modify the CSS in the style.css (in your theme)
`<?php
	class My_MJCaroussel  extends MJCaroussel { ... }
?>`

= How to add a shortcode ? =
You must add manually : [mjcaroussel id="3"] in your wordpress editor ;-) (see screenshot-3)

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
3. screenshot-4.png
3. screenshot-5.png

== Changelog ==

= 1.0.0 =
The new version

= 1.0.1 =
Add a new TINYMCE button to edit page.
Force thumbnail call

== Upgrade Notice ==

= 1.0.0 =
The new version

= 1.0.1 =
Compatibility test and add new functionnality

== A brief Markdown Example ==

1. Look the ID_term or ID Gallery.
2. Add shortcode in the editor: [mjcaroussel id="3"]
