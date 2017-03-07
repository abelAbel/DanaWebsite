$(document).ready(function(){
    var loaded = false;
    var state = "add";
//abel test
// Add Page
    $("#p1").on( "swiperight", function( event ) {
        // alert("swiperight");
        $( ":mobile-pagecontainer" ).pagecontainer( "change", "#pAdd", { transition: "slide", reverse: true} );
        // window.open("http://www.google.com", "_system");
    });

    $("#pAdd").on( "swipeleft", function( event ) {
        // alert("swiperight");
        $( ":mobile-pagecontainer" ).pagecontainer( "change", "#p1", { transition: "slide"} );
        // window.open("http://www.google.com", "_system");
    });

    $("#googleButton").on("tap",function(){
        window.open("http://www.google.com", "_system");
    });




// Result Page
    $("#p1").on( "swipeleft", function( event ) {
        // alert("swipeleft");
        if (loaded == false) {
            alert("Please search for something");
        }
        else{
            // alert("Page is loaded");
            $( ":mobile-pagecontainer" ).pagecontainer( "change", "#pResults", { transition: "slide" } );

        }
    });

    $("#pResults").on( "swiperight", function( event ) {
        $( ":mobile-pagecontainer" ).pagecontainer( "change", "#p1", { transition: "slide", reverse: true} );
    });

    $("#slider-fill").on("change",function (e) {
    	console.log($(this).val());
         $('#p1').css({"background-color": "hsl("+hsl_rating($(this).val())+", 100%, 50%)"});
    });






    $("form.ajax").on("submit",function (event) {

        var url = $(this).attr('action'),
            type = $(this).attr('method'),
            data = {};
        if(url == "add.php"){
        	state = "add";
        }
        else{//searching
        	state = "search"
        }
         console.log("State: " + state);

        $(this).find('[name]').each(function (index, value) {
            var name = $(this).attr('name'),
                value = $(this).val();
                data[name] = value;

        });

        console.log( data);

        $.ajax({
            url:url,
            type:type,
            data: data,
            dataType:"json",
            success:function (datas,textStatus,jqXHR) {
                var d = datas;
            	if(state == "add"){//adding
            		console.log("Successfull add");
            		$('#addForm').trigger("reset");

            	}
            	else
            	{//searching
                    var finalResult = "";
            		console.log("Successfull Search");
            		loaded = true;
            		//$("#pResults>.ui-content").html(d['0']['title']);
                    console.log(d);

                    if(d['total'] == 0)
                    {
                    	finalResult = "0 result found... <hr/>";
                    }

                    else
                    {
                    	finalResult = d['total'] + " Result Found <hr/>";
	                    $.each( d['contents'], function( i, l ){
				         finalResult+= 
				         '<div style='+'"border-bottom: 6px solid hsl('+hsl_rating(l['rating'])+', 100%, 50%);\
				                      background-color: lightgrey;\
				                      margin-bottom: 10px;\
				                      box-shadow: 5px 5px 5px #888888;">'+
				                      'Title: '+ l['title'] + '<br>'+
				                      'Rating: '+ l['rating'] + '<br>'+
				                      'URL: <a target="_blank" href="'+ l['url'] +'">'+l['url']+'</a> <br>'+
				                      'Keywords: '+ l['keywords'] + '<br>'+
				                      'Description: '+ l['description'] + '<br>'+
				          '</div>';
				        });

                    }

	                    $("#pResults>.ui-content").html(finalResult);
	            	    $('#p1').css({"background-color": "hsl("+hsl_rating(d['average'])+", 100%, 50%)"});
			    }


            }
        });

        
        return false;
    });

});

function hsl_rating(rating){
    var change ;
	var step = 0.16666666666666666666666666666667;
	var hue;
	  if(rating == 0){
	    change = 1;
	  }
	  else{
	    change=((6-rating)*step.toFixed(16));
	  }

	  hue = (1 - change) *120;
	  return(hue);
}


