<?php
/**
 * Documentation Page
 *
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>	
<div id="cb-admin-wrap" class="wrap">
	<h1><?php echo esc_html( $this->name ); ?><sup><?php echo esc_html( $this->lets_review_get_version() ); ?></sup></h1>
	<p>The documenation can also be found online where it might be easier to navigate: <a href="http://docs.cubellthemes.com/letsreview">http://docs.cubellthemes.com/letsreview</a></p>
	<p>To find out about new updates and news about the plugin - make sure to follow <a href="http://www.facebook.com/cubellthemes">Cubell Facebook page</a>.</p>

	<h2>Documentation</h2>
	Thank you very much for choosing Let's Review. Hopefully you enjoy using it!

	

	<div class="page-header"><h3>Installation</h3><hr class="notop"></div>

	<p>The easiest way to install a Codecanyon plugin is to do it via you WordPress site's plugin installer functionality.</p>
	<ol>
		<li>Log into your Codecanyon account and download the plugin file.</li>
		<li>Extract the contents of the downloaded zip file to your computer.</li>
		<li>Log into your WordPress site's backend and go to Plugins → Add New.</li>
		<li>Click the "Upload plugin" button that is near the top of the screen.</li>
		<li>Click the "Choose File" button. Now go to where you extracted the downloaded file earlier and select the Let's Review.zip file. Now click the "Install now" button.</li>
		<li>Once installed, click on the Activate button to activate it.</li>
		<li>You will see a notice about installing the Envato Market plugin now, which is a plugin created by Envato to make it easy to update your themes and plugins. If you want to install it click the install button, if not, dismiss the notice to hide it.</li>
	</ol>


	<div class="page-header"><h3>Default Options</h3><hr class="notop"></div>

	<p>If your site has very similar review setups, you can quickly set the default options you wish your reviews to start with each time. To do so simply:</p>
	<ol>
		<li>Hover your mouse cursor over the Let's Review menu in your WordPress backend and click the "Default Options" option.</li>
		<li>You will see all the options that you can set a default value for.</li>
		<li>You can also add default criterias to appear in your reviews.</li>
		<li>Once you have all your default options set up, click the save changes button to save them.</li>
	</ol>

	<p>Note: When you create a new review, you can change the options inside the post for that specific post. You can also remove/add/re-arrange criterias there too.</p>


	<div class="page-header"><h3>Typography</h3><hr class="notop"></div>

	<p>To set the fonts you want to use for the review boxes you can</p>
	<ol>
		<li>Hover your mouse cursor over the Let's Review menu in your WordPress backend and click the "Typography" option.</li>
		<li>You will see dropdown lists to choose fonts from, select your desired font.</li>
		<li>You can also load specific character sets if your language requires them</li>
		<li>Once you have all your options set up, click the save changes button to save them.</li>
	</ol>

	<p>Note: If you don't want the plugin to load any fonts, simply keep the "Inherit from theme" options selected. This option means the review boxes will use whatever fonts your theme is using.</p>



	<div class="page-header"><h3>Adding a review</h3><hr class="notop"></div>

	<p>To add a review to a post:</p>
	<ol>
		<li>Simply edit or add a new post.</li>
		<li>Scroll down and look for the "Let's Review Options" metabox.</li>
		<li>Click the on/off switch to enable a review for this post.</li>
	</ol>

	<p>Once the review is enabled you can continue on with the following sections:</p>


	<div class="page-header"><h3>Adding Criterias</h3><hr class="notop"></div>

	<p>To add a criteria to a review:</p>
	<ol>
		<li>Click on the "Fields" menu item inside the metabox.</li>
		<li>Click on the "+ Add" button under the Criterias title.</li>
		<li>Enter a title for that criteria and select a score using the slider.</li>
	</ol>

	<p>You can add as many as you want and you can rearrange them easily by dragging and dropping. To delete a criteria simply click the red "x" button on the top right of the criteria.</p>


	<div class="page-header"><h3>Adding Positives</h3><hr class="notop"></div>

	<p>To add a positive point to a review:</p>
	<ol>
		<li>Click on the "Fields" menu item inside the metabox.</li>
		<li>Optional: If you want to show a text heading above your positives, write it in the "optional positive heading" input.</li>
		<li>Click on the "+ Add" button just below and write the positive.</li>
	</ol>

	<p>You can add as many as you want and you can rearrange them easily by dragging and dropping. To delete one simply click the red "x" button on the top right corner of it.</p>


	<div class="page-header"><h3>Adding Negatives</h3><hr class="notop"></div>

	<p>To add a negative point to a review:</p>
	<ol>
		<li>Click on the "Fields" menu item inside the metabox.</li>
		<li>Optional: If you want to show a text heading above your negatives, write it in the "optional negative heading" input.</li>
		<li>Click on the "+ Add" button just below and write the negative.</li>
	</ol>

	<p>You can add as many as you want and you can rearrange them easily by dragging and dropping. To delete one simply click the red "x" button on the top right corner of it.</p>


	<div class="page-header"><h3>Adding Affiliate Links</h3><hr class="notop"></div>

	<p>To add a negative point to a review:</p>
	<ol>
		<li>Click on the "Affiliate" menu item inside the metabox.</li>
		<li>Optional: If you want to show a text heading above your affiliate buttons, write it in the "Affiliate Title" input.</li>
		<li>Click on the "+ Add" button under the Affiliate Buttons title.</li>
		<li>Enter the title to show in the button and enter the affiliate URL the button should link to.</li>
	</ol>

	<p>You can add as many as you want and you can rearrange them easily by dragging and dropping. To delete one simply click the red "x" button on the top right corner of it.</p>
	<p>Note: The affiliate buttons are already coded to be rel="nofollow".</p>


	<div class="page-header"><h3>Change review style output</h3><hr class="notop"></div>

	<p>To add a negative point to a review:</p>
	<ol>
		<li>Click on the "General" menu item inside the metabox.</li>
		<li>Under the "Review Style" heading select where to output the review box. Options are Percent/Points/Stars/Custom Icon/Custom Image.</li>
	</ol>



	<div class="page-header"><h3>Custom Icon style</h3><hr class="notop"></div>

	<p>If you select the Custom Icon review style, an input box will appear where you can enter the code of the icon you wish to use. The plugin comes with Font Awesome integrated, so you can add any icon found here:</p>

<p>https://fortawesome.github.io/Font-Awesome/icons/</p>

<p>As an example, to use the heart icon: http://fortawesome.github.io/Font-Awesome/icon/heart/ you'd simply copy/paste the code provided in that link:</p>

<pre>&lt;i class="fa fa-heart" aria-hidden="true"&gt;&lt;/i&gt;</pre>

<p>The plugin will automatically output it 5 times and add special code to show the correct value highlighted on the icons.</p>



	<div class="page-header"><h3>Custom Image style</h3><hr class="notop"></div>

	<p>If you select the Custom Image review style, a button called "Select image" will appear. Simply click it and using WordPress' media manager you can upload or set an existing image to use for the review. </p>
	<p>The recommended size for the image is 80px x 80px and you only need to have a single object in the image. The plugin will automatically output the image 5 times and add special code to show the correct value highlighted on the images.</p>


	<div class="page-header"><h3>Add subtitle to the final score</h3><hr class="notop"></div>

	<p>To add some text to appear inside the final score box and under the final score value: </p>

	<ol>
		<li>Click on the "General" menu item inside the metabox.</li>
		<li>Under the "Final Score Subtitle" heading there is an input where you can enter a title to appear inside the score box.</li>
	</ol>


	<div class="page-header"><h3>Add a conclusion</h3><hr class="notop"></div>

	<ol>
		<li>Click on the "General" menu item inside the metabox.</li>
		<li>Under the "Conclusion" heading there is an input where you can enter a title to appear above the conclusion text (this is optional).</li>
		<li>Just below that you will see an input called "Conclusion content", this is where you enter the conclusion for your review.</li>
	</ol>

	<div class="page-header"><h3>Add a main title to review box</h3><hr class="notop"></div>

	<ol>
		<li>Click on the "General" menu item inside the metabox.</li>
		<li>Under the "Review Main Title" heading there is an input where you can enter the title you wish to show.</li>
	</ol>


	<div class="page-header"><h3>Add main thumbnail to appear next to title (Minimalist & Bold design options only)</h3><hr class="notop"></div>

	<ol>
		<li>Click on the "Media" menu item inside the metabox.</li>
		<li>Click on the "Select Image" button under the "Main Title" title.</li>
		<li>Using WordPress' media manager you can upload or insert an existing image from your media library to be the image to appear next to the title. </li>
	</ol>

	<div class="page-header"><h3>Add main thumbnail to appear as background image (Modern design option only)</h3><hr class="notop"></div>

	<ol>
		<li>Click on the "Media" menu item inside the metabox.</li>
		<li>Click on the "Select Image" button under the "Main Title" title.</li>
		<li>Using WordPress' media manager you can upload or insert an existing image from your media library to be the image to be the background of your review box.</li>
	</ol>


	<div class="page-header"><h3>Add image gallery</h3><hr class="notop"></div>

	<ol>
		<li>Click on the "Media" menu item inside the metabox.</li>
		<li>Optional: If you want to show a text heading above your gallery thumbnails, write it in the "Optional Gallery Heading" input.</li>
		<li>Click on the "Add/edit" button just below.</li>
		<li>Now using WordPress' media manager you can upload or insert existing images from your media library to your review's gallery. </li>
	</ol>
	<p>You can add as many as you want and you can rearrange them easily by dragging and dropping. To delete an image from the gallery simply click the white "x" button on the top right of the relevant image.</p>



	<div class="page-header"><h3>Set Light/Dark Skin (Minimalist & Bold design options only)</h3><hr class="notop"></div>

	<ol>
		<li>Click on the "Design" menu item inside the metabox.</li>
		<li>Under the "Skin Style" title, select the skin you want the review box to use, Light or Dark.</li>
	</ol>

	<div class="page-header"><h3>Select animation style for criterias (Minimalist & Bold design options only)</h3><hr class="notop"></div>

	<ol>
		<li>Click on the "Design" menu item inside the metabox.</li>
		<li>Under the "Animation Type" title, select the animation you want the criterias to use. The image options are all animations so you can see what they will look like. It can be Incremental/Fade in/No animation.</li>
	</ol>


	<div class="page-header"><h3>Select accent color for review box</h3><hr class="notop"></div>

	<ol>
		<li>Click on the "Design" menu item inside the metabox.</li>
		<li>Under the "Accent color" title, select the desired color you wish to have by using the colorpicker to select it. Or if you know the exact hexadecimal value already, simply paste it into the input there.</li>
	</ol>



	<div class="page-header"><h3>Extras</h3><hr class="notop"></div>

	<p>To see the extra options of the plugin, simply:</p>
	<ol>
		<li>Hover your mouse cursor over the Let's Review menu in your WordPress backend and click the "Extras" option.</li>
		<li>Enable or disable the various extras.</li>
	</ol>

	<p>Note: The plugin uses the latest Font Awesome version, if your theme comes with it integrated and is using an older version, the icons may not appear. Simply disable the "Load FontAwesome files" option to use the theme's version.</p>


	<div class="page-header"><h3>Updating the plugin</h3><hr class="notop"></div>

	<p>If you installed the Envato Market plugin then updating the Let's Review plugin is very easy. Simply activate the Envato Market plugin, and click on the "Envato Market" menu in WordPress and then connect to their API.</p>
	<p>Alternatively, the other way to update is to:</p>

	<ol>
		<li>Download the latest version of the plugin on Codecanyon.</li>
		<li>Go to your Plugins menu in your WordPress site's backend and deactivate the current installed version of Let's Review.</li>
		<li>Delete the plugin.</li>
		<li>Now install the plugin again using the latest lets-review.zip file (see Installation section for full instructions).</li>
	</ol>



	<div class="page-header"><h3>Shortcodes</h3><hr class="notop"></div>

	<p>Let's review comes with a single shortcode with a few options to simplify using it.</p>
	<p>To insert the review box of a post somewhere specific, simply put the following shortcode somewhere in the post content area:</p>

		
	<pre>[letsreview]</pre>


	<p>If you want to add a post review from a different post inside a post, you can use </p>

	<pre>[letsreview postid="123"]</pre>

	<p>And simply replace the 123 value with the id of the post you wish to grab the review from.</p>




	<div class="page-header"><h3>Filters (for developers)</h3><hr class="notop"></div>

		
	<p>If you understand PHP and would like to add your own custom design, you can easily do so by hooking on to the plugin's filters.</p>

	<p>To add a new style/output to the plugin's widget you can easily do it by:</p>

	<p>First let's add the style names you wish to add, by putting this code into your theme's functions.php file:</p>
	 
	<pre>
	function lets_review_custom_widget_style( ) {

		return 'My style name';
	}
	add_filter( 'lets_review_widget_add_design', 'lets_review_custom_widget_style' );</pre>

	<p>And if you want to add multiple designs, simply return an array, like this:</p>

	<pre>
	function lets_review_custom_widget_style( ) {

		return array( 'My style name', 'My second style' );
	}
	add_filter( 'lets_review_widget_add_design', 'lets_review_custom_widget_style' );</pre>

	<p>Now that the styles have been added, you can now write the code you wish is outputted in the widget on your site for your added style(s).</p>

	<p>Add this  code into your theme's functions.php file also:</p>
	<pre>
	function lets_review_widget_style_1_output( ) {

		return '&lt;div&gt;Hello, World!&lt;/div&gt;';
	}
	add_filter( 'lets_review_widget_add_design_html_1', 'lets_review_widget_style_1_output' );</pre>

	<p>The key thing to note is that the filter has a number representing the style. "lets_review_widget_add_design_html_1" finishes with the number "1" in this case would mean the code outputs for the first style added ("My Style Name"). If you have multiple custom styles, you would copy/paste that code for each style and change the number in the filter to the style, so for your second style, you'd use:</p>

	<pre>
	function lets_review_widget_style_2_output( ) {

		return '&lt;div&gt;Hello, Style 2!&lt;/div&gt;';
	}
	add_filter( 'lets_review_widget_add_design_html_2', 'lets_review_widget_style_2_output' );</pre>

	<p>For an example of the html you should be outputting for your custom style, check the widget's PHP file current output for the existing styles as reference.</p>


	<div class="page-header"><h3>Troubleshooting</h3><hr class="notop"></div>

	<p>Common errors:</p>

	<p><strong>Problem #1: Icons aren't showing</strong></p>
	<p>The plugin uses the latest Font Awesome version, if your theme comes with it integrated and is using an older version, the icons may not appear. Simply disable the "Load FontAwesome files" option in the plugin's Extras section in the WordPress menu to use your theme's version.</p>


</div>