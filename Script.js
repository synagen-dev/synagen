
$(document).ready(function(){
  // Add smooth scrolling to all links in navbar + footer link
  $(".navbar a, footer a[href='#myPage']").on('click', function(event) {
    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 900, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
  
  $(window).scroll(function() {
    $(".slideanim").each(function(){
      var pos = $(this).offset().top;

      var winTop = $(window).scrollTop();
        if (pos < winTop + 600) {
          $(this).addClass("slide");
        }
    });
  });
})

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

    function showImages(el) {
        var windowHeight = jQuery( window ).height();
        $(el).each(function(){
            var thisPos = $(this).offset().top;

            var topOfWindow = $(window).scrollTop();
            if (topOfWindow + windowHeight - 10 > thisPos ) {
                $(this).addClass("fadeIn");
				
            }
        });
    }

    // if the image in the window of browser when the page is loaded, show that image
    $(document).ready(function(){
            showImages('.star');
    });

    // if the image in the window of browser when scrolling the page, show that image
    $(window).scroll(function() {
            showImages('.star');
    });

jQuery(document).ready(function($) {
 
	$(".scroll").click(function(event){		
		event.preventDefault();
		$('html,body').animate({scrollTop:$(this.hash).offset().top}, 800);
	});
});
function ValidateItem(formname,item, name, validate_mail, validate_phone)
{
  if (document.forms[formname][formname].elements[item]) {
    if (document.form.elements[item].value.length < 1)
    {  alert("You must enter a value for " + name);
      document.forms[formname].elements[item].focus();
      return false;
    }
    if (validate_mail)
    {
      var emailFilter=/^.+@.+\..{2,3}$/;
      var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/;
      var strng = document.forms[formname].elements[item].value;
      if (!(emailFilter.test(strng)))
      { alert("You have entered an invalid email address.");
        document.forms[formname].elements[item].focus();
        return false;
      }
      if (strng.match(illegalChars))
      { alert("Your email address contains invalid characters.");
        document.forms[formname].elements[item].focus();
        return false;
      }
    }
  }
  return true;
}
function ValidateForm()
{
    if (!ValidateItem("form","first_name", "First Name", false )) return false;
    if (!ValidateItem("form","email", "Email Address", true )) return false;
    if (!ValidateItem("form","comment",   "Your Comment/Enquiry",      false)) return false;
    return true;
}

var hover_id='';
var myTimer;
var red=255;
var green=255;
var blue=255;
function hover_blend(myObj){
	// Initiates the gradual change of format at start of a hover
	
	//Make sure that the mouseover event isn't triggered when moving from a child element
	//or bubbled from a child element
	if (hover_id=='' || myObj == hover_id ){
		//Event handling code here
		hover_id=myObj;
		myTimer=setInterval(hover_increment, 20);
		red=255;
		green=255;
		blue=255;
	}
}

function hover_exit(myObj){
	// Called on mouseOut event
	
	//Make sure that the mouseout event is triggered when moving onto a child element
	//or bubbled from a child element
	if (myObj == hover_id){
		//Event handling code here
		clearInterval(myTimer);
		hover_id.style.background='transparent';
		hover_id.style.borderColor='transparent';
		hover_id='';
	}
}
var over = 0, out = 0;

function hover_increment(){
	red=red - 2;
	green=green -1;
	//blue--;
	hover_id.style.background="rgb("+red+","+green+","+blue+")";
	hover_id.style.borderColor="rgb("+(red-50)+","+(green-50)+","+(blue-50)+")";
	if (red<=210) hover_end();
}
function hover_end(){
	clearInterval(myTimer);
}
//Detect if otherNode is contained by refNode
function isParent(refNode, otherNode) {
	var parent = otherNode.parentNode;
	do {
		if (refNode == parent) {
			return true;
		} else {
			parent = parent.parentNode;
		}
	} while (parent);
	return false;
}
