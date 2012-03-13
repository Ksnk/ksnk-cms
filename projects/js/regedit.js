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
        var i, ii, child_cnt = row.children && row.children.length || 0;
        if (row.title && level.length > 0) {
            if(row.type.match(/^aro/))
                options._keys[row.key] = options._prefix + row.title;
            var $dig = '';

            if(row.prop){
                for ( i in row.prop) {
                    ii=row.prop[i];if(!ii) continue;
                    if (ii.type == 'right') {
                        $dig += options._keys[ii.id || 0] + ':' + (ii.allow == 0 ? '-' : '+') + ii.action + ';';
                    } else if (ii.type == 'action') {
                        $dig += ii.value + ';';
                    }
                }
            }
            $result = '';
            // символ +-[] -
            var $xxx = '';
            if (child_cnt== 0) {
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
            for (i = level.length - 2; i >= 0; i--) {
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
            row._element = $($result).appendTo(xtree_body).data('row', row);
         }
        if (child_cnt) {
            level.push(child_cnt);
            if (row.type == 'aro_prefix') {
                options._prefix = row.title + '|';
            }
            for ( i = 0; i < row.children.length; i++) {
                 ii=row.children[i];if(!ii) continue;
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
        return $(element).closest('tr');
    }
    function getRow(element){
        return getNode(element).data('row');
    }

    /**
     * преобразовать узел к другому типу
     * @param el
     * @param type
     */
    function trnTo(el,type){
        var row=el.data('row');
        row.type=type;
        update();
    }

    /**
     * Добавить узел в узел
     * @param el
     * @param type
     * @param title
     */
    function insertRow(el,type,title){
        var row=el.data('row');
        row._status='show';
        if(!row.children) row.children=[];
        var child={key:options._nextKey--,type:type,title:title||'New '+type};
        row.children.push(child);
        update();
        options._append[child.key]=child;

        if(child._element){
            make_Active(child._element);
            editcell($('.regedit-title',child._element));
        }
//        console.log(child);
    }

    /**
     * удалить узел напрочь
     * @param el
     * @param row
     */
    function deleteRow(el,row) {
        var activeNode=false;
        if(!row){
            row = options.activeNode.data('row');
            var x = $('#tree tr.active').prevAll(':visible:first');
            if (x.length)
                activeNode=x.data('row');
            else
                activeNode=$('#tree tr:visible:first').data('row');
            options.activeNode.data('row',activeNode);
        }
        //console.log('x ',row.key);
        if(row.children) {
            var xx=[],i;
            for( i in row.children)xx.push(row.children[i]);
            for( i in xx)
                deleteRow(null,xx[i]);
        }
        if(row.key<0) {// удаляем старую строку, иначе - вновьвставленную
            if(options._append[row.key]) delete(options._append[row.key]);
        } else {
            options._delete[row.key]=row.key;
            //console.log(row.key);
        }
        if(options._update[row.key]) delete(options._update[row.key]);
        // удаляем из парента
        if(el) {
            var idx=$.inArray(row,row._parent.children);
            //console.log('y ',row.key,row._parent.key);
            if(idx>=0){
                row._parent.children.splice(idx,1);
            }
            update();
        }
        if(row._element)
            row._element.remove();
    }

    function editcell(el){
       // $(document).contextMenu('keyboard',false);
        $(el).editcell('go',options.editcell_data);
    }
    function editselect(el){
        $(document).contextMenu('select',el.parent(),options.editcell_data);
    }

    function fill_table(childs) {
        var tab = $('#propdata');
        tab.find('tbody').find('tr').remove();
        var $html;
        for (var i in childs) {
            $html = '<tr><td class="regedit-propname"><span class="regedit-icon-select"></span>'
                        + options._keys[parseInt(childs[i].id) || 0]
                    + '</td><td></td>'
                    + '<td class="regedit-propvalue"><span class="regedit-icon-select"></span>'
                        + (childs[i].allow == 0 ? '-' : '+')
                    + '</td><td></td>'
                    + '<td class="regedit-propaction"><span class="regedit-icon-select"></span>'
                        + childs[i].action
                    + '</td><td></td>'
                    + '<td></td></tr>';
            $($html).appendTo(tab.find('tbody')).data('row', childs[i]);
        }
        $html = '<tr><td class="regedit-propname"><span class="regedit-icon-select"></span>&nbsp;</td><td></td>'
                + '<td class="regedit-propvalue"><span class="regedit-icon-select"></span></td><td></td>'
                + '<td class="regedit-propaction"><span class="regedit-icon-select"></span></td><td></td>'
                + '<td></td></tr>';
        $($html).appendTo(tab.find('tbody'));
    }

    function make_Active($tgt){
        if (options.activeNode) {
             options.activeNode.removeClass('active');
        }
        options.activeNode = getNode($tgt);
        if (options.onActivate) {
             options.onActivate(options.activeNode);
        }
        var row = options.activeNode.data('row');
        fill_table(row.prop||[]);
        _scrollIntoView(options.activeNode);
        options.activeNode.addClass('active');
    }

    function create(){

        options={
            _keys:[],
            _prefix:'',
            // массивы для слежения за редактированием дерева
            _delete:[],
            _update:[],
            _append:[],
            /** @var int - индекс, устанавливаемый для вставки */
            _nextKey:-1,
            activeState:'tree',
            activeNode:null,
            /**
             * постоянный параметр для editcell
             */
            editcell_data:{
                set_text:function(txt){
                    var row,regedit=$('#regedit'),options=regedit.data('regedit');
                    if($(this).is('.regedit-title')){
                        //делаем rename в дереве
                        row=$(this).parent().parent().data('row');
                        if(!options._append[row.key])
                            options._update[row.key]=row;
                        row.title=txt;
                     } else if($(this).is('.regedit-propname,.regedit-propvalue,.regedit-propaction')){
                        var parent=options.activeNode.data('row');
                        //делаем rename в дереве
                        row=$(this).parent().data('row');
                        if(!row) var str ={id:0,allow:0,action:'read',type:'right'};
                        if($(this).is('.regedit-propname')){
                            (row||str)['id']=$.inArray(txt,options._keys);
                        } else if($(this).is('.regedit-propvalue')){
                            (row||str)['allow']=(0+(txt=='+'));
                        } else
                            (row||str)['action']=txt;

                        if(!row) { // обновляем данные
                            //добавляем новую строку
                            if(parent=options.activeNode.data('row')){
                                if(parent.prop){
                                    parent.prop.push(str)
                                } else {
                                    parent.prop=[str]
                                }
                                options._update[parent.key]=parent;
                            }
                        } else {
                            options._update[row.key]=row;
                        }

                       // $(this).html('<span class="regedit-icon-select"></span>'+txt);
                       // update.call($('#regedit'));
                        update.call(regedit);
                        make_Active(parent._element);
                        setActive('prop',options);
                        return true;
                    }
                    $(this).text(txt);
                }
            },
            // просто затычко
            empty:''

        };
        if(!o) o={};
    //      else if (typeof(o)=='string')
    //          o={action:o};
        $.extend(options,o);
        $(this).data('regedit',options);

        function recclose(row, $op) {
            if (row._element)
                $op = $op.add(row._element);
            if (row.children && row._status != 'hide')
                for (var i  in row.children) {
                    $op = recclose(row.children[i], $op);
                }
            return $op;
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
                editselect($tgt);
                //todo: исправить на более разумный выбор
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
            menu:function (o) {
                if(options.activeState=='prop'){
                    if(o._mode=='select'){
                        if($(this).is('.regedit-propname')){
                            var xxx={};
                            return $.map( options._keys, function (a) {
                                if(xxx[a]) return null;
                                xxx[a]=1;
                                return a;
                           } ).sort();
                        } else if($(this).is('.regedit-propvalue')){
                           return ['+','-']
                        } else if($(this).is('.regedit-propaction')){
                           return ['create','read','write','delete'];
                        }
                    } else
                    return ['hello','is it me','you looking for'];
                }
                else if(options.activeState=='tree')
                if ($(this).is('.regedit-title')) {
                    make_Active($(this));
                    var $result =[] ,
                        node = getRow(this);
                    // статистико рулед!
                    var m=node.type.match(/(a[rx]o)(_prefix|_group)?/)
                        ,cnt=node.children && node.children.length>0||false;

                    if(cnt){
                        if (node._status == 'hide')
                            $result.push('Развернуть#open');
                        else
                            $result.push('Свернуть#open');
                        $result.push('');
                    }

                    $result.push('Переименовать#rename');
                    if (!!m[2]) {
                        var children=['Группу#add_'+m[1]+'group'];
                        if(m[2]=='_group')
                            children.push('Префикс#add_'+m[1]+'prefix');
                        if('aro'==m[1])
                            children.push('Объект#add_aro');
                        else
                            children.push('Пользователя#add_axo');
                        $result.push({title:'Добавить',children:children});
                    }
                    $result.push({title:'Преобразовать в', children:
                        [
                            'Группу#trn_'+m[1]+'group',
                            'Префикс#trn_'+m[1]+'prefix',
                            'aro'==m[1]?'Объект#trn_aro':'Пользователя#trn_axo'
                        ]
                    });

                    $result.push('Удалить#del');
                    $result.push('', 'Copy#copy', 'Paste#paste');
                    return $result;
                }
                return false
            }, action:function (act/*,event*/) {
                var x,row,pos,func;
                if(options.activeState=='tree')
                switch (act) {
                    case 'contextMenu':
                        $(document).contextMenu('show', options.activeNode.find('.regedit-title').eq(0));
                        break;
                    case 'keyleft':
                        row = options.activeNode.data('row');
                        if (row && row._status && row._status == 'hide') {
                            openSubtree(options.activeNode);
                            break;
                        }
                    case 'keydown':
                        pos = 'first'; func = 'nextAll';
                        if ($('#tree tr.active').length == 0) {
                            make_Active($('#tree tr:visible:' + pos));
                        } else {
                            x = $('#tree tr.active')[func](':visible');
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
                        pos = 'last'; func = 'prevAll';
                        if ($('#tree tr.active').length == 0) {
                            make_Active($('#tree tr:visible:' + pos));
                        } else {
                            x = $('#tree tr.active')[func](':visible');
                            if (x.length > 0)
                                make_Active(x.eq(0));
                        }
                        break;
                    case 'slowdbl':
                    case 'rename':
                        x =  this;
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
                    // вставка элементов
                    case 'add_axogroup':
                        insertRow(options.activeNode,'axo_group','Группа пользователей'); break;
                    case 'add_axoprefix':
                        insertRow(options.activeNode,'axo_prefix','Префикс'); break;
                    case 'add_axo':
                        insertRow(options.activeNode,'axo','Пользователь'); break;
                    case 'add_arogroup':
                        insertRow(options.activeNode,'aro_group','Группа объектов'); break;
                    case 'add_aroprefix':
                        insertRow(options.activeNode,'aro_prefix','Префикс'); break;
                    case 'add_aro':
                        insertRow(options.activeNode,'aro','Объект'); break;
                    case 'trn_axogroup':
                        trnTo(options.activeNode,'axo_group'); break;
                    case 'trn_axoprefix':
                        trnTo(options.activeNode,'axo_prefix'); break;
                    case 'trn_axo':
                        trnTo(options.activeNode,'axo'); break;
                    case 'trn_arogroup':
                        trnTo(options.activeNode,'aro_group'); break;
                    case 'trn_aroprefix':
                        trnTo(options.activeNode,'aro_prefix'); break;
                    case 'trn_aro':
                        trnTo(options.activeNode,'aro'); break;

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
                $('#regedit').removeClass('state_' + options.activeState);
            options.activeState=state;
            $('#regedit').addClass('state_'+options.activeState);
        }
    }

    function ToString(prefix,obj){
        var result=[];
        for(var a in obj){
            for(var b in obj[a]){
                if(b!='key' && !b.match(/^_/) && !!obj[a][b])
                    result.push(prefix+'['+obj[a].key+']['+b+']='+encodeURIComponent(obj[a][b]));
            }
            if(obj[a]._parent)
                result.push(prefix+'['+obj[a].key+'][parent]='+obj[a]._parent.key);
        }
        return result.join('&');
    }

    function update(){
        var tree = $('#tree');
        if (options && options.children) {
            var row=options.activeNode && options.activeNode.data('row') || false,
                xtree = tree.clone(true).find('tr').remove().end();
            append_tr(xtree.find('tbody'), options, []);
            var parent = tree.parent();
            tree.remove();
            parent.append(xtree);
            if(row)
                make_Active(row._element);
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
                var x;
                if(x=ToString('update',options._update))result.push(x);
                if(x=ToString('append',options._append))result.push(x);
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

    $('#tree').closest('td').bind('mousedown mouseup',function(){
        $('#regedit').regedit('setActive','tree');
    });
    $('#propdata').closest('td').bind('mousedown mouseup',function(){
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
                    col = $self.closest('table').find('col'),
                    childcol = this.tagName.toLowerCase() == 'td'
                        ? $self.closest('div').find('table').eq(1).find('col')
                        : $('#' + $self.closest('table').attr('id').substr(1)).find('col')
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
                var $x=$self.closest('tr'),
                    maxheight=$x.height()+$x.prev('tr').height()+$x.next('tr').height();
                data=[
                    $self.closest('tr').next('tr').find('td').eq(0),-1,maxheight];
                $self.data('vresizer',data);
            }
            options.startheight = parseInt(data[0].css('height'));
        }
        ,on_drag:function (event, options) {
            var xxx=$(this).data('vresizer'),
                w = options.startheight + (event.pageY - options.start) * xxx[1];
           // console.log(xxx);
            if(w>15 && w<xxx[2])
                xxx[0].css('height', w);
        }
    })

});