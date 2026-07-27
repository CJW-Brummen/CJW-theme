/**
 * File theme.js.
 *
 * Behavior for the CJW design: the off-canvas mobile menu, the
 * scroll-reveal of front page sections and the camp countdown.
 */
( function () {
	'use strict';

	const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	// Mobile menu drawer. Behaves as a modal dialog: while open, the rest
	// of the page is inert and body scroll is locked.
	const toggle = document.querySelector( '.menu-toggle' );
	const drawer = document.getElementById( 'menu-drawer' );

	if ( toggle && drawer ) {
		const closeButton = drawer.querySelector( '.menu-drawer__close' );
		const toggleLabel = toggle.querySelector( '.menu-toggle__label' );
		const mobileNav = window.matchMedia( '(max-width: 920px)' );
		const pageRegions = Array.from(
			document.querySelectorAll( '#page > *' )
		).filter( ( element ) => element !== drawer );

		const setOpen = ( open ) => {
			drawer.classList.toggle( 'is-open', open );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );

			if ( toggleLabel ) {
				toggleLabel.textContent = open
					? toggle.dataset.labelClose
					: toggle.dataset.labelOpen;
			}

			if ( open ) {
				drawer.removeAttribute( 'inert' );
				pageRegions.forEach( ( element ) =>
					element.setAttribute( 'inert', '' )
				);
				document.body.style.overflow = 'hidden';
				if ( closeButton ) {
					closeButton.focus();
				}
			} else {
				pageRegions.forEach( ( element ) =>
					element.removeAttribute( 'inert' )
				);
				document.body.style.overflow = '';
				if (
					document.activeElement &&
					drawer.contains( document.activeElement ) &&
					mobileNav.matches
				) {
					toggle.focus();
				}
				drawer.setAttribute( 'inert', '' );
			}
		};

		toggle.addEventListener( 'click', () => {
			setOpen( ! drawer.classList.contains( 'is-open' ) );
		} );

		if ( closeButton ) {
			closeButton.addEventListener( 'click', () => setOpen( false ) );
		}

		// Close when a menu link is chosen.
		drawer.addEventListener( 'click', ( event ) => {
			if ( event.target.closest( 'a' ) ) {
				setOpen( false );
			}
		} );

		document.addEventListener( 'keydown', ( event ) => {
			if (
				event.key === 'Escape' &&
				drawer.classList.contains( 'is-open' )
			) {
				setOpen( false );
			}
		} );

		// Close when the viewport grows past the desktop breakpoint, so the
		// drawer never lingers next to the desktop navigation.
		mobileNav.addEventListener( 'change', ( event ) => {
			if ( ! event.matches && drawer.classList.contains( 'is-open' ) ) {
				setOpen( false );
			}
		} );
	}

	// Scroll-reveal for front page sections.
	const revealTargets = document.querySelectorAll( '[data-reveal]' );

	if (
		revealTargets.length &&
		! reducedMotion.matches &&
		'IntersectionObserver' in window
	) {
		const observer = new IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'is-revealed' );
						observer.unobserve( entry.target );
					}
				} );
			},
			{ threshold: 0.1 }
		);

		revealTargets.forEach( ( element ) => {
			element.classList.add( 'reveal' );
			observer.observe( element );
		} );
	}

	// Camp countdown. Rendered server-side, recomputed here so cached
	// pages stay correct; refreshed every minute like the design. The
	// target is an epoch timestamp so PHP and JS share one instant
	// regardless of the visitor's timezone.
	const countdown = document.querySelector( '[data-countdown]' );

	if ( countdown && countdown.dataset.targetTs ) {
		const target = parseInt( countdown.dataset.targetTs, 10 ) * 1000;
		const nowUntil =
			parseInt( countdown.dataset.nowUntilTs, 10 ) * 1000 || target;
		const valueElement = countdown.querySelector( '.fp-countdown__value' );
		const labelElement = countdown.querySelector( '.fp-countdown__label' );

		const render = () => {
			const now = Date.now();
			let value, label;

			if ( now < target ) {
				const days = Math.ceil( ( target - now ) / 86400000 );
				value = String( days );
				label =
					days === 1
						? countdown.dataset.labelOne
						: countdown.dataset.labelMany;
			} else if ( now < nowUntil ) {
				value = countdown.dataset.valueNow;
				label = countdown.dataset.labelNow;
			} else {
				value = countdown.dataset.valueDone;
				label = countdown.dataset.labelDone;
			}

			if ( valueElement ) {
				valueElement.textContent = value;
			}
			if ( labelElement ) {
				labelElement.textContent = label;
			}
		};

		if ( ! isNaN( target ) && valueElement && labelElement ) {
			render();
			window.setInterval( render, 60000 );
		}
	}
}() );
