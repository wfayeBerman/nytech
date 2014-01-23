// GLOBAL VARIABLES
var $ = jQuery;
var pageDir;
var pageID;
var defaultPage = 'sample-page';
var RewriteBase = "/~illatoz/onepage/"; // if url is http://171.321.43.24/~foobar/ then the value should equal /~foobar/
var urlArray = window.location.pathname.replace(RewriteBase, '');
urlArray = urlArray.split("/");
var urlQueryString = "?" + Math.floor((Math.random()*10000)+1);
var defaultPageDir = "http://50.87.144.13/~illatoz/onepage/wp-content/themes/onepage";
var filesToVariablesArray = [
    {'text_input': 'views/input_text.php'},
    {'mainView': 'views/mainView.php'},
    {'sticky_side': 'views/output_sticky_side.php'},
    {'person_item': 'views/output_person_item.php'},
    {'event_item': 'views/output_event_item.php'},
    {'slide_page': 'views/output_slide_page.php'}
];
var pageOrder;
var pagesCollection;
var startUpRan = false;
var zIndexMax = 300;

// DOCUMENT READY
$(document).ready(function() {
    if(typeof $('body').data('tempdir') === "undefined"){
        pageDir = defaultPageDir;
    } else {
        pageDir = $('body').data('tempdir');
    }
    loadFilesToVariables(filesToVariablesArray);
        // var config = {
        //  kitId: 'mhq7lpe',
        //  scriptTimeout: 1000,
        //  loading: function() {
        //  // JavaScript to execute when fonts start loading
        //  },
        //  active: function() {
        //      loadFilesToVariables(filesToVariablesArray);
        //  },
        //  inactive: function() {
        //      loadFilesToVariables(filesToVariablesArray);
        //  }
        // };
        // var h=document.getElementsByTagName("html")[0];h.className+=" wf-loading";var t=setTimeout(function(){h.className=h.className.replace(/(\s|^)wf-loading(\s|$)/g," ");h.className+=" wf-inactive"},config.scriptTimeout);var tk=document.createElement("script"),d=false;tk.src='//use.typekit.net/'+config.kitId+'.js';tk.type="text/javascript";tk.async="true";tk.onload=tk.onreadystatechange=function(){var a=this.readyState;if(d||a&&a!="complete"&&a!="loaded")return;d=true;clearTimeout(t);try{Typekit.load(config)}catch(b){}};var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(tk,s)
    });

// LOAD FILES TO VARIABLES
function loadFilesToVariables(fileArray, ignoreStartUp){
    fileTracker = {};
    $.each(fileArray, function(index, value){
        fileKey = Object.keys(fileArray[index]);
        fileValue = "/" + fileArray[index][Object.keys(fileArray[index])];
        fileType = fileValue.split(".").slice(-1)[0];
        fileTracker[fileKey] = {};
        fileTracker[fileKey]['fileType'] = fileType;
        fileTracker[fileKey]['fileKey'] = fileKey;

        switch(fileType){
            case "json":
                fileTracker[fileKey]['pageData'] = $.getJSON(pageDir + fileValue + urlQueryString, function() {});
            break;

            case "html":
                fileTracker[fileKey]['pageData'] = $.get(pageDir + fileValue + urlQueryString, function() {});
            break;

            case "php":
                fileTracker[fileKey]['pageData'] = $.get(pageDir + fileValue + urlQueryString, function() {});
            break;
        }

        fileTracker[fileKey]['pageData'].complete(function(data){
            thisURL = this.url.replace(defaultPageDir + "/", '');
            thisURL = thisURL.split("?");
            thisURL = thisURL[0];
            $.each(filesToVariablesArray, function(index1, value1){
                if(thisURL.indexOf(filesToVariablesArray[index1][Object.keys(filesToVariablesArray[index1])]) > -1){
                    window[thisURL.split(".")[1] + "_" + Object.keys(filesToVariablesArray[index1])] = data.responseText;
                    fileLoaded();
                }
            });
        });
    });
}

repTracker = 0;
function fileLoaded(){
    repTracker++;
    if(repTracker == filesToVariablesArray.length){
        startUp();
    }
}

// SET FRONT END OR BACK END MODE
function startUp(){
    setPageID();
    if(pageID == "admin"){
        loadDatePicker($('.date'));
        loadSortable($(".sortable"));
        if($('#people_sample_hidden_meta').size() != 0){
            $('#people_sample_hidden_meta').find('.hidden_meta').val('I was generated dynamically')
        }
        $('head').append('<link rel="stylesheet" id="jquery-style-css" href="' + pageDir + '/styles/admin-styles.min.css" type="text/css" media="all">');
    } else {
        // prependUrl = returnPrependUrl();
        // fixLinks();
        // $('#menu-main-menu').superfish({
        // 	delay: 600,
        // 	speed: 300
        // });
        // loadEvents("menuClicker");
        // loadEvents("logoClicker");
        // loadEvents("subNavClicker");

        // loadEvents("footerClicker");
        // $('#menu-main-menu-1').easyListSplitter({ colNumber: 2 });

        // loadView(pageID, postID);
        startTime = new Date().getTime();
        startUpRan = true;

        runApplication();
    }
}

// PAGE SPECIFIC FUNCTIONS
    function checkPagesCollection(){
        if((Object.keys(pagesCollection['pageData']).length) == pagesCollection.size){
            // first page gets special treatment
            if(pagesCollection.hideDefault){
                jsonArgs1={
                    idArray: []
                }
                pagesCollection['pageData'][defaultPage].attr('data-stellar-background-ratio', '0.07');
                pagesCollection['pageData'][defaultPage].css('z-index', zIndexMax--);
                $('.mainView').append(pagesCollection['pageData'][defaultPage]);
                $('#' + defaultPage).flowtype({
                    minFont : 21
                });
            }
            // subsequent pages are then dealt with
            _.each(pageOrder, function(value, index){
                switch(index){
                    case "foobar":
                    pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                    pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                    $('.mainView').append(pagesCollection['pageData'][index]);
                    returnJsonData('listPeople', jsonArgs1).done(function(data){
                        console.log(data)
                    });

                    $('#foobar').flowtype({
                        minFont : 28,
                        maxFont : 36
                    });
                    break;

                    default:
                        // pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                        // pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                        // $('.mainView').append(pagesCollection['pageData'][index]);
                        // returnJsonData('listPeople', jsonArgs1).done(function(data){
                        //     console.log(data)
                        // });

                        // $('#foobar').flowtype({
                        //     minFont : 28,
                        //     maxFont : 36
                        // });
                    break;
                }
            })
            $(window).stellar();
            if(urlArray[0] != ""){
                if(!(urlArray[0] == defaultPage) && (window.pageYOffset == 0)){
                    goToByScroll(urlArray[0]);
                }
            }
        }
    }

// RETURN JSON DATA
    function returnJsonData(jsonRequest, args){
        if(typeof args !== 'undefined'){
            args['idArray'] = (typeof args['idArray'] === "undefined") ? null : args['idArray'];
        } else {
            args = {};
            args['idArray'] = null
        }
        returnedJsonData = $.post(pageDir + "/machines/handlers/loadJSON.php", { jsonRequest: jsonRequest, args: args }, function() {}, 'json');
        return returnedJsonData;
    }

// SET PAGE ID
    function setPageID(pageIDrequest){
        if($('body').hasClass("wp-admin")){
            pageID = "admin";
        } else {
            pageID = defaultPage;
            _.each(json_pages, function(value, index){
                if(urlArray[0] == value.pageID){
                    pageID = urlArray[0];
                }
            });
        }
    }

// RETURN PAGE DATA
    function returnPageData(pageRequest){
        $.each(json_pages, function(index, value){
            if(pageRequest == value.pageID){
                returnedPageData = $.post(pageDir + "/machines/handlers/loadPage.php", { pageID: value.wp_page_id}, function() {});
            }
        });
        return returnedPageData;
    }

// SCROLL TO PAGE
    function goToByScroll(dataslide) {
        $('html,body').animate({
            scrollTop: $('.slide[data-slide="' + dataslide + '"]').offset().top,
            complete: scrollEnded()
        }, 2000, 'easeInOutQuint');
    }

// PAGE SCROLL CALLBACK
    function scrollEnded(){
    }

// TURN SLUG INTO STRING
    function slugify(text){
        return text.toString().toLowerCase()
        .replace(/\+/g, '')           // Replace spaces with 
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
    }

// HISTORY PUSH STATE
    function pushHistory(){
        if (Modernizr.history){
            prependPushStateUrl = returnPrependUrl();
            var postIDurl = "";
            newPage = prependPushStateUrl + pageID + "/" + postIDurl;
            var stateObj = { pageID: newPage};
            history.pushState(stateObj, null, newPage);
        } 
    }

// HISTORY POP STATE
    $(window).on('popstate',function(e){
        if(startUpRan){
            setURLarray();
            popPage = (urlArray[0] == "") ? defaultPage : urlArray[0];
            setPageID(popPage);
            goToByScroll(popPage);
        }
    });

// GET WINDOW'S URL AND SET ARRAY
    function setURLarray(){
        tempUrlArray = window.location.pathname.replace(RewriteBase, '');
        urlArray = tempUrlArray.split("/");
    }

// RETURN BUILT URL PREPEND STRING
    function returnPrependUrl(){
        pageLevel = window.location.pathname.replace(RewriteBase, "").split("/");
        if(pageLevel[pageLevel.length-1] == ""){
            pageLevel.pop();
        }
        prependUrl = "";
        for (var i = 0; i < pageLevel.length; i++) {
            prependUrl += "../";
        };
        return prependUrl;
    }

// RUN APPLICATION
    function runApplication(){
        mainViewObject = $(php_mainView);
        pageOrder = {};
        pagesCollection = {
            size: 0,
            pageData: {},
            hideDefault: true
        };
        mainViewObject.find('.menu-item').each(function(index){
            if((pagesCollection.hideDefault) && (slugify($(this).children('a').text()) == defaultPage)){
                $(this).remove();
            } else {
                pageOrder[slugify($(this).children('a').text())] = index;
                $(this).children('a').attr('href', slugify($(this).children('a').text()));
                $(this).children('a').data('slide', slugify($(this).children('a').text()));
            }
            pagesCollection.size++
        });
        mainViewObject.find('.logoLink').attr('href', defaultPage);
        mainViewObject.find('.logoLink').data('slide', defaultPage);


        $('section').append(mainViewObject);

        $('.menu-main-menu-container, #logo').on('click', 'a', function(e){
            e.preventDefault();
            goToByScroll($(this).data('slide'));
            pageID = $(this).data('slide');
            pushHistory();
        });

        $('.menuButton').on('click', function(){
            if($('.headerMenu').hasClass('showMenu')){
                $('.headerMenu').removeClass('showMenu');
            } else {
                $('.headerMenu').addClass('showMenu');
            }
        });

        prevPage = "";
        _.each(json_pages, function(value, index1){
            json_pages[index1]['pageOrder'] = pageOrder[value.pageID];
            returnPageData(value.pageID).done(function(data){
                        // POPULATE WITH PAGE DATA
                        if(value.pageID != "auto-draft"){
                            returnObject = $(php_slide_page);
                            returnObject.attr('id', value.pageID);
                            returnObject.attr('data-slide', value.pageID);
                            returnObject.find('.container').append(data);
                            pagesCollection['pageData'][value.pageID] = returnObject;
                            checkPagesCollection();
                        }
                    });
        });


    }

// ADMIN
    // LOAD JQUERY UI DATE PICKER
    function loadDatePicker(target, changeCallback){
        hiddenDate = target.siblings('input');
        target.datetimepicker();
        if(hiddenDate.val() == ""){
            var myDate = new Date();
            var prettyDate =(myDate.getMonth()+1) + '/' + myDate.getDate() + '/' + myDate.getFullYear() + " " + myDate.getHours() + ":" + myDate.getMinutes();
            target.val(prettyDate);
            hiddenDate.val(Date.parse(prettyDate)/1000);
        }
        target.change(function() {
            $(this).siblings('input').val(Date.parse($(this).val())/1000);
            dateArray = [{event_start: $('input[name="event_start"]').val(), event_end: $('input[name="event_end"]').val()}];
            $('#event_date_array_meta').find('.hidden_meta').val(JSON.stringify(dateArray));
            if(changeCallback){
                updateRepeatConfig();
            }
        });
    }

    // LOAD JQUERY UI SORTABLE
        function loadSortable(target){
            target.sortable();
            target.disableSelection();
            target.on( "sortstop", function( event, ui ) {
                sortData = {};
                $(this).children('li').each(function(){
                    sortData[$(this).data('id')] = $(this).index();
                });
                var data = {
                    action: 'update_sort',
                    sort_data: sortData
                };
                $.post(ajaxurl, data, function(response) {
                    console.log('Got this from the server: ' + response);
                });                 
            });
        }

    // ADD CONSOLE SUPPORT TO IE8
        var method;
        var noop = function () {};
        var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
        ];
        var length = methods.length;
        var console = (window.console = window.console || {});
        while (length--) {
            method = methods[length];
            if (!console[method]) {
                console[method] = noop;
            }
        }


    // ADD OBJECT.KEYS SUPPORT TO IE8
        if (!Object.keys) {
            Object.keys = (function () {
                'use strict';
                var hasOwnProperty = Object.prototype.hasOwnProperty,
                hasDontEnumBug = !({toString: null}).propertyIsEnumerable('toString'),
                dontEnums = [
                'toString',
                'toLocaleString',
                'valueOf',
                'hasOwnProperty',
                'isPrototypeOf',
                'propertyIsEnumerable',
                'constructor'
                ],
                dontEnumsLength = dontEnums.length;
                return function (obj) {
                    if (typeof obj !== 'object' && (typeof obj !== 'function' || obj === null)) {
                        throw new TypeError('Object.keys called on non-object');
                    }
                    var result = [], prop, i;
                    for (prop in obj) {
                        if (hasOwnProperty.call(obj, prop)) {
                            result.push(prop);
                        }
                    }
                    if (hasDontEnumBug) {
                        for (i = 0; i < dontEnumsLength; i++) {
                            if (hasOwnProperty.call(obj, dontEnums[i])) {
                                result.push(dontEnums[i]);
                            }
                        }
                    }
                    return result;
                };
            }());
        }

