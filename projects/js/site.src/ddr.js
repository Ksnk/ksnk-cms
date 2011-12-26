/**
 * кора от Драг&Дроп
 *
 * options:{
 *     on_drag_complete(event,options) - вызывается по завершении драга
 *     on_drag_start(event,options) - по началу драга
 *     on_drag(event,options) - в процессе драга
 * //    start:{x,y} - точка начала операции - не используется внутри коры
 * }
 */
var mousetgt=(window.execScript || window.opera)?document:window;

$.fn.ddr=function(options){
    this.bind('mousedown',function(event){
        function drag_stop(){
            $(mousetgt).unbind('mousemove',mousemove).unbind('mouseup',mouseup);
            options._drag_target=null;
            options._dragging=false;
        }
        function mouseup(event){
            if(options.on_drag_complete)
                options.on_drag_complete.call(options._drag_target,event,options);
            if(options._dragging){
                event.preventDefault();
            }
            drag_stop();//.call(options._drag_target);
        }
        function mousemove(event){
            if(!options._dragging){
                options._dragging=true;
                if(options.on_drag_start)
                    options._dragging=(options.on_drag_start.call(options._drag_target,event,options)!==false)
                if(!options._dragging){
                    drag_stop()//.call(options._drag_target);
                    return;
                }
            }
            if(options.on_drag)
                options.on_drag.call(options._drag_target,event,options);
            event.preventDefault();
            event.stopPropagation();
            return event.result=false;
        }
        options._drag_target=this; // потенциальный leak
        $(mousetgt).bind({
            mouseup:mouseup,
            mousemove:mousemove
        });
    })
}
