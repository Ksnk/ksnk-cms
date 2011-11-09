/**
 * Debug_HackerConsole_Js: JavaScript frontend for hacker console.
 * (C) 2005 Dmitry Koterov, http://forum.dklab.ru/users/DmitryKoterov/
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * See http://www.gnu.org/copyleft/lesser.html
 *
 * @version 1.05
 */
 
function Debug_HackerConsole_Js(top) { this.construct(window) }
Debug_HackerConsole_Js.prototype = {
	top: null,
	div: null,
	height: {HEIGHT},
	curHeight: 0,
	step: 0,
	speedOn: 0.3,
	speedOff: 10,
	dt: 50,
	fontsize: 15,
	groups: null,

	construct: function(t) { with (this) {
		top = t || window;
		groups = {};
		with (top.document) {
			div = body.insertBefore(createElement('div'), body.childNodes[0]);
			div.className = 'console';
			with (div.style) {
				display = 'none';
				background = 'black';
				zIndex = 999999999;
				position = 'relative';
				padding = '4px';
				width = 'auto';
				height = this.height + 'px';
				overflow = 'auto';
				border = 'inset 2px #FFFFFF';
				color = '#00EE00';
				font = 'normal ' + this.fontsize + 'px "Courier new"';
			}
			var th = this;
			var owner = window.HTMLElement? window : body;
			var prevKeydown = owner.onkeydown;
			owner.onkeydown = function(e) {
				if (!e) e = window.event;
				if (e.ctrlKey && (e.keyCode == 192 || e.keyCode == 96)) {
					th.toggle(-1);
					return false;
				}
				if (prevKeydown) {
					this.__prev = prevKeydown;
					return this.__prev(e);
				}
			}
			toggle(null);
		}
	}},
  
	toggle: function(on, onstart) {
		var cookName = 'console';
		if (on == null) on = Math.round(this.getCookie(cookName));
		if (on == -1) on = !Math.round(this.getCookie(cookName));
		if (on) {
			this.curHeight = 0;
			this.step = this.speedOn;
		} else {
			this.curHeight = this.div.style.display=='block'? this.height : 0;
			this.step = -this.speedOff;
		}
		var th = this;
		var fResizer = function() {
			th.div.style.display = on? 'block' : 'none';
			th.curHeight = th.curHeight + th.height*th.step;
			if (th.curHeight < 0) th.curHeight = 0;
			if (th.curHeight > th.height) th.curHeight = th.height;
			th.div.style.height = (Math.round(th.curHeight)+1) + "px";
			th.div.style.display = 'block';
			if (th.curHeight <= 1) {
				th.div.style.display = 'none';
				return;
			} else if (th.curHeight >= th.height) {
				return;
			}
			setTimeout(fResizer, th.dt);
		}
		fResizer();
		this.setCookie(cookName, on? 1 : 0, '/', new Date(new Date().getTime()+3600*24*365*1000));
	},
  
 	out: function(msg, title, group) { with (this) {
		if (!msg) return;
		var span = top.document.createElement('div');
		span.innerHTML = msg;
		var container = div;
		if (group) {
			container = groups[group];
			if (!container) {
				var groupDiv = top.document.createElement('div');
				div.appendChild(groupDiv);
				groupDiv.style.marginBottom = "7px";
				var headDiv = top.document.createElement('div');
				groupDiv.appendChild(headDiv);
				headDiv.innerHTML = group + ":";
				headDiv.style.fontWeight = "bold";
				headDiv.style.fontSize = (fontsize+5)+"px";
				container = top.document.createElement('div');
				groupDiv.appendChild(container);
				container.style.marginLeft = "1em";
				container.style.paddingLeft = "4px";
				container.style.borderLeft = "3px double";
				groups[group] = container;
			}
		}
		container.appendChild(span);
		div.scrollTop = 10000000;
		if (title != null) {
			span.title = title;
			try { span.style.cursor = "pointer"; } catch (e) {}
		}
	}},

	// ������� ��������� �������� cookie.
	setCookie: function(name, value, path, expires, domain, secure) {
	  var curCookie = name + "=" + escape(value) +
	    ((expires) ? "; expires=" + expires.toGMTString() : "") +
	    ((path) ? "; path=" + path : "; path=/") +
	    ((domain) ? "; domain=" + domain : "") +
	    ((secure) ? "; secure" : "");
	  document.cookie = curCookie;
	},
	
	// ������� ������ �������� cookie.
	getCookie: function(name) {
	  var prefix = name + "=";
	  var cookieStartIndex = document.cookie.indexOf(prefix);
	  if(cookieStartIndex == -1) return null;
	  var cookieEndIndex = document.cookie.indexOf(";", cookieStartIndex + prefix.length);
	  if (cookieEndIndex == -1) cookieEndIndex = document.cookie.length;
	  return unescape(document.cookie.substring(cookieStartIndex + prefix.length, cookieEndIndex));
	}
}