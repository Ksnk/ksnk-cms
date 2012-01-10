/**
 * поддержка расширения элементов на всю доступную броузеру высот
 * .lofty
 *
 * элемент вынимается из лейаута(hide), после чего ему ставится нужный размер(parent.client.height)
 *
 * todo: ведется кратковременное кэширование для обеспечения операций скролла
 */

$(function(){
    setTimeout(function(){
        var oldheight=$(document.body).height();
        $('.lofty').each(function(){
            this.__oldheight=$(this).height();
        });
        $(window).bind('resize',function(){
            var disp=$(document.body).height()-oldheight ;
            //console.log(newheight);
            //oldheight=newheight;
            if(!!disp){
                $('.lofty').each(function(){
                    var min=parseInt($(this).css('min-height')),
                        max=parseInt($(this).css('max-height')),
                        val=this.__oldheight+disp;
                    if(min && val<min)
                        val=min;
                    if(max && val>=max)
                        val=max;
                    $(this).css('height',val);
                })
            }
        });
    },10);
})