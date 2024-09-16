$(function () {
	
	let vh = window.innerHeight * 0.01;
	document.documentElement.style.setProperty("--vh", vh + "px");
	window.addEventListener("resize", function () {
		let vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty("--vh", vh + "px");
	});
	
	// 비주얼 아이콘
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
				trigger: '.visual-sec',
				start: 'top top',
				end: 'bottom top',
				scrub: 1,
			},
			yPercent: y,
			xPercent: x,
			rotation: r
		})
	});

	// 텍스트 효과
	gsap.to('.current p', {
		backgroundPositionX: '0%',
		stagger: 1,
		scrollTrigger: {
			trigger: '.current',
			scrub: 1,
			start: 'top center',
			end: 'bottom center',
		},
	});


	// 프로젝트 리스트 active
	$(document).ready(function () {
		$(".project-list li").on("touchstart mouseenter", function () {
			$(this).addClass("active");
		}).on("touchend mouseleave", function () {
			$(this).removeClass("active");
		});
	});


	// 페이지가 로드될 때 스크롤 위치 복원
	$(document).ready(function () {
		if (sessionStorage.getItem('scrollPos')) {
			$(window).scrollTop(sessionStorage.getItem('scrollPos'));
		}
	});

	// 페이지가 떠날 때 스크롤 위치 저장
	$(window).on('beforeunload', function () {
		sessionStorage.setItem('scrollPos', $(window).scrollTop());
	});

});