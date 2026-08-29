/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(($) => {
	// Site title and description.
	wp.customize("blogname", (value) => {
		value.bind((to) => {
			$(".site-brand__title").text(to);
		});
	});
	wp.customize("blogdescription", (value) => {
		value.bind((to) => {
			$(".site-brand__tagline").text(to);
		});
	});

	// Header text color.
	wp.customize("header_textcolor", (value) => {
		value.bind((to) => {
			if ("blank" === to) {
				$(".site-brand__title, .site-brand__tagline").css({
					clip: "rect(1px, 1px, 1px, 1px)",
					position: "absolute",
				});
			} else {
				$(".site-brand__title, .site-brand__tagline").css({
					clip: "auto",
					position: "relative",
				});
				$(".site-brand__title, .site-brand__tagline").css({
					color: to,
				});
			}
		});
	});
})(jQuery);
