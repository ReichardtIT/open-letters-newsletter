Framework Readme-File:
***********************

	This framework offers basic functionality for developing PHP5-Applications. If
	you want to create your own application on top of these classes, you will need
	to create this folder structure first:
	- config/config.inc.php including database access data and logging configuration
	  config/log.txt for logging errors: You can edit logfile location and max size
	  in config file
	- external_scripts/htmlpurifier-4.0.0-lite for checking html transfered from form
	  input fields with framework/postman.class.php: You can specify the location of
	  htmlpurifier on top of the class PostMan.
	- framework (folder) including basic classes
	- languages (folder) including basic language files as specified in config.inc.php
	- website_templates (folder) including the template file for your website