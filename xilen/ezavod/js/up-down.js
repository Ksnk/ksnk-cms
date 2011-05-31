jQuery.fn.accordion = function() {
    function update(dl) {
       
        $("dl dd").slideUp(200);
       
        $("dt.current", dl).next().slideDown(200);
        
    }

    return this.each(function() {
        var dl = $(this), active = $("dt.current", dl);
        update(dl);

        $("dt", dl).click(function() {
            if (!$(this).hasClass("current")) {
                active && active.removeClass("current");
                active = $(this).addClass("current");
                update(dl);
            }
        });
    });
};