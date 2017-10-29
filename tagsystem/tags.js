(function ($) {
    "use strict";
    $.fn.tagSystem = function (options) {
        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            maxTags: 5, autocomplete_url:"engine.php",addAutocomplete:true
            // listviewAjaxSuccess: function (data) {}
        }, options);

        return this.each(function () {
            let hiddenInput = document.createElement('input'),
                divWrapper = document.createElement('div'),
                tagsArr = [];
            var mainInput = $(this).get(0);

            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('required', '');
            hiddenInput.setAttribute('name', mainInput.getAttribute('data-name'));
            divWrapper.className = "ui-field-contain";
            mainInput.setAttribute('placeholder',"e.g. Walmart,Kingsuper|https://superkingmarkets.com")

            $(this).after(divWrapper);
            $(this).after(hiddenInput);
            var ul ;
            if(settings.addAutocomplete == true)
            {
              ul = autoComplete();
            }
            
            $(mainInput).on('blur', function (e) {
                if (settings.addAutocomplete == true)
                {
                  $(ul).html("");
                  $(ul).listview( "refresh" );
                }
            });


            $(mainInput).on('input focus', function (e) {
            // mainInput.addEventListener('input', function () {
              console.log("Inputed something");
              let enteredTags = mainInput.value.split(',');
              if (enteredTags.length > 1) {
                if(tagsArr.length < settings.maxTags){
                  enteredTags.forEach(function (t) {
                      let filteredTag = filterTag(t);
                      if (filteredTag['text'].length > 0  && tagsArr.find(dupTags,filteredTag['text']) === undefined)
                          addTag(filteredTag['text'],filteredTag['url']);
                  });
                } else alert("You have reach MAX tags limit of " + settings.maxTags);
                  console.log("tagsArr.length="+ tagsArr.length +" / settings.maxTags=" + settings.maxTags );
                  mainInput.value = '';
              }
              else if (settings.addAutocomplete == true)
              {
                var $ul = $(ul);
                var $mainInput = $(this),
                value = $mainInput.val();
              
                console.log("in UL filterablebeforefilter");
                  if ( value.trim().length > 0 && tagsArr.length < settings.maxTags ) 
                  {
                         $mainInput.val(value); //Need this Because Chrome on adroid was doing some weird stuff 
                         // $mainInput.textinput( "refresh" );
                      console.log("Going to call Ajax");
                      $.ajax({
                          url: settings.autocomplete_url,
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
                              let li = $("<li data-icon='plus' data-theme ="+((val['tag_url'].trim() != "")?"b":"a")+"> <a href='"+val['tag_url']+ "'>"+ val['tag_name'] + "</a></li>");
                              li.children('a').on("tap",function (e) {
                                let a = $(this);
                                if(tagsArr.length < settings.maxTags && tagsArr.find(dupTags, val['tag_name']) === undefined)
                                {
                                  addTag(a.text(),a.attr('href')); 
                                }
                                // $mainInput.val("");//.textinput( "refresh" ).siblings('a').trigger('click');
                                $ul.html("");
                                $ul.listview( "refresh" );
                                 $mainInput.val("");
                                 $mainInput.trigger('change');
                                 // $mainInput.textinput( "refresh" );
                                // return false;
                                e.preventDefault();
                                 // e.stopPropagation();
                                // alert($mainInput.val());
                              });
                              $ul.append(li);
                          });
                          $ul.listview( "refresh" );
                          $ul.trigger( "updatelayout");
                      });
                  } else {$ul.html( "" ); $ul.listview( "refresh" );}
              }

            });


            mainInput.addEventListener('keydown', function (e) {
                let keyCode = e.which || e.keyCode;
                if (keyCode === 8 && mainInput.value.length === 0 && tagsArr.length > 0) {
                  console.log("Delete Key Pressed");
                    removeTag(tagsArr.length - 1);
                }

                // prevent for tab
                if(e.keyCode == 9) {
                  e.preventDefault();
                }
                // for enter key
                if(e.keyCode == 13) {
                  console.log("Enter Key Pressed");
                  if(tagsArr.length < settings.maxTags)
                  {
                    let filteredTag = filterTag(mainInput.value);
                    if (filteredTag['text'].length > 0  && tagsArr.find(dupTags,filteredTag['text']) === undefined)
                      addTag(filteredTag['text'],filteredTag['url']);
                  }
                  mainInput.value = '';
                  e.preventDefault();
                  // return false;
                }

            });

          function autoComplete() {

            let ul = document.createElement('ul');
            ul.setAttribute ("data-inset","true");
            ul.setAttribute ("data-role","listview");
            // ul.setAttribute ("data-filter","true");
            // ul.setAttribute ("data-filter-reveal","true");
            // ul.setAttribute ("data-input","#"+mainInput.id);
            ul.id = mainInput.id + "-autoComplete";
            $(hiddenInput).after(ul);
            return ul;
          }

          function dupTags (tag) {
            let filteredTagText = this;
            if(tag['text'] == filteredTagText.toLowerCase()) 
              console.log("Exists tag['text'] = " + tag['text']);
            return tag['text'] == filteredTagText.toLowerCase();
          }

          function filterTag (tag) {
            let seperateIndex = -1,
                            t = [];
            seperateIndex = tag.search(/\|/); //Look for '|'
            t['url'] = "";
            if( seperateIndex >= 0)
            {
              t['text'] =  tag.slice(0,seperateIndex).replace(/[^\w -]/g, '').trim();
              var url = tag.slice(seperateIndex+1).trim(); 
              if(t['text'].length > 0 && url.length > 0)
              {
                if(isValidUrl(url))
                  t['url'] = url;
              }
            }
            else
            {
              t['text'] = tag.replace(/[^\w -]/g, '').trim();
            }

            return t;
          }
            
           // addTag("hello");
           // addTag("Walmart","http://walmart.com");
            function addTag (text,url) {
              let tag = {
              text: text.toLowerCase(),
              element: document.createElement('a')
              };

              tag.element.className = "ui-btn ui-btn-"+ ((url != "")?"b":"a") +" ui-btn-inline ui-mini ui-icon-delete ui-btn-icon-right";
              tag.element.textContent = tag.text;
              tag.element.setAttribute('href',url);

              $(tag.element).on('tap', function (e) {
              // tag.element.addEventListener('click', function (e) {
                removeTag(tagsArr.indexOf(tag));
                e.preventDefault();
              });

              tagsArr.push(tag);

              // divWrapper.appendChild(tag.element);
              divWrapper.insertBefore(tag.element,divWrapper.childNodes[0]);

              refreshTags();

            }//End of addTag()

            function removeTag (index) {
                    let tag = tagsArr[index];
                    tagsArr.splice(index, 1);
                    divWrapper.removeChild(tag.element);
                    refreshTags();
            }

            function refreshTags () {
              let tagsList = [];
              tagsArr.forEach(function (t) {
                  tagsList.push({text:t.text,url:t.element.getAttribute('href')});
              });
              if(tagsList.length)
                hiddenInput.value = JSON.stringify(tagsList);
              else
                hiddenInput.value="";
            }

            // Public functions
            this.isTagAdded = function(e){
                console.log("tagsArr.length = " + tagsArr.length  );
                return tagsArr.length;
            };
            this.clearAll = function(e){
              mainInput.value = "";
              tagsArr.splice(0, tagsArr.length);
              $(divWrapper).empty();
              // divWrapper.removeChild(tag.element);
              refreshTags();
            };


        }); //End of this.each

        // return this;

    };



    function isValidUrl(url) {
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

}(jQuery));
