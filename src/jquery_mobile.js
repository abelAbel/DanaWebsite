
var loaded = false;
var state = "add";
// var addStatusText = "";
// var theme = '';

$(document).ready(function(){
//Info button
  $("#info").on("click",function (event) {
//     addPopUp(
//       '<h2> Welcome to EBK</h1> <hr>\
//       <div class="ui-field-contain">\
//         <fieldset data-role="controlgroup" data-type="vertical" data-mini="true">\
//             <h3>To request add</h3>\
//             <img src="img/one-finger-swipe-right.png" alt="swipe right">\
//             <h3>To see search result(s)</h3>\
//            <img id = "swipe-left-icon" src="img/one-finger-swipe-left.png" alt="swipe left">\
//         </fieldset>\
//       </div>'
//       , "b");

    addPopUp(
      '<div class="ui-grid-a ui-responsive">\
      <h2> Welcome to EBK</h1> <hr>\
          <div class="ui-block-a">\
            <h3>To request add</h3>\
            <img src="img/one-finger-swipe-right.png" alt="swipe right">\
          </div>\
          <div class="ui-block-b ">\
            <h3>To see search result(s)</h3>\
           <img src="img/one-finger-swipe-left.png" alt="swipe left">\
          </div>\
        </div>'
      , "b");


//           <div class="ui-grid-b ui-responsive">
//           <div class="ui-block-a">
//           <fieldset data-role="controlgroup" data-type="vertical" data-mini="true">\
//             <h3>To request add</h3>\
//             <img src="img/one-finger-swipe-right.png" alt="swipe right">\
//         </fieldset>\
//           </div>
//           <div class="ui-block-b ">
//           <fieldset data-role="controlgroup" data-type="vertical" data-mini="true">\
//             <h3>To see search result(s)</h3>\
//            <img id = "swipe-left-icon" src="img/one-finger-swipe-left.png" alt="swipe left">\
//         </fieldset>\
//           </div>
//           <div class="ui-block-c"></div>
//         </div>

      //     <h1> Welcome to EBK</h1>\
      // <hr> \
      // <h3>TO REQUEST ADD </h3>\
      // <img src="img/one-finger-swipe-right.png" alt="swipe right">\
      // <h3>SEE SEACHED RESULT(S)</h3>\
      // <img src="img/one-finger-swipe-left.png" alt="swipe left">'
      

  });
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

    $("#googleButton").on("click",function(){
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

    $("form.ajax").on("submit",function (event) 
    {

        var url = $(this).attr('action'),
            type = $(this).attr('method'),
            data = {};
        if(url == "add.php")
        {//Ask if they are sure they want to add
        	state = "add";
        }
        else
        {//searching
        	state = "search"
        }
         console.log("State new: " + state);

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
            success: function (datas,textStatus,jqXHR) {
                      ajaxResponseProccess(datas);
                    }, 
            error: function (jqXHR, exception) {
                      console.log("Error");
                      addPopUp("Error occured, please try again later",'c');
                      // Your error handling logic here..
                    }

        });

        return false;
    });


    // $(window).scroll(function() {
    //     if($(this).scrollTop() != 0) {
    //         $('#toTop').fadeIn();   
    //         console.log("Fade in"); 
    //     } else {
    //         $('#toTop').fadeOut();
    //         console.log("Fade out"); 
    //     }
    // });

    // $('#toTop').click(function() {
    //     $('body,html').animate({scrollTop:0},800);
    // }); 

    //  $(document).on("scrollstop",function(){
    //     // alert("Stopped scrolling!");
    //      $('#toTop').fadeIn();

    // });         


});

function addPopUp(addStatusText,theme)
{

  console.log(addStatusText);
  $("#popup-area").html(
      '<div data-role="popup"  data-dismissible="false" id="addPopupDiv" class="ui-content" data-theme="'+ theme +'" data-transition="slidedown" >'+
          '<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>'+
          '<p><b>'+ addStatusText +'</b></p>\
      </div>'
      ).trigger('create');

  $( "#addPopupDiv" ).popup({ 
      afterclose: function( event, ui ) {//Get rid of the pop up, so we can add different theme later
          $( "#addPopupDiv" ).popup( "destroy" );
          $( "#popup-area" ).empty();
      }
  });
  $( "#addPopupDiv" ).popup( "open" ); //Show pop up

}

function ajaxResponseProccess(d)
{

    if(state == "add")
    {//adding
      console.log("d['urlErr'] -> " + d['urlErr']);
      console.log("d['email_sent'] -> " +d['email_sent']);
        if(d['urlErr'] == true)
        {
          console.log("Invalid URL");
          $("#urlErr").text("Invalid *");
          addPopUp("Invalid URL enterered please try again", 'c');
        }
        else if (d['email_sent'] == true)
        {
          console.log("Successfull email for add sent");
          //$('#addForm').trigger("reset");
          $("#urlErr").text(""); //Clear invalid message
          addPopUp("Add request successfully sent.....", 'd');
        }
        else
        {
           addPopUp("!!SYSTEM ERROR PLEASE TRY AGAIN LATTER!!", 'c');
        }

    }
    else
    {//searching
        var wAvgr = 0;
        var finalResult = "";
        // var wAverage = {'0':0,'1':0,'2':0,'3':0,'4':0,'5':0};
        var wAverage = {'0':0,'5':0};
        var sum = 0;

        console.log("Successfull Search");
        loaded = true;
        //$("#pResults>.ui-content").html(d['0']['title']);
        console.log(d);
        // $('#toTop').hide(); 

        //Default the values
        finalResult = "0 Result Found... <hr/>";
        $('#p1').css({"background-color": "#f9f9f9"});
        $('#mPresult-slider').val("").slider("refresh");

        if( d['total'] > 0)
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
              wAverage[l['rating']]+=1;
            });
            console.log(wAverage);

            //Calulate average
            // sum = (wAverage['0'] + wAverage['1']+ wAverage['2'] + wAverage['3']+ wAverage['4'] + wAverage['5']); 
            sum = (wAverage['0'] + wAverage['5']); 
            if(sum > 0){
                // wAvgr = (wAverage['0']*0 + wAverage['1']*1 + wAverage['2']*2 + wAverage['3']*3 + wAverage['4']*4 + wAverage['5']*5)/sum;
                wAvgr = (wAverage['5']*5)/sum;
                $('#p1').css({"background-color": "hsl("+hsl_rating(Math.round(wAvgr))+", 100%, 50%)"});    
                console.log("Sum: " + sum);
                $('#mPresult-slider').val(Math.round(wAvgr)).slider("refresh");
            }

        }
        
        console.log("wAvgr = " + wAvgr);
        console.log("Math.round(wAvgr) = " + Math.round(wAvgr));
        $("#pResults>.ui-content").html(finalResult);
    }

}

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


