/**
 * ��������� ��������� ������ ��� jQuery
 * @param element
 * @author http://habrahabr.ru/blogs/webdev/18080/
 * TODO: ��������� ��� ���������� ��������
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
    if (event.ctrlKey && key == 'A'.charCodeAt(0))  // 'A'.charCodeAt(0) ����� �������� �� 65
    {
      removeSelection();

      if (event.preventDefault) 
        event.preventDefault();
      else
        event.returnValue = false;
    }
  }

  // �� ���� �������� ����� ������
  this.bind('mousemove', function(){
    if(preventSelection)
      removeSelection();
  });
  
  this.bind('mousedown', function(event){
    var sender = event.target || event.srcElement;
    preventSelection = !sender.tagName.match(/INPUT|TEXTAREA/i);
  });

  // ����� dblclick
  // ���� ������ ������� �� �� ������� dblclick, ����� ��������
  // ��������� ��������� ������ � ��������� ���������
  this.bind('mouseup', function(event){
	var sender = event.target || event.srcElement;  
    if (!sender.tagName.match(/INPUT|TEXTAREA/i) && preventSelection)
      removeSelection();
    preventSelection = false;
  });

  // ����� ctrl+A
  // ������ ����� ��� � �� ����, � ���� �� ���� ����������
  // ��� � ������ ��� �� ����� ������������� ������� ����� 
  // ������ ���� ��� � �� document, � �� �� �������
/*  this.bind('keydown', killCtrlA);
  this.bind('keyup', killCtrlA); */
};