/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 26.12.11
 * Time: 17:33
 * To change this template use File | Settings | File Templates.
 */
$.fn.regedit = function (action,o) {

    var options;

    if(typeof(action)!='string'){
        if(typeof(o)=='undefined')
            o=action;
        action='create';
    }

    function append_tr(xtree_body, row, level, hidden) {
        hidden = hidden || 0;
        // append one cell
        var child_cnt = row.children && row.children.length || 0;
        if (row.title && level.length > 0) {
            options._keys[row.key] = options._prefix + row.title;
            var $dig = '';
            var child_cnt = 0;
            if (row.children) {
                for (var i in row.children) {
                    var ii=row.children[i];if(!ii) continue;
                    if (ii.type == 'right') {
                        $m = ii.value.match(/(\d+)\|(.*?)\|(.+)$/);
                        $dig += options._keys[parseInt($m[1]) || 0] + ':' + ($m[2] == 0 ? '-' : '+') + $m[3] + ';';
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
            row._level=level.length;
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

            $result += '<span class="regedit-icon-'+(row['type']||'common')+'"/>';
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
                options._prefix = row.title + '|';
            }
            for (var i = 0; i < row.children.length; i++) {
                var ii=row.children[i];if(!ii) continue;
                ii._parent=row;
                append_tr(xtree_body, ii, level, hidden+(row._status == 'hide'?1:0));
            }
            if (row.type == 'aro_prefix') {
                options._prefix = '';
            }
            level.pop();
        }

    }

    function getNode(element){
        if($(element).is('tr')) return $(element);
        return $(element).parents('tr').eq(0);
    }
    function getRow(element){
        return getNode(element).data('row');
    }

    function deleteRow(el,row){
        if(!row){
            var e = $.Event("keydown", { keyCode: 38 }), r = getRow(el);
            $(document).trigger( e );
            row = r;
        }
        if(row.children){
            for(var i in row.children){
                var ii=row.children[i];if(!ii) continue;
                deleteRow(null,ii);
                delete(row.children[i]);
            }
        };
        if(row.key) // удаляем старую строку, иначе - вновьвставленную
            options._delete[row.key]=row.key;
        if(row._element)
            row._element.remove();
    }

    function editcell(el){
        $(document).contextMenu('keyboard',false);
        $(el).editcell('go',{exit:function(){$(document).contextMenu('keyboard',true)}});
    }
    function editselect(el,selmenu){
        console.log(selmenu);
        //$(document).contextMenu('keyboard',false);
        //$(el).editcell('go',{exit:function(){$(document).contextMenu('keyboard',true)}});
    }

    function create(){

        options={
            _keys:[],
            _prefix:'',
            // массивы для слежения за редактированием дерева
            _delete:[],
            _update:[],
            _append:[],
            activeState:'tree',
            // просто затычко
            empty:''

        };
        if(!o) o={};
    //      else if (typeof(o)=='string')
    //          o={action:o};
        $.extend(options,o);
        $(this).data('regedit',options);

        function fill_table(childs) {
            var tab = $('#propdata');
            tab.find('tbody').find('tr').remove();
            if (childs.length == 0) return;
            var $html;
            for (var i in childs) {
                var $m = (childs[i].value || '').match(/(\d+)\|(.*?)\|(.+)$/);
                if ($m) {
                    $html = '<tr><td class="regedit-propname"><span class="regedit-icon-select"></span>' + options._keys[parseInt($m[1]) || 0]
                            + '</td><td></td>'
                            + '<td class="regedit-propvalue">' + ($m[2] == 0 ? '-' : '+')
                            + '</td><td></td>'
                            + '<td class="regedit-propaction">' + $m[3]
                            + '</td><td></td>'
                            + '<td></td></tr>';
                    tab.find('tbody').append($html);
                }
            }
            $html = '<tr><td class="regedit-propname"></td><td></td>'
                    + '<td class="regedit-propvalue"></td><td></td>'
                    + '<td class="regedit-propaction"></td><td></td>'
                    + '<td></td></tr>';
            tab.find('tbody').append($html);
        }

        function recclose(row, $op) {
            if (row._element)
                $op = $op.add(row._element);
            if (row.children && row._status != 'hide')
                for (var i  in row.children) {
                    var ii=row.children[i];
                    $op = recclose(ii, $op);
                }
            return $op;
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
            if (row && row.children && row.children.length)
                for (var i  in row.children) {
                    var ii=row.children[i];
                    if (ii.type == 'right' || ii.type == 'action') {
                        childs.push(ii);
                    }
                 }
            fill_table(childs);
            var pos= options.activeNode.position(),
                xx = options.activeNode.parents('div:eq(0)'),
                xpos=xx.position();
            pos.top-=xpos.top;
            pos.left-=xpos.left;
            if ( pos.top+20>xx.height() )
                xx.scrollTop(xx.scrollTop() +20 + pos.top-xx.height() );
            else if ( pos.top <0 )
                xx.scrollTop(xx.scrollTop() + pos.top  );
            options.activeNode.addClass('active');
        }

        function openSubtree(el) {
            if (!el || el == window) {
                el = options.activeNode;
            }
            var $tr = getNode(el);
            var c = ['minus', 'lastminus', 'lastplus', 'plus'];
            for (var i = 0; i < c.length; i++) {
                var $x = $tr.find('.regedit-tree-' + c[i]);
                if ($x.length > 0) {// 0-3 1-2
                    tree_close($x, 'regedit-tree-' + c[i], 'regedit-tree-' + c[3 - i]);
                } else
                    continue;
                break;
            }
        }

        function tree_close($tgt, classR, classA) {
            var row = getRow($tgt);
            if (row && row.children) {
                var $op = $();
                for (var i in row.children) {
                   $op = recclose(row.children[i], $op);
                }
                var op = classR.match(/minus$/) ? 'hide' : 'show';
                $op[op]();
                row._status = op;
            }
            $tgt.removeClass(classR).addClass(classA);
        }

        this.click(function (event) {
            var $tgt = $(event.target);
            if ($tgt.is('.regedit-title')) {
                make_Active($tgt);
            } else
            if ($tgt.is('.regedit-tree-plus,.regedit-tree-lastplus,.regedit-tree-minus,.regedit-tree-lastminus')){
                if ($tgt.is('.regedit-tree-plus,.regedit-tree-lastplus,.regedit-tree-minus,.regedit-tree-lastminus'))
                    openSubtree($tgt);
            } else
            if ($tgt.is('.regedit-propname,.regedit-propvalue,.regedit-propaction'))
                editcell($tgt);
            else
            if ($tgt.is('.regedit-icon-select'))
                editselect($tgt,$tgt.parent()[0].className); //todo: исправить на более разумный выбор
            }
        );

        $(document).contextMenu({
            hotkey:{
                32:'contextMenu',
                93:'contextMenu',
                37:'keyright',
                38:'keyup',
                39:'keyleft',
                40:'keydown',
                Del:'del',
                'Alt-R':'rename',
                'Enter':'open',
                'default':'open'
            },
            menu:function () {
                if(options.activeState=='prop'){
                    return ['hello','is it me','you looking for'];
                }
                else if(options.activeState=='tree')
                if ($(this).is('.regedit-title')) {
                    make_Active($(this));
                    var $result = ['Переименовать#rename'],
                        node = getRow(this);
                    if (node.type == 'axo_group' || node.type == 'axo_prefix') {
                        if (node._status == 'hide')
                            $result.push('Развернуть#open');
                        else
                            $result.push('Свернуть#open');
                        $result.push('');
                        $result.push({title:'Добавить', children:[
                            'Группу#add_group_down',
                            'префикс#add_group',
                            'пользователя#add_user'
                        ]});
                    }
                    $result.push('Удалить#del');
                    $result.push('', 'Copy#copy', 'Paste#paste');
                    return $result;
                }
                return false
            }, action:function (act/*,event*/) {
                if(options.activeState=='tree')
                switch (act) {
                    case 'contextMenu':
                        $(document).contextMenu('show', options.activeNode.find('.regedit-title').eq(0));
                        break;
                    case 'keyleft':
                        var row = options.activeNode.data('row');
                        if (row && row._status && row._status == 'hide') {
                            openSubtree(options.activeNode);
                            break;
                        }
                    case 'keydown':
                        var pos = 'first', func = 'nextAll';
                        if ($('#tree tr.active').length == 0) {
                            make_Active($('#tree tr:visible:' + pos));
                        } else {
                            var x = $('#tree tr.active')[func](':visible');
                            if (x.length > 0)
                                make_Active(x.eq(0));
                        }
                        break;
                    case 'keyright':
                        row = options.activeNode.data('row');
                        if (row && (!row._status || row._status == 'hide')) {
                            if (row._parent && row._parent._element)
                                make_Active(row._parent._element);
                            break;
                        } else if (row && row._status && row._status != 'hide') {
                            openSubtree(options.activeNode);
                            break;
                        }
                    case 'keyup':
                        var pos = 'last', func = 'prevAll';
                        if ($('#tree tr.active').length == 0) {
                            make_Active($('#tree tr:visible:' + pos));
                        } else {
                            var x = $('#tree tr.active')[func](':visible');
                            if (x.length > 0)
                                make_Active(x.eq(0));
                        }
                        break;
                    case 'slowdbl':
                    case 'rename':
                        var x =  this;
                        if(!x || x==window){
                            x=options.activeNode.find('.regedit-title').eq(0);
                        }
                        if($(x).is('a.regedit-title'))
                            editcell(x);
                        break;
                    case 'open':
                        openSubtree(this);
                        break;
                    case 'del':
                        deleteRow(options.activeNode);
                        break;
                    default:
                        alert(act)

                }
                else if(options.activeState=='prop')
                switch (act) {
                    case 'contextMenu':
                        $(document).contextMenu('show', ['hello','it\'sme']);
                        break;
                    /*
                    case 'keyleft':
                        var row = options.activeNode.data('row');
                        if (row && row._status && row._status == 'hide') {
                            openSubtree(options.activeNode);
                            break;
                        }
                    case 'keydown':
                        var pos = 'first', func = 'nextAll';
                        if ($('#tree tr.active').length == 0) {
                            make_Active($('#tree tr:visible:' + pos));
                        } else {
                            var x = $('#tree tr.active')[func](':visible');
                            if (x.length > 0)
                                make_Active(x.eq(0));
                        }
                        break;
                    case 'keyright':
                        row = options.activeNode.data('row');
                        if (row && (!row._status || row._status == 'hide')) {
                            if (row._parent && row._parent._element)
                                make_Active(row._parent._element);
                            break;
                        } else if (row && row._status && row._status != 'hide') {
                            openSubtree(options.activeNode);
                            break;
                        }
                    case 'keyup':
                        var pos = 'last', func = 'prevAll';
                        if ($('#tree tr.active').length == 0) {
                            make_Active($('#tree tr:visible:' + pos));
                        } else {
                            var x = $('#tree tr.active')[func](':visible');
                            if (x.length > 0)
                                make_Active(x.eq(0));
                        }
                        break;
                    case 'slowdbl':
                    case 'rename':
                        var x =  this;
                        if(!x || x==window){
                            x=options.activeNode.find('.regedit-title').eq(0);
                        }
                        if($(x).is('a.regedit-title'))
                            editcell(x);
                        break;
                    case 'open':
                        openSubtree(this);
                        break;
                    case 'del':
                        deleteRow(options.activeNode);
                        break;
                        */
                    default:
                        alert(act)

                }
            }
        });

 //       .contextMenu('disable','rename');
    }

    function setActive(state,options){
        if(options.activeState!=state){
            if(options.activeState)
                $('#regedit').removeClass('state_'+options.activeState)
            options.activeState=state;
            $('#regedit').addClass('state_'+options.activeState);
        }
    }

    function update(){
        var tree = $('#tree');
        if (options && options.children) {
            var xtree = tree.clone(true).find('tr').remove().end();
            append_tr(xtree.find('tbody'), options, []);
            var parent = tree.parent();
            tree.remove();
            parent.append(xtree);
        }
    }
    if(action=='create'){
        create.call(this);
    } else {
        options=$(this).data('regedit');
        switch (action){
            case 'setActive':// выделить дерево
                setActive(o,options);
                break;
            case 'getActive':
                return getRow(options.activeNode);
            case 'serializeTree':
               // сериализовать все кусочки данных
                var result = [];
                if(options._delete){
                    for(var a in options._delete)
                        result.push('delete[]='+a);
                }
                if(options._update){
                    result.push('update='+options._update.join(','));
                }
                if(options._append){
                    result.push('append='+options._append.join(','));
                }
                return result.join('&');
            case 'delNode':
                deleteRow(options.activeNode);
                break;
            case 'update':
                $.extend(options,o);
                update.call(this);
                break;
        }
    }

    return this;
};
$(function () {

    $('#tree').parents('td:eq(0)').bind('mousedown mouseup',function(){
        $('#regedit').regedit('setActive','tree');
    });
    $('#propdata').parents('td:eq(0)').bind('mousedown mouseup',function(){
        $('#regedit').regedit('setActive','prop');
    });

    // do resize via table with fixed layout
    $('.hresizer').ddr({
        //drag_container:'#slider'
        on_drag_start:function (event, options) {
            options.start = event.pageX;
            // считаем, что это td
            // найдем соответствующий col
            if (!$(this).data('hresizer')) {
                var
                    $self = $(this),
                    cnt = $self.prevAll(this.tagName).length,
                    col = $self.parents('table').eq(0).find('col'),
                    childcol = this.tagName.toLowerCase() == 'td'
                        ? $self.parents('div').eq(0).find('table').eq(1).find('col')
                        : $('#' + $self.parents('table').eq(0).attr('id').substr(1)).find('col')
                    ;

                if (col.eq(cnt - 1).attr('width')) {
                    $self.data('hresizer', [$(col.eq(cnt - 1)), $(childcol.eq(cnt - 1)), 1]);
                } else {
                    $self.data('hresizer', [$(col.eq(cnt + 1)), $(childcol.eq(cnt + 1)), -1])
                }
            }
        }, on_drag:function (event, options) {
            var xxx = $(this).data('hresizer'),
                w = parseInt(xxx[0].attr('width')) + (event.pageX - options.start) * xxx[2];

            options.start = event.pageX;
            if(w>0 ){
                xxx[0].attr('width', w);
                xxx[1].attr('width', w);
            }
        }
    });
    $('.vresizer').ddr({
        on_drag_start:function (event, options) {
            options.start = event.pageY;
            var $self=$(this),data=$self.data('vresizer');
            if(!data){
                var $x=$self.parents('tr:eq(0)'),
                    maxheight=$x.height()+$x.prev('tr').height()+$x.next('tr').height();
                data=[
                    $self.parents('tr:eq(0)').next('tr').find('td').eq(0),-1,maxheight];
                $self.data('vresizer',data);
            }
            options.startheight = parseInt(data[0].css('height'));
        }
        ,on_drag:function (event, options) {
            var xxx=$(this).data('vresizer'),
                w = options.startheight + (event.pageY - options.start) * xxx[1];
            console.log(xxx);
            if(w>15 && w<xxx[2])
                xxx[0].css('height', w);
        }
    })

});
