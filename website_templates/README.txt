Readme-File for Template-Management:
*************************************

What is a "template"?
----------------------
	A template, as meant by this template system, is an (almost) valid (x)html 
	file, that replaces the dynamic contents of the website by placeholders. We
	usualy use placeholders like #####a_placeholders_name#####, but for
	your own template you can define placeholders in your taste. You only
	have to make shure, that the used string can not be in your website on
	another way.

	The class "Template" (placed in file ./framework/template.class.php)
	will help you reading the template file and replacing all placeholders by
	outputs of your own classes.


The folder "website_templates":
--------------------------------
	The template-class knows to find the template for its websites
	in this folder. This fact is defined by class-constant TEMPLATE_FOLDER
	in class framework/template.class.php.


Usage of template-class:
-------------------------
	Create an object of class framework/template.class.php in your
	main application. As parameter this class wants to have the filename
	of your website template (without path, only filename and extension).

			$tmpl = new Template( "my_template_file.html");

	Create an associative array, that contents the outputs of all subprograms
	of your application. This array should associate an placeholder of your
	website template with the output that has to be placed in it:

		$website_outputs = array(
			"#####content#####" => "This is the text shown in my website!",
			"#####counter#####" => "You are visitor 123 of my website! Yo won a car!");

	Call the method named "show" of the template object and give this array as
	parameter with it. The method will return the rendered website as a string:

		echo $tmpl->show( $website_outputs);


What else does the template class do for me?
---------------------------------------------

	The template class knows some placeholders, that are automaticaly replaced
	by contents:
		- #####title##### will be replaced by the content of the html-head-tag
		  <title></title>.
		  To set the title of your website you can use methods of class HtmlHead
		  in any of your classes. This even allows a dynamic modification of the
		  title of your website during your visitors session.

		- #####html_head_entries##### will be replaced by other tags you added
		  to the html head of your website. To add your own head tags you can
		  again use the methods of class HtmlHead. By using this you can
		  dynamicaly load css or javascript tags.


Im no hacker: I only want to customize the template for this website:
----------------------------------------------------------------------
	You can first create a (x)html file an fill it in your taste. Alternative
	you can modify on of our template files left in this template folder. You
	can use css, images an so on like in any other html based website you might
	have created in the past.

	Finally you replace the contents of your html file by placeholders like
	described above. The newsletter system I'm writing this readme file for
	knows the following placeholders:
		- Two placeholders you already know from the section above.
		- #####content##### will be replaced by the newsletter entries you will
		  create in your newsletter system later.
		- #####website_url##### will be replaced by the url of this website. The
		  url used to replace this can be configured in file config/config.inc.php.
		- #####date_Y##### will be replaced with the current year number. I used
		  this for a copyright entry at the bottom of my newsletters.

	If you want to specify further placeholders, simply visit the file index.php
	in the top folder (for frontend-template) or in the "admin" folder (for
	backend). There you can see how easy I specified the described placeholders.