/**
 * Created by aravind on 4/28/17.
 */

$(function () {

  var getUrl = window.location;
  var url = getUrl .protocol + "//" + getUrl.host;

  $(".chat_on").click(function(){
    $(".Layout").toggle();
    $(".chat_on").hide(300);
    $('#message').focus();
  });

     $(".chat_close_icon").click(function(){
    $(".Layout").hide();
       $(".chat_on").show(300);
  });

    $('form#chatBotDialogflow').on('submit', function (e) {
        var query = $("#message").val();

		if( query === "" )
			return false;

      $("#message").attr('readonly', true);
		var objDiv = document.getElementById("scroll");

        showUserText();
        e.preventDefault();

		objDiv.scrollTop = objDiv.scrollHeight;

        $.ajax({
            type: 'post',
            url: url+'/local/chatbot_dialogflow/process.php',
            data: {submit:true, message:query},
            success: function (response) {
                $("#message").attr('readonly', false);
                $('#message').focus();
                var responseObj = JSON.parse(response);
                var defaultResponse = null;
                if(responseObj.defaultResponse){
                    defaultResponse = responseObj.defaultResponse;
                }
                var speech = responseObj.speech;
                var messages = responseObj.messages;
                var eoc = responseObj.isEndOfConversation;

                var answerRow = jQuery('<div/>',{
                    'class':'row'
                });
                var answerCol = jQuery('<div/>',{
                    'class':'col'
                });
                var answerContainerDiv = jQuery('<div/>',{
                    'class':"float-right",
                    tabindex:0
                });

                $('#chat-text').append(answerRow);
                $(answerRow).append(answerCol);
                $(answerCol).append(answerContainerDiv);


                var textFromDefaultResponse = defaultResponse;
                if (textFromDefaultResponse && textFromDefaultResponse.trim()!==''){
                    renderDefaultResponse(textFromDefaultResponse,answerContainerDiv);
                }
                renderRichControls(messages, answerContainerDiv);


                var isDisabled = $('#message').prop('disabled');
                if(eoc){
                    $('#message').attr("disabled","disabled");
                    $('#chat-text').append('<hr/>');
                    var divMessage = $('<div/>',{
                        class:'d-flex justify-content-center'
                    });
                    var btnStartOver = $('<button/>',{
                        class:'btn btn-sm',
                        text:'Start Over'
                    });
                    var textStartOver = $('<h5/>',{
                        html:'End of Conversation'
                    });
                    $(divMessage).append(textStartOver);
                    $(btnStartOver).css('margin-left','10px');
                    $(divMessage).append(btnStartOver);
                    $('#chat-text').append(divMessage);
                    $(btnStartOver).click(function(){
                        var textToSubmit = 'start over';
                        $("#message").val(textToSubmit);
                        $( "form#chatBotDialogflow" ).trigger( "submit" );
                        $(divMessage).addClass('disabledbutton')
                    });
                }

                objDiv.scrollTop = objDiv.scrollHeight;
            }
        });

    });


});

function postAjax(query){
    $.ajax({
        type: 'post',
        url: url+'/local/chatbot_dialogflow/process.php',
        data: {submit:true, message:query},
        success: function (response) {
            $("#message").removeAttr("disabled");
            $('#message').focus();
            var responseObj = JSON.parse(response);
            var defaultResponse = null;
            if(responseObj.defaultResponse){
                defaultResponse = responseObj.defaultResponse;
            }
            var speech = responseObj.speech;
            var messages = responseObj.messages;
            var eoc = responseObj.isEndOfConversation;

            var answerRow = jQuery('<div/>',{
                'class':'row'
            });
            var answerCol = jQuery('<div/>',{
                'class':'col'
            });
            var answerContainerDiv = jQuery('<div/>',{
                'class':"float-right",
                tabindex:0
            });

            $('#chat-text').append(answerRow);
            $(answerRow).append(answerCol);
            $(answerCol).append(answerContainerDiv);


            var textFromDefaultResponse = defaultResponse;
            if (textFromDefaultResponse && textFromDefaultResponse.trim()!==''){
                renderDefaultResponse(textFromDefaultResponse,answerContainerDiv);
            }
            renderRichControls(messages, answerContainerDiv);


            var isDisabled = $('#message').prop('disabled');
            if(eoc){
                $('#message').attr("disabled","disabled");
                $('#chat-text').append('<hr/>');
                var divMessage = $('<div/>',{
                    class:'d-flex justify-content-center'
                });
                var btnStartOver = $('<button/>',{
                    class:'btn btn-sm btn-danger',
                    text:'Start Over'
                });
                var textStartOver = $('<h3/>',{
                    html:'End of Conversation'
                });
                $(divMessage).append(textStartOver);
                $(btnStartOver).css('margin-left','10px');
                $(divMessage).append(btnStartOver);
                $('#chat-text').append(divMessage);
                $(btnStartOver).click(function(){
                    var textToSubmit = 'start over';
                    $("#message").val(textToSubmit);
                    $( "form#chatBotDialogflow" ).trigger( "submit" );
                    $(divMessage).addClass('disabledbutton')
                });
            }
            var objDiv = document.getElementById("chat-text");
            objDiv.scrollTop = objDiv.scrollHeight;
        }
    });
}

function renderDefaultResponse(textFromDefaultResponse,parent){
    var simpleResponseRow = jQuery('<div/>',{
        class:'row'
    });
    var simpleResponseDiv = jQuery('<div/>',{
        class:'textResponse'
    });
    $(simpleResponseRow).append(simpleResponseDiv);
    $(simpleResponseDiv).html(md2html(textFromDefaultResponse));
    parent.append(simpleResponseRow);
}

function renderRichControls(data, parent){

    var i,len = data.length;
    for(i=0;i<len;i++){
        if(data[i] && data[i].hasOwnProperty('platform')){
            if(data[i]['platform']==='ACTIONS_ON_GOOGLE'){
                if(data[i].hasOwnProperty('simpleResponses')){
                    renderSimpleResponse(data[i],parent);
                }
                if(data[i].hasOwnProperty('basicCard')){
                    renderBasicCard(data[i],parent);
                }
                if(data[i].hasOwnProperty('listSelect')){
                    renderList(data[i],parent);
                }
                if(data[i].hasOwnProperty('suggestions')){
                    renderSuggestionChips(data[i],parent);
                }
                if(data[i].hasOwnProperty('linkOutSuggestion')){
                    renderLinkOutSuggestion(data[i],parent);
                }
            }
            if(data[i]['type']==='list_card' &&
                data[i]['platform']==='ACTIONS_ON_GOOGLE'){
                renderList(data[i],parent);
            }
            if(data[i]['type']==='carousel_card' &&
                data[i]['platform']==='ACTIONS_ON_GOOGLE'){
                renderCarousel(data[i],parent);
            }
        }
    }

    for(i=0;i<len;i++){
        if(data[i] && data[i].hasOwnProperty('type')){
            if(data[i]['type']==='suggestion_chips' &&
                data[i]['platform']==='google'){
                renderSuggestionChips(data[i],parent);
            }
        }
    }

}

function renderList(data,parent){
    data = data['listSelect'];
    var i, len = data['items'].length;
    var listGroup = jQuery('<div/>',{
        'class':'list-group card gaListGroup'
    });
    if(data['title']){
        var titleOfCard = data['title'];
        var listGroupHeading = jQuery('<div/>',{
            'class':'gaListHeader card-header deep-orange lighten-1 white-text',
            'html':titleOfCard
        });
        listGroup.append(listGroupHeading);
    }
    for(i=0;i<len;i++){
        var item = data['items'][i];
        if(item){
            var optionTitle = item["title"];
            var optionDescription = item["description"];
            var optionKey = item['info']['key'];
            var imageUrl;
            if(item["image"]){
                imageUrl = item["image"]["imageUri"];
            }
            var anchor = jQuery('<a/>',{
                'data-key':optionKey,
                'class':'gaListItem list-group-item py-0 list-group-item-action flex-column ' +
                'align-items-start'
            });
            anchor.click(function(){
                if(window.currentSuggestionChips){
                    var buttonRow = window.currentSuggestionChips;
                    buttonRow.remove();
                    window.currentSuggestionChips = null;
                    $("#message").removeAttr("disabled");
                }
                var textToSubmit = $(this).attr('data-key');
                $("#message").val(textToSubmit);
                $( "form#chatBotDialogflow" ).trigger( "submit" );
                $(listGroup).addClass('disabledbutton');
            });
            var headingDiv = jQuery('<div/>',{
            });
            var heading = jQuery('<div/>',{
                'class':'card-title',
                'text':optionTitle
            });
            heading.css("font-weight","bold");
            var row = jQuery('<div/>',{
                'class':'row'
            });
            var colSpanText = 'col';
            if(imageUrl) colSpanText = 'col-8';
            var colText = jQuery('<div/>',{
                'class':colSpanText
            });
            var colImage =jQuery('<div/>',{
                'class':'col-4'
            });
            var para = jQuery('<p/>',{
                'class':'mb-1',
                'html':optionDescription
            });

            if(imageUrl){
                var img = jQuery('<img/>',{
                    'class':'img-fluid',
                    'src':imageUrl,
                    'width':'50px'
                });
                colImage.append(img);
            }
            row.append(colText);
            if(imageUrl) row.append(colImage);
            headingDiv.append(heading);
            headingDiv.append(para);
            colText.append(headingDiv);
            anchor.append(row);
            listGroup.append(anchor);
        }
    }
    parent.append(listGroup);
    $("#message").attr("disabled","disabled");
}

function renderCarousel(data,parent){
    var i, len = data['items'].length;
    var carouselContainer = jQuery('<div/>',{
        'width':'550px'
    });
    $(carouselContainer).addClass('gaCarousel');
    var listGroup = jQuery('<ul/>',{

    });
    for(i=0;i<len;i++){
        var item = data['items'][i];
        if(item){
            var optionTitle = truncateString(item["title"],20);
            var optionDescription = item["description"];
            var optionKey = item["optionInfo"]["key"];
            var imageUrl = item["image"]["url"];
            var listItem = jQuery('<li/>',{});
            var cardDiv = jQuery('<div/>',{
                'width':'200px'
            });
            $(cardDiv).addClass('gaCarouselItem');
            var anchor = jQuery('<a/>',{
                'data-key':optionKey,
                'class':'list-group-item list-group-item-action flex-column '+
                'align-items-start'
            });
            anchor.click(function(){
                if(window.currentSuggestionChips){
                    var buttonRow = window.currentSuggestionChips;
                    buttonRow.remove();
                    window.currentSuggestionChips = null;
                    $("#message").removeAttr("disabled");
                }
                var textToSubmit = $(this).attr('data-key');
                $("#message").val(textToSubmit);
                $( "form#chatBotDialogflow" ).trigger( "submit" );
                $(carouselContainer).addClass('disabledbutton');
            });
            var heading = jQuery('<div/>',{
                'class':'card-title',
                'text':md2html(optionTitle)
            });
            heading.css("font-weight","bold");
            var para = jQuery('<p/>',{
                'class':'mb-1',
                'html':md2html(optionDescription)
            });
            var divForImage = jQuery('<div/>',{
                'class':'card-title'
            });
            divForImage.css("height","100px");
            var img = jQuery('<img/>',{
                'class':'img-fluid',
                'src':imageUrl,
                'width':'100px'
            });
            img.css("margin-left","auto");
            img.css("margin-right","auto");
            img.css("display","block");
            divForImage.append(img);

            cardDiv.append(divForImage);
            anchor.append(heading);
            anchor.append(para);
            cardDiv.append(anchor);
            listItem.append(cardDiv);
            listGroup.append(listItem);
        }
    }
    parent.append(carouselContainer);
    carouselContainer.append(listGroup);
    $(listGroup).lightSlider({
        autoWidth:true
    });
    $("#message").attr("disabled","disabled");
}

function renderBasicCard(data,parent){
    data = data['basicCard'];
    var cardDiv = jQuery('<div/>',{
        'class':'card gaCard'
    });
    var img = jQuery('<img/>',{
        'class':'gaCardImage',
        'src':data['image']['imageUri']
    });
    var cardBodyDiv = jQuery('<div/>',{
        'class':'card-body'
    });

	try {
		var strTitle = truncateString(data['title'],28);
	}
	catch (e) {
		console.error("Erro: ", "Título do Card não definido - " + e.message);
	}

    var cardTitleContainerDiv = jQuery('<h5/>',{
        'class':'card-title',
        'html':md2html(strTitle)
    });

	try {
		var textContainerPara = jQuery('<p/>',{
			'class':'card-text',
			'html':md2html(data['formattedText'])
		});
	}
	catch (e) {
		console.error("Erro: ", "Texto do Card não definido - " + e.message);
	}

    var linkDiv = jQuery('<div/>',{
        'class':'card-link'
    }).append('<hr/>');
    var buttons_array = data['buttons'];
    if (typeof buttons_array !== 'undefined' && buttons_array.length > 0) {
        // the array is defined and has at least one element
        var link = $("<a>");
        link.attr('href',(data['buttons'][0])['openUriAction']['uri']);
        link.attr("title",(data['buttons'][0])['title']);
		link.attr("target","_blank");
        link.text((data['buttons'][0])['title']);
        link.addClass("card-link");
        linkDiv.append(link);
    }

    cardDiv.append(img);
    cardBodyDiv.append(cardTitleContainerDiv);
    cardBodyDiv.append(textContainerPara);
    cardBodyDiv.append(linkDiv);
    cardDiv.append(cardBodyDiv);
    parent.append(cardDiv);
}

function md2html(input){
    var converter = new showdown.Converter();
    input = input.replace(/\n{2,}/g, m => m.replace(/\n/g, "<br/>"));
    input = input.replace(/<br\/>([^<])/g, "<br\/>\n\n$1");
    html      = converter.makeHtml(input);
    return html;
}

// description = data.description.replace(/\n{2,}/g, m => m.replace(/\n/g, "<br/>"));
// description = description.replace(/<br\/>([^<])/g, "<br\/>\n\n$1");
// var html = converter.makeHtml(description);

function renderSimpleResponse(data, parent){
    var simpleResponseDiv = jQuery('<div/>',{
        'class':'row'
    });
    var simpleResponseInnerDiv = jQuery('<div/>',{
        'class':'textResponse gaSimpleResponse'
    });
    var simpleResponseText = jQuery('<p/>',{
        html:md2html(data['simpleResponses']['simpleResponses'][0]['textToSpeech']),
        tabindex:1
    });
    simpleResponseDiv.append(simpleResponseInnerDiv);
    simpleResponseInnerDiv.append(simpleResponseText);
    parent.append(simpleResponseDiv);
}

function renderLinkOutSuggestion(data, parent){
    data = data['linkOutSuggestion'];
    var linkoutDiv = jQuery('<div/>', {
        tabindex:1,
        'class': "card gaLinkOutSuggestion"
    });
    var linkoutInnerDiv = jQuery('<div/>',{
        'class':'card-body'
    });
    var linkOutAnchor = jQuery('<a/>',{
        text:data['destinationName']
    });
    $(linkOutAnchor).attr("href",data['uri']);
    $(linkOutAnchor).attr("target","_blank");
    $(linkOutAnchor).attr("title",data['destinationName']);
    linkoutDiv.append(linkoutInnerDiv);
    linkoutInnerDiv.append(linkOutAnchor);
    parent.append(linkoutDiv);
}

function renderSuggestionChips(data,parent){
    data = data['suggestions'];
    var i, len = data['suggestions'].length;
    var buttonRowDiv = jQuery('<div/>',{
        class:'row'
    });
    var suggestionChipRowDiv = jQuery('<div/>',{
        class:'gaSuggestionChipRow'
    });
    for (i = 0; i < len; i++) {
        if (data["suggestions"][i]) {
            //make a button for it
            var buttonText = data["suggestions"][i]['title'];
            var button = jQuery('<button/>',{
                //type:'button',
                class:'btn btn-primary btn-sm gaSuggestionChipButton',
                text:buttonText
            });

            button.click(function(){
                var textToSubmit = this.textContent;
                suggestionChipRowDiv.remove();
                window.currentSuggestionChips = null;
                $("#message").removeAttr("disabled");
                $("#message").val(textToSubmit);
                $( "form#chatBotDialogflow" ).trigger( "submit" );
            });
        }
        suggestionChipRowDiv.append(button);
    }

    $(buttonRowDiv).append(suggestionChipRowDiv);
    $(parent).append(buttonRowDiv);
    window.currentSuggestionChips = suggestionChipRowDiv;
    //also disable the manual input
    $("#message").attr("disabled","disabled");
}

function getValueByKey(key, data) {
    var i, len = data.length;

    for (i = 0; i < len; i++) {
        if (data[i] && data[i].hasOwnProperty(key)) {
            return data[i][key];
        }
    }

    return -1;
}

function sendGAEvent(category, action, label){

}

function showUserText(){
    var userMessageRow = jQuery('<div/>',{
        class:'row'
    });
    var div = jQuery('<div/>', {
        text: $("#message").val(),
        'class': "rounded-div",
        'style': "border-color:"+$("input[name=chatbot_topcolor]").val(),
        tabindex:1
    });
    $(userMessageRow).append(div);
    $("#chat-text" ).append(userMessageRow);
    $("#message").val('');
}

function truncateString(input, charLimit){
    if(input.length > charLimit) {
        return input.truncate(charLimit)+"...";
    }
    else{
        return input;
    }
}

String.prototype.truncate = String.prototype.truncate ||
    function (n){
        return this.slice(0,n);
    };
