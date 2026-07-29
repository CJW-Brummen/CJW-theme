/**
 * File blocks.js.
 *
 * Editor registration for the six cjw/* front page blocks. The blocks are
 * rendered server-side (the template parts in template-parts/front-page/
 * stay the source of truth); the editor only shows a live preview via
 * ServerSideRender. Titles, descriptions, icons and keywords are localized
 * in the PHP registration (inc/blocks.php) and merged with these settings.
 *
 * The wp global comes from the editor script dependencies; it is declared
 * in eslint.config.js.
 */
(() => {
	const registerBlockType = wp.blocks.registerBlockType;
	const createElement = wp.element.createElement;
	const useBlockProps = wp.blockEditor.useBlockProps;
	const ServerSideRender = wp.serverSideRender;

	const blocks = [
		{ name: "cjw/hero", title: "CJW Hero" },
		{ name: "cjw/intro", title: "CJW Introductie" },
		{ name: "cjw/jaarthema", title: "CJW Jaarthema & aftellen" },
		{ name: "cjw/cards", title: "CJW Snel-naar kaarten" },
		{ name: "cjw/photos", title: "CJW Kampplakboek" },
		{ name: "cjw/sponsors", title: "CJW Sponsors" },
	];

	blocks.forEach((block) => {
		registerBlockType(block.name, {
			apiVersion: 3,
			title: block.title,
			category: "cjw",
			supports: {
				html: false,
				multiple: false,
				reusable: false,
			},
			edit: function Edit() {
				return createElement(
					"div",
					useBlockProps(),
					createElement(ServerSideRender, { block: block.name }),
				);
			},
			save: () => null,
		});
	});
})();
