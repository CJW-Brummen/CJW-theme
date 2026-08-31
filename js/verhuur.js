/**
 * File verhuur.js.
 *
 * The "Past het?" helper on the verhuurpagina.
 *
 * It works out nothing. The form is a plain GET form that the page answers
 * server-side, and every answer it can ever give was worked out in PHP and
 * shipped with the page (see cjw_brummen_rental_fit_table()). All this does is
 * read one out as you type, so the answer arrives without a round trip and can
 * never disagree with the one the server would have given.
 */
(() => {
	const form = document.querySelector(".verhuur-fit__form");
	const field = document.getElementById("verhuur-personen");
	const answer = document.getElementById("verhuur-antwoord");
	const notice = document.getElementById("verhuur-let-op");
	const data = window.cjwVerhuur;

	if (!form || !field || !answer || !notice || !data || !data.answers) {
		return;
	}

	const strings = data.strings || {};

	// The translations are WordPress format strings, so they carry both
	// positional (%1$s) and plain (%d) placeholders, and a translator may
	// reorder them. Filling them here keeps the Dutch in the .po file rather
	// than glued together out of fragments in JavaScript.
	const fill = (template, values) =>
		String(template || "").replace(
			/%(?:(\d+)\$)?[sd]/g,
			(match, position) =>
				String(values[position ? Number(position) - 1 : 0] ?? ""),
		);

	const line = (lead, rest) => {
		const row = document.createElement("div");
		row.className = "verhuur-fit__line";

		const strong = document.createElement("b");
		strong.textContent = lead;

		const span = document.createElement("span");
		span.textContent = rest;

		row.append(strong, span);

		return row;
	};

	const render = () => {
		const people = parseInt(field.value, 10);

		answer.replaceChildren();
		notice.textContent = "";

		if (!Number.isFinite(people) || people < 1) {
			answer.append(line("—", strings.empty || ""));

			return;
		}

		const found = data.answers[people];

		if (!found) {
			answer.append(line(String(data.ceiling), strings.tooMany || ""));

			return;
		}

		found.parts.forEach((part) => {
			answer.append(
				line(part.units + "×", fill(strings.part, [part.title, part.places])),
			);
		});

		answer.append(
			line(
				String(found.places),
				found.spare
					? fill(strings.spare, [found.spare])
					: strings.exact || "",
			),
		);

		if (data.mattresses > 0 && people > data.mattresses) {
			notice.textContent = fill(strings.mattressWarning, [
				data.mattresses,
				people,
				people - data.mattresses,
			]);
		}
	};

	form.addEventListener("submit", (event) => {
		event.preventDefault();
		render();
	});

	field.addEventListener("input", render);

	// Only now that the script is certainly working: without it the button is
	// the whole mechanism.
	const button = form.querySelector(".verhuur-fit__submit");

	if (button) {
		button.hidden = true;
	}

	render();
})();
