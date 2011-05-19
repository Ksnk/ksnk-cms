function checkImg(el,width,height){
    if(!el || !el.src) return ;
	var Img=new Image();
	var load_function= function(){
		Img.onload = null;
		if(!!this.__lastTry && (this.width==0 || this.height==0)){
			this.__lastTry=true;
			setTimeout(load_function,100); return;
		}
		if(this.width && this.height) {
			if(!width) if(el.style.width) width=parseInt(el.style.width);
			if(!height) if(el.style.height) height=parseInt(el.style.height);
			// looks like loaded!
			var k=1; // 1 - вписываем 0 - расширяем по габаритам
			if(width) k=Math.max(k,Img.width/width); else width=Img.width;
			if(height) k=Math.max(k,Img.height/height); else height=Img.height;
			if(!k) k=1;
	//		if (Img.width>width || Img.height>height){
				el.style.width=Math.round(Img.width/k)+'px';
				el.style.height=Math.round(Img.height/k)+'px';
	//		}
		}
		Img=null;
		load_function=null;
	}
	Img.onload=load_function;
	Img.src=el.src;
}
