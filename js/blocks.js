/**
 * File blocks.js.
 *
 * Editor registration for the cjw/* blocks. The blocks are rendered
 * server-side (the template parts stay the source of truth); the editor only
 * shows a live preview via ServerSideRender. Titles, descriptions, icons and
 * keywords are localized in the PHP registration (inc/blocks.php) and merged
 * with these settings.
 *
 * The six front page blocks have no settings. The three verhuur blocks carry
 * their section heading as an attribute, so the editor owns the wording -- and
 * can clear it to write a heading block of their own above instead.
 *
 * The wp global comes from the editor script dependencies; it is declared
 * in eslint.config.js.
 */
(() => {
	const registerBlockType = wp.blocks.registerBlockType;
	const createElement = wp.element.createElement;
	const useBlockProps = wp.blockEditor.useBlockProps;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const TextControl = wp.components.TextControl;
	const ServerSideRender = wp.serverSideRender;
	const __ = wp.i18n.__;

	const blocks = [
		{ name: "cjw/hero", title: "CJW Hero" },
		{ name: "cjw/intro", title: "CJW Introductie" },
		{ name: "cjw/jaarthema", title: "CJW Jaarthema & aftellen" },
		{ name: "cjw/cards", title: "CJW Snel-naar kaarten" },
		{ name: "cjw/photos", title: "CJW Kampplakboek" },
		{ name: "cjw/sponsors", title: "CJW Sponsors" },
		{
			name: "cjw/verhuur-past-het",
			title: "CJW Past het?",
			heading: "Past het?",
		},
		{
			name: "cjw/verhuur-kaarten",
			title: "CJW Verhuurkaarten",
			heading: "Wat verhuren we zoal?",
		},
		{
			name: "cjw/verhuur-tabel",
			title: "CJW Verhuurtabel",
			heading: "Alles op een rij",
		},
	];

	blocks.forEach((block) => {
		const hasHeading = typeof block.heading === "string";

		registerBlockType(block.name, {
			apiVersion: 3,
			title: block.title,
			category: "cjw",
			attributes: hasHeading
				? { titel: { type: "string", default: block.heading } }
				: {},
			supports: {
				html: false,
				multiple: false,
				reusable: false,
			},
			edit: function Edit({ attributes, setAttributes }) {
				const preview = createElement(ServerSideRender, {
					block: block.name,
					attributes: attributes,
				});

				if (!hasHeading) {
					return createElement("div", useBlockProps(), preview);
				}

				return createElement(
					"div",
					useBlockProps(),
					createElement(
						InspectorControls,
						null,
						createElement(
							PanelBody,
							{ title: __("Kop", "cjw-brummen") },
							createElement(TextControl, {
								__nextHasNoMarginBottom: true,
								__next40pxDefaultSize: true,
								label: __("Kop boven deze sectie", "cjw-brummen"),
								help: __(
									"Laat leeg om geen kop te tonen, bijvoorbeeld als je er zelf een kop-blok boven zet.",
									"cjw-brummen",
								),
								value: attributes.titel,
								onChange: (titel) => setAttributes({ titel }),
							}),
						),
					),
					preview,
				);
			},
			save: () => null,
		});
	});
})();
