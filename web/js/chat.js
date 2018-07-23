var ChatBase = {
    jChatPlace:null,
    jUserPlace:null,
    jForm:null,
    jFileForm:null,
    urlUpdate:null,
    timeToUpdate: 10000,
    timer: null,
    quickUpdate: false,
    loadingMessagesData: false,
    firstLoad: true

};

ChatBase.blockForm = function()
{
    var messageField = this.jForm.find("[name='message']");
    messageField.attr('disabled',true);
};

ChatBase.unBlockForm = function()
{
    var messageField = this.jForm.find("[name='message']");
    messageField.attr('disabled',false);
};

ChatBase.clearForm = function()
{
    var messageField = this.jForm.find("[name='message']");
    messageField.val('');
    this.jFileForm.html('');
};

ChatBase.sendMessage = function () {
    var data = this.jForm.serializeArray();
    this.quickUpdate = false;

    this.blockForm();

    $.ajax
    ({
        type: 'POST',
        url: this.jForm.attr('action'),
        data: data,
        dataType:'json',
        context: this,
        success: function(responce)
        {
            this.unBlockForm();
            if(responce.success === undefined)return;
            if(!responce.success)return;
            this.clearForm();

            if(!this.loadingMessagesData)
            {
                this.update();
            }
            else
            {
                this.quickUpdate = true;
            }
        },

        error:  function(xhr, str)
        {
            this.unBlockForm();
        }
    });

};

ChatBase.keyPress = function (event) {
    if((!event.originalEvent.shiftKey) && (!event.originalEvent.altKey) && (!event.originalEvent.ctrlKey) && (event.originalEvent.keyCode == 13))
    {
        this.sendMessage();
    }
};


ChatBase.start = function()
{
    if(this.jForm)
    {
        this.jForm.find("[name='message']").keypress(this.keyPress.bind(this));
    }
    this.jChatPlace.find('.LoadMorePlace .LoadMoreButton').click(this.loadOlder.bind(this));
    this.jChatPlace.find('.LoadMorePlace').hide();
    this.update();
};

ChatBase.update = function ()
{
    if(this.loadingMessagesData)
    {
        return;
    }

    if(this.timer)
    {
        clearTimeout(this.timer);
    }

    var firstMessageId = this.jChatPlace.find('.chat-message').first().data('itemid');
    if(!firstMessageId) firstMessageId = 0;


    this.loadingMessagesData = true;
    $.ajax
    ({
        type: 'POST',
        url: this.urlUpdate,
        data: {'last':firstMessageId},
        dataType:'json',
        context: this,
        success: function(responce)
        {
            this.loadingMessagesData = false;
            if
            ((responce.messages == undefined) || (responce.users == undefined))
            {
                return;
            }

            if((this.jUserPlace)&&(this.jUserPlace.html()!=responce.users))
            {
                this.jUserPlace.html(responce.users);
            }

            if(responce.messages.length > 0)
            {
               var m = $(responce.messages);
               this.jChatPlace.prepend(m);
               if(this.firstLoad)
               {
                   this.firstLoad = false;
                   this.jChatPlace.find('.LoadMorePlace').show();
               }
            }

            if(this.quickUpdate)
            {
                this.quickUpdate = false;
                this.update().bind(this);
            }
            else
            {
                this.timer = setTimeout(this.update.bind(this),this.timeToUpdate);
            }
        },

        error:  function(xhr, str)
        {
            this.loadingMessagesData = false;
        }
    });
};

ChatBase.loadOlder = function ()
{
    if(this.loadingMessagesData)
    {
        return;
    }

    var button = this.jChatPlace.find('.LoadMorePlace .LoadMoreButton');
    var lastMessageId = this.jChatPlace.find('.chat-message').last().data('itemid');
    if(!lastMessageId) lastMessageId = 0;
    if(lastMessageId<1)return;
    button.attr('disabled',true);

    if(this.timer)
    {
        clearTimeout(this.timer);
    }

    this.loadingMessagesData = true;
    $.ajax
    ({
        type: 'POST',
        url: this.urlUpdate,
        data: {'last':lastMessageId,'reverse':1},
        dataType:'json',
        context: this,
        success: function(responce)
        {
            button.attr('disabled',false);
            this.loadingMessagesData = false;
            if
            ((responce.messages == undefined) || (responce.users == undefined))
            {
                return;
            }

            if(this.jUserPlace) {
                if (this.jUserPlace.html() != responce.users) {
                    this.jUserPlace.html(responce.users);
                }
            }

            if(responce.messages.length > 0)
            {
                var m = $(responce.messages);
                this.jChatPlace.find('.LoadMorePlace').before(m);
            }
            else
            {
                this.jChatPlace.find('.LoadMorePlace').hide();
            }

            if(this.quickUpdate)
            {
                this.quickUpdate = false;
                this.update().bind(this);
            }
            else
            {
                this.timer = setTimeout(this.update.bind(this),this.timeToUpdate);
            }
        },

        error:  function(xhr, str)
        {
            button.attr('disabled',false);
            this.loadingMessagesData = false;
        }
    });
};


function Chat(jChatPlace, jUserPlace, jForm, jFileForm, urlUpdate) {
    this.jChatPlace = jChatPlace;
    this.jUserPlace = jUserPlace;
    this.jForm = jForm;
    this.urlUpdate = urlUpdate;
    this.jFileForm = jFileForm;
}

Chat.prototype = ChatBase;