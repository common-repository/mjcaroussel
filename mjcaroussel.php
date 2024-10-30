<?php
 /*
	Plugin Name:  MJCaroussel
	Plugin URI:   http://www.morgan-jourdin.fr/
	Description: 	Used to create the galleries.
  Version:      1.0.1
  Author:       Morgan JOURDIN
	Author URI:   http://www.morgan-jourdin.fr/
	Text Domain:  mjcaroussel
	Domain Path: 	/languages
	License:      GPL2
 	License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/


// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'MJCAROUSSEL_VERSION', '1.0.0' );
define( 'SLICKSLIDER_VERSION', '1.6.0' );
define( 'MJCAROUSSEL_MINIMUM_WP_VERSION', '4.0.0' );
define( 'MJCAROUSSEL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( MJCAROUSSEL_PLUGIN_DIR . 'class.mjcaroussel.php' );

$mjcaroussel = new MJCaroussel;

register_activation_hook( __FILE__, array( $mjcaroussel, 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( $mjcaroussel, 'plugin_desactivation' ) );
?>
