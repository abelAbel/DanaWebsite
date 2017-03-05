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
            
        $("#p1").css("background","red");
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
        console.log(data);

        $.ajax({
            url:url,
            type:type,
            data: data,
            success: function  (response) {
            	if(state == "add"){//adding
            		console.log("Successfull add");
            		$('#addForm').trigger("reset");

            	}
            	else{//searching
            		console.log("Successfull Search");
            		loaded = true;
            	}

                // body...
            }
        });

        
        return false;
    });

});


