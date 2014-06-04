<?php
	/**
	 * Copyright (c) 2014, Free Advertising
	 * All rights reserved.
	 *
	 * Redistribution and use in source and binary forms, with or without
	 * modification, are permitted provided that the following conditions are met:
	 *
	 * 1. Redistributions of source code must retain the above copyright notice, this
	 *  list of conditions and the following disclaimer. 
	 * 2. Redistributions in binary form must reproduce the above copyright notice,
	 *  this list of conditions and the following disclaimer in the documentation
	 *  and/or other materials provided with the distribution.
	 *	
	 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
	 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
	 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	 * 
	 * The views and conclusions contained in the software and documentation are those
	 * of the authors and should not be interpreted as representing official policies, 
	 * either expressed or implied, of the FreeBSD Project.
	 */
	
	namespace Free;

	class SB_UserControl {
		/**
		 * Reference to the current user
		 * @var object
		 */
		private static $_user;

		/**
		 * Instantiate the plugin and run custom actions
		 */
		public function __construct(){
			//http://stackoverflow.com/questions/6127559/wordpress-plugin-call-to-undefined-function-wp-get-current-user
			if(!function_exists('wp_get_current_user')) {
				include(ABSPATH . "wp-includes/pluggable.php"); 
			}

			self::$_user = wp_get_current_user();

			add_action("init", "\Free\SB_UserControl::removeUpdateNotice");
			add_action("wp_dashboard_setup", "\Free\SB_UserControl::removeMetaBoxes");
			add_action("login_enqueue_scripts", "\Free\SB_UserControl::brandLoginScreen");
			add_action("init", "\Free\SB_UserControl::setupClientView");
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
		public static function brandLoginScreen(){
			echo '
				<style type="text/css">
					body.login {
						background-color: white;
					}
					
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
				"publish_pages" => true,
				"publish_posts" => true,
				"read_private_pages" => true,
				"read_private_posts" => true,
				"read" => true,
				"upload_files" => true,
			);

			try {
				$client_role = add_role("client_admin", "Client Administrator", $capabilities);

				if(false === is_null($client_role)){
					self::_createUser();
				}else {
					throw new Exception("Role could not be created, default client_admin user creation skipped.");
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}

		/**
		 * Create a new user with the newly created client_admin role
		 * @return mixed  (false|object) Returns new user object if successful
		 */
		public static function _createUser(){
			if(($exists = username_exists("clientadmin")) === false){
				$id = wp_create_user("clientadmin", "test123!", "sbucp+user@example.com");

				$newUser = new WP_User($id);
				$newUser->setRole("client_admin");

				return $newUser;
			}

			return false;
		}
	}

?>