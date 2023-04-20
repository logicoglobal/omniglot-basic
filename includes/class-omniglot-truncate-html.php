<?php

/**
 * The class to truncate or split html string.
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/includes
 */

/**
 * Truncate or split large html string.
 */
class Omniglot_Truncate_HTML {

	/**
	 * Length of returned string.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	protected $length = 80;

	/**
	 * Length of maximum returned string.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	protected $max_length = 100;

	/**
	 * The content to be truncated or splitted.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * The array containing the string splitted by html tags.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $lines_splitted_by_html_tags = array();

	/**
	 * Sets the length.
	 *
	 * @since 1.0.0
	 *
	 * @param int $length Length of returned string.
	 *
	 * @return void
	 */
	public function set_length( $length ) {
		$this->length = $length;
	}

	/**
	 * Gets the length.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_length() {
		return $this->length;
	}

	/**
	 * Sets the max length.
	 *
	 * @since 1.0.0
	 *
	 * @param int $length Length of maximum returned string.
	 *
	 * @return void
	 */
	public function set_max_length( $length ) {
		$this->max_length = $length;
	}

	/**
	 * Gets the maximum length.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_max_length() {
		return $this->max_length;
	}

	/**
	 * Sets the content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content The content to be truncated or splitted.
	 *
	 * @return void
	 */
	public function set_content( $content ) {
		$this->content = $content;
		$this->set_lines_splitted_by_html_tags(
			$this->get_splittted_by_html_tags( $this->content )
		);
	}

	/**
	 * Gets the content.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_content() {
		return $this->content;
	}

	/**
	 * Sets the scanable lines splitted by html tags.
	 *
	 * @since 1.0.0
	 *
	 * @param array $array The array containging the scanable lines splitted by html tags.
	 *
	 * @return void
	 */
	public function set_lines_splitted_by_html_tags( $array ) {
		$this->lines_splitted_by_html_tags = $array;
	}

	/**
	 * Gets the scanable lines splitted by html tags.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_lines_splitted_by_html_tags() {
		return $this->lines_splitted_by_html_tags;
	}

	/**
	 * Splits all html-tags to scanable lines.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string Gets the scanable lines splitted by html tags.
	 *
	 * @return array
	 */
	public function get_splittted_by_html_tags( $string ) {
		preg_match_all( '/(<.+?>)?([^<>]*)/s', $string, $lines, PREG_SET_ORDER );

		return $lines;
	}

	/**
	 * Truncate or split the html string.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $split Optional. If true return the array containing the splitted string
	 *                       and last index used from scanable lines array.
	 *
	 * @return string|array The truncated string or the array containing the splitted string
	 *                      and last index used from scanable lines array.
	 */
	public function truncate( $split = false ) {
		$truncate                = '';
		$open_tags               = array();
		$total_length            = 0;
		$last_full_section       = '';
		$last_index              = null;
		$last_full_section_index = null;

		$lines = $this->get_lines_splitted_by_html_tags();

		foreach ( $lines as $index => $line_matchings ) {
			$closing_tag = false;

			if ( ! empty( $line_matchings[1] ) ) {
				if ( // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
					preg_match(
						'/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is',
						$line_matchings[1]
					)
				) {
					// If it's an "empty element" with or without xhtml-conform closing slash.
					// Do nothing.
				} elseif ( preg_match( '/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings ) ) {
					// If tag is a closing tag.

					// If we are closing the parent element.
					if ( count( $open_tags ) === 1 ) {
						$closing_tag = true;
					}

					// Delete tag from $open_tags list.
					$pos = array_search( $tag_matchings[1], $open_tags ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict

					if ( false !== $pos ) {
						unset( $open_tags[ $pos ] );
					}
				} elseif ( preg_match( '/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings ) ) {
					// Ff tag is an opening tag.

					// Add tag to the beginning of $open_tags list.
					array_unshift( $open_tags, strtolower( $tag_matchings[1] ) );
				}

				$truncate .= $line_matchings[1];
			}

			// Calculate the length of the plain text part of the line; handle entities as one character.
			$content_length = strlen( preg_replace( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2] ) );

			if ( $total_length + $content_length > $this->get_length() ) {
				// If we have parent tag as not closed then add the remaining string.
				if ( $open_tags ) {
					$parent_tag = end( $open_tags );

					if ( $parent_tag ) {
						$parent_element = $this->add_remaining_string_from_parent_element( $parent_tag, $index );

						$truncate     .= $parent_element['remaining'];
						$last_index    = $parent_element['last_index'];
						$total_length += $parent_element['length'];

						// If total length is greater than the max length then return the last parent element.
						if ( $total_length > $this->get_max_length() && $last_full_section ) {
							$truncate   = $last_full_section;
							$last_index = $last_full_section_index;

							break;
						}
					}
				} else {
					// Length ends after the closing tag and closing tag have space/line breaks.
					$truncate  .= $line_matchings[2];
					$last_index = $index;
				}

				break;
			} else {
				$truncate .= $line_matchings[2];

				if ( $closing_tag ) {
					$last_full_section       = $truncate;
					$last_full_section_index = $index;
				}

				$total_length += $content_length;
				$last_index    = $index;
			}
		}

		if ( true === $split ) {
			return array(
				'truncate'   => $truncate,
				'last_index' => $last_index,
			);
		}

		return $truncate;
	}

	/**
	 * Adds the remaining string from the parent level element.
	 *
	 * @since 1.0.0
	 *
	 * @param string $parent_tag The parent tag to be used to retrive the remaining content.
	 * @param int    $start      The key of the lines array to start finding the remaining content.
	 *
	 * @return array
	 */
	public function add_remaining_string_from_parent_element( $parent_tag, $start ) {
		$new_lines = array();
		$lines     = $this->get_lines_splitted_by_html_tags();
		end( $lines );
		$last_line_index = key( $lines );

		for ( $i = $start; $i <= $last_line_index; $i++ ) {
			$new_lines[ $i ] = $lines[ $i ];
		}

		$increment  = 0;
		$remaining  = '';
		$length     = 0;
		$last_index = -1;

		foreach ( $new_lines as $index => $line_matchings ) {
			if ( 0 === $increment ) {
				$remaining .= $line_matchings[2];
			} else {
				// Calculate the length of the plain text part of the line; handle entities as one character.
				$content_length = strlen( preg_replace( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2] ) );

				$remaining .= $line_matchings[1];
				$remaining .= $line_matchings[2];

				$length += $content_length;
			}

			$tag = "</${parent_tag}>";

			$last_index = $index;

			$increment++;

			if ( $tag === $line_matchings[1] ) {
				break;
			}
		}

		return array(
			'remaining'  => $remaining,
			'last_index' => $last_index,
			'length'     => $length,
		);
	}

	/**
	 * Gets the truncated content.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_truncated() {
		$content = $this->get_content();

		// If the plain text is shorter than the maximum length, return the whole text.
		if ( strlen( preg_replace( '/<.*?>/', '', $content ) ) <= $this->get_max_length() ) {
			return $this->get_content();
		}

		return $this->truncate();
	}

	/**
	 * Gets the splitted contents into an array.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_splitted() {
		$splitted = array();

		$content = $this->get_content();

		// If the plain text is shorter than the maximum length, return the whole text.
		if ( strlen( preg_replace( '/<.*?>/', '', $content ) ) <= $this->get_max_length() ) {
			return array( $this->get_content() );
		}

		$split = true;
		$lines = $this->get_lines_splitted_by_html_tags();
		end( $lines );
		$last_line_index = key( $lines );

		while ( ! empty( $lines ) ) {
			$cropped    = $this->truncate( $split );
			$splitted[] = $cropped['truncate'];
			$last_index = $cropped['last_index'];

			$new_lines = array();

			for ( $i = $last_index + 1; $i <= $last_line_index; $i++ ) {
				$new_lines[ $i ] = $lines[ $i ];
			}

			$lines = $new_lines;

			$this->set_lines_splitted_by_html_tags( $lines );
		}

		return $splitted;
	}

}
