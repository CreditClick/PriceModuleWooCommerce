<?php



class credit_click_pm_admin {
	/**
	 * Holds the values to be used in the fields callbacks
	 */

	private $options; //all options in array
	public  $option_name;
	private $option_group_name;
	private $option_page_name;
	private $option_section_name;


	/**
	 * Start up
	 */
	public function __construct()
	{

		$this->option_name          = 'credit_click_pm_name';
		$this->option_group_name    = 'credit_click_pm_group';
		$this->option_page_name     = 'credit_click_pm_page';
		$this->option_section_name  = 'credit_click_pm_section';

		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'CreditClick Price Module Settings Admin',
			'CreditClick Price Module',
			'manage_options',
			$this->option_page_name, //can be something else
			array( $this, 'create_admin_page' )
		);
	}


	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option($this->option_name);

		?>
		<div class="wrap">
			<h1>CreditClick Price Module</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( $this->option_group_name );
				do_settings_sections( $this->option_page_name );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}


	/**
	 * Register and add settings
	 */
	public function page_init()
	{
		register_setting(
			$this->option_group_name, // Option group
			$this->option_name, // Option name
			array( $this, 'sanitize' ) // Sanitize
		);
		add_settings_section(
			$this->option_section_name, // ID
			'Price Module settings', // Title
			array( $this, 'print_section_info' ), // Callback
			$this->option_page_name // Page
		);


		//Hooks boolean fields
		//1: archive page
		//2: single page
		//3: cart page
		add_settings_field(
			'archive_enable', // ID
			'Enable price module on overview (archive) pages', // Title
			array( $this, 'archive_enable_callback' ), // Callback
			$this->option_page_name, // Page
			$this->option_section_name // Section
		);
		add_settings_field(
			'single_enable', // ID
			'Enable price module on product details (single) pages', // Title
			array( $this, 'single_enable_callback' ), // Callback
			$this->option_page_name, // Page
			$this->option_section_name // Section
		);
		add_settings_field(
			'cart_enable', // ID
			'Enable price module on cart page', // Title
			array( $this, 'cart_enable_callback' ), // Callback
			$this->option_page_name, // Page
			$this->option_section_name // Section
		);

        add_settings_field(
			'country', // ID
			'Select Country', // Title
			array( $this, 'country_callback' ), // Callback
			$this->option_page_name, // Page
			$this->option_section_name  // Section
		);

//		// Ad type / class
//		add_settings_field(
//			'type_class', // ID
//			'Choose type of button (example: 1 or 2)', // Title
//			array( $this, 'type_class_callback' ), // Callback
//			$this->option_page_name, // Page
//			$this->option_section_name  // Section
//		);
		// Custom CSS
		add_settings_field(
			'style',  // ID
			'Add custom styles (CSS). use classname cc-wrapper',  // Title
			array( $this, 'style_callback' ), // Callback
			$this->option_page_name, // Page
			$this->option_section_name // Section
		);
	}


	/**
     *
	 */
	public function sanitize($input)
	{

		$new_input = array();
		//Hooks
		//1: archive page
		//2: single page
		//3: cart page
		if(isset($input['archive_enable']))
		    $new_input['archive_enable'] = $input['archive_enable'];

		if( isset($input['single_enable']))
			$new_input['single_enable'] = absint(intval($input['single_enable']));

		if(isset($input['cart_enable']))
			$new_input['cart_enable'] = absint(intval($input['cart_enable']));

        if(isset($input['country']))
            $new_input['country'] = sanitize_textarea_field($input['country']);

//		// Ad type / class
//		if(isset($input['type_class']))
//			$new_input['type_class'] = absint(intval($input['type_class']));

		// Custom CSS
		if(isset($input['style']))
			$new_input['style'] = sanitize_textarea_field($input['style']);

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Setup the CreditClick Price Module button options here.';
	}


	/**
	 *
	 */
	public function archive_enable_callback()
	{
		printf(
			'<input type="checkbox" id="archive_enable" name="%s[archive_enable]" %s />',
			$this->option_name,
			isset( $this->options['archive_enable']) ? 'checked' : ''
		);
	}
	/**
	 *
	 */
	public function single_enable_callback()
	{
		printf(
			'<input type="checkbox" id="single_enable" name="%s[single_enable]" %s />',
			$this->option_name,
			isset( $this->options['single_enable']) ? 'checked' : ''
		);
	}
	/**
	 *
	 */
	public function cart_enable_callback()
	{
		printf(
			'<input type="checkbox" id="cart_enable" name="%s[cart_enable]" %s />',
			$this->option_name,
			isset( $this->options['cart_enable']) ? 'checked' : ''
		);
	}

    /**
     *
     */
    public function  country_callback() {
        $items = array("NL", "DE");
        echo "<select id='country' name='" . $this->option_name . "[country]'>";
        foreach($items as $item) {
            $selected = ($this->options['country'] == $item) ? 'selected="selected"' : '';
            echo "<option value='$item' $selected>$item</option>";
        }
        echo "</select>";
    }


	/**
	 *
	 */
//	public function type_class_callback()
//	{
//		printf(
//			'<input type="number" id="type_class" name="%s[type_class]" value="%s" />',
//			$this->option_name,
//			isset( $this->options['type_class']) ? esc_attr( $this->options['type_class']) : ''
//		);
//	}

	/**
	 *
	 */
	public function style_callback() {
		printf(
			'<textarea id="style" name="%s[style]">%s</textarea>',
			$this->option_name,
			isset( $this->options['style']) ? esc_attr( $this->options['style'] ) : ''
		);
	}
}