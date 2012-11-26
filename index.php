<?
session_start();
require_once("../dbconn.php");

mysql_query("set character_set_connection=utf8;");
mysql_query("set character_set_server=utf8;");
mysql_query("set character_set_client=utf8;");
mysql_query("set character_set_results=utf8;");
mysql_query("set character_set_database=utf8;");

$viewtheme = $_COOKIE['viewtheme'];
if( !$viewtheme )
	$viewtheme='c';
if( $_GET['changetheme'])
	$viewtheme = $_GET['changetheme'];

if( $_GET['command'] ){
	setcookie("viewmode2",$_GET['command'],time()+60*60*24*356);
}


$viewmode2 = $_COOKIE['viewmode2'];

//$viewmode2 = 'direct';
if( !$viewmode2 )
	$viewmode2 = 'direct';

if($_COOKIE['viewmode2'] == 'direct')
	$changemode = 'indirect';
else
	$changemode = 'direct';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="apple-touch-startup-image" href="logo.png" />
	<link rel="apple-touch-icon" href="shortcut.png"/>
	<link rel="apple-touch-icon-precomposed" href="shortcut.png"/>
	<title>흥한글</title>
	<?//<link rel="stylesheet" href="js/jquery.mobile-1.0b1.min.css" />?>
	<link rel="stylesheet" href="js/jquery.mobile-1.1.1.min.css" />
	<?//<script src="js/jquery-1.6.1.min.js"></script>?>
	<script src="js/jquery-1.8.0.min.js"></script>
	<?//<script src="js/jquery.mobile-1.0b1.min.js"></script>?>
	<script src="js/jquery.mobile-1.1.1.min.js"></script>
	<style>
	body { font-size:0.8em; }
	</style>

		<script type="text/javascript"><!--
  		// XHTML should not attempt to parse these strings, declare them CDATA.
      	/* <![CDATA[ */
	    window.googleAfmcRequest = {
			client: 'ca-mb-pub-9683070520101388',
			format: '320x50_mb',
			output: 'html',
			slotname: '3257313290',
		};
		/* ]]> */
		//--></script>

<script>



function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function updateArticle(community,board,mode,direct,target,firstid,viewmode)
{
	$.mobile.showPageLoadingMsg();
	$.get('bbs_ajax.php', { 'comm': community , 'board' : board, 'mode' : mode,'direct':direct , 'id' : firstid ,'rnd' : Math.floor(Math.random() * 99999) } , function(html){
	    $.mobile.hidePageLoadingMsg();
		//alert(html);
	    $(target).prepend(html).listview("refresh");
	});
}

function moreArticle(community,board,mode,direct,target,lastid,viewmode)
{
    $.mobile.showPageLoadingMsg();
    $.get('bbs_ajax.php', { 'comm': community , 'board' : board, 'mode': mode,'direct':direct ,'id':lastid,'rnd' : Math.floor(Math.random() * 99999) } , function(html){
		$.mobile.hidePageLoadingMsg();
		$(target).append(html).listview("refresh");
	});
}

function imgResize() {
    $('img').css({'width':'100%', 'height':'auto'});
}

$(document).ready(imgResize);

function getReply2(community,board,no,target){
	$.mobile.showPageLoadingMsg();
	url = '/service/crawler/'+community+'_comment.py';
	$.get(url, {'board':board,'id':no} , function(html) {
		$.mobile.hidePageLoadingMsg();
		$(target).html(html);
	});
}

function changetheme(){
	setCookie('viewtheme','<?=$viewtheme=='a'?'c':'a'?>',365);
	location.reload(true);
}

function changeviewmode(){
	setCookie('viewmode2','<?=$changemode?>',365);
	location.reload(true);
}

function test(){
	$('#testad').html($('#googlead').html());
}

function searchpage(community,board,keyword){
	keyword = encodeURI(keyword);
	url = './bbs_list.php?comm='+community+'&board='+board+'&type=search&key='+keyword;
	$.mobile.changePage(url);
}

</script>

<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-28206238-1']);
_gaq.push(['_trackPageview']);

(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>
</head>
<body>
	<div id='home' data-role="page">
		<div data-role="header">
			<a href='javascript:changetheme()' data-icon="gear">theme <?=$viewtheme?></a>
			<h1> 게시판 인기글 </h1>
			<!--<a href='bbs_loginform.php' data-icon="gear" class="ui-btn-right" data-rel="dialog" data-transition="slidedown">Login</a>-->
			<a href='javascript:changeviewmode()' data-icon="refresh" class="ui-btn-right"><?=$viewmode2?></a>
		</div>
		<div id='main' data-role="content" data-theme='<?=$viewtheme?>'>

		<ul data-role="listview" id='index' data-theme="<?=$viewtheme?>">
			<li data-role="list-divider"> 게시판 흥한글  </li>
			<li><a href=bbs_list.php?comm=all&board=all&type=pop rel=external> 전체 게시판 인기글 </a></li>
			<li><a href=bbs_naverrank.php> 실시간 인기 검색어 </a></li>
			<li><a href=bbs_list.php?comm=all&board=all&type=youtube rel=external> 전체 게시판 동영상 </a></li>
			<li data-role="list-divider"> 뽐뿌게시판 </li>
			<li><a href=bbs_list.php?comm=ppomppu&board=freeboard&type=pop rel=external> 뽐뿌 자유게시판 인기글 </a></li>
			<li><a href=bbs_list.php?comm=ppomppu&board=humor&type=pop rel=external> 뽐뿌 카툰/유머 인기글 </a></li>
			<li data-role="list-divider"> MLBPARK </li>
			<li><a href=bbs_list.php?comm=mlbpark&board=bullpen&type=pop rel=external> BULLPEN 인기글 </a></li>
			<li data-role="list-divider"> 클리앙 </li>
			<li><a href=bbs_list.php?comm=clien&board=news&type=pop rel=external> 새로운 소식 인기글 </a></li>
			<li><a href=bbs_list.php?comm=clien&board=park&type=pop rel=external> 모두의 공원 인기글 </a></li>
			<!--<li><a href=bbs_list.php?comm=clien&board=park&type=cert rel=external> 모두의 공원 인증글 </a></li>-->
			<li data-role="list-divider"> SLR클럽 </li>
			<li><a href=bbs_list.php?comm=slr&board=free&type=pop rel=external> SLR 자게 인기글 </a></li>
			<li data-role="list-divider"> 검색 </li>
			<li>
			<select id="comm_cel" data-inline="true">
				<option value="all">ALL</option>
				<option value="slr">SLR</option>
				<option value="clien">클리앙</option>
				<option value="ppomppu">뽐뿌</option>
				<option value="mlbpark">MLBPARK</option>
			</select>
			<input type="search" id="search" value="" onchange="searchpage($('#comm_cel').val(),'all',$('#search').val());"></li>
		</ul>
		<br>
		<div data-role="footer" data-id="banner" id='googlead'>
		<script type="text/javascript"    src="http://pagead2.googlesyndication.com/pagead/show_afmc_ads.js"></script>
		</div>
		</div>
	</div>
</body>
</html>
