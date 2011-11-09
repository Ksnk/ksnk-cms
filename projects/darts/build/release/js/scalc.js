/**
 * @author	Serge Koriakin <SergeKoriakin@mail.ru>
 * @link 	http://forum.dklab.ru/users/ksnk/
 *
 * @copyright	Copyright (c) 2007 Serge Koriakin <SergeKoriakin@mail.ru>
 * @license 	Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * @Version: 2007.06.17
 */
$(function(){
    var ex3a = false, ex3c = false;
    /*
     *   Create a styles for using with Dialogue windows
     */
    (function(){
        var link = document.createElement('link'), head = document.getElementsByTagName('head')[0];
 //       with (link) {
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = 'css/alert.css';
            link.media = 'screen';
//        }
        head.appendChild(link);
        if ($.browser.msie) {
            link = document.createElement('link');
//            with (link) {
                link.rel = 'stylesheet';
                link.type = 'text/css';
                link.href = 'css/ie-alert.css';
                link.media = 'screen';
//            }
            head.appendChild(link);

        }
    })();
    /**
     *   ex3a window - modal window for registration and dialogue
     */
    function create_ex3a(){
        var i = $('<div class="jqm fixed" style="background:gray;z-index:8;"></div>').css({
            opacity: 0.5
        }).appendTo($(document.body));
        ex3a = $('<div id="ex3a" class="jqmDialog fixed" style="top:-1000px;left:314px;display:none;">' +
        '<div class="jqmdTL">' +
        '<div class="jqmdTR">' +
        '<div class="jqmdTC jqDrag">Registration</div>' +
        '</div>' +
        '</div>' +
        '<div class="jqmdBL">' +
        '<div class="jqmdBR">' +
        '<div class="jqmdBC">' +
        '<div class="jqmdMSG" align="center">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<input type="image" class="jqmdQ" src="img/question.gif" />' +
        //	'<input type="image" class="jqmdX" src="img/close.gif"/>' +
        '</div>').appendTo($(document.body)).hide();
        var opt = {
            t: [element.$('ex3a')]
        };
        ex3a.find('.jqDrag').each(function(){
            opt.drag = this;
        });
        $DDR(opt);
        // Close Button Highlighting. IE doesn't support :hover. Surprise?
        $('input.jqmdX, input.jqmdQ').each(function(){
            element.add_event(this, ['mouseover', 'focus'], function(){
                $(this).addClass('jqmdXFocus');
            }).add_event(this, ['mouseout', 'blur'], function(){
                $(this).removeClass('jqmdXFocus');
            }).add_event(this, ['click'], function(){
                if ($(this).hasClass('jqmdX'))
                    showHint();
                else
                    showAlert('start', 'Start new game');
            })
        })
        element.set().add_event(i, 'mousedown', function(e){
            alert(1);
            element.clearEv(e);
            return false;
        })

        /* */
    }
    function create_ex3c(){
        ex3c = $('<div id="ex3c" class="jqmNotice">' +
        '<div class="jqmnTitle jqDrag"><h1></h1></div>' +
        '<div title="close" class="jqmClose"></div>' +
        '<div title="resize" class="jqResize"></div>' +
        '<div class="jqmnContent"> </div>' +
        '</div>').css('opacity', 0.92).appendTo($(document.body)).hide();
        //$('<iframe id="ex3c-frm" src="javascript:document.write(\'<html><body><\'+\'/body><\'+\'/html> \');" class="jqm" style="z-index:10;"></iframe>').css({opacity:0})
        $('<iframe id="ex3c-frm" src="" class="jqm fixed" style="z-index:10;"></iframe>').css({
            opacity: 0
        }).appendTo($(document.body)).hide();/**/
        var opt = {
            t: [element.$('ex3c'), element.$('ex3c-frm')]
        };
        ex3c.find('.jqDrag').each(function(){
            opt.drag = this;
        });
        ex3c.find('.jqResize').each(function(){
            opt.resize = this;
        });
        $DDR(opt);
        ex3c.find('.jqmClose').each(function(){
            element.add_event(this, ['click'], function(){
                showAlert('');
            })
        })
    }
    /*
     *   ex3c window - single notify window
     */
    esc_handler = function(e){
        if ((e.charCode || e.keyCode) == 27)
            showAlert();
    },    /*
     *   ex3c function - single notify window
     */
    showAlert = function(hint, title){
        if (!ex3c)
            create_ex3c();
        var x = ex3c;
        element.clearEv('alert');// ('keypress',esc_handler)
        if (!hint) {
            $("#ex3c-frm").slideUp("slow");
            x.hide();
            return;
        }
        else {
			x.show();
            if ($.browser.msie)
                if ($('select').size())
                    $("#ex3c-frm").show();
        }
        element.set('alert').add_event(window.HTMLElement ? window : document.body, 'keypress', esc_handler).set();
        x.css({
            width: '',
            height: ''
        });
        x.find('.jqmnTitle h1').html(title || hint);
        x.find('.jqmnContent').html(_l(hint) || hint);

	
        if (parseInt(ex3c.css('top')) < 0)
            ex3c.css({
				top: 50
			})
        if (parseInt(ex3c.css('left')) < 100)
            ex3c.css({left:100})
        ex3c.slideDown() //show();
    }
    showHint = function(hint, title){
        if (!ex3a)
            create_ex3a();
        var x = ex3a;
        if (!hint) {
            $(".jqm").hide();
            x.hide();
            return;
        }else 			
			x.show();

        x.find('.jqmdTC').html(title || hint);
        x.find('.jqmdMSG').html(_l(hint) || hint);
		var wo=element.getBounds(); //debug.dump(wo).alert();
		
        //if (parseInt(x.css('top')) < 0)
            x.css({
				top: (wo.height- x.height()) / 2
			})
        //if (parseInt(x.css('left')) < 100)
            x.css({left:(wo.width-x.width())/2})
    };
});
