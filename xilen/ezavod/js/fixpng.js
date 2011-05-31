function fixPNG(element)
{
  //Если браузер IE версии 5.5-6
  if (/MSIE (5\.5|6).+Win/.test(navigator.userAgent))
  {
    var src;

    if (element.tagName=='IMG') //Если текущий элемент картинка (тэг IMG)
    {
      if (/\.png$/.test(element.src)) //Если файл картинки имеет расширение PNG
      {
        src = element.src;
        element.src = "img/x1x1.gif"; //заменяем изображение прозрачным gif-ом
      }
    }
    else //иначе, если это не картинка а другой элемент
    {
	  //если у элемента задана фоновая картинка, то присваеваем значение свойства background-шmage переменной src
      src = element.currentStyle.backgroundImage.match(/url\("(.+\.png)"\)/i);
      if (src)
      {
        src = src[1]; //берем из значения свойства background-шmage только адрес картинки
        element.runtimeStyle.backgroundImage="none"; //убираем фоновое изображение
      }
    }
    //если, src не пуст, то нужно загрузить изображение с помощью фильтра AlphaImageLoader
    if (src) element.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "',sizingMethod='scale')";
  }
}