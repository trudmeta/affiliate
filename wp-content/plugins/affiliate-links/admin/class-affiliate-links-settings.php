<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Custom Affiliate Links Configuration Page.
 */
class Affiliate_Links_Settings {

	const DEFAULT_TAB = 'general';

	const SETTINGS_PAGE = 'affiliate_links';

	public static $fields;

	public static $tabs;

	/**
	 * List of settings fields.
	 */
	public static function get_default_fields() {
		return array(
			array(
				'name'        => 'slug',
				'title'       => 'Affiliate Link Base',
				'type'        => 'text',
				'tab'         => 'general',
				'default'     => 'go',
				'description' => "You can change the default base part '<strong>/go/</strong>' of your redirect link to something else",
			),
			array(
				'name'        => 'category',
				'title'       => 'Show Category in Link URL',
				'type'        => 'checkbox',
				'tab'         => 'general',
				'description' => 'Show the link category slug in the affiliate link URL',
			),

			array(
				'name'        => 'default',
				'title'       => 'Default URL for Redirect',
				'type'        => 'text',
				'tab'         => '',
				'default'     => get_home_url(),
				'description' => 'Enter the default URL for redirect if correct URL not set',
			),
			array(
				'name'        => 'nofollow',
				'title'       => 'Nofollow Affiliate Links',
				'type'        => 'checkbox',
				'tab'         => 'defaults',
				'description' => 'Add "X-Robots-Tag: noindex, nofollow" to HTTP headers',
			),
			array(
				'name'        => 'redirect',
				'title'       => 'Redirect Type',
				'type'        => 'radio',
				'tab'         => 'defaults',
				'default'     => '301',
				'description' => 'Set redirection HTTP status code',
				'values'      => array(
					'301' => '301 Moved Permanently',
					'302' => '302 Found',
					'307' => '307 Temporary Redirect',
				),
			),

		);
	}

	public static function get_default_tabs() {
		return array(
			'general'  => 'General',
			'defaults' => 'Defaults',
		);
	}

	public static function get_field( $field_name ) {
		foreach ( self::$fields as $field ) {
			if ( $field['name'] == $field_name ) {
				return $field;
			}
		}

		return '';
	}

	public static function get_field_attr( $field_name, $attr ) {
		$field = self::get_field( $field_name );

		if ( $field && isset( $field[ $attr ] ) ) {
			return $field[ $attr ];
		}

		return '';
	}

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		self::$fields = self::get_default_fields();
		self::$tabs   = self::get_default_tabs();

		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'settings_init' ) );
		}
	}

	public static function add_field( $field ) {
		array_push( self::$fields, $field );
	}

	public function remove_filed( $field_name ) {
		foreach ( self::$fields as $key => $field ) {
			if ( $field['name'] == $field_name ) {
				unset( self::$fields[ $key ] );
			}
		}
	}

	public static function add_tab( $tab_name, $tab_title ) {
		self::$tabs[ $tab_name ] = $tab_title;
	}

	public static function remove_tab( $tab_name ) {
		if ( isset( self::$tabs[ $tab_name ] ) ) {
			unset( self::$tabs[ $tab_name ] );
		}
	}

	public function add_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=affiliate-links',
			'Affiliate Links Settings',
			'Settings',
			'manage_options',
			self::SETTINGS_PAGE,
			array( $this, 'affiliate_links_options_page' )
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function settings_init() {
		$current_tab = $this->get_current_tab();

		register_setting( self::SETTINGS_PAGE, 'affiliate_links_settings', array(
			$this,
			'affiliate_links_save_value',
		) );

		add_settings_section( 'affiliate_links_' . $current_tab, '', array(
			$this,
			'render_affiliate_links_' . $current_tab,
		), self::SETTINGS_PAGE );

		foreach ( self::$fields as $field ) {
			if ( isset( $field['tab'] ) && $current_tab == $field['tab'] ) {
				add_settings_field(
					$field['name'],
					__( $field['title'], 'affiliate-links' ),
					array( $this, 'render_' . $field['type'] . '_field' ),
					self::SETTINGS_PAGE,
					self::SETTINGS_PAGE . '_' . $field['tab'],
					$field
				);
			}
		}
	}

	public function affiliate_links_save_value( $input ) {
		$af_setting  = get_option( 'affiliate_links_settings' );
		$field_value = ! empty( $af_setting ) ? $af_setting : array();

		$defaults = array(
			'category'  => array( 'tab' => 'general', 'value' => 0 ),
			'nofollow'  => array( 'tab' => 'defaults', 'value' => 0 ),
			'enable_ga' => array( 'tab' => 'ga', 'value' => 0 ),
		);

		foreach ( $defaults as $name => $data ) {
			if ( $data['tab'] == $this->get_current_tab() && ! isset( $input['category'] ) ) {
				unset( $field_value[ $name ] );
			}
			if ( $data['tab'] == $this->get_current_tab() && ! isset( $input['nofollow'] ) ) {
				unset( $field_value[ $name ] );
			}
			if ( $data['tab'] == $this->get_current_tab() && ! isset( $input['enable_ga'] ) ) {
				unset( $field_value[ $name ] );
			}

		}

		$input = array_replace( $field_value, $input );

		return $input;
	}

	public function render_affiliate_links_general() {
		submit_button();
	}

	public function render_affiliate_links_defaults() {
		submit_button();
	}

	public function render_affiliate_links_ga() {
		submit_button();
	}

	/**
	 * Generate text input field.
	 */
	public function render_text_field( $args ) {
		$value = self::get_option( $args['name'] );
		?>
        <input
                type="text"
                name="affiliate_links_settings[<?php echo esc_attr( $args['name'] ) ?>]"
                value="<?php echo esc_attr( $value ) ?>"
        >
        <p class="description"><?php _e( $args['description'], 'affiliate-links' ) ?></p>
		<?php
	}

	/**
	 * Generate checkbox field.
	 */
	public function render_checkbox_field( $args ) {
		$checked_value = (int) self::get_option( $args['name'] );
		?>
        <input
                type="checkbox"
                id="<?php echo esc_attr( $args['name'] ) ?>"
                name="affiliate_links_settings[<?php echo esc_attr( $args['name'] ) ?>]"
                value="1"
			<?php checked( $checked_value, 1 ) ?>
        >
		<?php _e( $args['description'], 'affiliate-links' ) ?>
		<?php
	}

	/**
	 * Generate radio button fields.
	 */
	public function render_radio_field( $args ) {
		$values = $args['values'];
		reset( $values );
		$checked_value = self::get_option( $args['name'] );

		foreach ( $values as $key => $value ) {
			?>
            <input
                    type="radio"
                    id="<?php echo esc_attr( $args['name'] . '_' . $key ) ?>"
                    name="affiliate_links_settings[<?php echo esc_attr( $args['name'] ) ?>]"
                    value="<?php echo esc_attr( $key ) ?>"
				<?php checked( $checked_value, $key ) ?>
            >
            <label for="<?php echo esc_attr( $args['name'] . '_' . $key ) ?>">
				<?php echo esc_html( $value ) ?>
            </label>
            <br>
			<?php
		}

		echo '<p class="description">' . __( $args['description'], 'affiliate-links' ) . '</p>';
	}

	/**
	 * Plugin settings page HTML.
	 */
	public function affiliate_links_options_page() {
		$this->flush_rules();

		$current_tab = $this->get_current_tab();
		?>
        <h1><?php _e( 'Affiliate Links Settings', 'affiliate-links' ) ?></h1>

        <div id="af_links-wrapper">

            <div class="wrap" id="af_links-primary">
                <form action="options.php" method="post"
                      id="af_links-settings-form">

                    <h2 class="nav-tab-wrapper" id="af_links-nav-tabs">

						<?php foreach ( self::$tabs as $name => $label ): ?>
                            <a href="<?php echo $this->get_tab_url( $name ) ?>"
                               class="nav-tab <?php echo( $current_tab == $name ? 'nav-tab-active' : '' ) ?>">
								<?php _e( $label, 'affiliate-links' ) ?>
                            </a>
						<?php endforeach; ?>
                    </h2>

					<?php settings_fields( self::SETTINGS_PAGE ); ?>

                    <div class="af_links-nav-tab active" id="af_links_general">
						<?php $this->do_settings_sections( self::SETTINGS_PAGE ); ?>
                    </div>
                    <input type="hidden" name="tab"
                           value="<?php echo $this->get_current_tab(); ?>">
                </form>
            </div>
        </div>
		<?php
	}

	/**
	 * Flush permalinks rules.
	 */
	public function flush_rules() {
		if ( isset( $_GET['settings-updated'] ) ) {
			flush_rewrite_rules();
		}
	}

	/**
	 * Retrieve current tab
	 */
	public function get_current_tab() {
		if ( ! empty( $_REQUEST['tab'] ) ) {
			$_tab = (string) $_REQUEST['tab'];

			return $_tab;
		}
		if ( isset( $_GET['tab'] ) ) {
			$_tab = (string) $_GET['tab'];
			if ( isset( self::$tabs[ $_tab ] ) ) {
				return $_tab;
			}
		}

		return self::DEFAULT_TAB;
	}

	function do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return;
		}

		foreach ( (array) $wp_settings_sections[ $page ] as $section ) {

			echo '<table id="af-link-form-table" class="form-table">';
			$this->do_settings_fields( $page, $section['id'] );
			echo '</table>';
			if ( $section['callback'] ) {
				call_user_func( $section['callback'], $section );
			}
		}
	}

	public function do_settings_fields( $page, $section ) {
		global $wp_settings_fields;

		if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
			return;
		}

		foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
			$class = '';

			if ( ! empty( $field['args']['class'] ) ) {
				$class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
			}

			if ( 'ga' == $this->get_current_tab() && 'enable_ga' !== $field['id'] ) {
				echo "<tr{$class} style='display: none'>";
			} else {
				echo "<tr{$class}>";
			}

			if ( ! empty( $field['args']['label_for'] ) ) {
				echo '<th scope="row"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></th>';
			} else {
				echo '<th scope="row">' . $field['title'] . '</th>';
			}

			echo '<td>';
			call_user_func( $field['callback'], $field['args'] );
			echo '</td>';
			echo '</tr>';
		}
	}

	public function get_tab_url( $tab ) {
		return add_query_arg( array(
			'post_type' => Affiliate_Links::$post_type,
			'page'      => self::SETTINGS_PAGE,
			'tab'       => $tab,
		), admin_url( 'edit.php' )
		);
	}

	public static function get_option( $option ) {
		if ( isset( Affiliate_Links::$settings[ $option ] ) ) {
			return Affiliate_Links::$settings[ $option ];
		}

		return self::get_field_attr( $option, 'default' );
	}

}