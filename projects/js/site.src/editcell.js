/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 05.12.11
 * Time: 14:22
 * To change this template use File | Settings | File Templates.
 */
jQuery.fn.setSelectionRange=function ( selectionStart, selectionEnd) {
    var input=this[0];
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
};

jQuery.fn.setCaretToPos =function(pos) {
    this.setSelectionRange( pos, pos);
};

jQuery.fn.carret=function(pos){
    var $self=this[0];
    if(!!$self){
        $($self).focus();
        if(typeof($self.selectionStart)!='undefined'){
            return $self.selectionStart<=pos && pos<=$self.selectionEnd;
        }
        if (document.selection) {
            var sel=document.selection.createRange();
            var clone=sel.duplicate();
            sel.collapse(true);
            clone.moveToElementText($self);
            clone.setEndPoint('EndToEnd',sel);
            return clone.text.length;
        }
    }
    return 0;
};

$.fn.editcell=function(o){
    var options={
        selector: '.editable', //
        textarea:{
            width: '100%',
            height: '100%',
            'font-size': '10pt',
            outline: 0,
            border: '1px #CCCCCC dotted',
            resize: 'none',
            margin:0,
            overflow: 'hidden'
        },
        css:{
            position: 'absolute',border:0
        },
        get_text:function(){return $(this).text()},
        empty:''
     };
    if(!o) o={};
    else if (typeof(o)=='string')
        o={action:o};
    $.extend(options,o);
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

    function cell_editor(t){
        cell_editor.internalScroll=false;

        /** @var jQuery $self */
        var $self=$(t);
        //if(!$self.is(':visible'))t.scrollIntoView(true);
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
        $(t).parents().bind('scroll',scroll);

        var txt=options.get_text.call(t);
        $('textarea',options._editor)
            .css({
                'padding':'0 0 0 '+$self.css('padding-left'),
                'font-size':$self.css('font-size'),
                'font-style':$self.css('font-style'),
                'font-family':$self.css('font-family')
            })
            .focus().val(txt)
            .setSelectionRange(0,txt.length);
       // console.log('focus');
        cell_editor.control=$self;
        //one-time инициализация
        if(!cell_editor.init){

            $('textarea',options._editor)
                .blur(cell_editor.hide)
                .keyup(function(){
                   // console.log(this.scrollTop,this.scrollWidth,$(this).width());

                    if(this.scrollTop>0 || this.scrollWidth>$(this).width()+10){
                        $(options._editor).css('width',this.scrollWidth+10);
                        this.scrollTop(0);
                    }
                })
                .keydown(function(event){
                    var $this=$(this),$x,$y;
                    if (event.keyCode == '13') {//enter
                        event.preventDefault();
                        cell_editor.hide();
                    } else if (event.keyCode == '27') {// esc
                        $(this).val($self.text());
                        cell_editor.hide();
                    }
                    else if (event.keyCode == '37') {// left key
                        if($this.carret(0)){
                            // do_move prev cell
                            $x=$self.prev(options.selector);
                            if($x.length) {
                                cell_editor.hide();
                                cell_editor($x[0]);
                                event.preventDefault();
                            }
                            //alert('prev');
                        }
                    }
                    else if (event.keyCode == '38') {// up key
                        if($this.carret(0)){
                            // do_move upper cell
                            $x=$self.prev(options.selector);
                            $y=$self.parent('tr').prev().find(options.selector);
                            if($y.length) {
                                cell_editor.hide();
                                cell_editor($y[$x.length||0]);
                                event.preventDefault();
                            }
                            //alert('up');
                        }
                    }
                    else if (event.keyCode == '39') {// right key
                        if($this.carret($this.val().length)){
                            // do_move next cell
                            $x=$self.next(options.selector);
                            if($x.length) {
                                cell_editor.hide();
                                cell_editor($x[0]);
                                event.preventDefault();
                            }
                           // alert('next');
                        }
                    }
                    else if (event.keyCode == '40') {// down
                        if($this.carret($this.val().length)){
                            // do_move downer cell
                            $x=$self.prev(options.selector);
                            $y=$self.parent('tr').next().find(options.selector);
                            if($y.length) {
                                cell_editor.hide();
                                cell_editor($y[$x.length||0]);
                                event.preventDefault();
                            }
                            //alert('down');
                        }
                    }
//*---*/            else alert(event.keyCode)
                });
                cell_editor.init=true;
        }
    }
    cell_editor.hide=function(){
        if(cell_editor.control){
            //console.log('hide');
            var val = $('textarea', options._editor).val();
            cell_editor.control.text(val);
            options._editor.hide();
            cell_editor.control=null;
            $(cell_editor.control).parents().unbind('scroll',scroll);
        }
    };

    options.parent=this;

    if(options.action='go'){
        cell_editor(this);
    } else {
        this.click(function(e)	{
            var t = e.target || e.srcElement;
             if($(t).is(options.selector))
                cell_editor(t);
        });
    }

};