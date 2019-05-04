const glob = require( 'glob' )
const path = require( 'path' )
const jsonfile = require( 'jsonfile' )

const file = './dist/deprecation-tests.json'

const writeTests = tests => {
	jsonfile.writeFile( file, tests, { spaces: 2, EOL: '\r\n' }, err => {
		if ( err ) {
			console.error( err ) // eslint-disable-line
		}
	} )
}

const tests = glob.sync( './src/block/**/__test__/deprecated/*.js' ).reduce( ( tests, file ) => {
	return tests.concat( require( path.resolve( file ) ) )
}, [] )

writeTests( tests )
console.log( `✔️  Sucessfully writen ${ file }` ) // eslint-disable-line
