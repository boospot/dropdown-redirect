const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;


// https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-registration/
registerBlockType("create-block/gutenpridestatic", {
	title: __("Like & Subscribe", "podkit"),
	icon: 'smiley',
	category: "widgets",

	// https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/
	edit() {
		return (
			<div className="podkit-block podkit-static">
				<div className="podkit-info">
					<h3 className="podkit-title">
						{__("The Binaryville Podcast", "podkit")}
					</h3>
					<div className="podkit-cta">
						<a href="#">{__("Like & Subscribe!", "podkit")}</a>
					</div>
				</div>
			</div>
		);
	},
	save() {
		return (
			<div className="podkit-block podkit-static">
				<div className="podkit-info">
					<h3 className="podkit-title">
						{__("The Binaryville Podcast", "podkit")}
					</h3>
					<div className="podkit-cta">
						<a href="/subscribe">{__("Like & Subscribe!", "podkit")}</a>
					</div>
				</div>
			</div>
		);
	}
});
