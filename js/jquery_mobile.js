
var loaded = false;
var state = "add";
// var addStatusText = "";
// var theme = '';

// $(document).ready(function(){
$(document).one('pagecreate',function(){
  /* Instantiate the popup on DOMReady, and enhance its contents */
  $( "#popup-area" ).enhanceWithin().popup();

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
    }); //end of 'form.ajax'

        // Remove the popup after it has been closed to manage DOM size
    $( document ).on( "popupafterclose", ".ui-popup", function() {
        $( this ).remove();
          $( "#popup-area" ).empty();
    });


  /*BootStrap Start-rating section*/
  

}); //End of DOM ready function

          /*Search Page*/
$(document).one('pagecreate','#p1',function(){
    //Info button
    $("#info").on("click",function (event) {
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
        starCaptions: function (val) {return starCaptionRange(val)},
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

    $("#googleButton").on("click",function(){
        window.open("http://www.google.com", "_system");
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
      $('#slider-rating').val(0).slider("refresh");
    });

});
