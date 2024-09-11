$(function () {
	gsap.to('.bg', {
		autoAlpha: 1,
		ease: 'power1.in',
		scrollTrigger: {
			trigger: '.sc-about',
			start: 'bottom bottom',
			end: 'bottom 100px',
			scrub: 1,
		},
	});

	const cursorW = 124;
	const cursorH = 124;

	$(window).mousemove(function (e) {
		const xVal = e.clientX;
		const yVal = e.clientY;

		gsap.to('.cursor', {
			x: xVal,
			y: yVal,
			width: cursorW,
			height: cursorH,
			xPercent: -50,
			yPercent: -50,
		});
	});

	$(window).mousemove(() => {
		$('.cursor').css({ display: 'flex' });
	});

	$('span').mousemove(function (e) {
		if ($(e.target).parents('.sc-about').length) {
			$('.cursor').addClass('gradient');
			$('.cursor').html('<span>scroll</span>');
		} else if ($(e.target).parents('.sc-contact').length) {
			$('.cursor').addClass('gradient');
			$('.cursor').html('<span>contact me</span>');
		}
	});

	$('span').mouseout(function (e) {
		$('.cursor').removeClass('gradient');
		$('.cursor').html('<span>scroll</span>');
	});

	$('h1').mousemove(function (e) {
		$('.cursor').addClass('gradient');
		$('.cursor').html('<span>scroll</span>');
	});

	$('h1').mouseout(function (e) {
		$('.cursor').removeClass('gradient');
		$('.cursor').html('<span>scroll</span>');
	});

	$('a').mousemove(function (e) {
		if ($(e.target).parents('.works-item').length) {
			$('.cursor').addClass('fff');
			$('.cursor').html('<span>click</span>');
		} else if ($(e.target).parents('.addr-box').length) {
			$('.cursor').addClass('gradient');
			$('.cursor').html('<span>contact me</span>');
		} else if ($(e.target).parents('.link-box').length) {
			$('.cursor').addClass('gradient');
			$('.cursor').html('<span>click</span>');
		} else {
			$('.cursor').html('<span>scroll</span>');
		}
	});

	$('a').mouseout(function (e) {
		$('.cursor').removeClass('fff');
		$('.cursor').removeClass('gradient');
		$('.cursor').html('<span>scroll</span>');
	});

	// orbit

	var path = anime.path('.orbit-context path');
	var motionPath = anime({
		targets: '.square',
		easing: 'easeInQuad',
		translateX: path('x'),
		translateY: path('y'),
		rotate: path('angle'),
		duration: 8000,
		loop: true,
	});

	// 	// text reveal animation

	gsap.to('.orbit .text-area p', {
		backgroundPositionX: '0%',
		stagger: 1,
		scrollTrigger: {
			trigger: '.orbit .text-area p',
			scrub: 1,
			start: 'top center',
			end: 'bottom center',
		},
	});

	const contactText = $('.sc-contact span');
	contactText.each((idx, el) => {
		gsap.from(el, {
			yPercent: 100,
			duration: 1,
			scrollTrigger: {
				trigger: '.sc-contact',
				start: 'top center',
				toggleActions: 'restart none restart none',
			},
		});
	});

	const aboutText = $('.sc-about span');
	aboutText.each((idx, el) => {
		gsap.from(el, {
			yPercent: 100,
			duration: 1,
			scrollTrigger: {
				trigger: '.sc-about',
				start: 'top center',
				toggleActions: 'restart none restart restart',
			},
		});
	});
});
