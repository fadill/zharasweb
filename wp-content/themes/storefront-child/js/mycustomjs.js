/* dropdown menu
jQuery(document).ready(function(){
     jQuery('.menu .dropdown a').click(function(e){  
	if (jQuery(this).parent().hasClass('dropdown')) {
          if (jQuery(this).parent().children('.sub-menu').is(':visible')) {
               jQuery(this).parent().children('.sub-menu').slideUp();
          } else {
               jQuery(this).parent().children('.sub-menu').slideDown();
          }
		}
     });
});*/

/* Toggle between adding and removing the "active" and "show" classes when the user clicks on one of the "Section" buttons. 
The "active" class is used to add a background color to the current button when its belonging panel is open. 
The "show" class is used to open the specific accordion panel */
jQuery(document).ready(function() {
	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
		acc[i].onclick = function(){
			this.classList.toggle("active");
			jQuery(this).parent().next( ".panel" ).toggleClass( "show" );
		}
	}


});
/* smooth scroll up button 
jQuery(document).ready(function($){
    $(window).scroll(function(){
        if ($(this).scrollTop() < 200) {
            $('#smoothup') .fadeOut();
        } else {
            $('#smoothup') .fadeIn();
        }
    });
    $('#smoothup').on('click', function(){
        $('html, body').animate({scrollTop:0}, 'fast');
        return false;
        });
});*/

jQuery(document).ready(function() {
	/* login/register tab function */
	jQuery('.account-tab-list .account-tab-item a').on('click', function(e)  {
		var currentAttrValue = jQuery(this).attr('href');
		// Show/Hide Tabs
		jQuery('.customer_login ' + currentAttrValue).fadeIn(500).siblings().hide();
		// Change/remove current tab to active
		jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
		e.preventDefault();
	});

});


jQuery(document).ready(function($) {	
	$(".add_to_cart_button").hover(
	/* hover in */
	  function() {
		$(this).siblings('.woocommerce-LoopProduct-link').children('.archive-img-wrap').css( {"-webkit-filter": "brightness(95%)"});
		},
	/* hover out */
	  function() {
		$(this).siblings('.woocommerce-LoopProduct-link').children('.archive-img-wrap').css( {"-webkit-filter": "brightness(100%)"});
		},	
	);
});

jQuery(document).ready(function($) {
	/* hide whatsapp div when user is typing */
	$('input, textarea, select').focus(function(){
		$(".wa-button").hide();
	}).blur(function(){
		$(".wa-button").show();
	});
});		