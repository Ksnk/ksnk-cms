/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 26.12.11
 * Time: 17:33
 * To change this template use File | Settings | File Templates.
 */
$.fn.regedit = function (o) {
    var options={
        slowClick_timer:null,
        slowClick_low: 400,
        slowClick_high:3000,
        empty:''
    };
    if(!o) o={};
//      else if (typeof(o)=='string')
//          o={action:o};
    $.extend(options,o);

    var keys = [], prefix = '';

    function append_tr(xtree_body, row, level, hidden) {
        hidden = hidden || 0;
        // append one cell
        var child_cnt = row.children && row.children.length || 0;
        if (row.title && level.length > 0) {
            keys[row.key] = prefix + row.title;
            var $dig = '';
            var child_cnt = 0;
            if (row.children) {
                for (var i = 0; i < row.children.length; i++) {
                    var ii = row.children[i];
                    if (ii.type == 'right') {
                        $m = ii.value.match(/(\d+)\|(.*?)\|(.+)$/);
                        $dig += keys[parseInt($m[1]) || 0] + ':' + ($m[2] == 0 ? '-' : '+') + $m[3] + ';';
                    } else if (ii.type == 'action') {
                        $dig += ii.value + ';';
                    } else {
                        child_cnt++;
                    }
                }
            }
            if (child_cnt == 0) {
                row._empty = true;
            }
            $result = '';
            // символ +-[] -
            $xxx = '';
            if (row._empty) {
                $xxx = 'empty';
            } else if (row._status == 'show') {
                $xxx = 'minus';
            } else {
                row._status = 'hide';
                $xxx = 'plus';
            }
            //дорисовываем дерево
            if (level.length > 0 && level[level.length - 1] > 0) {
                if (--level[level.length - 1] == 0)
                    $result = '<span class="regedit-tree-last' + $xxx + '"/>'
                            + $result;
                else
                    $result = '<span class="regedit-tree-' + $xxx + '"/>'
                            + $result;
            }
            for (var i = level.length - 2; i >= 0; i--) {
                if (level[i] == 0)
                    $result = '<span class="regedit-tree-void"/>'
                            + $result;
                else
                    $result = '<span class="regedit-tree-vert"/>'
                            + $result;
            }

            var $result = '<tr' + (hidden > 0 ? ' style="display:none;"' : '') + '><td>' + $result;

            $result += '<span class="dynatree-icon"/>';
            $result += '<a href="#" class="regedit-title">' + row['title'] + '</a></td><td></td>';

            if ($dig != "")
                $result += '<td><span class="regedit-title">[' + $dig + ']</span></td>';
            else
                $result += '<td></td>';
            $result += '<td></td><td></td></tr>';
            row._element = $($result).appendTo(xtree_body);
            row._element.data('row', row);
        }
        if (child_cnt) {
            level.push(child_cnt);
            if (row.type == 'aro_prefix') {
                prefix = row.title + '|';
            }
            for (var i = 0; i < row.children.length; i++) {
                append_tr(xtree_body, row.children[i], level, hidden+(row._status == 'hide'?1:0));
            }
            if (row.type == 'aro_prefix') {
                prefix = '';
            }
            level.pop();
        }

    }

    function fill_table(childs) {
        var tab = $('#regedit table').eq(3);
        tab.find('tbody').find('tr').remove();
        if (childs.length == 0) return;
        var $html;
        for (var i = 0; i < childs.length; i++) {
            var $m = (childs[i].value || '').match(/(\d+)\|(.*?)\|(.+)$/);
            if ($m) {
                $html = '<tr><td class="regedit_propname">' + keys[parseInt($m[1]) || 0]
                        + '</td><td></td>'
                        + '<td class="regedit_propvalue">' + ($m[2] == 0 ? '-' : '+')
                        + '</td><td></td>'
                        + '<td class="regedit_propaction">' + $m[3]
                        + '</td><td></td><td></td></tr>';
                tab.find('tbody').append($html);
            }
        }
    }

    function recclose(data, $op) {
        if (data._element)
            $op = $op.add(data._element);
        if (data.children && data._status != 'hide')
            for (var i = 0; i < data.children.length; i++) {
                $op = recclose(data.children[i], $op);
            }
        return $op;
    }

    function getNode(element){
        return $(element).parents('tr').eq(0);
    }
    function getRow(element){
        return $(element).parents('tr').eq(0).data('row');
    }

    function make_Active($tgt){
        if (options.activeNode) {
             options.activeNode.removeClass('active');
         }
         options.activeNode = getNode($tgt);
         if (options.onActivate) {
             options.onActivate(options.activeNode);
         }
         var row = options.activeNode.data('row'), childs = [];
         if (row.children && row.children.length)
             for (i = 0; i < row.children.length; i++) {
                 var ii = row.children[i];
                 if (ii.type == 'right' || ii.type == 'action') {
                     childs.push(ii);
                 }
             }
         fill_table(childs);
         options.activeNode.addClass('active');
         //console.log('selected');

    }

    function openSubtree(el){
        var $tr=getNode(el);
        var c = ['minus','lastminus','lastplus', 'plus'];
        for (i = 0; i < c.length; i++) {
            var $x=$tr.find('.regedit-tree-' + c[i]);
            if ($x.length>0) {// 0-3 1-2
                tree_close($x, 'regedit-tree-' + c[i], 'regedit-tree-' + c[3 - i]);
            } else
                continue;
            break;
        }
    }

    function tree_close($tgt, classR, classA) {
        //console.log($tgt,classR, classA);
        var data = getRow($tgt);
        if (data && data.children) {
            var $op = $();
            for (var i = 0; i < data.children.length; i++) {
                $op = recclose(data.children[i], $op);
            }
            var op = classR.match(/minus$/) ? 'hide' : 'show';
            $op[op]();
            data._status = op;
        }
        $tgt.removeClass(classR).addClass(classA);
    }

    var tree = this.find('table').eq(1);
    if (options.children) {
        var xtree = tree.clone(true).find('tr').remove().end();
        append_tr(xtree.find('tbody'), options, []);
        var parent = tree.parent();
        tree.remove();
        parent.append(xtree);
    }
    this.click(function (event) {
        var i,$tgt = $(event.target);
        // отслеживаем двойной медленный клик
        if (options.slowClick_timer)
            clearTimeout(options.slowClick_timer);
        if (!options._lasttgt!=event.target) {
            options.slowClick_timer=(function(tgt){return setTimeout(function(){
                options._lasttgt=tgt;
                options.slowClick_timer=setTimeout(function(){
                    options._lasttgt=null;
                    options.slowClick_timer=null;
                },options.slowClick_high)
            },options.slowClick_low)})(event.target);
        }
        if(!!options._lasttgt && options._lasttgt==event.target){
            // двойной неторопливый клик !
            if ($tgt.is('.regedit-title')) {
                $tgt.editcell('go');
                return
            } else
            if ($tgt.is('.regedit_propname')) {
                $tgt.editcell('go');
                return
            }
        }
        if ($tgt.is('.regedit-title')) {
            make_Active($tgt);
        }
        if($tgt.is('.regedit-tree-plus,.regedit-tree-lastplus,.regedit-tree-minus,.regedit-tree-lastminus'))
        if($tgt.is('.regedit-tree-plus,.regedit-tree-lastplus,.regedit-tree-minus,.regedit-tree-lastminus'))
            openSubtree($tgt);
    }).bind('dblclick',function(event){
        if (options.slowClick_timer)
            clearTimeout(options.slowClick_timer);
        options._lasttgt=null;
        openSubtree(event.target);
    })
    $(document).contextMenu({
        menu:function(){
            if($(this).is('.regedit-title')){
                make_Active($(this));
                var $result=['Переименовать#rename'],
                    node=getRow(this);
            //    console.log(node);
                if(node.type=='axo_group' || node.type=='axo_prefix'){
                    if(node._status=='hide')
                        $result.push('Развернуть#open');
                    else
                        $result.push('Свернуть#open');
                    $result.push('');
                    if (node.level>0)
                        $result.push('Добавить группу#add_group_down');
                    $result.push('Добавить подгруппу#add_group');
                   $result.push('Добавить пользователя#add_user');
                }
                $result.push('','Copy#copy','Paste#paste');
                return $result;
            }
            return false
        }
        ,action:function(act,event){
            //console.log(event);
            if ( act=='rename' ) {
                $(this).editcell('go');
            } else if ( act=="open" ) {
                openSubtree(this);
            } else
                alert(act)
        }
    });

};
$(function(){
    // do resize via table with fixed layout
    $('.hresizer').ddr({
        //drag_container:'#slider'
        on_drag_start:function(event,options){
            options.start=event.pageX;
            // считаем, что это td
            // найдем соответствующий col
            var
                cnt=$(this).prevAll(this.tagName).length,
                col=$(this).parents('table').eq(0).find('col'),
                childcol=$(this).parents('div').eq(0).find('table').eq(1).find('col');
            if(col.eq(cnt-1).attr('width')){
                options._col=$(col.eq(cnt-1));
                options._childcol=$(childcol.eq(cnt-1));
                options.sign=1;
            } else {
                options._col=$(col.eq(cnt+1));
                options._childcol=$(childcol.eq(cnt+1));
                options.sign=-1;
            }
        }
        /*
         ,on_drag_complete:function(event){
         console.log('drag complete', this, event);
         }
         */
        ,on_drag:function(event,options){
            var w=parseInt(options._col.attr('width'))+(event.pageX-options.start)*options.sign;
            options.start=event.pageX;
            options._col.attr('width',w);
            options._childcol.attr('width',w);
        }
    })
})