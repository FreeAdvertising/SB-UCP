<?php
/**
 * Plugin Name: Super Badass UCP
 * Plugin URI: http://wearefree.ca
 * Description: User-group/level based controls for WP's backend
 * Version: 1.0.0
 * Author: Free Advertising
 * Author URI: http://wearefree.ca
 * License: BSD
 */

define("SBUCP_MIN_WP_VERSION", "3.5.0");

if(version_compare($wp_version, SBUCP_MIN_WP_VERSION) === 1){
	include 'usercontrol.php';

	$widget = new \Free\SB_UserControl();
}else {
	//unsupported WP version, display an error
	add_action("admin_notices", function(){
		global $wp_version;

		echo sprintf('
			<div class="error">
				<p><strong>SB-UCP Error</strong>: Your version of Wordpress (%s) is not supported.</p>
			</div>
		', $wp_version);
	});
}

?>