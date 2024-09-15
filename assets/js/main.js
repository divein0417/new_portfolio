$(function () {
	gsap.to('.bg', {
		autoAlpha: 1,
		ease: 'power1.in',
		scrollTrigger: {
			trigger: '.visual-sec',
			start: 'bottom bottom',
			end: 'bottom 100px',
			scrub: 1,
		},
	});

	gsap.to('.visual-sec .visual-icon img', {
		yPercent: 10,
		opacity: 1,
		stagger: 0.2,
	})

	dataChild = document.querySelectorAll('[data-y]');

	dataChild.forEach(element => {
		y = (element.dataset.y) ? element.dataset.y : 0;
		x = (element.dataset.x) ? element.dataset.x : 0;
		r = (element.dataset.r) ? element.dataset.r : 0;

		gsap.to(element, {
			scrollTrigger: {
				trigger: '.sc-visual',
				start: 'top top',
				end: 'bottom top',
				scrub: 1,
			},
			yPercent: y,
			xPercent: x,
			rotation: r
		})
	});

	gsap.registerPlugin(ScrollTrigger);
	// left의 소개글은 스크롤에 따라 움직이지 않도록 고정된 상태로 처리
	gsap.utils.toArray('.about-sec .about-info p').forEach((p, index) => {
		gsap.from(p, {
			y: 100,
			opacity: 0,
			duration: 1,
			ease: "power3.out",
			scrollTrigger: {
				trigger: p,
				start: "top 90%",
				end: "top 50%",
				scrub: true,
				once: false,
				stagger: 0.1
			}
		});
	});

	gsap.utils.toArray('.about-sec .about-skil img').forEach((img, index) => {
		gsap.from(img, {
			y: 100,
			opacity: 0,
			duration: 1,
			ease: "power3.out",
			scrollTrigger: {
				trigger: img,
				start: "top 90%",
				end: "top 50%",
				scrub: true,
				once: false,
				stagger: 0.1
			}
		});
	});



	// orbit

	// var path = anime.path('.orbit-context path');
	// var motionPath = anime({
	// 	targets: '.square',
	// 	easing: 'easeInQuad',
	// 	translateX: path('x'),
	// 	translateY: path('y'),
	// 	rotate: path('angle'),
	// 	duration: 8000,
	// 	loop: true,
	// });

	// 	// text reveal animation

	gsap.to('.current p', {
		backgroundPositionX: '0%',
		stagger: 1,
		scrollTrigger: {
			trigger: '.tit-point',
			scrub: 1,
			start: 'top center',
			end: 'bottom center',
		},
	});

	$(document).ready(function () {
		$(".project-list li").on("touchstart mouseenter", function () {
			$(this).addClass("active");
		}).on("touchend mouseleave", function () {
			$(this).removeClass("active");
		});
	});


});