(function($){
    $.fn.link_blank= function(){
        if ($(this).is('a')) {
            //var $link = $('<a href="<?= base_url('cms/page/editView')?>/' + $('#goto').data('id') + '" target="_blank"></a>').appendTo($('body'));
            if (document.createEvent) {
                var theEvent = document.createEvent("MouseEvent");
                theEvent.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                $(this).get(0).dispatchEvent(theEvent);
            }
            else if ($(this).get(0).click) $(this).get(0).click();
            //$link.remove();
        }
        return this;
    }

})(jQuery);