<?php
/**
 * Reading time calculation logic.
 *
 * @package ACF_Reading_Time
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calculates the reading time for a given post, including all ACF fields.
 */
class ACF_Reading_Time_Calculator {

	/**
	 * Calculate the estimated reading time in minutes for a post.
	 *
	 * Counts words from the post content and, when ACF is active, from every
	 * custom field attached to the post (repeaters, groups, flexible content
	 * and nested sub fields are traversed recursively).
	 *
	 * @param int $post_id          The post ID.
	 * @param int $words_per_minute Reading speed in words per minute.
	 * @return int Reading time in minutes (minimum 1).
	 */
	public function get_reading_time( $post_id, $words_per_minute = 200 ) {
		$word_count = $this->get_word_count( $post_id );

		$words_per_minute = absint( $words_per_minute );
		if ( $words_per_minute < 1 ) {
			$words_per_minute = 200;
		}

		$minutes = (int) ceil( $word_count / $words_per_minute );

		return max( 1, $minutes );
	}

	/**
	 * Count all words for a post (content + ACF fields).
	 *
	 * @param int $post_id The post ID.
	 * @return int Total word count.
	 */
	public function get_word_count( $post_id ) {
		$post_id = absint( $post_id );
		$post    = get_post( $post_id );

		if ( ! $post ) {
			return 0;
		}

		$text = $post->post_content;
		$text .= ' ' . $this->get_acf_text( $post_id );

		/**
		 * Filter the raw text used for the word count before counting.
		 *
		 * @param string $text    The combined text.
		 * @param int    $post_id The post ID.
		 */
		$text = apply_filters( 'acf_reading_time_text', $text, $post_id );

		return $this->count_words( $text );
	}

	/**
	 * Gather text from every ACF field attached to a post.
	 *
	 * @param int $post_id The post ID.
	 * @return string Concatenated field text.
	 */
	protected function get_acf_text( $post_id ) {
		if ( ! function_exists( 'get_fields' ) ) {
			return '';
		}

		$fields = get_fields( $post_id );

		if ( empty( $fields ) || ! is_array( $fields ) ) {
			return '';
		}

		return $this->extract_text( $fields );
	}

	/**
	 * Recursively extract readable text from an ACF value of any type.
	 *
	 * Only scalar string/numeric values are collected. Objects such as
	 * WP_Post, WP_Term or attachment arrays are skipped to avoid counting
	 * metadata that is not part of the readable content.
	 *
	 * @param mixed $value The field value.
	 * @return string Extracted text separated by spaces.
	 */
	protected function extract_text( $value ) {
		$text = '';

		if ( is_string( $value ) ) {
			$text .= ' ' . $value;
		} elseif ( is_numeric( $value ) ) {
			$text .= ' ' . (string) $value;
		} elseif ( is_array( $value ) ) {
			foreach ( $value as $item ) {
				$text .= ' ' . $this->extract_text( $item );
			}
		}

		return $text;
	}

	/**
	 * Count the number of words in a piece of text.
	 *
	 * Strips shortcodes and HTML, then counts words in a multibyte-safe way.
	 *
	 * @param string $text The text to count.
	 * @return int Word count.
	 */
	protected function count_words( $text ) {
		if ( ! is_string( $text ) || '' === $text ) {
			return 0;
		}

		$text = strip_shortcodes( $text );
		$text = wp_strip_all_tags( $text );
		$text = html_entity_decode( $text, ENT_QUOTES, get_bloginfo( 'charset' ) );

		// Normalise whitespace.
		$text = preg_replace( '/\s+/u', ' ', $text );
		$text = trim( $text );

		if ( '' === $text ) {
			return 0;
		}

		$words = preg_split( '/\s+/u', $text );

		return is_array( $words ) ? count( $words ) : 0;
	}
}
