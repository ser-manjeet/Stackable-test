<?php
/**
 * Creates minified css via PHP.
 */

if ( ! class_exists( 'STK_BLOCKS_CSS' ) ) {

	/**
	 * Class to create a minified css output.
	 */
	class STK_BLOCKS_CSS {

		/**
		 * The css selector that you're currently adding rules to
		 *
		 * @access protected
		 * @var string
		 */
		protected $_selector = '';

		/**
		 * Associative array of Google Fonts to load.
		 *
		 * Do not access this property directly, instead use the `get_google_fonts()` method.
		 *
		 * @var array
		 */
		protected static $google_fonts = array();

		/**
		 * Stores the final css output with all of its rules for the current selector.
		 *
		 * @access protected
		 * @var string
		 */
		protected $_selector_output = '';

		/**
		 * Can store a list of additional selector states which can be added and removed.
		 *
		 * @access protected
		 * @var array
		 */
		protected $_selector_states = array();

		/**
		 * Stores a list of css properties that require more formating
		 *
		 * @access private
		 * @var array
		 */
		private $_special_properties_list = array(
			'padding',
			'margin',
			'box-shadow',
			'top',
			'left',
			'bottom',
			'right',
			'border-width',
		);

		/**
		 * Stores all of the rules that will be added to the selector
		 *
		 * @access protected
		 * @var string
		 */
		protected $_css = '';

		/**
		 * Stores all of the custom css.
		 *
		 * @access protected
		 * @var string
		 */
		protected $_css_string = '';

		/**
		 * The string that holds all of the css to output
		 *
		 * @access protected
		 * @var string
		 */
		protected $_output = '';

		/**
		 * Stores media queries
		 *
		 * @var null
		 */
		protected $_media_query = null;

		/**
		 * The string that holds all of the css to output inside of the media query
		 *
		 * @access protected
		 * @var string
		 */
		protected $_media_query_output = '';

		/**
		 * Sets a selector to the object and changes the current selector to a new one
		 *
		 * @access public
		 *
		 * @param string $selector - the css identifier of the html that you wish to target.
		 *
		 * @return $this
		 * @since  1.0
		 *
		 */
		public function set_selector( $selector = '' ) {
			// Render the css in the output string everytime the selector changes.
			if ( '' !== $this->_selector ) {
				$this->add_selector_rules_to_output();
			}
			$this->_selector = $selector;

			return $this;
		}

		/**
		 * Sets css string for final output.
		 *
		 * @param string $string - the css string.
		 *
		 * @return $this
		 */
		public function add_css_string( $string ) {
			$this->_css_string .= $string;

			return $this;
		}

		/**
		 * Wrapper for the set_selector method, changes the selector to add new rules
		 *
		 * @access public
		 *
		 * @param string $selector the css selector.
		 *
		 * @return $this
		 * @since  1.0
		 *
		 * @see    set_selector()
		 */
		public function change_selector( $selector = '' ) {
			return $this->set_selector( $selector );
		}

		/**
		 * Adds a pseudo class to the selector ex. :hover, :active, :focus
		 *
		 * @access public
		 *
		 * @param  $state - the selector state
		 * @param reset - if true the        $_selector_states variable will be reset
		 *
		 * @return $this
		 * @since  1.0
		 *
		 */
		public function add_selector_state( $state, $reset = true ) {
			if ( $reset ) {
				$this->reset_selector_states();
			}
			$this->_selector_states[] = $state;

			return $this;
		}

		/**
		 * Adds multiple pseudo classes to the selector
		 *
		 * @access public
		 *
		 * @param array $states - the states you would like to add
		 *
		 * @return $this
		 * @since  1.0
		 *
		 */
		public function add_selector_states( $states = array() ) {
			$this->reset_selector_states();
			foreach ( $states as $state ) {
				$this->add_selector_state( $state, false );
			}

			return $this;
		}

		/**
		 * Removes the selector's pseudo classes
		 *
		 * @access public
		 * @return $this
		 * @since  1.0
		 *
		 */
		public function reset_selector_states() {
			$this->add_selector_rules_to_output();
			if ( ! empty( $this->_selector_states ) ) {
				$this->_selector_states = array();
			}

			return $this;
		}

		/**
		 * Adds a new rule to the css output
		 *
		 * @access public
		 *
		 * @param string $property - the css property.
		 * @param string $value - the value to be placed with the property.
		 * @param string $prefix - not required, but allows for the creation of a browser prefixed property.
		 *
		 * @return $this
		 * @since  1.0
		 *
		 */
		public function add_rule( $property, $value, $prefix = null ) {
			$property = self::camel2dash( $property );
			$format = is_null( $prefix ) ? '%1$s:%2$s;' : '%3$s%1$s:%2$s;';
			if ( $value && ! empty( $value ) || 0 === $value || '0' === $value ) {
				$this->_css .= sprintf( $format, $property, $value . " !important", $prefix );
			}

			return $this;
		}

		/**
		 * Adds browser prefixed rules, and other special rules to the css output
		 *
		 * @access public
		 *
		 * @param string $property - the css property
		 * @param string $value - the value to be placed with the property
		 *
		 * @return $this
		 * @since  1.0
		 *
		 */
		public function add_special_rules( $property, $value ) {
			// Switch through the property types and add prefixed rules.
			switch ( $property ) {
				case 'border-top-left-radius':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( 'border-radius-topleft', $value, '-moz-' );
					break;

				case 'border-top-right-radius':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( 'border-radius-topright', $value, '-moz-' );
					break;

				case 'border-bottom-left-radius':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( 'border-radius-bottomleft', $value, '-moz-' );
					break;

				case 'border-bottom-right-radius':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( 'border-radius-bottomright', $value, '-moz-' );
					break;

				case 'padding':
					$this->add_sizing_property( $property, $value );
					break;

				case 'margin':
					$this->add_sizing_property( $property, $value );
					break;

				case 'border':
					$this->add_border_property( $property, $value );
					break;

				case 'content':
					$this->add_rule( $property, sprintf( '%s', $value ) );
					break;

				case 'flex':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					break;
				case 'top':
				case 'left':
				case 'bottom':
				case 'right':
					if( $value[$property] || 0 === $value[$property] ) {
						$this->add_rule( $property, "$value[$property]px" );
					}
					break;
				case 'border-width':
					if( isset( $value['top'] ) ) {
						$this->add_rule( 'border-top-width', $value['top'] . "px" );
					}
					if( isset( $value['bottom'] ) ) {
						$this->add_rule( 'border-bottom-width', $value['bottom'] . "px" );
					}
					if( isset( $value['left'] ) ) {
						$this->add_rule( 'border-left-width', $value['left'] . "px" );
					}
					if( isset( $value['right'] ) ) {
						$this->add_rule( 'border-right-width', $value['right'] . "px" );
					}
					break;

				default:
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( $property, $value, '-moz-' );
					break;
			}

			return $this;
		}

		/**
		 * @param string $property
		 * @param array $value
		 */
		public function add_unit_value_rule( $property, $value, $index_key = 'value' ) {
			if ( is_array( $value ) ) {
				if ( isset( $value[ $index_key ] ) && '' !== $value[ $index_key ] && null !== $value[ $index_key ] ) {
					$unit = isset( $value['unit'] ) && $value['unit'] ? $value['unit'] : 'px';
					$this->add_rule( $property, sprintf( '%1$s%2$s', $value[ $index_key ], $unit ) );
				}
			} else if ( is_string( $value ) || is_numeric( $value ) ) {

				$this->add_rule( $property, $value );
			}

			return $this;
		}

		/**
		 * Adds a css property with value to the css output
		 *
		 * @access public
		 *
		 * @param string $property - the css property
		 * @param string $value - the value to be placed with the property
		 * @param string optional $unit_value_pair - pass true or value_key when value contain [ 'value' => '10', unit => 'px' ] values structure
		 *
		 * @return $this
		 * @since  1.0
		 *
		 */
		public function add_property( $property, $value = null, $unit = false ) {
			if ( null === $value || '' === $value ) {
				return $this;
			}

			$property = self::camel2dash($property);

			if ( $unit ) {
				$this->add_rule( $property, sprintf( '%1$s%2$s', $value, $unit ) );
			} else {
				if ( in_array( $property, $this->_special_properties_list ) ) {
					$this->add_special_rules( $property, $value );
				} else {
					$this->add_rule( $property, $value );
				}
			}

			return $this;
		}
		

		/**
		 * Adds multiple properties with their values to the css output
		 *
		 * @access public
		 *
		 * @param array $properties - a list of properties and values
		 *
		 * @return $this
		 * @since  1.0
		 *
		 */
		public function add_properties( $properties ) {
			foreach ( (array) $properties as $property => $value ) {
				$this->add_property( $property, $value );
			}

			return $this;
		}

		/**
		 * Sets a media query in the class
		 *
		 * @param string $value
		 *
		 * @return $this
		 * @since  1.1
		 */
		public function start_media_query( $value ) {
			// Add the current rules to the output
			$this->add_selector_rules_to_output();

			// Add any previous media queries to the output
			if ( $this->has_media_query() ) {
				$this->add_media_query_rules_to_output();
			}

			// Set the new media query
			$this->_media_query = $value;

			return $this;
		}

		/**
		 * Stops using a media query.
		 *
		 * @return $this
		 * @since  1.1
		 * @see    start_media_query()
		 *
		 */
		public function stop_media_query() {
			return $this->start_media_query( null );
		}

		/**
		 * Gets the media query if it exists in the class
		 *
		 * @return string|int|null
		 * @since  1.1
		 */
		public function get_media_query() {
			return $this->_media_query;
		}

		/**
		 * Checks if there is a media query present in the class
		 *
		 * @return boolean
		 * @since  1.1
		 */
		public function has_media_query() {
			if ( ! empty( $this->get_media_query() ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Adds the current media query's rules to the class' output variable
		 *
		 * @return $this
		 * @since  1.1
		 */
		private function add_media_query_rules_to_output() {
			if ( ! empty( $this->_media_query_output ) ) {
				$this->_output .= sprintf( '@media all and %1$s{%2$s}', $this->get_media_query(), $this->_media_query_output );

				// Reset the media query output string.
				$this->_media_query_output = '';
			}

			return $this;
		}

		/**
		 * Adds the current selector rules to the output variable
		 *
		 * @access private
		 * @return $this
		 * @since  1.0
		 *
		 */
		private function add_selector_rules_to_output() {
			if ( ! empty( $this->_css ) ) {
				$this->prepare_selector_output();
				$selector_output = sprintf( '%1$s{%2$s}', $this->_selector_output, $this->_css );

				if ( $this->has_media_query() ) {
					$this->_media_query_output .= $selector_output;
					$this->reset_css();
				} else {
					$this->_output .= $selector_output;
				}

				// Reset the css.
				$this->reset_css();
			}

			return $this;
		}

		/**
		 * Prepares the $_selector_output variable for rendering
		 *
		 * @access private
		 * @return $this
		 * @since  1.0
		 *
		 */
		private function prepare_selector_output() {
			if ( ! empty( $this->_selector_states ) ) {
				// Create a new variable to store all of the states.
				$new_selector = '';

				foreach ( (array) $this->_selector_states as $state ) {
					$format       = end( $this->_selector_states ) === $state ? '%1$s%2$s' : '%1$s%2$s,';
					$new_selector .= sprintf( $format, $this->_selector, $state );
				}
				$this->_selector_output = $new_selector;
			} else {
				$this->_selector_output = $this->_selector;
			}

			return $this;
		}


		/**
		 * Outputs a string if set.
		 *
		 * @param array $string a string setting.
		 * @param string $unit if needed add unit.
		 *
		 * @return string
		 */
		public function render_string( $string = null, $unit = null ) {
			if ( empty( $string ) ) {
				return false;
			}
			$string = $string . ( isset( $unit ) && ! empty( $unit ) ? $unit : '' );

			return $string;
		}

		/**
		 * Outputs a string if set.
		 *
		 * @param array $number a string setting.
		 * @param string $unit if needed add unit.
		 *
		 * @return string
		 */
		public function render_number( $number = null, $unit = null ) {
			if ( ! is_numeric( $number ) ) {
				return false;
			}
			$number = $number . ( isset( $unit ) && ! empty( $unit ) ? $unit : '' );

			return $number;
		}

		/**
		 * Spacing for padding and margin
		 *
		 * @param string $property css name
		 * @param object $value css value [ top => '', bottom => '', left => '', right => '', unit => '' ]
		 */
		public function add_sizing_property( $property, $value ) {
			if ( is_string( $value ) ) {
				$this->add_rule( $property, $value );
			} else {
				if( isset( $value['top'] ) ) {
					$this->add_rule( $property . "-top", $value['top'] . 'px' );
				}
				if( isset( $value['bottom'] ) ) {
					$this->add_rule( $property . "-bottom", $value['bottom'] . 'px' );
				}
				if( isset( $value['left'] ) ) {
					$this->add_rule( $property . "-left", $value['left'] . 'px' );
				}
				if( isset( $value['right'] ) ) {
					$this->add_rule( $property . "-right", $value['right'] . 'px' );
				}
			}

			return $this;
		}

		/**
		 * Resets the css variable
		 *
		 * @access private
		 * @return void
		 * @since  1.1
		 *
		 */
		private function reset_css() {
			$this->_css = '';

			return;
		}

		/**
		 * Returns the google fonts array from the compiled css.
		 *
		 * @access public
		 * @return string
		 * @since  1.0
		 *
		 */
		public function fonts_output() {
			return self::$google_fonts;
		}

		/**
		 * Returns the minified css in the $_output variable
		 *
		 * @access public
		 * @return string
		 * @since  1.0
		 *
		 */
		public function css_output() {
			// Add current selector's rules to output
			$this->add_selector_rules_to_output();

			$this->_output .= $this->_css_string;

			// Output minified css
			return $this->_output;
		}

		public function minify_css( $style = '' ) {
			if ( empty( $style ) ) {
				$style;
			}
			$style = preg_replace( '/\s{2,}/s', ' ', $style );
			$style = preg_replace( '/\s*([:;{}])\s*/', '$1', $style );
			$style = preg_replace( '/;}/', '}', $style );

			return $style;
		}

		public function custom_css( $custom_css = '', $replace_selector = '' ) {
			if ( empty( $replace_selector ) || empty( $custom_css ) ) {
				return $this;
			}
			$this->_output .= $this->minify_css( preg_replace( '/selector/i', $replace_selector, $custom_css ) );

			return $this;
		}

		public static function camel2dash($string) {
			return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $string));
		}

	}
}
