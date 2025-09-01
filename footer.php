

<div class="jumbotron-footer text-center">
	<div class="container">
 
		<div class="row">
			<div class="col-sm-12">
				Website&#169; 2010 - 2021 Synagen.  All rights reserved
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12" id="contactdets"> &nbsp; &nbsp; &nbsp; &nbsp; </div>
		</div>
		<div class="row">
			<div class="col-sm-12" >				<i class="fa fa-facebook-official" style="font-size:36px; color:black;"></i>
				<i class="fa fa-google-plus" style="font-size:36px; color:black; padding-left:10px;"></i>
				<i class="fa fa-linkedin" style="font-size:36px; color:black; padding-left:10px;"></i>
 </div>
		</div>
	</div>
</div>
<script>
document.getElementById("contactdets").innerHTML="Email:admin@synagen.net  &nbsp; &nbsp; Ph:+61-406296373<BR>"; 


// Find each DIV that has class=gradualHover & attach the mouseenter event listener

$( "div.gradualHover" ).mouseenter(
	function(ev){
		var target = $( ev.target );
		//Make sure that the mouseenter event isn't triggered when moving from a child element or bubbled from a child element
		if (target.is("div") && !isParent(this, ev.relatedTarget) && ev.target == this){
			
			// Sometimes, if the cursor moves too quickly, the browser doesn't catch the mouseleave event from the previous mouseenter.
			// So, just to make sure everything is clean, we go through and reset all relevant DIVs
			if(myTimer && myTimer!=undefined && myTimer!='') clearInterval(myTimer);
			$( "div.gradualHover" ).each(function(){this.style.background='transparent'; this.style.borderColor='transparent';});
			
			hover_id=this;
			red=255;
			green=255;
			blue=255;
			myTimer=setInterval(hover_increment, 30);
		}
	});

$( "div.gradualHover" ).mouseleave(
	function(ev){
		//Make sure that the mouseleave event is triggered when moving onto a child elementor bubbled from a child element
		var target = $( ev.target );
		if (target.is("div") && !isParent(this, ev.relatedTarget) && ev.target == this){
			//Event handling code here
			clearInterval(myTimer);
			if(hover_id!=undefined && hover_id!=''){
				hover_id.style.background='transparent';
				hover_id.style.borderColor='transparent';
			}
			hover_id='';
			red=255;
			green=255;
			blue=255;
		}
	});
</script>
</body>
</html>