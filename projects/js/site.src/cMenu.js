/**
 * context Menu
 * срабатывает на пробел и на правую кнопку мыши. Встраивается в низ контрола
 * <code>
 *     // пример простого меню, генерируемого на лету
 * $('input').contextMenu({
 *     menu:['О нас#about','Привет#hello','Opps!'],
 *     action:function(action){
 *          switch(action){
 *              case 'about': alert('about'); break;
 *              case 'hello': alert('hello'); break;
 *              case 'Opps!': alert('Opps!'); break;
 *          }
 *     }
 * })
 * </code>
 *
 * @param o
 * o.menu array|string|function
 */
$.fn.contextMenu = function (o) {
    var options = {
        inSpeed:150,
        outSpeed:75,
        menu:function () {
            return $($(this).data('contextmenu'))[0]
        },
        empty:''
    };
    if (!o) o = {};
    else if (typeof(o) == 'string')
        o = {xxx:o};
    $.extend(options, o);

    function getMenu(el){
        var menu=options.menu;
        if (typeof(menu) == 'function'){
            menu=menu.call(el);
        }
        if (menu instanceof Array) {
            // строим меню
            var xmenu=$('<ul/>').addClass("contextMenu");
            for(var i=0;i<menu.length;i++){
                var line=menu[i];
                if ( line=='' ) {
                    xmenu.append('<li class="separator"></li>');
                    continue;
                }
                if(typeof(line)=='string'){
                    line=line.split('#');
                    line={'title':line[0],'href':line[1]||line[0]}
                }
                xmenu.append('<li><a href="#'+line.href+'">'+line.title+'</a></li>')
            }
            options._xmenu=$(xmenu).appendTo(document.body)[0];
            menu=options._xmenu;

        } else if (typeof(options.menu) == 'string') {
            // ищем селектор
            menu = $(sel)[0]
        }
        return menu;
    }

    function keypress(e) {
        switch (e.keyCode) {
            case 38: case 40: // up
                var pos,func;
                if (e.keyCode==38){ pos='last';func='prevAll'}
                else { pos='first';func='nextAll'}
                if ($('LI.hover',options._menu).length==0) {
                    $('LI:'+pos,options._menu).addClass('hover');
                } else {
                    $('LI.hover',options._menu).removeClass('hover')[func]('LI').filter(':not(.disabled,.separator)').eq(0).addClass('hover');
                    if ($('LI.hover',options._menu).length == 0) $('LI:'+pos,options._menu).addClass('hover');
                }
                break;
            case 13: // enter
                $('LI.hover A',options._menu).trigger('click');
                break;
            case 27: // esc
                $(document).trigger('mousedown');
                break
        }
    }

    // Defaults
    function mouseup(event) {
        event.stopPropagation();
        var tgtElement=event.target;
        $(this).unbind('mouseup',mouseup);
        // Hide context menus that may be showing
        //$(".contextMenu").hide();
        // Get this context menu
        var _menu = getMenu(tgtElement);//options.menu.call(srcElement);
        if(!_menu) return;
        options._menu=_menu;
        // Show the menu
        if (!_menu.show_menu){
            menu(_menu,{
                show:function(){
                    $(document).bind('keydown',keypress);
                    $(this).fadeIn(options.inSpeed)
                },
                hide:function(){
                    $(document).unbind('keydown',keypress);
                    if(options._xmenu){
                        $(options._xmenu).remove();
                        options._xmenu=false;
                    }
                    $(this).fadeOut(options.outSpeed)
                }
            });
            $('A',_menu).mouseover( function() {
                 $(_menu).find('LI.hover').removeClass('hover');
                 $(this).parent('LI').addClass('hover');
            }).mouseout( function() {
                 $(_menu).find('LI.hover').removeClass('hover');
            }).click(function(event){
                 _menu.hide_menu() ;
                 if( options.action )
                     options.action.call(tgtElement,
                         $(this).attr('href').substr(1),
                         event
                     );
                 return false;
            });
        }
        $(_menu).css({ top:event.pageY, left:event.pageX });
        _menu.show_menu();
    }

    this.mousedown(function (e) {
        if (e.button == 2) {
             $(this).mouseup(mouseup);
            e.stopPropagation();
        }
    }).bind('contextmenu',function(e) {
        if(e.ctrlKey) return; return false;
    });
    return this;

};
/*
    // Disable context menu items on the fly
    disableContextMenuItems: function(o) {
        if( o == undefined ) {
            // Disable all
            $(this).find('LI').addClass('disabled');
            return( $(this) );
        }
        $(this).each( function() {
            if( o != undefined ) {
                var d = o.split(',');
                for( var i = 0; i < d.length; i++ ) {
                    $(this).find('A[href="' + d[i] + '"]').parent().addClass('disabled');

                }
            }
        });
        return( $(this) );
    },

    // Enable context menu items on the fly
    enableContextMenuItems: function(o) {
        if( o == undefined ) {
            // Enable all
            $(this).find('LI.disabled').removeClass('disabled');
            return( $(this) );
        }
        $(this).each( function() {
            if( o != undefined ) {
                var d = o.split(',');
                for( var i = 0; i < d.length; i++ ) {
                    $(this).find('A[href="' + d[i] + '"]').parent().removeClass('disabled');

                }
            }
        });
        return( $(this) );
    },

    // Disable context menu(s)
    disableContextMenu: function() {
        $(this).each( function() {
            $(this).addClass('disabled');
        });
        return( $(this) );
    },

    // Enable context menu(s)
    enableContextMenu: function() {
        $(this).each( function() {
            $(this).removeClass('disabled');
        });
        return( $(this) );
    },

    // Destroy context menu(s)
    destroyContextMenu: function() {
        // Destroy specified context menus
        $(this).each( function() {
            // Disable action
            $(this).unbind('mousedown').unbind('mouseup');
        });
        return( $(this) );
    }

});
*/