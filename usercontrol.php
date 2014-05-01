<?php

	namespace Free;

	class SB_UserControl {
		private static $_user;

		public function __construct(){
			//http://stackoverflow.com/questions/6127559/wordpress-plugin-call-to-undefined-function-wp-get-current-user
			if(!function_exists('wp_get_current_user')) {
				include(ABSPATH . "wp-includes/pluggable.php"); 
			}

			self::$_user = wp_get_current_user();

			add_action("init", "\Free\SB_UserControl::removeUpdateNotice");
			add_action("wp_dashboard_setup", "\Free\SB_UserControl::removeMetaBoxes");
			add_action("login_enqueue_scripts", "\Free\SB_UserControl::addFreeBrandedLogin");
			add_action("init", "\Free\SB_UserControl::setupClientView");
		}

		public static function display(){
			
		}

		/**
		 * Remove standard Wordpress Dashboard widgets
		 * @return void
		 */
		public static function removeMetaBoxes(){
			global $wp_meta_boxes;

			if(false === self::$_user->has_cap("manage-options")){
				$ignoredMetaBoxes = array(
					array("dashboard_recent_comments", "normal"),
					array("dashboard_incoming_links", "normal"),
					array("dashboard_quick_press", "side"),
					array("dashboard_primary", "side"),
					array("dashboard_secondary", "side"),
					array("dashboard_right_now", "normal"),
					array("dashboard_activity", "normal"),
				);

				foreach($ignoredMetaBoxes as $box){
					unset($wp_meta_boxes["dashboard"][$box[1]]["core"][$box[0]]);
				}
			}
		}

		/**
		 * Ignore "Your version of Wordpress is out of date, please upgrade" notices
		 * that result in client panic and willy-nilly button clicking
		 * @return void
		 */
		public static function removeUpdateNotice(){
			remove_action("init", "wp_version_check");
			remove_action("admin_notices", "update_nag", 3);

			add_filter("pre_option_update_core", function(){
				return null;
			});
		}

		/**
		 * Brand the login page with our logo, or the client's logo if you really
		 * want to do something TOTALLY CRAZY
		 * @return void
		 */
		public static function addFreeBrandedLogin(){
			echo '
				<style type="text/css">
					.login h1 a {
						background-image: url("wp-content/plugins/sb-ucp/images/logo.png");
						background-size: auto;
						height: 250px;
						width: 250px; 
					}

					.login {
						background-color: white;
					}

					#loginform {
						background-color: black;
						box-shadow: none;
					}

					#loginform label {
						color: white;
					}
				</style>
			';
		}

		/**
		 * Setup client user roles and, by extension, what they can see
		 * @return void
		 */
		public static function setupClientView(){
			$capabilities = array(
				"delete_others_pages" => true,
				"delete_others_posts" => true,
				"delete_pages" => true,
				"delete_posts" => true,
				"delete_private_pages" => true,
				"delete_private_posts" => true,
				"delete_published_pages" => true,
				"delete_published_posts" => true,
				"edit_files" => true,
				"edit_others_pages" => true,
				"edit_others_posts" => true,
				"edit_pages" => true,
				"edit_posts" => true,
				"edit_private_pages" => true,
				"edit_private_posts" => true,
				"edit_published_pages" => true,
				"edit_published_posts" => true,
				"import" => true,
				"export" => true,
				"list_users" => true,
				"manage_categories" => true,
				"manage_links" => true,
				"manage_options" => true,
				"moderate_comments" => true,
				"promote_users" => true,
				"publish_pages" => true,
				"publish_posts" => true,
				"read_private_pages" => true,
				"read_private_posts" => true,
				"read" => true,
				"remove_users" => true,
				"upload_files" => true,
			);

			$client_role = add_role("client_admin", "Client Administrator", $capabilities);
		}
	}

?>