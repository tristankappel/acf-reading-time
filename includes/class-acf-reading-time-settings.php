<?php
/**
 * Settings page and options handling.
 *
 * @package ACF_Reading_Time
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the settings page under the Settings menu.
 */
class ACF_Reading_Time_Settings {

	/**
	 * Option name used in the options table.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'acf_reading_time_settings';

	/**
	 * Settings group / page slug.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'acf-reading-time';

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Return all settings merged with defaults.
	 *
	 * @return array{prefix:string,postfix:string,words_per_minute:int}
	 */
	public function get_settings() {
		$defaults = array(
			'prefix'           => 'Reading time:',
			'postfix'          => 'min read',
			'words_per_minute' => 200,
		);

		$settings = get_option( self::OPTION_NAME, array() );

		return wp_parse_args( is_array( $settings ) ? $settings : array(), $defaults );
	}

	/**
	 * Get a single setting value.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	public function get( $key, $default = '' ) {
		$settings = $this->get_settings();

		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}

	/**
	 * Add the options page under Settings.
	 *
	 * @return void
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'ACF Reading Time', 'acf-reading-time' ),
			__( 'ACF Reading Time', 'acf-reading-time' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register the setting, section and fields.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			self::PAGE_SLUG,
			self::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize' ),
				'default'           => array(),
			)
		);

		add_settings_section(
			'acf_reading_time_main',
			__( 'Reading Time Settings', 'acf-reading-time' ),
			array( $this, 'render_section_intro' ),
			self::PAGE_SLUG
		);

		add_settings_field(
			'prefix',
			__( 'Prefix (before time)', 'acf-reading-time' ),
			array( $this, 'render_prefix_field' ),
			self::PAGE_SLUG,
			'acf_reading_time_main'
		);

		add_settings_field(
			'postfix',
			__( 'Postfix (after time)', 'acf-reading-time' ),
			array( $this, 'render_postfix_field' ),
			self::PAGE_SLUG,
			'acf_reading_time_main'
		);

		add_settings_field(
			'words_per_minute',
			__( 'Words per minute', 'acf-reading-time' ),
			array( $this, 'render_wpm_field' ),
			self::PAGE_SLUG,
			'acf_reading_time_main'
		);
	}

	/**
	 * Sanitize the submitted settings.
	 *
	 * @param array $input Raw input.
	 * @return array Sanitized settings.
	 */
	public function sanitize( $input ) {
		$input = is_array( $input ) ? $input : array();

		$wpm = isset( $input['words_per_minute'] ) ? absint( $input['words_per_minute'] ) : 200;
		if ( $wpm < 1 ) {
			$wpm = 200;
		}

		return array(
			'prefix'           => isset( $input['prefix'] ) ? sanitize_text_field( $input['prefix'] ) : '',
			'postfix'          => isset( $input['postfix'] ) ? sanitize_text_field( $input['postfix'] ) : '',
			'words_per_minute' => $wpm,
		);
	}

	/**
	 * Section intro text.
	 *
	 * @return void
	 */
	public function render_section_intro() {
		echo '<p>' . esc_html__( 'Configure how the reading time is displayed. Use the shortcode [reading_time] inside a post to output the value.', 'acf-reading-time' ) . '</p>';
	}

	/**
	 * Render the prefix field.
	 *
	 * @return void
	 */
	public function render_prefix_field() {
		$value = $this->get( 'prefix' );
		printf(
			'<input type="text" name="%1$s[prefix]" value="%2$s" class="regular-text" />',
			esc_attr( self::OPTION_NAME ),
			esc_attr( $value )
		);
		echo '<p class="description">' . esc_html__( 'Text shown before the reading time, e.g. "Reading time:".', 'acf-reading-time' ) . '</p>';
	}

	/**
	 * Render the postfix field.
	 *
	 * @return void
	 */
	public function render_postfix_field() {
		$value = $this->get( 'postfix' );
		printf(
			'<input type="text" name="%1$s[postfix]" value="%2$s" class="regular-text" />',
			esc_attr( self::OPTION_NAME ),
			esc_attr( $value )
		);
		echo '<p class="description">' . esc_html__( 'Text shown after the reading time, e.g. "min read".', 'acf-reading-time' ) . '</p>';
	}

	/**
	 * Render the words-per-minute field.
	 *
	 * @return void
	 */
	public function render_wpm_field() {
		$value = $this->get( 'words_per_minute', 200 );
		printf(
			'<input type="number" min="1" step="1" name="%1$s[words_per_minute]" value="%2$s" class="small-text" />',
			esc_attr( self::OPTION_NAME ),
			esc_attr( $value )
		);
		echo '<p class="description">' . esc_html__( 'Average reading speed used for the calculation (default: 200).', 'acf-reading-time' ) . '</p>';
	}

	/**
	 * Render the settings page wrapper.
	 *
	 * @return void
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( self::PAGE_SLUG );
				do_settings_sections( self::PAGE_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
