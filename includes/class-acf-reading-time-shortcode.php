<?php
/**
 * Shortcode handling.
 *
 * @package ACF_Reading_Time
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders the [reading_time] shortcode.
 */
class ACF_Reading_Time_Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'reading_time';

	/**
	 * Settings handler.
	 *
	 * @var ACF_Reading_Time_Settings
	 */
	protected $settings;

	/**
	 * Calculator.
	 *
	 * @var ACF_Reading_Time_Calculator
	 */
	protected $calculator;

	/**
	 * Constructor.
	 *
	 * @param ACF_Reading_Time_Settings $settings Settings handler.
	 */
	public function __construct( ACF_Reading_Time_Settings $settings ) {
		$this->settings   = $settings;
		$this->calculator = new ACF_Reading_Time_Calculator();
	}

	/**
	 * Register the shortcode.
	 *
	 * @return void
	 */
	public function register() {
		add_shortcode( self::TAG, array( $this, 'render' ) );
	}

	/**
	 * Render the shortcode output.
	 *
	 * Supported attributes:
	 * - post_id: Override the post to measure. Defaults to the current post.
	 * - prefix:  Override the configured prefix.
	 * - postfix: Override the configured postfix.
	 * - wpm:     Override the configured words per minute.
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @return string The formatted reading time.
	 */
	public function render( $atts ) {
		$defaults = array(
			'post_id' => get_the_ID(),
			'prefix'  => $this->settings->get( 'prefix' ),
			'postfix' => $this->settings->get( 'postfix' ),
			'wpm'     => $this->settings->get( 'words_per_minute', 200 ),
		);

		$atts = shortcode_atts( $defaults, $atts, self::TAG );

		$post_id = absint( $atts['post_id'] );
		if ( ! $post_id ) {
			return '';
		}

		$minutes = $this->calculator->get_reading_time( $post_id, $atts['wpm'] );

		$parts = array();

		if ( '' !== trim( (string) $atts['prefix'] ) ) {
			$parts[] = esc_html( $atts['prefix'] );
		}

		$parts[] = esc_html( number_format_i18n( $minutes ) );

		if ( '' !== trim( (string) $atts['postfix'] ) ) {
			$parts[] = esc_html( $atts['postfix'] );
		}

		$output = implode( ' ', $parts );

		return sprintf(
			'<span class="acf-reading-time" data-minutes="%1$d">%2$s</span>',
			(int) $minutes,
			$output
		);
	}
}
