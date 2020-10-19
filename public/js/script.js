

jQuery(document).ready(function () {
  /*jQuery('.modal-box').hide();
  jQuery('.sign-in').hide();
  jQuery('.click_button').click(function(){
    jQuery('.modal-box').fadeIn(1000);
    jQuery('.cover-back').addClass('blur');
  })
  jQuery('.close').click(function(){
    jQuery('.modal-box').fadeOut(1000);
    jQuery('.cover-back').removeClass('blur');
  })
  jQuery('.signup .signup-image-link').click(function(){
      jQuery('.signup').fadeOut(1000);
      jQuery('.sign-in').fadeIn(1000);
  })

  jQuery('.sign-in .signup-image-link').click(function(){
      jQuery('.signup').fadeIn(1000);
      jQuery('.sign-in').fadeOut(1000);
  })*/
  jQuery('.modal-box').hide();
  jQuery('.signup .signup-image-link').click(function () {
    jQuery('.signup').removeClass('open');
    jQuery('.sign-in').addClass('open');
  })
  jQuery('.sign-in .signup-image-link').click(function () {
    jQuery('.signup').addClass('open');
    jQuery('.sign-in').removeClass('open');
  })
  jQuery('.click_button').click(function () {
    jQuery('.modal-box').fadeIn(1000);
    jQuery('.cover-back').addClass('blur');
  })
  jQuery('.close').click(function () {
    jQuery('.modal-box').fadeOut(1000);
    jQuery('.cover-back').removeClass('blur');
  })
  jQuery('.edit').click(function () {
    jQuery('.chat-back').toggleClass('open');
  })
  jQuery('.drop').click(function () {
    jQuery('.sub_taps').toggleClass('open');
  })
  jQuery('.edit').click(function () {
    jQuery('.cover_right_back').toggleClass('close');
  })
  jQuery('.round1').click(function () {
    jQuery('.round1').addClass('open');
    jQuery('.round2').removeClass('open');
    jQuery('.round3').removeClass('open');
    jQuery('.round4').removeClass('open');
    jQuery('.round5').removeClass('open');
    
  })
  jQuery('.round2').click(function () {
    jQuery('.round2').addClass('open');
    jQuery('.round1').removeClass('open');
    jQuery('.round3').removeClass('open');
    jQuery('.round4').removeClass('open');
    jQuery('.round5').removeClass('open');
  })
  jQuery('.round3').click(function () {
    jQuery('.round3').addClass('open');
    jQuery('.round2').removeClass('open');
    jQuery('.round1').removeClass('open');
    jQuery('.round4').removeClass('open');
    jQuery('.round5').removeClass('open');
  })
  jQuery('.round4').click(function () {
    jQuery('.round4').addClass('open');
    jQuery('.round2').removeClass('open');
    jQuery('.round3').removeClass('open');
    jQuery('.round1').removeClass('open');
    jQuery('.round5').removeClass('open');
  })
  jQuery('.round5').click(function () {
    jQuery('.round5').addClass('open');
    jQuery('.round2').removeClass('open');
    jQuery('.round3').removeClass('open');
    jQuery('.round4').removeClass('open');
    jQuery('.round1').removeClass('open');
  })

});

var carousel = document.querySelector('.carousel');
var cellCount = 14;
var selectedIndex = 0;

function rotateCarousel() {
  // var angle = selectedIndex / cellCount * -360;
  // carousel.style.transform = 'translateZ(-288px) rotateY(' + angle + 'deg)';
}

/*var prevButton = document.querySelector('.previous-button');
prevButton.addEventListener( 'click', function() {
  selectedIndex--;
  rotateCarousel();
});

var nextButton = document.querySelector('.next-button');
nextButton.addEventListener( 'click', function() {
  selectedIndex++;
  rotateCarousel();
});*/

/*document.addEventListener('click', function(){
  selectedIndex--;
  rotateCarousel();
})*/

window.addEventListener('load', (event) => {

  rotateCarousel();
  setInterval(() => {
    selectedIndex--;
    rotateCarousel();
  }, 3000);

})

// window.addEventListener('load', function () {
//   var load_screen = document.getElementById('load_screen');
//   document.body.removeChild(load_screen);
// })


jQuery(window).load(function () {
  jQuery(".column-section").mCustomScrollbar({
    scrollButtons: {
      enable: true
    }
  }).hover(function () {
    jQuery('.mCSB_dragger_bar').css({
      visiblity: "visible"
    })
  }, function () {
    jQuery('.mCSB_dragger_bar').css({
      visiblity: "hidden"
    })
  })
  
});
jQuery(function () {
  jQuery("#datepicker").datepicker();
});
