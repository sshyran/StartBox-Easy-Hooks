<?php
/*
Plugin Name: Startbox Easy Hooks
Plugin URI: http://webdevstudios.com
Description: This plugin allows you to insert any content into the many hooks that the Startbox provides, without editing any files! Based on Thesis OpenHook & K2 Hook Up and GPLed.
Version: 1.0
Author: WebDevStudios
Author URI: http://webdevstudios.com
*/

// Localization. Should be bablefish ready.
load_plugin_textdomain( 'sb_easy_hooks', FALSE, dirname( __FILE__ ) );

// Fire up ze engines!
add_action( 'admin_menu', 'add_sb_easy_hooks_options_page' );
add_action( 'admin_init', 'sb_easy_hooks_options_init');

/**
 * Add the options page to the Appearance submenu.
 *
 * @since 1.0
 */
function add_sb_easy_hooks_options_page() {
  add_theme_page( __( 'Startbox Easy Hooks', 'sb_easy_hooks' ), __( 'Startbox Easy Hooks', 'sb_easy_hooks' ), 'manage_options', 'sbeasyhook', 'sb_easy_hooks_do_settings_page' );
}

/**
 * Set up our master sword array, register our settings, and add our sections/fields.
 *
 * @since 1.0
 */
function sb_easy_hooks_options_init() {
	$sb_easy_hooks_array = apply_filters( 'sb_easy_hooks_array', array(
		'header_group'         => array( 'title' => __( 'Header.php', 'sb_easy_hooks' ),         'description' => __( 'Hooks related to the header', 'sb_easy_hooks' ),          'hooks' => array( 'sb_before', 'sb_before_header', 'sb_header', 'sb_after_header' ) ),
		'frontpage_group'      => array( 'title' => __( 'Front-page.php', 'sb_easy_hooks' ),     'description' => __( 'Hooks related to the front page', 'sb_easy_hooks' ),      'hooks' => array( 'sb_before_featured', 'sb_featured', 'sb_after_featured', 'sb_home' ) ),
		'all_group'            => array( 'title' => __( 'All Templates', 'sb_easy_hooks' ),      'description' => __( 'Hooks related to the all templates', 'sb_easy_hooks' ),   'hooks' => array( 'sb_before_content', 'sb_after_content' ) ),
		'loop_group'           => array( 'title' => __( 'Loop', 'sb_easy_hooks' ),               'description' => __( 'Hooks related to the post loop', 'sb_easy_hooks' ),       'hooks' => array( 'sb_before_post', 'sb_after_post', 'sb_before_first_post', 'sb_after_first_post' ) ),
		'loop_single_group'    => array( 'title' => __( 'Loop and Single', 'sb_easy_hooks' ),    'description' => __( 'Hooks related to the single post', 'sb_easy_hooks' ),     'hooks' => array( 'sb_post_header', 'sb_before_post_content', 'sb_after_post_content', 'sb_post_footer' ) ),
		'admin_group'          => array( 'title' => __( 'Admin.php', 'sb_easy_hooks' ),          'description' => __( 'Hooks related to the admin area', 'sb_easy_hooks' ),      'hooks' => array( 'sb_admin_left', 'sb_admin_right' ) ),
		'404_group'            => array( 'title' => __( '404', 'sb_easy_hooks' ),                'description' => __( 'Hooks related to the 404 page', 'sb_easy_hooks' ),        'hooks' => array( 'sb_404' ) ),
		'sidebar_group'        => array( 'title' => __( 'Sidebar.php', 'sb_easy_hooks' ),        'description' => __( 'Hooks related to the sidebar', 'sb_easy_hooks' ),         'hooks' => array( 'sb_before_primary_widgets', 'sb_between_primary_and_secondary_widgets', 'sb_after_secondary_widgets' ) ),
		'sidebar_footer_group' => array( 'title' => __( 'Sidebar-footer.php', 'sb_easy_hooks' ), 'description' => __( 'Hooks related to the footer sidebars', 'sb_easy_hooks' ), 'hooks' => array( 'sb_before_footer_widgets', 'sb_between_footer_widgets', 'sb_after_footer_widgets' ) ),
		'footer_group'         => array( 'title' => __( 'Footer.php', 'sb_easy_hooks' ),         'description' => __( 'Hooks related to the footer', 'sb_easy_hooks' ),          'hooks' => array( 'sb_between_content_and_footer', 'sb_before_footer', 'sb_footer', 'sb_after_footer', 'sb_after' ) ),
		'wp_native_group'      => array( 'title' => __( 'WordPress Native', 'sb_easy_hooks' ),   'description' => __( 'Hooks related to WordPress itself', 'sb_easy_hooks' ),    'hooks' => array( 'wp_head', 'wp_footer'  ) )
	) );

	// Register our settings options
	register_setting( 'sb_easy_hooks_options', 'sb_easy_hooks_options', 'sb_easy_hooks_options_validate' );

	// Setup all our sections and inputs
	foreach ( $sb_easy_hooks_array as $section_id => $section ) {

		// Register each settings section
		add_settings_section( $section_id, $section['title'], 'sb_easy_hooks_render_hook_section', 'sbeasyhook' );

		// Register each hook as a setting for it's section
		foreach ( $section['hooks'] as $hook ) {
			add_settings_field( $hook, $hook, 'sb_easy_hooks_render_hook_field', 'sbeasyhook', $section_id, array( 'label_for' => $hook, 'section_id' => $section_id, 'hook' => $hook ) );
		}
	}
}

/**
 * Lets get our page rendered.
 *
 * @since 1.0
 */
function sb_easy_hooks_do_settings_page() {

  // Setup our master hooks array, and make it filterable for other devs
	global $sb_easy_hooks_array; ?>

	<div class="wrap">
		<?php screen_icon();?>
		<h2><?php _e('Startbox Easy Hooks Settings', 'sb_easy_hooks'); ?></h2>
		<form action="options.php" method="post">
			<?php submit_button(); ?>
			<?php settings_fields('sb_easy_hooks_options'); ?>
			<?php do_settings_sections( 'sbeasyhook' ); ?>

			<?php submit_button(); ?>
		</form>
	</div>
<?php }

/**
 * Validation for each of our hook fields
 *
 * @since 1.0
 */
function sb_easy_hooks_options_validate( $input ) {
	global $sb_easy_hooks_array;
	//add_settings_error( $setting, $code, $message, $type );

	foreach ( $sb_easy_hooks_array as $section_id => $section ) {
		foreach ( $section['hooks'] as $hook ) {
			$newinput[ $hook ] = trim($input[ $hook ]);
		}
	}


	return $input;
	/*$options = get_option( 'sb_easy_hooks_options' );
	$options['text_string'] = trim($input['text_string']);
	if(!preg_match('/^[a-z0-9]{32}$/i', $options['text_string'])) {
		$options['text_string'] = '';
	}

	return $options;*/
	/*
	$setting (required) Slug title of the setting to which this error applies.
	$code (required) Slug-name to identify the error. Used as part of 'id' attribute in HTML output.
	$message (required) The formatted message text to display to the user (will be shown inside styled <div> and <p>)
	$type (optional) The type of message it is, controls HTML class. error/updated
	*/
}

/**
 * Helper function for rendering our hook sections
 *
 * @since  1.0
 * @param  string $section_id The given section we want to render
 */
function sb_easy_hooks_render_hook_section( $section ) {
	global $sb_easy_hooks_array;
	echo $sb_easy_hooks_array[$section['id']]['description'];
}

/**
 * Helper function for rendering our input fields
 *
 * @since  1.0
 * @param  string $group The section ID for our hook
 * @param  string $hook  The name of our hook
 */
function sb_easy_hooks_render_hook_field( $args ) {
	$options = get_option( 'sb_easy_hooks_options' );

	$sb_hook_value = isset( $options[ $args['hook'] ] ) ? $options[ $args['hook'] ] : '';
	?>
	<textarea id="<?php $args['hook']; ?>" name="sb_easy_hooks_options[<?php echo $args['hook']; ?>]" style="height: 150px; resize: vertical; width: 530px; float: right;"><?php echo $sb_hook_value; ?></textarea>
<?php
}

/**
 * Add our option values to each registered hook
 *
 * @since 1.0
 */
function sb_easy_hooks_add_actions() {

	// Grab our easy hooks array
	global $sb_easy_hooks_array;

	// Loop through each section of the master array
	foreach ( $sb_easy_hooks_array as $section_id => $section ) {

		// Loop through each hook in each section
		foreach ( $section['hooks'] as $hook ) {
			// Add our option output to the hook
			// TODO: This doesn't work because we're not passing the $hook name through to the sb_easy_hooks_option_output callback
			add_action( $hook, 'sb_easy_hooks_option_output' );
		}
	}
}

/**
 * Helper function for handling hook output
 *
 * @since  1.0
 * @param  string $hook The name of the hook whose output we want to generate
 */
function sb_easy_hooks_option_output( $hook ) {

	// Grab our settings
	$easy_hooks_options = get_option( 'sb_easy_hooks_options' );

	// TODO: this needs to be fixed to grab the actual value based on our hook
	$hook_value = $easy_hooks_options[$hook];

	// Echo our output
	echo $hook_value;
}
