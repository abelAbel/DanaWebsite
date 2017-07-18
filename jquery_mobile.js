
var loaded = false;
var state = "add";
// var addStatusText = "";
// var theme = '';

// $(document).ready(function(){
$(document).bind('pageinit',function(){
  /* Instantiate the popup on DOMReady, and enhance its contents */
  $( "#popup-area" ).enhanceWithin().popup();

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
      '<div class="ui-grid-a ui-responsive pop-img-load">\
      <h2> Welcome to EBK</h1> <hr>\
          <div class="ui-block-a photopopup">\
            <h3>To request add</h3>\
            <img src="img/one-finger-swipe-right.png" alt="swipe right">\
          </div>\
          <div class="ui-block-b photopopup">\
            <h3>To see search result(s)</h3>\
           <img class ="photo" src="img/one-finger-swipe-left.png" alt="swipe left">\
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

    // $("#slider-fill").on("change",function (e) {
    // 	console.log($(this).val());
    //      $('#p1').css({"background-color": "hsl("+hsl_rating($(this).val())+", 100%, 50%)"});
    // });

    $("form.ajax").on("submit",function (event) 
    {
       $.mobile.loading( "show");
        var url = $(this).attr('action'),
            type = $(this).attr('method'),
            data = {};
        var erroMessage = "";
        if(url == "add.php")
        {//Ask if they are sure they want to add
        	state = "add";
          erroMessage  = "Error occured while adding, try again later";
        }
        else
        {//searching
        	state = "search";
          erroMessage = "Error occured while searching, try again later";

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
                      $.mobile.loading( "hide");
                    }, 
            error: function (jqXHR, exception) {
                      $.mobile.loading( "hide");
                      console.log("Error");
                      addPopUp(erroMessage,'c');
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
          
// $( document ).on( "pagecreate", function() {
//     $( ".photopopup" ).on({
//         popupbeforeposition: function() {
//             var maxHeight = $( window ).height() - 60 + "px";
//             $( ".photopopup img" ).css( "max-height", maxHeight );
//         }
//     });
// });


//     // Remove the popup after it has been closed to manage DOM size
    $( document ).on( "popupafterclose", ".ui-popup", function() {
        $( this ).remove();
          $( "#popup-area" ).empty();
    });

   $('#slider-rating').on('change',function (e) {
         $('#rating-star').rating('update', $(this).val());
         console.log ($(this).val());
    });

  /*BootStrap Start-rating section*/
   $('#mPresult-stars').rating({
        // starCaptions: {0: 'Unacceptable', 1: 'Questionable', 2: 'Neutral', 3: 'Ok', 4: 'Good', 5: 'Great'},
        // starCaptionClasses: {0:'label label-default', 1: 'label label-default', 2: 'label label-default', 3: 'label label-default', 4: 'label label-default', 5: 'label label-default'},
        clearCaption: '',
        starCaptions: function (val) {return starCaptionRange(val)},
        starCaptionClasses: function (val) {return 'label label-default';}

    });

    $('#rating-star').rating({
      // starCaptions: {0: 'Unacceptable', 1: 'Questionable', 2: 'Neutral', 3: 'Ok', 4: 'Good', 5: 'Great'},
      // starCaptionClasses: {0:'label label-default', 1: 'label label-default', 2: 'label label-default', 3: 'label label-default', 4: 'label label-default', 5: 'label label-default'},
      clearCaption: '0 - Unacceptable',
      starCaptions: function (val) {return starCaptionRange(val)},
      starCaptionClasses: function (val) {
        if(val >= 0  && val <= 1 )
          return 'label label-danger';
        else if (val > 1  && val <= 2 )
          return 'label label-warning';
        else if (val > 2  && val <= 3 )
          return 'label label-info';
        else if (val > 3  && val <= 4 )
          return 'label label-primary';
        else return 'label label-success';
      }

    });

    $('#rating-star').on('rating.change', function(event, value, caption) {
      $('#slider-rating').val(value).slider("refresh");
      console.log(value);
      console.log(caption);

    });
    $('#rating-star').on('rating.clear', function(event) {
      console.log("rating.clear");
      $('#slider-rating').val(0).slider("refresh");
    });





}); //End of DOM ready function

function starCaptionRange(val) {
  if(val >= 0  && val <= 1 )
    return val +' - Unacceptable';
  else if (val > 1  && val <= 2 )
    return val +' - Questionable';
  else if (val > 2  && val <= 3 )
    return val +' - Neutral';
  else if (val > 3  && val <= 4 )
    return val +' - Good';
  else return val +' - Great';
}

function addPopUp(addStatusText,theme)
{

  console.log(addStatusText);

  $("#popup-area").html(
      '<div data-role="popup"  data-dismissible="false" id="addPopupDiv" class="ui-content" data-theme="'+ theme +'" data-transition="slidedown" >'+
          '<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>'+
          '<p><b>'+ addStatusText +'</b></p>\
      </div>'
      ).trigger('create');

  // $( "#addPopupDiv" ).popup({ 
  //     afterclose: function( event, ui ) {//Get rid of the pop up, so we can add different theme later
  //         $( "#addPopupDiv" ).popup( "destroy" );
  //         $( "#popup-area" ).empty();
  //     }
  // });

  if($(".pop-img-load").length != 0) 
  {
    console.log("Fired the Load event");
      // Wait with opening the popup until the popup image has been loaded in the DOM.
      // This ensures the popup gets the correct size and position  
      $( ".photo", "#addPopupDiv").load(function() {
            // Open the popup
            $( "#addPopupDiv" ).popup( "open" );
            // Clear the fallback
            clearTimeout( fallback );
        });
      // Fallback in case the browser doesn't fire a load event
        var fallback = setTimeout(function() {
            $( "#addPopupDiv" ).popup( "open" );
        }, 2000);
  }
  else
    $( "#addPopupDiv" ).popup( "open" ); //Show pop up

  // $.mobile.resetActivePageHeight();

}

// function addPopUp(addStatusText,theme)
// {

//  var target = $( this ),
//             brand = "Brand",
//             model = "Model",
// //             short = target.attr( "id" ),
//             short = "one-finger-swipe-left",
//             closebtn = '<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>',
//             header = '<div data-role="header"><h2>' + brand + ' ' + model + '</h2></div>',
//             img = '<img src="img/' + short + '.png" alt="' + brand + '" class="photo">',
//             popup = '<div data-role="popup" id="popup-' + short + '" data-short="' + short +'" data-theme="none" data-overlay-theme="a" data-corners="false" data-tolerance="15"></div>';
//         // Create the popup.
//         $( header )
//             .appendTo( $( popup )
//                 .appendTo( $.mobile.activePage )
//                 .popup() )
//             .toolbar()
//             .before( closebtn )
//             .after( img );
//         // Wait with opening the popup until the popup image has been loaded in the DOM.
//         // This ensures the popup gets the correct size and position
//         $( ".photo", "#popup-" + short ).load(function() {
//             // Open the popup
//             $( "#popup-" + short ).popup( "open" );
//             // Clear the fallback
//             clearTimeout( fallback );
//         });
//         // Fallback in case the browser doesn't fire a load event
//         var fallback = setTimeout(function() {
//             $( "#popup-" + short ).popup( "open" );
//         }, 2000);

// }


//     // Set a max-height to make large images shrink to fit the screen.
//     $( document ).on( "popupbeforeposition", ".ui-popup", function() {
//         var image = $( this ).children( "img" ),
//             height = image.height(),
//             width = image.width();
//         // Set height and width attribute of the image
//         $( this ).attr({ "height": height, "width": width });
//         // 68px: 2 * 15px for top/bottom tolerance, 38px for the header.
//         var maxHeight = $( window ).height() - 68 + "px";
//         $( "img.photo", this ).css( "max-height", maxHeight );
//     });
//     // Remove the popup after it has been closed to manage DOM size
//     $( document ).on( "popupafterclose", ".ui-popup", function() {
//         $( this ).remove();
//     });

// ===========================================================

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
        // var wAverage = {'0':0,'5':0};
        var wAverage = {};
        var sum = 0;

        console.log("Successfull Search");
        loaded = true;
        //$("#pResults>.ui-content").html(d['0']['title']);
        console.log(d);
        // $('#toTop').hide(); 


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
              // wAverage[l['rating']]+=1;
              wAverage[l['rating']] = (isNaN(wAverage[l['rating']])) ? 1 : wAverage[l['rating']]+=1;
            });
            console.log(wAverage);
            // console.log ("0=>" + hsl_rating(0) + " / 5=>" + hsl_rating(5));

            //Calulate average
            // sum = (wAverage['0'] + wAverage['1']+ wAverage['2'] + wAverage['3']+ wAverage['4'] + wAverage['5']); 
            // sum = (wAverage['0'] + wAverage['5']); 
            for (x in wAverage) {
              sum+= wAverage[x];
              wAvgr += x * wAverage[x];
            }

            console.log("Sum = " + sum);
            console.log("wAvgr before divide = " + wAvgr);

            if(sum > 0){
                // wAvgr = (wAverage['0']*0 + wAverage['1']*1 + wAverage['2']*2 + wAverage['3']*3 + wAverage['4']*4 + wAverage['5']*5)/sum;
                // wAvgr = (wAverage['5']*5)/sum;
                wAvgr = wAvgr/sum;
                $('#p1').css({"background-color": "hsl("+hsl_rating(Math.round(wAvgr))+", 100%, 50%)"});    
                // console.log("Sum: " + sum);
                // $('#mPresult-slider').val(Math.round(wAvgr)).slider("refresh");
                $('#mPresult-stars').rating('update', Math.round(wAvgr * 10)/10);
                if (wAvgr == 0) {
                  $('#mPresult-stars').rating('refresh', {clearCaption:'0 - Unacceptable'});
                }

                
            }

            console.log("wAvgr = " + wAvgr);
            console.log("Math.round(wAvgr) = " + Math.round(wAvgr));

        }
        else
        {
          //Clear the values to default
          finalResult = "0 Result Found... <hr/>";
          $('#p1').css({"background-color": "#f9f9f9"});
          // $('#mPresult-slider').val("").slider("refresh").val("");
          $('#mPresult-stars').rating('reset');
          $('#mPresult-stars').rating('refresh', {clearCaption:''});
        }
        

        $("#pResults>.ui-content").html(finalResult);
        alert(d['total'] + " Result Found");
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


