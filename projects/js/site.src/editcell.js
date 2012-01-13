/**
 * .editcell & .carret jQuery plugins
 *
 * <%=point('hat','comment');%>
 */
/**
 * работа с курсором. Работаем с первым элементом коллекции.
 * .carret('get')| .carret() - выдать позицию курсора, верхняя граница отмеченного участка
 * .carret('sel') - выдать позицию курсора, верхняя и нижняя граница отмеченного участка в массиве
 * .carret('set',X,Y)| carret(X,Y)| carret(X) - установить курсор в поззицию
 * .carret('is',X) - проверить, что курсор в позиции X (отмеченая область считается курсором)
 * @param act
 * @param selectionStart
 * @param selectionEnd
 */
$.fn.carret=function(act,selectionStart, selectionEnd){
    var input=this[0];
    if(!input) return 0 ;
    // анализ параметров
    if(undefined==act){
        act='get';
    } else
    if(typeof(act)=="number"){
        selectionEnd=selectionStart;
        selectionStart=act;
        act='set';
    } else
    if(undefined==selectionEnd)selectionEnd=selectionStart;

    if(act=='set'){
        if (input.setSelectionRange) {
            input.focus();
            input.setSelectionRange(selectionStart, selectionEnd);
        }
        else if (input.createTextRange) {
            var range = input.createTextRange();
            range.collapse(true);
            range.moveEnd('character', selectionEnd);
            range.moveStart('character', selectionStart);
            range.select();
        }
        return this;
    } else {
        $(input).focus();
        var cursor=[];
        if(typeof(input.selectionStart)!='undefined'){
            cursor=[ input.selectionStart,input.selectionEnd];
        } else
        if (document.selection) {
            var sel=document.selection.createRange();
            var clone=sel.duplicate();
            sel.collapse(true);
            clone.moveToElementText(input);
            clone.setEndPoint('EndToStart',sel);
            cursor[0]=clone.text.length;

            sel=document.selection.createRange();
            clone=sel.duplicate();
            sel.collapse(false);
            clone.moveToElementText(input);
            clone.setEndPoint('EndToEnd',sel);
            cursor[1]=clone.text.length;
        }
        if(act=='is'){
            return cursor[0]<=selectionStart && selectionStart<=cursor[1];
        }else if(act=='sel'){
            return cursor;
        } else
            return cursor[0];
    }

};

$.fn.editcell=function(action,o){
    var options={
        // селектор для автоматической установки редактора
        selector: '.editable',

        /** примерный CSS для элемента textarea */
        textarea:{
            width: '100%',
            height: '100%',
            outline: 0,
            border: '1px #CCCCCC dotted',
            resize: 'none',
            margin:0,
            overflow: 'hidden'
        },

        /** примерный CSS для контейнера div */
        css:{
            position: 'absolute',border:0,display:'none'
        },

        /**
         * хандлер "дай редактируемый текст"
         * @param object options - параметры вызова
         * @return string - текст, который будет редактироваться
         * this указывает на редактируемый объект, а не на устанавливаемый объект
         */
        get_text:function(/*options*/){return $(this).text()},

        /**
         * хандлер "получи отредактированный текст"
         * @param string txt - отредактированный редактором текст
         * @param object options - параметры вызова
         * @return string - текст, который будет вставлен в .html элементу
         * this указывает на редактируемый объект, а не на устанавливаемый объект
         */
        set_text:function(txt/*,options*/){$(this).text(txt);return txt},

        /**
         * хандлер "редактор закрывается"
         * @param object options - параметры вызова
         * this указывает на редактируемый объект, а не на устанавливаемый объект
         */
        //exit:function(options){},

        empty:''
     };

    //magic! donov realy-realy :(
    if(!o) o={};
    else if (typeof(o)=='string')
        o={selector:o};
    $.extend(options,o);

    // устанавливаем редактор. Только раз в жизни
    if(!$(document).data('editcell-editor')){
        $(document).data('editcell-editor',$('<div/>').append(
                $('<textarea></textarea>').css(options.textarea)
            ).css(options.css)
            .appendTo(document.body));
    }
    options._editor=$(document).data('editcell-editor');

    function scroll(){
        if(!cell_editor.internalScroll)
            cell_editor.hide();
        else
            cell_editor.internalScroll=false;
    }
    /** scroll editor window into view */
    function cell_editor(t){
        cell_editor.internalScroll=false;
        options.exit_key=false;

        /** @var jQuery $self */
        var $self=$(t);
        // scroll into view
        var position,rect,$parent,prect,ppos;
        for (var c=0;c<2;c++) {
            position = $self.position();
            rect = {
                height:$self.innerHeight(),
                width:$self.innerWidth(),
                top:position.top - 1, //+parseInt($self.css('padding-top')),
                left:position.left + parseInt($self.css('padding-left'))
            };
            var tt = 1 + (($self.outerHeight() - $self.innerHeight()) >> 1);
            rect.top -= tt;
            tt = 1 + (($self.outerWidth() - $self.innerWidth()) >> 1);
            rect.left -= tt;
            $parent = $(options.parent);
            ppos = $parent.position();
            prect = {
                height:$parent.innerHeight(),
                width:$parent.innerWidth(),
                top:ppos.top,
                left:ppos.left
            };
            var pst = $parent.scrollTop();
            if (rect.top < prect.top + 24 && pst > 0) {
                // scroll down
                cell_editor.internalScroll = true;
                $parent.scrollTop(
                    pst + (rect.top - prect.top - 24)
                );
            } else if (rect.top + rect.height > prect.top + prect.height) {
                // scroll up
                cell_editor.internalScroll = true;
                $parent.scrollTop(
                    pst + (rect.top + rect.height - prect.top - prect.height)
                );
            } else {
                break;
            }
        }
        //console.log(rect.top-prect.top,rect.top+rect.height-prect.top-prect.height)
        options._editor.css(rect).show();
        $(t).parents('div,body').bind('scroll',scroll);

        var txt=options.get_text.call(t,options);
        $('textarea',options._editor)
            .css({
                'padding':$self.css('padding-top')+' 0 0 '+$self.css('padding-left'),
                'font-size':$self.css('font-size'),
                'font-style':$self.css('font-style'),
                'font-family':$self.css('font-family')
            })
            .focus().val(txt)
            .carret('set',0,txt.length);
       // console.log('focus');
        cell_editor.control=$self;
        //one-time инициализация
        if(!cell_editor.init){

            $('textarea',options._editor)
                .blur(cell_editor.hide)
                //todo: не очень удачная попытка изменить размер поля редактирования
  /*             .keyup(function(){
                    if(this.scrollTop>0 || this.scrollWidth>$(this).width()+10){
                        $(options._editor).css('width',this.scrollWidth+10);
                        this.scrollTop(0);
                    }
                })*/
                .keydown(function(event){
                    var $this=$(this);
                    if (event.keyCode == '13') {//enter
                        event.preventDefault();
                        cell_editor.hide();
                    } else if (event.keyCode == '27') {// esc
                        options._cancel=true;
                        cell_editor.hide();
                    }
                    switch (event.keyCode) {
                        case 37:
                            if(!$this.carret('is',0)) break;
                        case 38:
                            options.exit_key=event.keyCode;
                            cell_editor.hide();
                            event.preventDefault();
                             break;
                        case 39:
                            if(!$this.carret('is',$this.val().length)) break;
                        case 40:
                            options.exit_key=event.keyCode;
                            cell_editor.hide();
                            event.preventDefault();
                            break;
                    }
                    return ;
                });
                cell_editor.init=true;
        }
    }
    cell_editor.hide=function(){
        if(cell_editor.control){
            //console.log('hide');
            var val = $('textarea', options._editor).val();
            if(!options._cancel){
                options._cancel=false;
                options.set_text.call(cell_editor.control,val,options);
            }
            if(options.exit)
                options.exit.call(cell_editor.control,options);
            options._editor.hide();
            $(cell_editor.control).parents('div,body').unbind('scroll',scroll);
            cell_editor.control=null;
        }
    };

    options.parent=this;

    if(action=='go' && this.length>0){
        cell_editor(this);
    } else {
        this.click(function(e)	{
            var t = e.target || e.srcElement;
             if($(t).is(options.selector))
                cell_editor(t);
        });
    }

};