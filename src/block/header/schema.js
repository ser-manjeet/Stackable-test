/**
 * External dependencies
 */
import {
	BlockDiv,
	BlockLink,
	Alignment,
	EffectsAnimations,
	ConditionalDisplay,
	CustomAttributes,
	ContainerDiv,
	CustomCSS,
	Responsive,
	Advanced,
	MarginBottom,
} from '~stackable/block-components'
import { AttributeObject } from '~stackable/util'
import { version as VERSION } from 'stackable'

export const attributes = ( version = VERSION ) => {
	const attrObject = new AttributeObject()

	BlockDiv.addAttributes( attrObject )
	MarginBottom.addAttributes( attrObject )
	ContainerDiv.addAttributes( attrObject, { hasDefaultContainer: true } )
	Alignment.addAttributes( attrObject )
	Advanced.addAttributes( attrObject )
	EffectsAnimations.addAttributes( attrObject )
	CustomAttributes.addAttributes( attrObject )
	ConditionalDisplay.addAttributes( attrObject )
	CustomCSS.addAttributes( attrObject )
	Responsive.addAttributes( attrObject )
	BlockLink.addAttributes( attrObject )

	attrObject.add( {
		attributes: {
			contentAlign: {
				type: 'string',
				default: 'center',
			},
		},
		versionAdded: '3.0.0',
		versionDeprecated: '',
	} )

	return attrObject.getMerged( version )
}

export default attributes( VERSION )