/**
 * Internal dependencies
 */
import { addAttributes } from './attributes'
import { Style } from './style'
import { Edit } from './edit'
import { Link } from '../link'
import { Icon } from '../icon'

export const Button = props => {
	const {
		className,
	} = props

	return (
		<Link className={ className }>
			<Icon hasLinearGradient={ false } />
			{ props.children }
		</Link>
	)
}

Button.defaultProps = {
	className: '',
}

Button.Content = props => {
	const {
		className,
		attributes,
		...propsToPass
	} = props

	return (
		<Link.Content { ...propsToPass } attributes={ attributes } className={ className }>
			<Icon.Content
				attributes={ attributes }
				hasLinearGradient={ false }
			/>
			{ props.children }
		</Link.Content>
	)
}

Button.Content.defaultProps = {
	className: '',
	attributes: {},
}

Button.InspectorControls = Edit

Button.addAttributes = addAttributes

Button.Style = Style