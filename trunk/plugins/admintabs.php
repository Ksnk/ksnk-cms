<?php
/**
 * включить табы в панель администрирования
 */
//<% point_start('admin_css'); %>


.tabsheets,
.tabsheets dt,
.tabsheets dd
{
margin: 0;
padding: 0;
width: auto;
border: none;
}
dl.tabsheets dt,
dl.tabsheets dd
{
background-color: ThreeDFace;
color: ButtonText;
border: 1px solid ThreeDFace;
border-top-color: ThreeDHighlight;
border-right-color: ThreeDShadow;
border-bottom-color: ThreeDShadow;
border-left-color: ThreeDHighlight;
}
.tabsheets
{
position: relative;
padding: 0;
margin-bottom: 10px;
}
.tabsheets dt
{
float: left;
padding: 0.2em 1em;
cursor: default;
white-space: nowrap;
margin-top: 0.1em;
margin-bottom: -0.2em;
}
.tabsheets dt.active
{
background-color: ButtonHighlight;
color: ButtonText;
margin-top: 0;
padding-bottom: 0.3em;
}
.tabsheets dd
{
position: absolute;
width: 100%;
display: block;
left: 0;
margin: 1.6em 0 1em 0;
padding: 0;
}
.tabsheets dd .reducer
{
margin: 1em;
}
.tabsheets dd.inactive
{
display: none;
}
.tabsheets dd.inactive iframe,
.tabsheets dd.inactive select,
.tabsheets dd.inactive textarea,
.tabsheets dd.inactive input
{
display: none ! important;
}
.tabsheets dd.active
{
display: block;
}
.tabsheets .reducer
{
margin: 1em;
}
<% point_start('admin_js_onload') %>

function Make_Tabsheet(){
	var i, j, k, eDD, iMax_height, iDT_height, aeDL_child, sDD_inner_HTML;
	var bFirst_tab = true;
	var aeDl = document.getElementsByTagName( "DL" );

	for( i = 0 ; i < aeDl.length ; i++ ){
		if( aeDl[i].className == "tabsheets" ){
			aeDL_child = aeDl[i].childNodes;
			iMax_height = 0;
			for( j = 0 ; j < aeDL_child.length ; j++ ){
				if( aeDL_child[j].nodeName == "DT" ){
					iDT_height = aeDL_child[j].offsetHeight;
					aeDL_child[j].unselectable = true;
					aeDL_child[j].onmousedown = Switch_sheet;
					eDD = aeDL_child[j];
					while( eDD.nextSibling ){
						eDD = eDD.nextSibling;
						if( eDD.nodeName == "DD" ){
							if( eDD.offsetHeight > iMax_height ){
								iMax_height = eDD.offsetHeight;
							}
							if( !bFirst_tab ){
								eDD.className = "inactive";
							}else{
								aeDL_child[j].className = "active";
							}
							bFirst_tab = false;
							break;
						}
					}
				}
			}
			aeDl[i].style.height = (iMax_height + iDT_height) * 1 + "px";
			for( j = 0 ; j < aeDL_child.length ; j++ ){
				if( aeDL_child[j].nodeName == "DD" ){
					aeDL_child[j].style.height = iMax_height + "px";
				}
			}
		}
		return true
	}
}

function Switch_sheet( e ){
	var eTab = e ? e.target : window.event.srcElement;
	if( eTab.nodeType == 3){
		eTab = eTab.parentNode;
	}
	var eSheet = eTab
	while( eSheet.nextSibling ){
		eSheet = eSheet.nextSibling
		if( eSheet.nodeName == "DD" ){
			break;
		}
	}

	if( eSheet.className == "inactive" ){
		eTab.className = "on" ;
		var aeDL_child = eTab.parentNode.childNodes;
		for( var i = 0 ; i < aeDL_child.length ; i++ ){
			if( aeDL_child[i].nodeName == "DT" && aeDL_child[i].className != "on" ){
				aeDL_child[i].className = "";
			}else if( aeDL_child[i].nodeName == "DD" ){
				aeDL_child[i].className = "inactive";
			}
		}
		eSheet.className = "active";
		eTab.className = "active";
	}
	return false;
}
Make_Tabsheet();
<% point_finish(); %>