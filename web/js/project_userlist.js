var projectUserList = {};
projectUserList.addUrl = '';
projectUserList.modUrl = '';
projectUserList.removeUrl = '';
projectUserList.addUserForm = null;
projectUserList.userList = null;
projectUserList.init = function
    (
        userList,
        addUserForm,
        items,
        addUrl,
        modUrl,
        removeUrl
    )
{
    "use strict";

    projectUserList.userList = userList;
    projectUserList.addUserForm = addUserForm;
    projectUserList.addUrl = addUrl;
    projectUserList.modUrl = modUrl;
    projectUserList.removeUrl = removeUrl;

    projectUserList.addUserForm.find('select').change(projectUserList.addUser);

    for(var i = 0; i<items.length; i++)
    {
        projectUserList.initItem($(items.get(i)));
    }
};

projectUserList.initItem = function (item)
{
    "use strict";
    projectUserList.initItemPostRenamer(item);
    projectUserList.initItemRemover(item);
};

projectUserList.initItemRemover = function (item) {
    "use strict";
    var removeButton = item.find('.close-link');
    if(removeButton.length!==1){return;}

    removeButton.click(function () {
        projectUserList.removeUser(item,{'projectId':item.data('project'),'projectUserId':item.data('project_user')});
    });
};

projectUserList.initItemPostRenamer = function (item) {
    "use strict";
    var placePole = item.find('.post-change');
    if(placePole.length!==1){return;}

    var placeOut = item.find('.post-name');
    if(placeOut.length!==1){return;}

    var pole = placePole.find("input[name='post']");
    if(pole.length!==1){return;}


    placePole.hide();
    placeOut.show();
    pole.val(pole.data('value'));

    placeOut.click(function () {
        placePole.show();
        placeOut.hide();
        pole.focus();
    });

    pole.blur(function () {
        placePole.hide();
        placeOut.show();
        pole.val(pole.data('value'));
    });

    pole.keypress(function (event) {
        if(event.originalEvent.keyCode === 13)
        {
            projectUserList.modUser(item,{'post':pole.val(),'projectId':item.data('project'),'projectUserId':item.data('project_user')});
        }
    });
};

projectUserList.modUser = function(item,data)
{
    projectUserList.blockItem(item);
    $.ajax
    ({
        type: 'POST',
        url: projectUserList.modUrl,
        data: data,
        dataType:'json',
        item:item,
        success: function(responce)
        {
            projectUserList.unBlockItem(item);
            if((responce.success == undefined) || (responce.message == undefined) || (responce.html == undefined))return;

            if(!responce.success)
            {
                alert(responce.message);
                return;
            }

            var new_item = $(responce.html);
            item.html(new_item.find('.contact-box').html());
            projectUserList.initItem(item);
        },

        error:  function(xhr, str)
        {
            projectUserList.unBlockItem(item);
        }
    });
};

projectUserList.removeUser = function(item,data)
{
    projectUserList.blockItem(item);
    $.ajax
    ({
        type: 'POST',
        url: projectUserList.removeUrl,
        data: data,
        dataType:'json',
        item:item,
        success: function(responce)
        {
            projectUserList.unBlockItem(item);
            if((responce.success == undefined) || (responce.message == undefined))return;

            if(!responce.success)
            {
                alert(responce.message);
                return;
            }

            item.addClass('bounceOut');
            item.addClass('animated');
            setTimeout(function () {item.closest('.item-contaner').remove();},1000);

        },

        error:  function(xhr, str)
        {
            projectUserList.unBlockItem(item);
        }
    });
};

projectUserList.addUser = function () {
    var form = projectUserList.addUserForm;
    var pole = form.find('select');
    if(!pole.val())return;

    var projectId = form.data('project');
    projectUserList.blockItem(form);

    var data =
        {
            projectId:projectId,
            userId:pole.val()
        };
    pole.val('');
    pole.empty();
    $.ajax
    ({
        type: 'POST',
        url: projectUserList.addUrl,
        data: data,
        dataType:'json',
        success: function(responce)
        {
            projectUserList.unBlockItem(form);
            if((responce.success == undefined) || (responce.message == undefined) || (responce.html == undefined))return;
            if(!responce.success)
            {
                alert(responce.message);
                return;
            }
            var new_item = $(responce.html);

            projectUserList.userList.append(new_item);
            projectUserList.initItem(new_item.find('.contact-box'));
            new_item.addClass('bounceInDown');
            new_item.addClass('animated');
        },

        error:  function(xhr, str)
        {
            projectUserList.unBlockItem(form);
        }
    });
};

projectUserList.blockItem = function (item) {
    item.addClass('loading-process');
};

projectUserList.unBlockItem = function (item) {
    item.removeClass('loading-process');
};