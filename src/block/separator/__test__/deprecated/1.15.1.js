/**
 * This file contains saved block HTML from older versions.
 * These will be tested if they pass migration.
 * This will be built into the dist folder as `deprecation-tests.json`
 */

module.exports = [
	{
		block: 'Separator',
		version: '1.15.1',
		description: 'Default block',
		html: `<!-- wp:ugb/separator -->
<div class="wp-block-ugb-separator alignfull ugb-separator ugb-separator--design-wave-1" style="margin-top:-1px;margin-bottom:-1px" aria-hidden="true"><div class="ugb-separator__top-pad" style="height:0px"></div><div class="ugb-separator__svg-wrapper" style="height:200px"><div class="ugb-separator__svg-inner"><svg viewbox="0 0 1600 200" xmlns="http://www.w3.org/2000/svg" preserveaspectratio="none" style="transform:scaleX(1)"><path class="wave-1_svg__st2" d="M1341.4 48.9c-182.4 0-254.2 80.4-429.4 80.4-117.8 0-209.7-67.5-393.5-67.5-142.2 0-212.6 38.8-324.6 38.8S-10 64.7-10 64.7V210h1620V102c-110.6-40.2-181-53.1-268.6-53.1z"></path></svg></div></div><div class="ugb-separator__bottom-pad" style="height:0px"></div></div>
<!-- /wp:ugb/separator -->`,
	},
	{
		block: 'Separator',
		version: '1.15.1',
		description: 'Modified settings',
		html: `<!-- wp:ugb/separator {"design":"wave-3","height":276,"flipVertically":true,"backgroundColor":"#cf2e2e","marginTop":179,"marginBottom":-214,"paddingTop":75,"paddingBottom":99,"layer1Color":"#fcb900","layer1Width":2.1,"layer1Flip":true,"layer1Shadow":true} -->
<div class="wp-block-ugb-separator alignfull ugb-separator ugb-separator--design-wave-3 ugb-separator--flip-vertical" style="background-color:#cf2e2e;margin-top:178px;margin-bottom:-215px" aria-hidden="true"><div class="ugb-separator__top-pad" style="height:75px;background:#cf2e2e"></div><div class="ugb-separator__svg-wrapper" style="height:276px"><div class="ugb-separator__svg-inner"><svg viewbox="0 0 1600 200" filter="url(#wave-3-shadow_svg__a)" enablebackground="new 0 0 1600 200" xmlns="http://www.w3.org/2000/svg" preserveaspectratio="none" style="fill:#fcb900;transform:scaleX(2.1) scaleX(-1)"><filter id="wave-3-shadow_svg__a"><feGaussianBlur in="SourceAlpha" stddeviation="4"></feGaussianBlur><feComponentTransfer><feFuncA type="linear" slope="0.4"></feFuncA></feComponentTransfer><feMerge><feMergeNode></feMergeNode><feMergeNode in="SourceGraphic"></feMergeNode></feMerge></filter><path class="wave-3-shadow_svg__st2" d="M1413.6 161.4c-157.9 0-338.2-37.7-495.1-67.4-215.6-40.8-328.1-44.6-418.2-41.1S317 73.4 188.5 102-10 136.2-10 136.2v10s69.9-5.7 198.5-34.3 221.7-45.7 311.8-49.1 202.6.3 418.2 41.1c156.9 29.7 337.2 67.4 495.1 67.4 127.6 0 196.4-19.4 196.4-19.4v-10s-68.8 19.5-196.4 19.5z"></path></svg><svg viewbox="0 0 1600 200" xmlns="http://www.w3.org/2000/svg" preserveaspectratio="none" style="fill:#fcb900;transform:scaleX(2.1) scaleX(-1)"><path class="wave-3_svg__st2" d="M1413.6 161.4c-157.9 0-338.2-37.7-495.1-67.4-215.6-40.8-328.1-44.6-418.2-41.1S317 73.4 188.4 102-10 136.2-10 136.2v74.2h1620v-68.5s-68.8 19.5-196.4 19.5z"></path></svg></div></div><div class="ugb-separator__bottom-pad" style="height:99px;background:#fcb900"></div></div>
<!-- /wp:ugb/separator -->`,
	},
]
