/**
 * BLOCK: New Block.
 */
/**
 * External dependencies
 */
import { TabsContentIcon } from '~stackable/icons'

/**
 * Internal dependencies
 */
import edit from './edit'
import save from './save'
import schema from './schema'
import metadata from './block.json'

export const settings = {
	...metadata,

	icon: TabsContentIcon,
	attributes: schema,
	supports: {
		anchor: true,

		// If this block contains inner columns, uncomment this if you
		// want to disable the resize handlers of the inner columns.
		stkColumnResize: false,

		// If this block contains inner columns, uncomment this if you
		// want it to support block linking. accross the inner columns.
		stkBlockLinking: true,

		reusable: false,
		stkSaveBlockStyle: false,
	},
	edit,
	save,
}
