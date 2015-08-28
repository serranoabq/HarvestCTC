<?php
/**
 * Theme options class
 *
 * Based off of: 
 *  http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 *  http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-2/
 *  http://alisothegeek.com/2011/04/wordpress-settings-api-tutorial-follow-up/
 *  http://wp.tutsplus.com/tutorials/creative-coding/how-to-integrate-the-wordpress-media-uploader-in-theme-and-plugin-options/
 *  http://www.justinwhall.com/multiple-upload-inputs-in-a-wordpress-theme-options-page/
 *
 *
 * @since 2.5
 */
 

class Theme_Options {
	
	private $sections;
	private $checkboxes;
	private $settings;
	private $theme_name; // Theme name
	private $theme_safename; // Theme safe name
	
	/**
	 * Construct
	 *
	 * @since 1.0
	 */
	public function __construct( $my_settings = NULL, $my_sections = NULL ) {
		global $pagenow;
		
		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
				
		// Create the sections
		if(isset($my_sections)){
			// Sections passed on class instantiation 
			$this->sections = $my_sections;
		}else{
			// Default sections 
			$this->sections['general']    = __( 'General Settings', 'cltop' );
			$this->sections['about']      = __( 'About this Theme', 'cltop' );
		}
		
		// Get theme name to save the options 
		$this->theme_name = $my_settings['themename'];
		$this->theme_safename = sanitize_title($my_settings['themename']);
		unset($my_settings['themename']);
		
		// Get the settings
		$this->get_settings($my_settings);
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		
		if ( ! get_option( $this->theme_safename .'-options' ) )
			$this->initialize_settings(); 
		
	}
	
	/**
	 * Add options page
	 *
	 * @since 1.0
	 */
	public function add_pages() {
		
		$admin_page = add_theme_page( 
			__( 'Theme Options', 'cltop' ), 		// page title
			__( 'Theme Options', 'cltop' ), 		// menu title
			'manage_options', 									// capability
			$this->theme_safename . '-options', // menu slug
			array( &$this, 'display_page' ) 		// callback
		);
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
		
	}
	
	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {
		
		$defaults = array(
			'id'      	=> 'default_field',
			'title'   	=> __( 'Default Field', 'cltop' ),
			'desc'    	=> __( 'This is a default description.', 'cltop' ),
			'std'     	=> '',
			'type'    	=> 'text',
			'section' 	=> 'general',
			'choices' 	=> array(),
			'class'   	=> '',
			'condition' => true  // boolean flag to determine whether to include the option or not
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class,
			'condition' => $condition
		);
		
		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
		
		if ( $condition ) 
			add_settings_field( $id, $title, array( $this, 'display_setting' ), $this->theme_safename . '-options', $section, $field_args );
	}
	
	/**
	 * Display options page
	 *
	 * @since 1.0
	 */
	public function display_page() {
		$name = $this->theme_safename;
		
		echo '<div class="wrap"><h2>' . ucwords($this->theme_name).  __( ' Theme Options', 'cltop' ) . '</h2>';
	
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.', 'cltop' ) . '</p></div>';
		
		echo '<form action="options.php" method="post">';
	
		settings_fields( $name . '-options' );
		
		echo '<div class="options-tabs">
				<ul class="tabs-nav">';
		
		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
		
		echo '</ul>';
		
		do_settings_sections( $_GET['page'] );
		
		echo '</div> <!-- .options-tabs -->
		
		<p class="submit">
			<input name="reset" id="reset" type="submit" class="reset-button button-secondary" value="'. __( 'Restore Defaults', 'cltop' ) .'" onclick="return confirm(\'' . __( 'Click OK to reset. Any theme settings will be lost!', 'cltop' ) . '\');" />
			<input name="submit" id="submit" type="submit" class="button-primary" value="' . __( 'Save Changes', 'cltop' ) . '" />
		</p>
		
	</form>';
	
	echo '
		<script type="text/javascript">
			var sections = [];';
	foreach ( $this->sections as $section_slug => $section )
		echo "sections['$section'] = '$section_slug';";
		
	echo '			
		</script>
	</div> <!-- .wrap -->';
		
	}
	
	/**
	 * Description for section
	 *
	 * @since 1.0
	 */
	public function display_section() {
		// code
	}
	
	/**
	 * Description for About section
	 *
	 * @since 1.0
	 */
	public function display_about_section() {
		$theme_data = wp_get_theme();
		
		echo '<div style="padding:20px;">';
		if(file_exists(STYLESHEETPATH .'/screenshot.jpg'))
			echo '<img src="' . get_stylesheet_directory_uri() . '/screenshot.jpg" /><br/>';
		elseif(file_exists(STYLESHEETPATH .'/screenshot.png'))
			echo '<img src="' . get_stylesheet_directory_uri() . '/screenshot.png" /><br/>';
		
		echo '<h4>'.$theme_data.' Theme'; 
		if ( $theme_data->get( 'Version' ) )
			echo ', Version ' . $theme_data -> get( 'Version' ) . '</h4>';
		else
			echo '</h4>';
		if ( $theme_data -> get( 'Description' ) )
			echo '<span class="description">' . $theme_data -> get( 'Description' ) . '</span><br/>';
		if ( $theme_data -> get( 'Author' ) )
			echo '<span class="description">&copy; ' . date('Y ') . $theme_data -> get( 'Author' ) . '</span>';
			
		echo '</div>';
	}
	
	/**
	 * HTML output for text field
	 *
	 * @since 1.0
	 */
	public function display_setting( $args = array() ) {
		
		extract( $args );

		$name = $this->theme_safename;

		$options = get_option( $name . '-options' );

		if ( ! isset( $options[$id] ) )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		switch ( $type ) {
			
			case 'description':
				echo  '<span class="description'. $field_class . '">' . $desc . '</span>' ;
				break;
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="'. $name . '-options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc .'</label>';
				
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="'. $name . '-options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="'. $name . '-options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="'. $name . '-options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="'. $name . '-options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'email':
				echo '<input class="regular-text' . $field_class . '" type="email" id="' . $id . '" name="'. $name . '-options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'url':
				echo '<input class="regular-text' . $field_class . '" type="url" id="' . $id . '" name="'. $name . '-options[' . $id . ']" value="' . esc_url( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
				
			case 'upload':
				echo '<input id="' . $id . '" class="upload-url' . $field_class . '" type="text" name="'. $name . '-options[' . $id . ']" value="' . esc_url( $options[$id] ) . '" /> 
				<input id="'. $id . '-upload_button" class="upload_button button-secondary" type="button" name="' . $name . '-upload_button" value="Upload" />
				<input id="'. $id . '-remove_button" class="remove_button button-secondary" type="button" name="' . $name . '-remove_button" value="Remove" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				echo '<br /><div class="media-preview"><img id="' . $id . '-preview" class="upload-preview" src="' . esc_url( $options[$id] ) . '" /></div>';
				
				
				break;
				
			case 'styles':
				$choices = $this->alt_css;
				echo '<select class="select' . $field_class . '" name="'. $name . '-options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			case 'color':
				echo '<input class="regular-text color' . $field_class . '" type="text" id="' . $id . '" name="'. $name . '-options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" data-default-color="'. $std .'" style="border-bottom: 6px solid '. $std .'" />';
				echo '<input type="button" class="button-secondary color-reset" value="Reset" />';
		 		
				if ( $desc != '' )
		 			echo '<br/><span class="description">' . $desc . '</span>';
				
		 		//echo '<div class="booho"></div> ';
				//echo '<div class="color-preview" style="width: 50px;>&nbsp;</div> ';
				
				
		 		break;
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="'. $name . '-options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}
		
	}
	
	/**
	 * Settings and defaults
	 * 
	 * @since 1.0
	 */
	public function get_settings( $my_settings = NULL ) {
		if( isset( $my_settings ) ) {
			/* The settings are created outside and passed to the class constructor */
			$this->settings = $my_settings;
			return;
		} else {
			/* Hard coded settings  */
			/* General Settings
			===========================================*/
			if( isset( $this->settings ) ) return;
			$this->settings['dummy'] = array(
				'title'   => __( 'Dummy Setting', 'cltop' ),
				'desc'    => __( 'Dummy setting to demo functionality.', 'cltop' ),
				'std'     => '',
				'type'    => 'text',
				'section' => 'general'
			);
			
		}
	}
	
	/**
	 * Initialize settings to their default values
	 * 
	 * @since 1.0
	 */
	public function initialize_settings() {
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' && $setting['type'] != 'description' )
				$default_settings[$id] = $setting['std'];
		}
		
		update_option( $this->theme_safename . '-options', $default_settings );
		
	}
	
	/**
	* Register settings
	*
	* @since 1.0
	*/
	public function register_settings() {
		
		register_setting( 
			$this->theme_safename . '-options', 
			$this->theme_safename . '-options', 
			array ( &$this, 'validate_settings' ) 
		);
		
		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( 
					$slug, 
					$title, 
					array( &$this, 'display_about_section' ), 
					$this -> theme_safename . '-options' 
				);
			else
				add_settings_section( 
					$slug, 
					$title, 
					array( &$this, 'display_section' ), 
					$this -> theme_safename . '-options' 
				);
		}
		
		$this->get_settings();
		
		foreach ( $this -> settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
	
	
	/**
	* scripts
	*
	* @since 1.0
	*/
	public function scripts() {
		$file = realpath( dirname( __FILE__ ) );
		$templ = realpath( get_stylesheet_directory() );
		$subf = trailingslashit( str_replace( '\\', '/', str_replace( $templ, '', $file ) ) );
		$uri = get_stylesheet_directory_uri() . $subf;
		
		wp_enqueue_media();
		wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
		wp_enqueue_script( 'responsive-tabs', $uri . 'jquery.responsiveTabs.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'theme-options-js', $uri . 'theme-options.js', array( 'jquery' ) );
	}
	
	/**
	* Styling for the theme options page
	*
	* @since 1.0
	*/
	public function styles() {
		$file = realpath( dirname(__FILE__) );
		$templ = realpath( get_stylesheet_directory() );
		$subf = trailingslashit( str_replace( '\\', '/', str_replace( $templ, '', $file ) ) );
		$uri = get_stylesheet_directory_uri() . $subf;
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'theme-options-css', $uri . 'theme-options.css' );
		//wp_enqueue_style( $this->theme_safename .'-admin' );
		//wp_enqueue_style('thickbox');
	}
	
	/**
	* Validate settings
	*
	* @since 1.0
	*/
	public function validate_settings( $input ) {
		
		if ( ! isset( $_POST['reset'] ) ) {
			$options = get_option( $this->theme_safename .'-options' );
			
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			flush_rewrite_rules();
			return $input;
		}
		return false;		
	}
	
}

