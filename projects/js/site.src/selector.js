/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 29.11.11
 * Time: 19:06
 * Helper to work with textarea and his corsor.
 */
jQuery.fn.carret=function(){
        var $self=this[0];
        if(!!$self){
            $($self).focus();
           if($self.selectionStart){
                return $self.selectionStart;
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


