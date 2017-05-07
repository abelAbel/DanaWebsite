// $(document).ready(function () {
// 	// body...
// });
//Same as:
	// $(function() {
	// 	// body...
	// });


// $(function() {
// 	// body...
// });

// $(function() {
// 	// body...
// });



$(function() {
	var search = false;
	$('.swipeleft').on('swipeleft',function(event){

		// alert("HOWDY do swipte left");
		if(search){
			$( ":mobile-pagecontainer" ).pagecontainer( "change", "#page3", {
			transition: "slide"//,
			//             reverse: true
			});
		}
		else{
			alert("Please enter value to search");
		}

	});
	$('.swiperight').on('swiperight',function(event){
	  $( ":mobile-pagecontainer" ).pagecontainer( "change", "#page1", {
	            transition: "slide",
	            reverse: true
	    });
		// alert("HOWDY do swipte right");
	});

	$('input#rating').on('click change',function(){
		// var value = $(this).val();
		// 	var r = ((255*value)/5)
		//     g = ((255*(5-value))/5);
		//     $("#page1").css("background", "rgb("+r+","+g+",0)");
		// 	console.log("data rating:" + value);
		// 	console.log("r:" +r);
		//     console.log("g:" +g);

		 //    			var r = ((255*data['rating'])/5)
		 //    g = ((255*(5-data['rating']))/5);
			// console.log("data rating:" +data['rating']);
			// console.log("r:" +r);
		 //    console.log("g:" +g);
	
	});

	$('form.ajax').on('submit',function () {
		var currentObject = $(this),
		url = currentObject.attr('action'),
		type = currentObject.attr('method'),
		data = {};

		currentObject.find('[name]').each(function (index,value) {
			var currElement = $(this); // or can use 'value' in parameter
			var name = currElement.attr('name'),
			value = currElement.val();
			data[name] = value;
			
		});//find anything with attribute of name and traverse through them
		console.log(data);
		$.ajax({
			url: url,
			type: type,
			data: data,
			dataType:"json",
			success:function (data,textStatus,jqXHR) {
				search = true;
				var d = data;
				// alert(d);
				$('#page3').html(d['rate']);
				// alert(textStatus);
				// alert(jqXHR['rate']);
				// console.log(response);
				// $("#page1").css("background-color", "rgb("+data['rating']+",0,0)");
				var value = d['rate'];
				var r = ((255*value)/5)
			    g = ((255*(5-value))/5);
			    $("#page1").css("background", "rgb("+r+","+g+",0)");
    			console.log("data rating:" + value);
				console.log("r:" +r);
		    	console.log("g:" +g);
			}
		});
		// console.log('HOWDY');
		// $('#page1').css("background-color", "rgb(220,0,0)");
		// alert("Data: ");

		return false;
	});
	// $("button").click(function(){
	//     $.get("engine.php", function(data, status){
	//         alert("Data: " + data + "\nStatus: " + status);
	//     });
	// });


});

// $(function() {
// 	$('#page2').css("background-color", "rgb(220,0,0)");
// });

// $(document).ready(function(){
//     $("button").click(function(){
//     	// alert("Data: ");
//     	$('#page1').css("background-color", "rgb(220,0,0)");
//     });
// });