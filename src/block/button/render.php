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

$classes = get_classnames(
    'stk-block-button',
    'stk-block',
    $blockAlignmentClass,
    $uniqueBlockClass,
    $responsiveClass,
    $blockClasses
);

$block_attrs = get_block_wrapper_attributes([
    'class' => $classes,
    'data-block-id' => isset( $attributes['uniqueId'] ) ? $attributes['uniqueId'] : false
]);
$button_attrs = [];
if( getAttrName( $attributes, 'linkUrl' ) ) {
    $button_attrs['href'] = getAttrName( $attributes, 'linkUrl' );
}
if( getAttrName( $attributes, 'linkRel' ) ) {
    $button_attrs['rel'] = getAttrName( $attributes, 'linkRel' );
}
if( getAttrName( $attributes, 'linkNewTab' ) ) {
    $button_attrs['target'] = '__blank';
    if( ! str_contains( $button_attrs['rel'], 'noreferrer' )  ) {
        $button_attrs['rel'] = trim( $button_attrs['rel'] . ' noreferrer' );

    }
    if( ! str_contains( $button_attrs['rel'], 'noopener' )  ) {
        $button_attrs['rel'] = trim( $button_attrs['rel'] . ' noopener' );
    }
}
if( getAttrName( $attributes, 'linkTitle' ) ) {
    $button_attrs['title'] = getAttrName( $attributes, 'linkTitle' );
}
$button_class = get_classnames(
    'stk-button stk-link',
    ["stk--hover-effect-" . getAttrName( $attributes, 'buttonHoverEffect' ) => getAttrName( $attributes, 'buttonHoverEffect' ) ]
);
$button_attrs['class'] = $button_class;
$b_attrs = '';

foreach ($button_attrs as $key => $value) {
    $b_attrs = $key . '="' . $value . '" ';
}


$button_attrs = array_map( function($value) {
    return '"=' . $value . '"';
}, $button_attrs )
?>
<div <?php echo $block_attrs; ?>>
    <?php
    $button = 'right' === getAttrName( $attributes, 'iconPosition' ) ? $content . getAttrName( $attributes, 'icon' )  : getAttrName( $attributes, 'icon' ) . $content;

    if( $button ) {
        echo sprintf(
            '<a %1$s>%2$s</a>',
            trim( $b_attrs ),
            $button,
        );
    }
    ?>
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


$css->set_selector(".$uniqueBlockClass .stk-button"); //new selector
$css->add_property('textShadow', getAttrName($attrs, 'textShadow') );
$css->add_property('fontSize', getAttrName($attrs, 'fontSize'), 'px' );
$css->add_property('color', getAttrName($attrs, 'textColor1') );
$css->add_property('fontWeight', getAttrName($attrs, 'fontWeight') );
$css->add_property('textTransform', getAttrName($attrs, 'textTransform') );
$css->add_property('fontStyle', getAttrName($attrs, 'fontStyle') );
$css->add_property('fontFamily', getAttrName($attrs, 'fontFamily') );
$css->add_property('letterSpacing', getAttrName($attrs, 'letterSpacing'), 'px' );



$css->set_selector(".$uniqueBlockClass"); // new selector
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
$css->add_property('columnGap', getAttrName($attrs, 'columnGap'), 'px');
$css->add_property('rowGap', getAttrName($attrs, 'rowGap'), 'px');

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

$css->set_selector(".$uniqueBlockClass .stk-button"); //new selector
$css->add_property('borderStyle', getAttrName($attrs, 'buttonBorderType') );
if( getAttrName($attrs, 'buttonBorderType') ) {
    $css->add_property('borderColor', getAttrName($attrs, 'buttonBorderColor') );
    $css->add_property('borderWidth', getAttrName($attrs, 'buttonBorderWidth') );
}
$css->add_property('backgroundColor', getAttrName($attrs, 'buttonBackgroundColor') );
$css->add_property('margin', getAttrName($attrs, 'buttonMargin') );
$css->add_property('padding', getAttrName($attrs, 'buttonPadding') );


$style = $css->css_output();
if( $style ) {
    ?>
    <style>
        <?php echo $style; ?>
    </style>
    <?php
}
