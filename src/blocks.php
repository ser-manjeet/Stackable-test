<?php
/**
 * Blocks Loader
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since 	2.17.2
 * @package Stackable
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'stackable_get_metadata_by_folders' ) ) {
	/**
	 * Function for getting the block.json metadata
	 * based on folder names array.
	 *
	 * @array array folders
	 * @array string handle
	 * @return array metadata
	 */
	function stackable_get_metadata_by_folders( $block_folders, $handle = 'metadata' ) {
		$blocks = array();
		$blocks_dir = dirname( __FILE__ ) . '/block';
		if ( ! file_exists( $blocks_dir ) ) {
			return $blocks;
		}

		foreach ( $block_folders as $folder_name ) {
			$block_json_file = $blocks_dir . '/' . $folder_name . '/block.json';
			if ( ! file_exists( $block_json_file ) ) {
				continue;
			}

			$metadata = json_decode( file_get_contents( $block_json_file ), true );
			array_push( $blocks, array_merge( $metadata, array( 'block_json_file' => $block_json_file ) ) );
		}

		return $blocks;
	}
}

if ( ! function_exists( 'stackable_get_stk_block_folders_metadata' ) ) {
	function stackable_get_stk_block_folders_metadata() {
	/**
	 * folders containing stackable blocks without inner blocks.
	 */
	$stk_block_folders = array(
		'button',
		'count-up',
		'countdown',
		'divider',
		'heading',
		'icon-button',
		'icon-list',
		'icon',
		'image',
		'number-box',
		'map',
		'progress-bar',
		'progress-circle',
		'separator',
		'spacer',
		'subtitle',
		'table-of-contents',
		'tab-labels',
		'text',
	);

	return stackable_get_metadata_by_folders( $stk_block_folders, 'stk-block-folders' );
	}
}

if ( ! function_exists( 'stackable_get_stk_wrapper_block_folders_metadata' ) ) {
	function stackable_get_stk_wrapper_block_folders_metadata() {
		/**
		 * folders containing stackable blocks with inner blocks.
		 */
		$stk_wrapper_block_folders = array(
			'accordion',
			'blockquote',
			'button-group',
			'call-to-action',
			'card',
			'column',
			'columns',
			'expand',
			'feature-grid',
			'feature',
			'hero',
			'icon-box',
			'icon-label',
			'image-box',
			'notification',
			'posts',
			'price',
			'tabs',
			'tab-content',
			'pricing-box',
			'team-member',
			'testimonial',
			'timeline',
			'video-popup',
			'horizontal-scroller',
		);

		return stackable_get_metadata_by_folders( $stk_wrapper_block_folders, 'stk-wrapper-block-folders' );
	}

}

if ( ! function_exists( 'stackable_register_blocks' ) ) {
	function stackable_register_blocks() {
		// Blocks directory may not exist if working from a fresh clone.
		$blocks_dir = dirname( __FILE__ ) . '/block';
		if ( ! file_exists( $blocks_dir ) ) {
			return;
		}

		$blocks_metadata = array_merge(
			stackable_get_stk_block_folders_metadata(),
			stackable_get_stk_wrapper_block_folders_metadata()
		);

		foreach ( $blocks_metadata as $metadata ) {
			$registry = WP_Block_Type_Registry::get_instance();
			if ( $registry->is_registered( $metadata['name'] ) ) {
				$registry->unregister( $metadata['name'] );
			}

			$register_options = apply_filters( 'stackable.register-blocks.options',
				// This automatically enqueues all our styles and scripts.
				array(
					'style' => 'ugb-style-css', // Frontend styles.
					// 'script' => 'ugb-block-frontend-js', // Frontend scripts.
					'editor_script' => 'ugb-block-js', // Editor scripts.
					'editor_style' => 'ugb-block-editor-css', // Editor styles.
				),
				$metadata['name'],
				$metadata
			);

			register_block_type_from_metadata( $metadata['block_json_file'], $register_options );
		}
	}
	add_action( 'init', 'stackable_register_blocks' );
}

/**
 * Allow our blocks to display post excerpts
 * when calling `get_the_excerpt` function.
 *
 * @see https://developer.wordpress.org/reference/hooks/excerpt_allowed_blocks/
 */
if ( ! function_exists( 'stackable_add_excerpt_wrapper_blocks' ) ) {
	/**
	 * Register stackable blocks with inner blocks.
	 */
	function stackable_add_excerpt_wrapper_blocks( $allowed_wrapper_blocks ) {
		$blocks_dir = dirname( __FILE__ ) . '/block';
		if ( ! file_exists( $blocks_dir ) ) {
			return $allowed_wrapper_blocks;
		}

		$allowed_stackable_wrapper_blocks = array();
		$blocks_metadata = stackable_get_stk_wrapper_block_folders_metadata();

		foreach ( $blocks_metadata as $metadata ) {
			array_push( $allowed_stackable_wrapper_blocks, $metadata['name'] );
		}

		return array_merge( $allowed_stackable_wrapper_blocks, $allowed_wrapper_blocks );
	}

	add_filter( 'excerpt_allowed_wrapper_blocks', 'stackable_add_excerpt_wrapper_blocks' );
}

if ( ! function_exists( 'stackable_add_excerpt_blocks' ) ) {
	/**
	 * Register "unit" stackable blocks (blocks without inner blocks)
	 */
	function stackable_add_excerpt_blocks( $allowed_blocks ) {
		$blocks_dir = dirname( __FILE__ ) . '/block';
		if ( ! file_exists( $blocks_dir ) ) {
			return $allowed_blocks;
		}

		$allowed_stackable_blocks = array();
		$blocks_metadata = stackable_get_stk_block_folders_metadata();

		foreach ( $blocks_metadata as $metadata ) {
			array_push( $allowed_stackable_blocks, $metadata['name'] );
		}

		return array_merge( $allowed_stackable_blocks, $allowed_blocks );
	}

	add_filter( 'excerpt_allowed_blocks', 'stackable_add_excerpt_blocks' );
}



if ( ! function_exists( 'stackable_blocks_hex2rgba' ) ) {
	/**
	 * Hex to RGBA
	 *
	 * @param string $hex string hex code.
	 * @param number $alpha alpha number.
	 */
	function stackable_blocks_hex2rgba( $hex, $alpha ) {
		if ( empty( $hex ) ) {
			return '';
		}
		if ( 'transparent' === $hex ) {
			return $hex;
		}
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgba = 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $alpha . ')';
		return $rgba;
	}
}


if ( ! function_exists( 'get_classnames' ) ) {
	/**
	 * Array to string function for html classes
	 */

	function get_classnames( ...$classes ) {
		$result = [];

		foreach ( $classes as $key => $class ) {
			if ( is_array( $class ) ) {
				// Recursively handle nested arrays
				$result[] = get_classnames( ...$class );
			} elseif ( is_string( $class ) && ! empty( $class ) ) {
				$result[] = $class;
			} elseif ( is_bool( $class ) && true === $class ) {
				$result[] = $key;
			}
		}

		return implode(' ', $result);
	}
}


if ( ! function_exists( 'getAlignmentClasses' ) ) {
	/**
	 * function to get block aligment classes using blocks attributes
	 *
	 * @param array $attributes
	 * @return string
	 */
	function getAlignmentClasses($attributes) {
		$innerBlocksClass = [];
	
		if ( ! empty( $attributes['contentAlign'] ) ) {
			$innerBlocksClass[] = "has-text-align-{$attributes['contentAlign']}";
		}
	
		if ( ! empty( $attributes['contentAlignTablet'] ) ) {
			$innerBlocksClass[] = "has-text-align-{$attributes['contentAlignTablet']}-tablet";
		}
	
		if ( ! empty( $attributes['contentAlignMobile'] ) ) {
			$innerBlocksClass[] = "has-text-align-{$attributes['contentAlignMobile']}-mobile";
		}
	
		if ( ! empty( $attributes['innerBlockOrientation'] ) && $attributes['innerBlockOrientation'] === 'horizontal' ) {
			$innerBlocksClass[] = 'stk--block-horizontal-flex';
		}
	
		if ( ! empty( $attributes['innerBlockJustify'] ) || ! empty( $attributes['innerBlockAlign'] ) ) {
			$innerBlocksClass[] = 'stk--column-flex';
		}
	
		if ( ! empty( $attributes['uniqueId'] ) ) {
			$innerBlocksClass[] = "stk--block-align-{$attributes['uniqueId']}";
		}
	
		if ( ! empty( $attributes['rowAlign'] ) || ! empty( $attributes['rowAlignTablet'] ) || ! empty( $attributes['rowAlignMobile'] ) ) {
			$innerBlocksClass[] = "stk--block-align-{$attributes['uniqueId']}";
		}
	
		return implode(' ', $innerBlocksClass);
	}
}

if ( ! function_exists( 'getResponsiveClasses' ) ) {
	function getResponsiveClasses($attributes) {
		$responsiveClasses = [
			'stk--hide-desktop' => isset( $attributes['hideDesktop'] ) ? $attributes['hideDesktop'] : '',
			'stk--hide-tablet' => isset( $attributes['hideTablet'] ) ? $attributes['hideTablet'] : '',
			'stk--hide-mobile' => isset( $attributes['hideMobile'] ) ? $attributes['hideMobile'] : ''
		];
	
		return get_classnames($responsiveClasses);
	}
}

if ( ! function_exists( 'getUniqueBlockClass' ) ) {
	function getUniqueBlockClass( $attributes ) {
		return isset( $attributes['uniqueId'] ) ? "stk-" . $attributes['uniqueId'] : '';
	}
}

if ( ! function_exists( 'getTypographyClasses' ) ) {
	function getTypographyClasses($attributes = []) {
		return get_classnames([
			'stk--is-gradient' => isset( $attributes['textColorType'] ) && $attributes['textColorType'] === 'gradient',
			'has-text-color' => ! empty( $attributes['textColor1'] ),
			getAttrName( $attributes, 'textColorClass' ),
			sprintf('has-text-align-%s', getAttrName( $attributes, 'textAlign' )) => getAttrName( $attributes, 'textAlign' ),
			sprintf('has-text-align-%s-tablet', getAttrName( $attributes, 'textAlignTablet' )) => getAttrName( $attributes, 'textAlignTablet' ),
			sprintf('has-text-align-%s-mobile', getAttrName( $attributes, 'textAlignMobile' )) => getAttrName( $attributes, 'textAlignMobile' ),
		]);
	}
	
}

if ( ! function_exists( 'getAttrName' ) ) {
	function getAttrName( $attributes = [], $key ) {
		if ( isset( $attributes[ $key ] ) ) {
			return $attributes[ $key ];
		}
		return '';
	}
}

if ( ! function_exists( '' ) ) {
	function getBlockClasses($attributes = []) {
		$classes = [
			'stk-block-background'          => ! empty( $attributes['hasBackground'] ),
			'stk--block-margin-top-auto'    => isset( $attributes['blockMargin']['top'] ) && $attributes['blockMargin']['top'] === 'auto',
			'stk--block-margin-bottom-auto' => isset( $attributes['blockMargin']['bottom'] ) && $attributes['blockMargin']['bottom'] === 'auto',
			'stk--has-lightbox'             => ! empty( $attributes['blockLinkHasLightbox'] ) || ! empty( $attributes['linkHasLightbox'] ),
			'stk--has-background-overlay'   => ! empty( $attributes['hasBackground'] ) && (  ( isset($attributes['backgroundColorType']) && $attributes['backgroundColorType'] === 'gradient' ) ||  ! empty( $attributes['backgroundUrl'] ) ||  ! empty( $attributes['backgroundUrlTablet'] ) ||  ! empty( $attributes['backgroundUrlMobile'] ) ),
		];
		return $classes;
	}
}