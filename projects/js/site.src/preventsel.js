/**
 * запретить выделения мышкой для jQuery
 * @param element
 * @author http://habrahabr.ru/blogs/webdev/18080/
 * TODO: придумать как прикрутить оптионсы
 */
$.fn.preventSelection=function(){
  var preventSelection = false;

  function removeSelection(){
    if (window.getSelection) { window.getSelection().removeAllRanges(); }
    else if (document.selection && document.selection.clear)
      document.selection.clear();
  }
  
  function killCtrlA(event){
    var event = event || window.event;
    var sender = event.target || event.srcElement;

    if (sender.tagName.match(/INPUT|TEXTAREA/i))
      return;

    var key = event.keyCode || event.which;
    if (event.ctrlKey && key == 'A'.charCodeAt(0))  // 'A'.charCodeAt(0) можно заменить на 65
    {
      removeSelection();

      if (event.preventDefault) 
        event.preventDefault();
      else
        event.returnValue = false;
    }
  }

  // не даем выделять текст мышкой
  this.bind('mousemove', function(){
    if(preventSelection)
      removeSelection();
  });
  
  this.bind('mousedown', function(event){
    var sender = event.target || event.srcElement;
    preventSelection = !sender.tagName.match(/INPUT|TEXTAREA/i);
  });

  // борем dblclick
  // если вешать функцию не на событие dblclick, можно избежать
  // временное выделение текста в некоторых браузерах
  this.bind('mouseup', function(event){
	var sender = event.target || event.srcElement;  
    if (!sender.tagName.match(/INPUT|TEXTAREA/i) && preventSelection)
      removeSelection();
    preventSelection = false;
  });

  // борем ctrl+A
  // скорей всего это и не надо, к тому же есть подозрение
  // что в случае все же такой необходимости функцию нужно 
  // вешать один раз и на document, а не на элемент
/*  this.bind('keydown', killCtrlA);
  this.bind('keyup', killCtrlA); */
};