
var loaded = false;
var state = "add";
// var addStatusText = "";
// var theme = '';
$(document).on('pagebeforecreate','#pAdd',function (a) {
  // $("#search").tagSystem({maxTags:10});       
  $("#tags").tagSystem({maxTags:10,addAutocomplete:true});       
});

/* Instantiate the popup on DOMReady, and enhance its contents */
// $(function(){
//     $( "#popup-area" ).enhanceWithin().popup(positionTo: "window",dismissible: false, transition: "slidedown");
// });

// $(document).ready(function(){
$(document).one('pagecreate',function(){
  /* Instantiate the popup on DOMReady, and enhance its contents */
  // $( "#popup-area" ).enhanceWithin().popup();

    // $("form.ajax").on("submit",function (event)
    // {
    //    $.mobile.loading( "show");
    //     var url = $(this).attr('action'),
    //         type = $(this).attr('method'),
    //         data = {};
    //     var erroMessage = "";
    //     if(url == "add.php")
    //     {//Ask if they are sure they want to add
    //     	state = "add";
    //       erroMessage  = "Error occured while adding, try again later";
    //     }
    //     else
    //     {//searching
    //     	state = "search";
    //       erroMessage = "Error occured while searching, try again later";

    //     }
    //      console.log("State new: " + state);

    //     $(this).find('[name]').each(function (index, value) {
    //         var name = $(this).attr('name'),
    //             value = $(this).val();
    //             data[name] = value;

    //     });

    //     console.log( data);

    //     $.ajax({
    //         url:url,
    //         type:type,
    //         data: data,
    //         dataType:"json",
    //         success: function (datas,textStatus,jqXHR) {
    //                   ajaxResponseProccess(datas);
    //                   $.mobile.loading( "hide");
    //                 },
    //         error: function (jqXHR, exception) {
    //                   $.mobile.loading( "hide");
    //                   console.log("Error");
    //                   addPopUp(erroMessage,'c');
    //                   // Your error handling logic here..
    //                 }

    //     });

    //     return false;
    // }); //end of 'form.ajax'

        // Remove the popup after it has been closed to manage DOM size
//     $( document ).on( "popupafterclose", ".ui-popup", function() {
    $( document ).on( "popupafterclose", function() {
        $( "#addPopupDiv" ).remove();
    });

  $("#searchForm").on('submit', function(e) {
    searchEKW($(this));
    return false;
  });

  $("#addForm").on('submit', function(e) {
    if($("#tags").get(0).isTagAdded())
      requestAddToEKW($(this));
    else
      addPopUp("Please input <b style='color:black'>'Who is being rated tags' </b> field",'c');
    return false;
  });


}); //End of DOM ready function

function requestAddToEKW (form) {
  $.mobile.loading( "show");
  var d = getFormData(form);
  console.log(d);
  // console.log(d.url);
  // console.log(d.type);
  // console.log(d.inputsData);
  d.inputsData['method'] = 'addRequestMain';
      ajaxCustom(d.url, d.type,d.inputsData,"",
        function (datas,textStatus,jqXHR) {
          addResponce(datas);
          $.mobile.loading( "hide");
        },
        function (jqXHR, exception) {
          $.mobile.loading( "hide");
          addPopUp("Error occured while adding, try again later",'c');
        }
      );
}// End of requestAddToEKW()

function addResponce(d) {
  //adding
      if (d == 0)
      {
        console.log("Successfull email for add request sent");
        addPopUp("Add request successfully sent.....", 'd');
      }
      else
      {
         addPopUp(d, 'c');
      }
} // End of addResponce()

function searchEKW(form) {
  $.mobile.loading( "show");
  // var url = form.attr('action'),
  //     type = form.attr('method');
  //     data = {};
  // form.find('[name]').each(function (index, value) {
  //   var name = $(this).attr('name'),
  //       value = $(this).val();
  //       data[name] = value;
  // });
  // data['method'] = 'engine';
  var data = getFormData(form);
  console.log(data);
  data.inputsData['method'] = 'engine';
      ajaxCustom(data.url, data.type,data.inputsData,"json",
        function (datas,textStatus,jqXHR) {
          searchResponce(datas);
          $.mobile.loading( "hide");
        },
        function (jqXHR, exception) {
          $.mobile.loading( "hide");
          addPopUp("Error occured while searching, try again later",'c');
        }
      );
} // End of searchEKW()

function searchResponce(d) {
  console.log(d);
  if(d['error'] != 0)
  {
    addPopUp("Search Error : "+d['error'],'c');
    return;
  }
  //searching
      var wAvgr = 0;
      var finalResult = "";
      // var wAverage = {'0':0,'1':0,'2':0,'3':0,'4':0,'5':0};
      // var wAverage = {'0':0,'5':0};
      var wAverage = d['wAvg'];
      var sum = 0;

      console.log("Successfull Search");
      loaded = true;
      //$("#pResults>.ui-content").html(d['0']['title']);
      console.log(d);
      // $('#toTop').hide();
        
        //Clear the values to default
        finalResult = "0 Result Found... <hr/>";
        $('#p1').css({"background-color": "#f9f9f9"});
        // $('#mPresult-slider').val("").slider("refresh").val("");
        $('#mPresult-stars').rating('reset');
        $('#mPresult-stars').rating('refresh', {clearCaption:''});
        $("#rMainTop").empty();
        $('[data-short-tg]').remove();

      if( d['total'] > 0)
      {
          finalResult = d['total'] + " Result Found <hr/>";
          finalResult += d['html'];
          // $.each( d['contents'], function( i, l ){
          //  finalResult+=
          //  '<div style='+'"border-bottom: 6px solid hsl('+hsl_rating(l['rating'])+', 100%, 50%);\
          //               background-color: lightgrey;\
          //               margin-bottom: 10px;\
          //               box-shadow: 5px 5px 5px #888888;">'+
          //               'Title: '+ l['title'] + '<br>'+
          //               'Rating: '+ l['rating'] + '<br>'+
          //               // 'URL: <a target="_blank" href="'+ l['url'] +'">'+l['url']+'</a> <br>'+
          //               // 'Keywords: '+ l['keywords'] + '<br>'+
          //               'Description: '+ l['description'] + '<br>'+
          //   '</div>';
          //   // wAverage[l['rating']]+=1;
          //   wAverage[l['rating']] = (isNaN(wAverage[l['rating']])) ? 1 : wAverage[l['rating']]+=1;
          // });
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

      // $("#pResults>.ui-content").html(finalResult);
      $("#rMainTop").html(finalResult);
      alert(d['total'] + " Result Found");
      console.log(d['popups']);
      var x;
      for(x in d['popups'])
      {
        console.log(d['popups'][x]);
        $(d['popups'][x]).appendTo("#rMainTop").enhanceWithin().popup();
      }

} // End of searchResponce()
          /*Search Page*/
$(document).one('pagecreate','#p1',function(){
  // Search Form ajax
    //Autocomplete
    $('#search').on('input focus',function (argument) {
      var $ul = $('#search-autoComplete'),
          $mainInput = $(this),
          value = $mainInput.val();
    
      console.log("in seach input filterablebeforefilter");
        if ( value.trim().length > 0 ) 
        {
            console.log("Going to call Ajax");
            $.ajax({
                url: 'engine.php',
                // type:"GET",
                dataType: "json",
                crossDomain: true,
                data: {q: value, method:'tagSearch'},
                success: function (arg) {
                  console.log("Success");
                },
                error: function (arg) {
                  console.log("Error ->" + arg);
                  console.log(arg);
                }
            })
            .then( function ( response ) {
                console.log(response);
                $ul.html("");
                $ul.listview( "refresh" );
                $.each( response, function ( i, val ) {
                    let $li = $("<li data-icon='arrow-u-l' data-theme='a'> <a href='#'>"+ val['tag_name'] + "</a></li>");
                    $li.children('a').on("click",function (e) {
                      $('#search').val($(this).text());
                      $ul.html("");
                      $ul.listview( "refresh" );
                       // $mainInput.textinput( "refresh" );
                      // return false;
                      // e.preventDefault();
                       // e.stopPropagation();
                      // alert($mainInput.val());
                      // $mainInput.trigger('change');
                       $("#searchForm").submit();
                    });
                    $ul.append($li);
                });
                $ul.listview( "refresh" );
                $ul.trigger( "updatelayout");
            });
        } else {$ul.html( "" ); $ul.listview( "refresh" );}
    });  

    //Info button
    $("#info").on("tap",function (event) {
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
    });

                     /*Search Page - Events */
    //Go to Add page
    $("#p1").on( "swiperight", function( event ) {
        // alert("swiperight");
        $( ":mobile-pagecontainer" ).pagecontainer( "change", "#pAdd", { transition: "slide", reverse: true} );
        // window.open("http://www.google.com", "_system");
    });
  // Go to Result Page
    $("#p1").on( "swipeleft", function( event ) {
        // alert("swipeleft");
        if (loaded == false) {
            // alert("Please search for something");
            addPopUp("Pleace seacrch for something",'c')
        }
        else{
            // alert("Page is loaded");
            $( ":mobile-pagecontainer" ).pagecontainer( "change", "#pResults", { transition: "slide" } );

        }
    });

   $('#mPresult-stars').rating({
        // starCaptions: {0: 'Unacceptable', 1: 'Questionable', 2: 'Neutral', 3: 'Ok', 4: 'Good', 5: 'Great'},
        // starCaptionClasses: {0:'label label-default', 1: 'label label-default', 2: 'label label-default', 3: 'label label-default', 4: 'label label-default', 5: 'label label-default'},
        clearCaption: '',
        starCaptions: function (val) {return starCaptionRange(val);},
        starCaptionClasses: function (val) {return 'label label-default';}

    });

});

          /*ResultPage*/
$(document).on('pagecreate','#pResults',function(){
      /*Result Page - Events */
     $("#pResults").on( "swiperight", function( event ) {
        $( ":mobile-pagecontainer" ).pagecontainer( "change", "#p1", { transition: "slide", reverse: true} );
    });


});

          /*Add Page*/
$(document).on('pagecreate','#pAdd',function(){

    /*Add Page - Events */
    $("#pAdd").on( "swipeleft", function( event ) {
        // alert("swiperight");
        $( ":mobile-pagecontainer" ).pagecontainer( "change", "#p1", { transition: "slide"} );
        // window.open("http://www.google.com", "_system");
    });

    $("#googleButton").on("tap",function(){
        window.open("http://www.google.com", "_system");
    });

    $("#addClearAll").on("tap",function(){
      $("#tags").get(0).clearAll();
    });
       // $('#slider-rating').on('slidestop',function (e) {
   //       $('#rating-star').rating('update', $(this).val());
   //       console.log ($(this).val());
   //  });

   // $("#slider-div").change(function() {
   //    $('#rating-star').rating('update', $('#slider-rating').val());
   //    console.log ($('#slider-rating').val());
   //  });
/*$( "#slider-rating" ).slider({
  stop: function( event, ui ) { console.log("stop")},
  start: function( event, ui ) {console.log("start")}
});
*/
  $(document).on("change", "#slider-rating", function(){
      // console.log( $(this).val());
      $('#rating-star').rating('update', $(this).val());
  });

   /*BootStrap Start-rating section*/

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
      $('#slider-rating').val('0').slider("refresh");
    });

    $('#titleKeyGen').on('tap',function (e) {
        let url = $('#url').val().trim();
        if(url != "" && isValidUrl(url))
        {
          $.mobile.loading( "show");
          let data = {'url': url, 'method': 'find_title_and_keyword' };
          ajaxCustom('index-live.php','GET',data,"json",
            function (datas,textStatus,jqXHR) {
              console.log(datas['length']);
              console.log(datas);
              if(datas['length'])
              {
                  $('#title').val(datas['title']);
                  $('#tags').val( datas['keywords']).trigger('input');
              }
              else
              {
                addPopUp("No Title and/or Tag found for url",'c');
              }
              
              $.mobile.loading( "hide");
            },
            function (jqXHR, exception) {
              $.mobile.loading( "hide");
              addPopUp("PHP Error occured while finding Title and Tag",'c');
            }
          );
        } 
        else
        {
          addPopUp("Please input a valid URL",'c');
        }
        return false;      
    });

});
