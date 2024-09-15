AOS.init({
    duration: 500,
    easing: 'ease-in-out',
    offset: 100,
});


// fix_btn
$('.top_btn').on('click', function() {
    $('html, body').animate({
        scrollTop: 0
    }, 1000);
})

$(window).on('scroll', function () {
    var scrollTop = $(window).scrollTop();
        

    if($('.gift_sec').offset().top <= scrollTop){
        $('.fix_btn_wrap').addClass('on');
    } else {
        $('.fix_btn_wrap').removeClass('on');
    }


});



$(document).ready(function(){

    var swiper = new Swiper('.realtime_list_slider', {
        slidesPerView: 2,
        slidesPerColumn: 3,
        slidesPerGroup: 6,
        spaceBetween: 10,
        
        navigation: {                       
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },

        breakpoints: {

           1201: {
                scrollbar: {
                    el: ".swiper-scrollbar",
                    
                  },
                observer: true,
                observeParents: true,
            },
        },
        autoplay: {
            delay: 2500,
        },

        observer: true,
        observeParents: true,
    });


    var buildSlider = $('.build_slider').slick({
        autoplay: true,
        autoplaySpeed: 4000,
        fade: true,
        lazyLoad: 'ondemand',

        responsive: [{  
						breakpoint: 851, 
						settings: {
							arrows: false,
                            dots: true,
						} 
					}
            
            ]
    });
    $('.build_slider').on('afterChange', function(event, slick, currentSlide, nextSlide) {
        currentSlide = currentSlide + 1;
        $('.build_slider li:nth-child('+currentSlide +') .text_box').addClass('active');
        $('.build_slider li:nth-child('+currentSlide +') .red_box01').addClass('active');
    })

    //slider 

    $(window).scroll(function(e){

        e.preventDefault();

        if($(window).width()){
            if($(window).scrollTop() > (  $('.build_slider').offset().top - $(window).height() + ( $(window).height()/4 )  ) ){
                $('.build_slider li:nth-child(1) .text_box').addClass('active');
                $('.build_slider li:nth-child(1) .red_box01').addClass('active');
            }
        } 
    });


})

scrollAni();

function scrollAni() {
    $('a[href^="#link"').on('click', function () {
        let href = $(this).attr('href');
        let hrefTop = $(href).offset().top;

        $('body, html').animate({
            'scrollTop': hrefTop
        });

        $('html').removeClass('gnb_open');

    })
}

// 참가영상 더보기
$('.more_btn').on('click', function (){
    $('.realtime_list_more').addClass('on');
});


// popup_wrap
$(function () {
    // ifhome 노래방 아이템 사용 방법
    $('#link01').on('click', function () {
        $('#pop01').fadeIn();
        $('html').addClass('overflow');
    })

    // ifhome 만들기 및 URL 복사 방법
    $('#link02').on('click', function () {
        $('#pop02').fadeIn();
        $('html').addClass('overflow');
    })
    
    // 이벤트 응모하기 
    $('#link03').on('click', function () {
        $('#pop03').fadeIn();
        $('html').addClass('overflow');
    })

    // 이벤트 응모완료
    $('.complete').on('click', function () {
        $('#pop03').fadeOut();
        $('#pop04').fadeIn();
        $('html').addClass('overflow');
    })
    
    // 팝업창 닫기
    $('.dim').on('click', function () {
        $('.popup_wrap').fadeOut();
        $('html').removeClass('overflow');
    })

    // 모바일 팝업창 닫기
    $('.closed').on('click', function () {
        $('.popup_wrap').fadeOut();
        $('html').removeClass('overflow');
    })
})

// 약관 체크
$(function(){
    // 전체 동의
    $('#agreeAll').click('change', function () {
    if ($(this).is(':checked')) {
        $('.agree_list .chk_box input[type="checkbox"]').prop('checked', true);
    } else {
        $('.agree_list .chk_box input[type="checkbox"]').prop('checked', false);
    }
    })

    $('.agree_list .chk_box input[type="checkbox"]').on('change', function () {
        var eleCount = $('.agree_list .chk_box input[type="checkbox"]').length;
        var checkCount = $('.agree_list .chk_box input[type="checkbox"]:checked').length;
        if (eleCount <= checkCount) {
            $('#agreeAll').prop('checked', true);
        } else {
            $('#agreeAll').prop('checked', false);
        }
    })
})

// 약관동의 아코디언 메뉴
$(function(){
    $(".acod_btn#ac01").click(function(){
        $("#acod01").slideToggle(300);
    });

    $(".acod_btn#ac02").click(function(){
        $("#acod02").slideToggle(300);
    });

    $(".acod_btn#ac03").click(function(){
        $("#acod03").slideToggle(300);
    });

    $(".acod_btn#ac04").click(function(){
        $("#acod04").slideToggle(300);
    });
});



// 파일첨부 커스텀
(function($){
  
    var $fileBox = null;
    
    $(function() {
      init();
    })
    
    function init() {
      $fileBox = $('.file_wrap');
      fileLoad();
    }
    
    function fileLoad() {
      $.each($fileBox, function(idx){
        var $this = $fileBox.eq(idx),
            $btnUpload = $this.find('[type="file"]'),
            $label = $this.find('.fileupload');
        
        $btnUpload.on('change', function() {
          var $target = $(this),
              fileName = $target.val(),
              $fileText = $target.siblings('.fileinput');
          $fileText.val(fileName);
        })
        
        $btnUpload.on('focusin focusout', function(e) {
          e.type == 'focusin' ?
            $label.addClass('file-focus') : $label.removeClass('file-focus');
        })
        
      })
    }
    
  })(jQuery);
