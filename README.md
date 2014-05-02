# Super Badass User Controls Plugin

This plugin is designed to make it as easy as possible for your clients to administrate their Wordpress websites.  They get a slightly customized dashboard, removing all default Wordpress widgets (works best if you have custom or 3rd party dashboard widgets), a user role (Client Administrator) which provides them access to manage all of their content and none of the higher level functions of Wordpress (such as updating plugins, installing themes, etc), and a customized login screen which you can replace with a logo of your choosing.

## Features

* Creates the Client Administrator user role which allows your clients to manage all content on their site but disables any installation, upgrade or code editing functions.
* Removes all standard dashboard widgets for a customized dashboard view.  Add new custom or 3rd party dashboard widgets so your clients see only what they really need to see when they login.
* Upgrade notices now recommend asking notifying the site administrator about the new version (Client Administrators do not have permission to upgrade any part of their website).
* Brand the login page with your own logo and colour scheme (replace images/logo.png with your own image).

## Client Administrator Role Capabilities

The following table shows you how the Client Administrator role differs from the Super Admin role.

|Role|Client Admin|Super Admin Value|
|----|:---:|:---------------:|
|activate_plugins|**false**|true|
|delete_plugins|**false**|true|
|edit_dashboard|**false**|true|
|switch_themes|**false**|true|
|update_core|**false**|true|
|update_plugins|**false**|true|
|update_themes|**false**|true|
|install_plugins|**false**|true|
|install_themes|**false**|true|
|delete_themes|**false**|true|
|edit_plugins|**false**|true|
|edit_themes|**false**|true|
|edit_users|**false**|true|
|create_users|**false**|true|
|delete_users|**false**|true|
|unfiltered_html|**false**|true|
|manage_network|**false**|true|
|manage_sites|**false**|true|
|manage_network_users|**false**|true|
|manage_network_plugins|**false**|true|
|manage_network_themes|**false**|true|
|manage_network_options|**false**|true|
|delete_others_pages|true|true|
|delete_others_posts|true|true|
|delete_pages|true|true|
|delete_posts|true|true|
|delete_private_pages|true|true|
|delete_private_posts|true|true|
|delete_published_pages|true|true|
|delete_published_posts|true|true|
|edit_files|true|true|
|edit_others_pages|true|true|
|edit_others_posts|true|true|
|edit_pages|true|true|
|edit_posts|true|true|
|edit_private_pages|true|true|
|edit_private_posts|true|true|
|edit_published_pages|true|true|
|edit_published_posts|true|true|
|import|true|true|
|export|true|true|
|list_users|true|true|
|manage_categories|true|true|
|manage_links|true|true|
|manage_options|true|true|
|moderate_comments|true|true|
|promote_users|true|true|
|publish_pages|true|true|
|publish_posts|true|true|
|read_private_pages|true|true|
|read_private_posts|true|true|
|read|true|true|
|remove_users|true|true|
|upload_files|true|true|