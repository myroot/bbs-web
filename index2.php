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
	$viewtheme='a';
if( $_GET['changetheme'])
	$viewtheme = $_GET['changetheme'];


$viewmode = $_COOKIE['viewmode'];

if( !$viewmode )
	$viewmode = 'indirect';

if($viewmode == 'direct')
	$changemode = 'indirect';
else
	$changemode = 'direct';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
	<link rel="apple-touch-startup-image" href="logo.png" />
	<link rel="apple-touch-icon" href="shortcut.png"/>
	<link rel="apple-touch-icon-precomposed" href="shortcut.png"/>
	<title>흥한글</title>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0b1/jquery.mobile-1.0b1.min.css" />
	<script src="http://code.jquery.com/jquery-1.6.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0b1/jquery.mobile-1.0b1.min.js"></script>
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
			slotname: '2369443231',
		};
		/* ]]> */
		//--></script>

<script>

var g_lastid = 99999999;
var g_firstid = 999999999;

var g_community = '';
var g_board = '';
var g_listtype = '';

var g_view_community = '';
var g_view_board = '';
var g_view_no = '';

var g_current_view = '';

$.ajax({cache:false});

$(window).scroll(function(){
	if  ($(window).scrollTop() == $(document).height() - $(window).height()){
		//alert('test');
		if( g_current_view == 'list' )
		{
			moreArticle(g_community,g_board,g_listtype,'more','#article_list',g_lastid,'<?=$viewmode?>');
		}
	}
	$('#test').html($(window).scrollTop());
});

$('#home').live('pageshow',function(event, ui){
	g_community = '';
	g_board = '';
	g_listtype = '';
	g_current_view = 'index';
});

$('#list').live('pageshow', function(event,ui){
	g_current_view = 'list';
});

$('#list').live('pagebeforeshow', function(event,ui){
	$('#article_list').listview("refresh");
});

$('#view').live('pageshow',function(event, ui){
	$('img').css({'max-width':'100%'});
	$('#reply').html('<img src="wait18trans.gif">리플 가져오는 중...');
	url = '/service/crawler/'+g_view_community+'_comment.py';
	$.get(url, {'board':g_view_board,'id':g_view_no} , function(html) {
    	$('#reply').html(html);
	});
	g_current_view = 'view';

});
$('#view').live('pagehide' , function(event, ui){
	$('#view_title').html('');
	$('#view_title2').html('');
	$('#view_content').html('');
	$('#view_name').html('');
});

function replybtn(){
	$('#reply').html('<img src="wait18trans.gif">리플 가져오는 중...');
	url = '/service/crawler/'+g_view_community+'_comment.py';
	$.get(url, {'board':g_view_board,'id':g_view_no} , function(html) {
    	$('#reply').html(html);
	});
}


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
	$.get('bbs_ajax2.php', { 'comm': community , 'board' : board, 'mode' : mode,'direct':direct , 'id' : firstid ,'viewmode':viewmode,'rnd' : Math.floor(Math.random() * 99999) } , function(html){
	    $.mobile.hidePageLoadingMsg();
		//alert(html);
	    $(target).prepend(html).listview("refresh");
	});
}

function moreArticle(community,board,mode,direct,target,lastid,viewmode)
{
    $.mobile.showPageLoadingMsg();
    $.get('bbs_ajax2.php', { 'comm': community , 'board' : board, 'mode': mode,'direct':direct ,'id':lastid,'viewmode':viewmode, 'rnd' : Math.floor(Math.random() * 99999) } , function(html){
		$.mobile.hidePageLoadingMsg();
		$(target).append(html).listview("refresh");
	});
}

function moreArticle2()
{
	moreArticle(g_community,g_board,g_listtype,'more','#article_list',g_lastid,'<?=$viewmode?>');
}
function updateArticle2()
{
	updateArticle(g_community, g_board, g_listtype, 'refresh', '#article_list', g_firstid,'<?=$viewmode?>');
}


function changelist(community, board, mode,title)
{
	$('#article_list').html('');
    $.mobile.showPageLoadingMsg();
	$('#list_title').html(title);	
    $.get('bbs_ajax2.php', { 'comm': community , 'board' : board, 'mode': mode, direct:'first' ,'rnd' : Math.floor(Math.random() * 99999) } , function(html){
		$('#article_list').append(html);
		//alert(html);
		$.mobile.changePage('#list');
		$.mobile.hidePageLoadingMsg();
	});
	
	g_community = community;
	g_board = board;
	g_listtype = mode;
}

function loadContentView(id,community)
{
	$.mobile.showPageLoadingMsg();
	$.get('bbs_view_ajax.php', {'id':id, 'viewmode':'<?=$viewmode?>'} , function(data){
		comm_icon = "<img src='"+community+".gif' height=16 class='ui-icon'>";
		$('#view_title').html(comm_icon+data.title);
		$('#view_title2').html(data.title);
		$('#view_content').html(data.content);
		$('#view_name').html(data.name);
		g_view_community = data.community;
		g_view_board = data.board;
		g_view_no = data.no;
		$.mobile.changePage('#view');
		$.mobile.hidePageLoadingMsg();
	},'json');
}

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
	setCookie('viewmode','<?=$changemode?>',365);
	location.reload(true);
}

function test(){
	$('#testad').html($('#googlead').html());
}

</script>

<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-24524340-1']);
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
			<a href='javascript:changeviewmode()' data-icon="refresh" class="ui-btn-right"><?=$viewmode?></a>
		</div>
		<div id='main' data-role="content" data-theme='<?=$viewtheme?>'>

		<ul data-role="listview" id='index' data-theme="<?=$viewtheme?>">
			<li data-role="list-divider"> 게시판 흥한글  </li>
			<li><a href="javascript:changelist('all', 'all','pop','전체 게시판 인기글')"> 전체 게시판 인기글 </a></li>
			<li><a href=bbs_naverrank.php?viewmode=<?=$viewmode?>> 실시간 인기 검색어 </a></li>
			<li><a href="javascript:changelist('all','all','magnet','전체 게시판 자석글')"> 전체 게시판 자석글 </a></li>
			<li data-role="list-divider"> 뽐뿌게시판 </li>
			<li><a href="javascript:changelist('ppomppu','freeboard','pop','뽐뿌 자게 인기글')"> 뽐뿌 자유게시판 인기글 </a></li>
			<li><a href="javascript:changelist('ppomppu','humor','pop','뽐뿌 카툰유버 인기글')"> 뽐뿌 카툰/유머 인기글 </a></li>
			<li data-role="list-divider"> MLBPARK </li>
			<li><a href="javascript:changelist('mlbpark','bullpen','pop','불팬 인기글')"> BULLPEN 인기글 </a></li>
			<li data-role="list-divider"> 클리앙 </li>
			<li><a href="javascript:changelist('clien','news','pop','클리앙 세소식 인기글')"> 새로운 소식 인기글 </a></li>
			<li><a href="javascript:changelist('clien','park','pop','클리앙 모공 인기글')"> 모두의 공원 인기글 </a></li>
			<li><a href="javascript:changelist('clien','park','cert','클리앙 모공 인증글')"> 모두의 공원 인증글 </a></li>
			<li data-role="list-divider"> SLR클럽 </li>
			<li><a href="javascript:changelist('slr','free','pop','SLR 자게 인기글')"> SLR 자게 인기글 </a></li>
			<li><a href="javascript:changelist('slr','free','jogong','SLR 자게 조공')"> SLR 자게 조공 </a></li>
			
		</ul>
		<br>
		<div data-role="footer" data-id="banner" id='googlead'>
		<script type="text/javascript"    src="http://pagead2.googlesyndication.com/pagead/show_afmc_ads.js"></script>
		</div>
		</div>
	</div>
	<div id='list' data-role='page'>
		<div data-role="header">
			<a href="#home" data-icon="home" data-direction="reverse">Home</a>
			<h1 id='list_title'></h1>
			<a href="javascript:updateArticle2()" data-icon='refresh'>Refresh</a>
		</div>
		<div id='listmain' data-role='content' data-theme='<?=$viewtheme?>'>
			<ul id='article_list' data-role="listview" data-theme='<?=$viewtheme?>'>

			</ul>
			<br>
	        <input type='button' onclick=moreArticle2() data-role="button" value="더 읽어 오기" data-theme='<?=$viewtheme?>'>
		</div>
	</div>
	<div id='view' data-role='page'>
		<div data-role="header">
			<a href='#' data-icon="back" data-rel="back">back</a>
			<h1 id='view_title'></h1>
			<a href='#' data-icon="grid" class="ui-btn-right" target=_blank>원본</a>
		</div>
		<div data-role="content" data-theme='<?=$viewtheme?>'>
			<div id=view_name></div>
			<h2><div id=view_title2></div></h2>
			<div id=view_content></div>
			<a href='javascript:replybtn()' data-role=button data-inline="true"> 리플 새로고침 </a>
			<hr>
			<div id=reply></div>
		</div>
        <div data-role="footer" data-position="">
	        <a href='javascript:history.back(1)' data-icon="back" style='font-size:13pt'>back</a>
	    </div>
	</div>
</body>
</html>
