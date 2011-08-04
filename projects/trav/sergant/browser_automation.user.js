<%
define("SERGANT","sergant/js.plugins/");
include SERGANT."header.txt" ;
%>


(function (){
<%
include SERGANT."engine.js";
//include SERGANT."FarmingMachine.js";
//include SERGANT."countdown.js";
include SERGANT."MainMenu.js";
include SERGANT."un-serial.js";
// должен быть самым последним
include SERGANT."trav.js";
%>
})()
