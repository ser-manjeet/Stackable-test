/**
 * This file only contains Premium notices and Premium panel notices.
 */
/**
 * External dependencies
 */
import { ProControl, PanelAdvancedSettings } from '~stackable/components'
import { i18n, showProNotice } from 'stackable'

/**
 * WordPress dependencies
 */
import {
	__, _x, sprintf,
} from '@wordpress/i18n'
import { addFilter } from '@wordpress/hooks'
import { Fragment } from '@wordpress/element'

if ( showProNotice ) {
	addFilter( 'stackable.separator.edit.inspector.style.after', 'stackable/separator', output => {
		return (
			<Fragment>
				{ output }
				<PanelAdvancedSettings
					initialOpen={ false }
					title={ sprintf( _x( '%s %d', 'Nth Title', i18n ), __( 'Layer', i18n ), 2 ) }
					className="ugb--help-tip-separator-layer2"
				>
					<ProControl type="separator" />
				</PanelAdvancedSettings>
				<PanelAdvancedSettings
					initialOpen={ false }
					title={ sprintf( _x( '%s %d', 'Nth Title', i18n ), __( 'Layer', i18n ), 3 ) }
					className="ugb--help-tip-separator-layer3"
				>
					<ProControl type="separator" />
				</PanelAdvancedSettings>
			</Fragment>
		)
	} )
}
