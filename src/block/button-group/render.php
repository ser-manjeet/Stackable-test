<?php
/**
 * Block Dynamic Render Callback
 * 
 * $attributes (array): The block attributes.
 * $content (string): The block default content.
 * $block (WP_Block): The block instance
 * 
 */
$blockAlignmentClass = getAlignmentClasses( $attributes );
$responsiveClass     = getResponsiveClasses( $attributes );
$uniqueBlockClass    = getUniqueBlockClass( $attributes );
$blockClasses        = getBlockClasses( $attributes );
$contentClassNames   = get_classnames( 
    'stk-row',
    'stk-inner-blocks',
    $blockAlignmentClass,
    'stk-block-content',
    'stk-button-group',
);
$classes = get_classnames(
    'stk-block',
    $uniqueBlockClass,
    $responsiveClass,
    $blockClasses
);

$block_attrs = get_block_wrapper_attributes([
    'class' => $classes,
    'data-block-id' => isset( $attributes['uniqueId'] ) ? $attributes['uniqueId'] : false
]);
?>
<div <?php echo $block_attrs; ?>>
    <div class="<?php echo $contentClassNames; ?>">
        <?php echo $content; ?>
    </div>
</div>


<?php
/**
 * Block Front Style
 */
$attrs = $attributes;
$css = new STK_BLOCKS_CSS();

// alignment
$css->set_selector(".stk--block-align-" . $attributes['uniqueId']);
$css->add_property('align-self', getAttrName($attrs, 'columnAlign'));
$css->add_property('alignItems', getAttrName($attrs, 'rowAlign'));
$css->add_property('alignItems', getAttrName($attrs, 'innerBlockJustify'));
$css->add_property('justify-content', getAttrName($attrs, 'innerBlockAlign'));

$css->add_property('columnGap', getAttrName($attrs, 'columnGap'), 'px');
$css->add_property('rowGap', getAttrName($attrs, 'rowGap'), 'px');


$css->set_selector(".$uniqueBlockClass .stk-button-group");
$css->add_property('flexWrap', getAttrName($attrs, 'flexWrap') );
$css->add_property('flexDirection', getAttrName($attrs, 'buttonAlign') );

$css->set_selector(".$uniqueBlockClass .stk-block");
$css->add_property('flexBasis', getAttrName($attrs, 'buttonAlign') );

$css->set_selector(".$uniqueBlockClass .stk-button-group");
$css->add_property('alignItems', getAttrName($attrs, 'buttonAlign') );

$css->set_selector(".$uniqueBlockClass .stk-block-button, .stk-block-icon-button");
$css->add_property('flex', getAttrName($attrs, 'buttonFullWidth') );

$css->set_selector(".$uniqueBlockClass");
$css->add_property('top', getAttrName($attrs, 'positionNum') );
$css->add_property('left', getAttrName($attrs, 'positionNum') );
$css->add_property('bottom', getAttrName($attrs, 'positionNum') );
$css->add_property('right', getAttrName($attrs, 'positionNum') );
$css->add_property('position', getAttrName($attrs, 'position') );
$css->add_property('opacity', getAttrName($attrs, 'opacity') );
$css->add_property('zIndex', getAttrName($attrs, 'zIndex') );
$css->add_property('overflow', getAttrName($attrs, 'overflow') );
$css->add_property('clear', getAttrName($attrs, 'clear') );
$css->add_property('blockMarginBottom', getAttrName($attrs, 'blockMarginBottom') );

$css->add_property('borderRadius', getAttrName($attrs, 'blockBorderRadius'), 'px' );
$css->add_property('boxShadow', getAttrName($attrs, 'shadow') );
$css->add_property('borderStyle', getAttrName($attrs, 'blockBorderType') );
if( getAttrName($attrs, 'blockBorderType') ) {
    $css->add_property('borderColor', getAttrName($attrs, 'blockBorderColor') );
    $css->add_property('borderWidth', getAttrName($attrs, 'blockBorderWidth') );
}

$css->add_property('minHeight', getAttrName($attrs, 'blockHeight'), 'px' );
$css->add_property('alignItems', getAttrName($attrs, 'verticalAlign') );
$css->add_property('maxWidth', getAttrName($attrs, 'blockWidth'), 'px' );
$css->add_property('margin', getAttrName($attrs, 'blockMargin') );
$css->add_property('padding', getAttrName($attrs, 'blockPadding') );

if( getAttrName( $attrs, 'hasBackground' ) ) {
    $css->add_property('backgroundColor', getAttrName($attrs, 'blockBackgroundColor') );
}
$style = $css->css_output();
if( $style ) {
    ?>
    <style>
        <?php echo $style; ?>
    </style>
    <?php
}



