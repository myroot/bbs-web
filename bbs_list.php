<?
session_start();
Header("Pragma: no-cache");
require_once("../dbconn.php");
mysql_query("set character_set_connection=utf8;");
mysql_query("set character_set_server=utf8;");
mysql_query("set character_set_client=utf8;");
mysql_query("set character_set_results=utf8;");
mysql_query("set character_set_database=utf8;");


$viewtheme = $_COOKIE['viewtheme'];
if( !$viewtheme )
    $viewtheme='c';


$board = $_GET['board'];
$comm = $_GET['comm'];
$type = $_GET['type'];
$page = $_GET['page'];
$viewmode = $_GET['viewmode'];
$key = $_GET['key'];
if(!$page)
	$page=1;

$title = "";
$bbs = $comm.'/'.$board;
$pageid = $comm.$board.$type.$page;
$listid = $pageid."list";

$perpage = 15;

$starter = ($page-1)*$perpage;

if( $comm == 'clien' )
	$title ="클리앙 ";
else if( $comm == 'slr' )
	$title ="SLR ";
else if( $comm == 'ppomppu' )
	$title = "뽐뿌 ";
else if ($comm == 'mlbpark')
	$title = "MLBPARK ";

if( $board == "park")
	$title .="모공 ";
else if ( $board == "new")
	$title .="새소식 ";
else if ( $board == 'free'){
	$title .="자게  ";
}else if ( $board == "humor"){
	$title .= "카툰/유머 ";
}else if ( $board == "freeboard"){
	$title .= "자유게시판 ";
}else if ( $board == 'bullpen' )
	$title .= 'Bullpen ';

if ( $comm == 'all' && $board =='all'){
	$title = '전체 게시판 ';
	$bbs = '%%';
}else if ($board=='all'){
	$bbs = $comm.'/%';
}

$where = "";
$table = 'crdata_article';
if( $type == "pop" ){
	$where = "1";
	$title .= "인기글";
	$table = 'crdata_popular';
}
else if ( $type == "19" ){ 
	$where = "title regexp '[0-9]금'";
	$title .="19금글";
}else if ( $type == "cert" ){
	$where = "title like '%인증%'";
	$title .= "인증글";
}else if ( $type == "data" ){
	$where = "has_image > 0 or has_youtube > 0 or has_flash > 0";
	$title .= "짤방글";
}else if ( $type == "jogong" ){
	$where = "has_image > 0 and title like '%조공%'";
	$title .= "조공";
}else if ( $type == 'magnet'){
	$where = "content like '%agnet:?xt=urn:btih%'";
	$title .="자석";
}else if( $type == "search" ){
	$where = "title like '%".$key."%'";
	$title .= $key;
}else if( $type == 'youtube' ){
	$where = "has_youtube > 0";
	$title .= "동영상";
}else 
	$where = "1";

$order = 'order by date desc';
if( $type == 'pop' )
	$order = 'order by id desc';

$query = "select * from ".$table." where bbs like '".$bbs."' and (".$where.") ".$order." limit $starter,$perpage";

		$cnt_query = "select count(*) as cnt from ".$table." where bbs like '".$bbs."' and (".$where.") ".$order;
		$total_article_num = mysql_result(mysql_query($cnt_query),0,'cnt');

function paging($total_article, $now_page)
{
	global $comm,$board,$type,$viewmode,$key,$perpage;
	$totalpage=ceil($total_article/$perpage);
	$first_page = $now_page - 2;
	$last_page = $now_page + 2;
	if( $first_page < 1 ){
		$last_page += (-$first_page+1);
		$first_page = 1;
	}
	if( $last_page > $totalpage ){
		$first_page -= ($last_page - $totalpage);
		$last_page = $totalpage;
		if($first_page < 1) 
			$first_page =1;
	}

	for($i = $first_page; $i<=$last_page; $i++)
	{
		if( $i < $now_page ){
			?>  <a href='bbs_list.php?comm=<?=$comm?>&board=<?=$board?>&type=<?=$type?>&viewmode=<?=$viewmode?>&page=<?=$i?>&key=<?=$key?>' data-role=button data-direction="reverse" style="font-size:1em" rel=external><?
		}else if( $i > $now_page ){
			?>  <a href='bbs_list.php?comm=<?=$comm?>&board=<?=$board?>&type=<?=$type?>&viewmode=<?=$viewmode?>&page=<?=$i?>&key=<?=$key?>' data-role=button style="font-size:1em" rel=external><?
		}
		echo $i;
		if( $i != $now_page )echo "</a>  ";
	}
}

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

$.event.special.swipe.horizontalDistanceThreshold = 600;
$.event.special.swipe.durationThreshold = 1000;
$.event.special.swipe.verticalDistanceThreshold = 1;

function goListPage(){
	$.mobile.changePage('#<?=$pageid?>');
	//location.href = 'bbs_list.php?comm=<?=$comm?>&board=<?=$board?>&type=<?=$type?>&viewmode=<?=$viewmode?>&page=<?=$page?>&key=<?=$key?>';
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
	$.get('bbs_ajax.php', { 'comm': community , 'board' : board, 'mode' : mode,'direct':direct , 'id' : firstid ,'viewmode':viewmode,'rnd' : Math.floor(Math.random() * 99999) } , function(html){
	    $.mobile.hidePageLoadingMsg();
		//alert(html);
	    $(target).prepend(html).listview("refresh");
	});
}

function moreArticle(community,board,mode,direct,target,lastid,viewmode)
{
    $.mobile.showPageLoadingMsg();
    $.get('bbs_ajax.php', { 'comm': community , 'board' : board, 'mode': mode,'direct':direct ,'id':lastid,'viewmode':viewmode, 'rnd' : Math.floor(Math.random() * 99999) } , function(html){
		$.mobile.hidePageLoadingMsg();
		$(target).append(html).listview("refresh");
	});
}

function imgResize() {
    $('img').css({'width':'100%', 'height':'auto'});
}

//$(document).ready(imgResize);

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

function searchpage(community,board,keyword){
	keyword = encodeURI(keyword);
	url = './bbs_list.php?comm='+community+'&board='+board+'&type=search&viewmode=<?=$viewmode?>&key='+keyword;
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
	<div id="<?=$pageid?>" data-role="page">
		<script>
			//$('#<?=$listid?>').live('swipeleft' , function() { $.mobile.changePage( "bbs_list.php?comm=<?=$comm?>&board=<?=$board?>&type=<?=$type?>&viewmode=<?=$viewmode?>&page="+(nowpage+1) ); });
			//$('#<?=$listid?>').live('swiperight' , function() {
			//	if(nowpage == 1){
			//		$.mobile.changePage('#home',{ reverse: true});
			//	}else{
			//		$.mobile.changePage( "bbs_list.php?comm=<?=$comm?>&board=<?=$board?>&type=<?=$type?>&viewmode=<?=$viewmode?>&page="+(nowpage-1),{reverse: true});
			//	}
			//});
		</script>
		<div id=header data-role="header">
			<!--<a href='javascript:history.back(1)' data-icon="back">back</a>-->
			<a href='./' data-icon="home" data-direction="reverse" rel=external>home</a>
			<h1> <?=$title?> </h1>
			<a href='javascript:refreshbtn()' data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div>
		<div data-role="content" data-theme='<?=$viewtheme?>'>
		<ul data-role="listview" id='<?=$listid?>' data-theme='<?=$viewtheme?>'>
		<?
		//echo $query;
		$result = mysql_query($query);
		$lastid = 0;
		$count = 0;
		while($data = mysql_fetch_array($result)){
			$tmp = explode('/', $data['bbs']);
			$community = $tmp[0];

			$dataid = $data['id'];
			if($type == 'pop')
				$dataid = $data['origin_id'];
			if($count == 0)
			{
				?>
				<script><?=$pageid?>_firstid = <?=$data['id']?>;
				function refreshbtn(){
					location.reload(true);
				}
				</script>
				<?	
			}
			$count++;
		?>
			<li><a href="bbs_view.php?id=<?=$dataid?>&comm=<?=$comm?>&board=<?=$board?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>" rel=external><?if($comm=='all')echo "<img src='$community.gif' height=16 class='ui-li-icon'>";?><?=$data['title']?></a><span class="ui-li-count"><?=$data['reply_count']?></span></li>
		<?
			$lastid = $data['id'];
		}
		?>
		</ul>
		</div>	
		<div data-role="footer" align=center>
			 <? if($page != 1) { ?>
			 <div style="float:left"><a href='bbs_list.php?comm=<?=$comm?>&board=<?=$board?>&type=<?=$type?>&viewmode=<?=$viewmode?>&page=<?=$page-1?>&key=<?=$key?>' data-role=button data-icon="arrow-l" data-direction="reverse" style="font-size:1em" rel=external>Prev</a></div>
			 <?}?>
			 <div style="float:right"><a href='bbs_list.php?comm=<?=$comm?>&board=<?=$board?>&type=<?=$type?>&viewmode=<?=$viewmode?>&page=<?=$page+1?>&key=<?=$key?>' data-role=button data-icon="arrow-r" style="font-size:1em" rel=external>Next</a></div>
			 
			 <div><?paging($total_article_num, $page)?></div>
		</div>
	</div>
</body>
