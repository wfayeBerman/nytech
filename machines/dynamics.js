// GLOBAL VARIABLES
var $ = jQuery;
var pageDir;
var pageID;
var defaultPage = 'home';
var RewriteBase = "/"; // if url is http://171.321.43.24/~foobar/ then the value should equal /~foobar/
var urlArray = window.location.pathname.replace(RewriteBase, '');
urlArray = urlArray.split("/");
var urlQueryString = "?" + Math.floor((Math.random()*10000)+1);
var defaultPageDir = "http://www.nytechforum.com/wp-content/themes/nytech";
var filesToVariablesArray = [
    {'text_input': 'views/input_text.php'},
    {'mainView': 'views/mainView.php'},
    {'page_section': 'views/page_section.php'},
    {'sticky_side': 'views/output_sticky_side.php'},
    {'person_item': 'views/output_person_item.php'},
    {'event_item': 'views/output_event.php'},
    {'event_info': 'views/output_event_info.php'},
    {'page_speakers': 'views/page_speakers.php'},
    {'output_speaker': 'views/output_speaker.php'},
    {'output_speaker_bio': 'views/output_speaker_bio.php'},
    {'page_partners': 'views/page_partners.php'},
    {'output_partners': 'views/output_partners.php'},
    {'output_list_image': 'views/output_list_image.php'},
    {'page_sponsorship': 'views/page_sponsorship.php'},
    {'output_sponsorship': 'views/output_sponsorship.php'},
    {'menu_hover': 'views/menu_hover.php'},
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
    // loadFilesToVariables(filesToVariablesArray);
        var config = {
         kitId: 'sqj0cba',
         scriptTimeout: 1000,
         loading: function() {
         // JavaScript to execute when fonts start loading
         },
         active: function() {
             loadFilesToVariables(filesToVariablesArray);
         },
         inactive: function() {
             loadFilesToVariables(filesToVariablesArray);
         }
        };
        var h=document.getElementsByTagName("html")[0];h.className+=" wf-loading";var t=setTimeout(function(){h.className=h.className.replace(/(\s|^)wf-loading(\s|$)/g," ");h.className+=" wf-inactive"},config.scriptTimeout);var tk=document.createElement("script"),d=false;tk.src='//use.typekit.net/'+config.kitId+'.js';tk.type="text/javascript";tk.async="true";tk.onload=tk.onreadystatechange=function(){var a=this.readyState;if(d||a&&a!="complete"&&a!="loaded")return;d=true;clearTimeout(t);try{Typekit.load(config)}catch(b){}};var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(tk,s)
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
                pagesCollection['pageData'][defaultPage].attr('data-stellar-background-ratio', Math.random());
                pagesCollection['pageData'][defaultPage].css('z-index', zIndexMax--);

                
                $('.mainView').append(pagesCollection['pageData'][defaultPage]);

                // Flowtype
                $('#home').flowtype({
                    minFont : 21,
                    maxFont : 50
                });
                 $('#home').removeClass('displayNone');

            }
            // subsequent pages are then dealt with
            _.each(pageOrder, function(value, index){
                switch(index){
                    case "overview":
                        pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                        pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                        $('.mainView').append(pagesCollection['pageData'][index]);

                        // get page data
                        returnPageData(index).done(function(data) {
                            // define object
                            returnObject = $(php_page_section);

                            // build object
                            returnObject.find('.pageInfo h2').html('Forum ' + index);
                            returnObject.find('.all-content').addClass(index);
                            returnObject.find('.all-content').html(_.unescape(data));

                            $('#' + index).find('.container').html(returnObject);

                            // Return Influencers Data
                            returnJsonData('listInfluencers', jsonArgs1).done(function(jsonData) {
                                // debug0 = jsonData;

                                returnInfluencersWrapper = $('<ul class="influencers" />');

                                _.each(jsonData, function(value, key) {
                                    returnInfluencersItem = $('<li />');
                                    returnInfluencersContainer = $('<div />');
                                    returnInfluencersImg = $('<img />');
                                    returnInfluencersImgDark = $('<img class="dark" />');

                                    // get dark image
                                    darkImg = value.featuredImage.split('.png');
                                    returnInfluencersImgDark.attr('src', darkImg[0] + '_dark.png');

                                    returnInfluencersImg.attr('src', value.featuredImage);
                                    returnInfluencersContainer.append(returnInfluencersImg);
                                    returnInfluencersContainer.append(returnInfluencersImgDark);
                                    returnInfluencersContainer.append(_.unescape(value.the_title));
                                    returnInfluencersItem.append(returnInfluencersContainer);

                                    returnInfluencersWrapper.append(returnInfluencersItem);
                                });

                                $('#' + index).find('.all-content').find('h3:contains(major topics)').before(returnInfluencersWrapper);

                                // alternate classes
                                $('#' + index).find('.all-content ul.influencers').find('li:odd').addClass('alternate');
                            });

                            $('#' + index).find('.pageInfo').flowtype({
                                minFont : 16,
                                maxFont : 115,
                            });

                            $('#' + index).find('.all-content').flowtype({
                                minFont : 16,
                                maxFont : 22,
                            });

                            $('#' + index).removeClass('displayNone');

                        });


                    break;

                    case "speakers":
                        pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                        pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                        $('.mainView').append(pagesCollection['pageData'][index]);
                        
                        returnJsonData('listSpeakers_type').done(function(speakersTypeData) {
                            // Define returnSpeakerObject
                            returnSpeakerObject = $(php_page_section);

                            // Build returnSpeakerObject
                            returnSpeakerObject.find('.pageInfo h2').html(index);
                            returnSpeakerObject.find('.all-content').addClass(index);

                            // Return speakers
                            returnJsonData('listSpeakers').done(function(data){
                                // loop speaker types
                                _.each(speakersTypeData, function(value, key) {
                                    speakersTypeWrapper = $('<div>');
                                    speakersTypeTitle = $('<h3 class="speakerTypeTitle" />');

                                    speakersTypeTitle.html(value.the_title);
                                    speakersTypeWrapper.addClass(slugify(value.the_title));
                                    speakersTypeWrapper.html(speakersTypeTitle);

                                    _.each(data, function(value1, key1) {
                                        returnSpeaker = $(php_output_speaker);
                                        returnSpeaker.data('speakerid', value1.post_id);

                                        _.each(value1, function(val, k) {
                                            switch(k){
                                                case 'featuredImage':
                                                    if(_.isNull(val)){
                                                        returnSpeaker.find('.photo').remove();
                                                    } else {
                                                        returnSpeaker.find('.photo').find('img').attr('alt', value1.first_name + ' ' + value1.last_name);
                                                        returnSpeaker.find('.photo').find('img').attr('src', val);
                                                    }
                                                    break;

                                                default:
                                                    returnSpeaker.find('.' + k).html(val);
                                                    break;
                                            }
                                        });

                                        if (value.post_id == value1.speaker_type) {
                                            speakersTypeWrapper.find('.speakerTypeTitle').after(returnSpeaker);
                                            returnSpeakerObject.find('.all-content').prepend(speakersTypeWrapper);
                                        }
                                    });
                                });

                                // init Shadowbox
                            Shadowbox.init({
                                skipSetup: true
                            });
                            // Speaker Bio toggle
                            $('.bio-toggle').on('click', function(e) {
                                e.preventDefault();

                                speakerID = $(this).parent().data('speakerid');

                                // Open bio with Shadowbox
                                Shadowbox.open({
                                    content: '<div class="speaker-popup" />',
                                    player: 'html',
                                    width: 800,
                                    height: 600,
                                    options: {
                                        onFinish: function() {
                                            returnSpeakerBio = $(php_output_speaker_bio);

                                            _.each(data, function(value, key) {
                                                if (value.post_id == speakerID) {
                                                    _.each(value, function(val, k) {
                                                        switch(k) {
                                                            case 'featuredImage':
                                                                if(_.isNull(val)){
                                                                    returnSpeakerBio.find('.photo').remove();
                                                                } else {
                                                                    returnSpeakerBio.find('.photo').find('img').attr('alt', value.first_name + ' ' + value.last_name);
                                                                    returnSpeakerBio.find('.photo').find('img').attr('src', val);
                                                                }
                                                                break;

                                                            case 'the_content':
                                                                returnSpeakerBio.find('.' + k).html(_.unescape(val));
                                                                break;

                                                            default:
                                                                returnSpeakerBio.find('.' + k).html(val);
                                                                break;
                                                        }

                                                        // Append speaker to popup
                                                        $('.speaker-popup').html(returnSpeakerBio);
                                                    });
                                                }
                                            });
                                        },
                                        // onFinish: function() {
                                        //     console.log('finish');
                                        // }
                                    }
                                });
                            });
                            });

                            // Get Page Data
                            returnPageData(index).done(function(pageData) {
                                pageDataWraper = $('<div class="more" />');
                                pageDataWraper.html(_.unescape(pageData))
                                returnSpeakerObject.find('.all-content').append(pageDataWraper);
                            });

                            // Append returnSpeakerObject
                            $('#' + index).find('.container').html(returnSpeakerObject);

                            $('#' + index).find('.pageInfo').flowtype({
                                minFont : 16,
                                maxFont : 115,
                            });

                            $('#' + index).find('.all-content').flowtype({
                                minFont : 16,
                                maxFont : 20,
                            });

                            $('#' + index).removeClass('displayNone');
                        });

                    break;

                    case "schedule":
                        pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                        pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                        $('.mainView').append(pagesCollection['pageData'][index]);
                        returnJsonData('listEvents', jsonArgs1).done(function(data) {
                            // debug0 = data;
                            // define object    
                            returnObject = $(php_page_section);
                            returnEventsWrapper = $('<ul />');
                            returnDivider = $('<div class="divider" />');
                            returnEventDate = $('<h3 class="event-date" / >');
                            returnEventInfo = $(php_event_info);

                            // build object
                            returnObject.find('.pageInfo h2').html(index);
                            returnObject.find('.all-content').addClass(index);

                            // build date from first item
                            eventDate = moment.unix(data[0].event_start).format('dddd, MMM D, YYYY');
                            returnEventDate.html(eventDate);

                            returnObject.find('.all-content').append(returnEventDate);

                            // Build events
                            _.each(data, function(value, key) {
                                returnEvent = $(php_event_item);
                                returnEvent.find('.event-toggle').data('eventid', value.post_id);

                                _.each(value, function(val, k) {
                                    switch(k) {
                                        case "event_start":
                                            startDate = moment.unix(val).format('h:mm A');
                                            returnEvent.find('.' + k).html(startDate);
                                            break;

                                        case "the_title":
                                            returnEvent.find('.' + k).html(_.unescape(val));
                                            break;

                                        case "the_content":
                                            returnEvent.find('.' + k).html(_.unescape(val));
                                            break;

                                        default:
                                            returnEvent.find('.' + k).html(val);
                                            break;
                                    }
                                });
                                returnEventsWrapper.append(returnEvent);
                            });

                            // Populate info box from first event
                            firstEvent = _.first(data);
                            _.each(firstEvent, function(value, key) {
                                switch(key) {
                                    case "event_start":
                                        startDate = moment.unix(value).format('h:mm A');
                                        returnEventInfo.find('.' + key).html(startDate);
                                        break;

                                    case "event_end":
                                        endDate = moment.unix(value).format('h:mm A');
                                        returnEventInfo.find('.' + key).html(endDate);
                                        break;

                                    case "the_title":
                                        returnEventInfo.find('.' + key).html(_.unescape(value));
                                        break;

                                    case "the_content":
                                        returnEventInfo.find('.' + key).html(_.unescape(value));
                                        break;
                                    default:
                                        returnEventInfo.find('.' + key).html(value);
                                        break;
                                }
                            });

                            returnObject.find('.all-content').append(returnEventsWrapper);
                            returnObject.find('.all-content').append(returnEventInfo);

                            $('#' + index).find('.container').html(returnObject);
                            // $('#' + index).find('.container').append(returnDivider);

                            // Set first item active
                            $('#' + index).find('.all-content').find('li:first-child').addClass('active');
                            $('#' + index).find('.all-content').find('li:first-child').find('.event-info').removeClass('hidden');

                            // Toggle Status/More info
                            $('.event-toggle').on('click', function(e) {
                                e.preventDefault();

                                infoParent = $(this).parent();

                                // hide if parent is not active
                                if (!infoParent.hasClass('active')) {
                                    $('.event-info').slideUp();
                                    $('.event-info').addClass('hidden');
                                }

                                // add active state
                                $('.all-content').find('li').removeClass('active');
                                infoParent.addClass('active');

                                // show info
                                eventInfo = $(this).next();
                                eventInfo.slideToggle();
                                eventInfo.toggleClass('hidden');

                                eventId = $(this).data('eventid');

                                // Make into function?
                                $('.event-info-box .info-wrapper > div').html('');
                                _.each(data, function(value, key) {
                                    if (eventId == value.post_id) {
                                        _.each(value, function(val, k) {
                                            switch(k) {
                                                case "event_start":
                                                    startDate = moment.unix(val).format('h:mm A');
                                                    $('.event-info-box').find('.' + k).html(startDate);
                                                    break;

                                                case "event_end":
                                                    endDate = moment.unix(value).format('h:mm A');
                                                    $('.event-info-box').find('.' + key).html(endDate);
                                                    break;

                                                case "the_title":
                                                    $('.event-info-box').find('.' + k).html(_.unescape(val));
                                                    break;

                                                case "the_content":
                                                    $('.event-info-box').find('.' + k).html(_.unescape(val));
                                                    break;

                                                default:
                                                    $('.event-info-box').find('.' + k).html(val);
                                                    break;
                                            }
                                        });
                                    }
                                });

                            });

                            $('#' + index).find('.pageInfo').flowtype({
                                minFont : 16,
                                maxFont : 115,
                            });

                            $('#' + index).find('.all-content').flowtype({
                                minFont : 16,
                                maxFont : 30,
                            });

                            $('#' + index).removeClass('displayNone');
                        });

                    break;

                    case "partners":
                        pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                        pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                        $('.mainView').append(pagesCollection['pageData'][index]);
                        returnJsonData('listPartnershipTypes', jsonArgs1).done(function(data) {

                            // // define object
                            returnObject = $(php_page_section);

                            // // Build object
                            returnObject.find('.pageInfo h2').html(index);

                            // // if data
                            if (!_.isEmpty(data)) {

                                _.each(data, function(value, key) {
                                    returnSponsorType = $(php_output_partners);

                                    returnSponsorType.attr('data-sponsorshipid', value.post_id);
                                    returnSponsorType.addClass(slugify(value.the_title));
                                    returnSponsorType.find('h3').html(value.the_title);

                                    returnObject.find('.all-content').append(returnSponsorType);
                                });

                            }

                            // Return individual sponsors
                            returnJsonData('listPartners', jsonArgs1).done(function(partnersData) {

                                // loop partners
                                if (!_.isEmpty(partnersData)) {

                                    _.each(partnersData, function(value, key) {
                                        returnPartner = $(php_output_list_image);

                                        _.each(value, function(val, k) {
                                            switch(k) {
                                                case "post_id":
                                                    returnPartner.data('partnerid', val);
                                                    break;

                                                case "attachments":
                                                    returnPartner.find('img').attr('src', value.featuredImage);
                                                    websiteURL = value.website;
                                                    if (websiteURL != "") {
                                                        if (websiteURL.indexOf('http://') != -1) {
                                                            websiteURL = websiteURL;
                                                        } else {
                                                            websiteURL = 'http://' + websiteURL;
                                                        }
                                                        returnPartner.find('img').wrap('<a target="_blank" />');
                                                        returnPartner.find('a').attr('href', websiteURL);
                                                    }
                                                    // if (val != null) {
                                                    //     _.each(val, function(l, m) {
                                                    //         returnPartner.find('img').wrap('<a />');
                                                    //         returnPartner.find('img').attr('src', l.full);
                                                    //     })
                                                    // }
                                                    break;
                                            }
                                        });
                                        
                                        $('.all-content').find('div[data-sponsorshipid="'+ value.partnership_type +'"] ul').append(returnPartner).parent().removeClass('hidden');

                                    });

                                }

                            });

                            $('#' + index).find('.container').append(returnObject);

                            $('#' + index).find('.pageInfo').flowtype({
                                minFont : 16,
                                maxFont : 115,
                            });

                            $('#' + index).find('.all-content').flowtype({
                                minFont : 16,
                                maxFont : 22,
                            });

                            $('#' + index).removeClass('displayNone');
                        });
                    break;

                    case "register":
                        pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                        pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                        $('.mainView').append(pagesCollection['pageData'][index]);
                        
                        // get page data
                        returnPageData(index).done(function(data) {
                            // define object
                            returnObject = $(php_page_section);

                            // build object
                            returnObject.find('.pageInfo h2').html(index);
                            returnObject.find('.all-content').addClass(index);
                            returnObject.find('.all-content').html(_.unescape(data));

                            $('#' + index).find('.container').html(returnObject);

                            $('#' + index).find('.pageInfo').flowtype({
                                minFont : 16,
                                maxFont : 115,
                            });

                            $('#' + index).find('.all-content').flowtype({
                                minFont : 16,
                                maxFont : 40,
                            });

                            $('#' + index).removeClass('displayNone');
                        });
                    break;

                    case "sponsorships":
                        pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                        pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                        $('.mainView').append(pagesCollection['pageData'][index]);
                        returnJsonData('listPartnershipTypes', jsonArgs1).done(function(data) {
                            debug0 = data;

                            // define object
                            returnObject = $(php_page_sponsorship);

                            // build object
                            returnObject.find('.pageInfo h2').html(index);

                            // if data
                            if (!_.isEmpty(data)) {

                                // loop sponsorship types
                                _.each(data, function(value, key) {
                                    returnSponsorship = $(php_output_sponsorship);

                                    _.each(value, function(val, k) {
                                        switch(k) {
                                            case "post_id":
                                                returnSponsorship.data('sponsorshipid', val);
                                                break;

                                            case "the_title":
                                                returnSponsorship.find('.' + k).html(val + ': ');
                                                break;

                                            case "price":
                                                returnSponsorship.find('.' + k).html('$' + val);
                                                break;

                                            default:
                                                returnSponsorship.find('.' + k).html(_.unescape(val));
                                                break;
                                        }
                                    });
                                    
                                    // exclude media partners and association partners
                                    if (value.post_id != '217' && value.post_id != '218') {
                                        returnObject.find('.all-sponsorship').append(returnSponsorship);
                                    }
                                });

                            }

                            $('#' + index).find('.container').append(returnObject)

                            $('#' + index).find('.pageInfo').flowtype({
                                minFont : 16,
                                maxFont : 115,
                            });

                            $('#' + index).find('.all-sponsorship').flowtype({
                                minFont : 16,
                                maxFont : 22,
                            });

                            $('#' + index).removeClass('displayNone');
                        });
                    break;

                    case "credit":
                        pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                        pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                        $('.mainView').append(pagesCollection['pageData'][index]);
                        returnJsonData('listPartnershipTypes', jsonArgs1).done(function(data) {

                            $('#' + index).flowtype({
                                minFont : 16,
                                maxFont : 25,
                            });

                            $('#' + index).removeClass('displayNone');
                        });
                    break;

                    default:
                        // pagesCollection['pageData'][index].attr('data-stellar-background-ratio', Math.random())
                        // pagesCollection['pageData'][index].css('z-index', zIndexMax--);
                        // $('.mainView').append(pagesCollection['pageData'][index]);
                        // returnJsonData('listSpeakers', jsonArgs1).done(function(data){
                        //     //console.log(data)
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
            if((pagesCollection.hideDefault) && ( (slugify($(this).children('a').text()) == defaultPage) || (slugify($(this).children('a').text()) == 'test') )){
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

        // Append menu hover
        menuHover('#menu-main-menu');

        $('.menu-main-menu-container, #logo').on('click', 'a', function(e){
            e.preventDefault();
            goToByScroll($(this).data('slide'));
            pageID = $(this).data('slide');
            pushHistory();
        });

        $('.menuButton').on('click', function(){
            if($('.headerMenu').hasClass('showMenu')){
                $('.headerMenu').removeClass('showMenu');
                $('.headerMenu').find('#nav').slideUp();
            } else {
                $('.headerMenu').addClass('showMenu');
                $('.headerMenu').find('#nav').slideDown();
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

// ADD HOVER MENU VIEW
    function menuHover(target) {
        // menuHover = php_menu_hover;
        $(target).find('li').each(function() {
            $(this).append(php_menu_hover);
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