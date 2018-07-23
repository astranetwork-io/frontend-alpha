var activeTab = {};
activeTab.init = function(tabs,place,indicatorPlace,addInfoFunction)
{
    for(var i = 0; i < tabs.length; i++)
    {
        var item = $(tabs.get(i));
        item.data('tabs-place',place);
        item.data('tabs-items',tabs);
        item.data('tabs-addInfo',addInfoFunction);
        item.data('tabs-indicatorPlace',indicatorPlace);

        var href = item.find('a');
        href.click(false);
        href.click(function () {activeTab.activateTab($(this).closest('li'));});
    }
};

activeTab.activateTab = function(item)
{
    if(item.hasClass('active'))return;
    activeTab.activateTabAction(item);
};

activeTab.activateTabAction = function(item)
{
    var tabs = item.data('tabs-items');
    for(var i = 0; i < tabs.length; i++)
    {
        var itemOld = $(tabs.get(i));
        itemOld.removeClass('active');
    }
    item.addClass('active');
    activeTab.refresh(item);
};

activeTab.refresh = function (item) {
    var place = item.data('tabs-place');
    var addInfo = item.data('tabs-addInfo');
    var indicatorPlace = item.data('tabs-indicatorPlace');
    var url = item.data('url');
    if ((!url)||(url.length < 1)) return;

    place.html('');
    indicatorPlace.addClass('loading-process');
    var data = addInfo();

    $.ajax
    ({
        type: 'POST',
        url: url,
        data: data,
        dataType:'html',
        place: place,
        success: function(responce)
        {
            indicatorPlace.removeClass('loading-process');
            this.place.html(responce);
        },

        error:  function(xhr, str)
        {
            indicatorPlace.removeClass('loading-process');
        }
    });
};