/**
 * External dependencies
 */
import { omit } from 'lodash'

/**
 * Wordpress dependencies
 */
import {
	registerStore, dispatch, select,
} from '@wordpress/data'
import domReady from '@wordpress/dom-ready'
import { loadPromise, models } from '@wordpress/api'

// Include all the stored state.
const DEFAULT_STATE = {
	defaultColors: [],
	useStackableColorsOnly: false,
	stackableColors: [],
	isInitializing: true,
}

const STORE_ACTIONS = {
	updateSettings: ( payload = {} ) => ( {
		type: 'UPDATE_SETTINGS',
		payload: omit( payload, 'type' ),
	} ),
}

const STORE_SELECTORS = {
	getSettings: state => state,
}

const STORE_REDUCER = ( state = DEFAULT_STATE, action ) => {
	switch ( action.type ) {
		case 'UPDATE_SETTINGS': {
			return {
				...state,
				...action.payload,
			}
		}
		default: {
			return state
		}
	}
}

registerStore( 'stackable/global-colors', {
	reducer: STORE_REDUCER,
	actions: STORE_ACTIONS,
	selectors: STORE_SELECTORS,
} )

// Load all our settings into our store.
domReady( () => {
	loadPromise.then( () => {
		const settings = new models.Settings()

		settings.fetch().then( response => {
			const {
				stackable_global_colors_palette_only: useStackableColorsOnly,
				stackable_global_colors: _stackableColors,
			} = response
			const stackableColors = _stackableColors[ 0 ] || []
			const stackableColorSlugs = stackableColors.map( color => color.slug )

			const colors = select( 'core/block-editor' ).getSettings().colors
			const defaultColors = colors.filter( ( { slug } ) => ! stackableColorSlugs.includes( slug ) )

			dispatch( 'stackable/global-colors' ).updateSettings( {
				defaultColors,
				useStackableColorsOnly,
				stackableColors,
				isInitializing: false,
			} )
		} )
	} )
} )