function getFormData (form) {
  var d = {"type":form.attr('method'), 'url':form.attr('action'),'inputsData':{}};
  form.find('[name]').each(function (index, value) {
        var name = $(this).attr('name'),
           value = $(this).val();
        d.inputsData[name] = value.trim();
  });

  return d;
}
function isValidUrl($url) {
  url = $url.trim();
  var expression = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,}))\.?)(?::\d{2,5})?(?:[/?#]\S*)?$/i
;
  var regex = new RegExp(expression);
  if (url.match(regex)) {
    // alert("Successful match");
    console.log("Successful match :" + url);
  } else {
    // alert("No match");
    console.log("No match :" + url);
  }
  return url.match(regex);
}

function ajaxCustom(_url,_type,_data,_data_type,_success,_failure)
{
  if (typeof(_data_type)==='undefined') _data_type = "";
  if (typeof(_success)==='undefined') _success = "";
  if (typeof(_failure)==='undefined') _failure = "";

  return $.ajax({
          url:_url,
          type:_type,
          data:_data,
          dataType:_data_type,
          success:_success,
          error:_failure
        });
}

function starCaptionRange(_val) {
  if(_val >= 0  && _val <= 1 )
    return _val +' - Unacceptable';
  else if (_val > 1  && _val <= 2 )
    return _val +' - Questionable';
  else if (_val > 2  && _val <= 3 )
    return _val +' - Neutral';
  else if (_val > 3  && _val <= 4 )
    return _val +' - Good';
  else return _val +' - Great';
  // return "0";
}

function addPopUp(addStatusText,theme)
{

  console.log(addStatusText);

  $("#popup-area").html(
      '<div data-role="popup"  data-dismissible="false" id="addPopupDiv" class="ui-content" data-theme="'+ theme +'" data-transition="slidedown" >'+
          '<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>'+
          '<p><b>'+ addStatusText +'</b></p></div>'
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
  {
    $( "#addPopupDiv" ).popup( "open" ); //Show pop up
  }

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
             '<div style='+'"border-bottom: 6px solid hsl('+ hsl_rating(l['rating']) + ', 100%, 50%);\
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
                $('#p1').css({"background-color": "hsl("+hsl_rating(Math.round(wAvgr * 10)/10)+", 100%, 50%)"});
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
