function Throw_Up(el,src,w,h){
	
	var t=$(el).parents('.galleryX').find('img')[0];
	t.src=src;
	//t.onload=function(){checkImg(t)};
	return false;
}