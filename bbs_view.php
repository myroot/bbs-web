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


$gboard = $_GET['board'];
$comm = $_GET['comm'];
$page = $_GET['page'];
$type = $_GET['type'];
$key = $_GET['key'];

$bbs = $_GET['comm']."/".$_GET['board'];
$id = $_GET['id'];

if ( $comm == 'all' && $gboard =='all'){
        $bbs = '%%';
}else if ($gboard=='all'){
        $bbs = $comm.'/%';
}


//$viewmode = $_GET['viewmode'];
$viewmode2 = $_COOKIE['viewmode2'];
$result = mysql_query("select * from crdata_article where id=".$id);
$data = mysql_fetch_array($result);
$tmp = explode('/', $data['bbs']);
$community = $tmp[0];
$board = $tmp[1];
$current_data = mysql_fetch_array(mysql_query("select * from crdata_popular where origin_id = ".$id." order by id asc limit 1"));
$current_id = $current_data['id'];

$predata = mysql_fetch_array(mysql_query("select * from crdata_popular where id > ".$current_id." and bbs like '".$bbs."' order by id asc limit 1"));
$nextdata = mysql_fetch_array(mysql_query("select * from crdata_popular where id < ".$current_id." and bbs like '".$bbs."' order by id desc limit 1"));
$preno = 0;
$nextno = 0;
if( $predata )
	$preno = $predata['origin_id'];
if( $nextdata )
	$nextno = $nextdata['origin_id'];

$mobileurl = '';
if( $community == 'ppomppu' )
$mobileurl = 'http://m.ppomppu.co.kr/new/bbs_view.php?id='.$board.'&no='.$data['no'];
else if( $community =='slr')
$mobileurl = 'http://m.slrclub.com/v/'.$board.'/'.$data['no'];
else if( $community == 'clien' )
$mobileurl = 'http://m.clien.career.co.kr/cs3/board?bo_table='.$board.'&bo_style=view&wr_id='.$data['no'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title><?=$data['title']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<!--<meta name="apple-mobile-web-app-capable" content="yes" />-->
<meta name="viewport" content="width=device-width, initial-scale=1">
        <?//<link rel="stylesheet" href="js/jquery.mobile-1.0b1.min.css" />?>
        <link rel="stylesheet" href="js/jquery.mobile-1.1.1.min.css" />
        <?//<script src="js/jquery-1.6.1.min.js"></script>?>
        <script src="js/jquery-1.8.0.min.js"></script>
        <?//<script src="js/jquery.mobile-1.0b1.min.js"></script>?>
        <script src="js/jquery.mobile-1.1.1.min.js"></script>
<script>

function load()
{
$('#reply<?=$data['id']?>').html('<img src="wait18trans.gif">리플 가져오는 중..');
$.get('/service/crawler/<?=$community?>_comment.py' , {board:'<?=$board?>' , id: <?=$data['no']?>} , function(html){
		$('#reply<?=$data['id']?>').html( html );
});

}


function imgResize() {
//    $('img').css({'max-width':'100%'});
//	load();
}
</script>

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
<script>
//function imgResize() {
//    $('img').css({'max-width':'100%'});
//	load();
//}
function getReply2(community,board,no,target){
$.mobile.showPageLoadingMsg();
url = '/service/crawler/'+community+'_comment.py';
$.get(url, {'board':board,'id':no} , function(html) {
	$.mobile.hidePageLoadingMsg();
	$(target).html(html);
});
}

function getReply(gid){
$.mobile.showPageLoadingMsg();
//$('#replybtn').hide();
$.get('/service/crawler/<?=$community?>_comment.py' , {board:'<?=$board?>' , id: gid} , appendReply);
}

function appendReply(data)
{
//alert(data);
$.mobile.hidePageLoadingMsg();
$('#reply<?=$data['id']?>').html( data );
}
</script>

<div id="home<?=$data['id']?>" data-role="page" data-add-back-btn="true">
	<script>
$.event.special.swipe.horizontalDistanceThreshold = 100;
$.event.special.swipe.verticalDistanceThreshold = 20;
//$.event.special.swipe.durationThreshold = 10;

$('#home<?=$data['id']?>').live('pageshow', function(event,ui){
$('img').css({'max-width':'100%'});
$('embed').css({'max-width':'100%'});
$('iframe').css({'max-width':'100%'});
//$('embed').attr('width', '100%');

if( $('#reply<?=$data['id']?>').html() == "" ){
	$('#reply<?=$data['id']?>').html('<img src="wait18trans.gif">리플 가져오는 중...');
	url = '/service/crawler/<?=$community?>_comment.py';
	$.get(url, {'board':'<?=$board?>','id':<?=$data['no']?>} , function(html) {
		$('#reply<?=$data['id']?>').html(html);
	});
}
});

//$('#home<?=$data['id']?>').live('swiperight', function(event){ 
<? if( $preno != 0 ){?>
 //$.mobile.changePage('bbs_view.php?id=<?=$preno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>', { transition : "slide" , reverse: true } );
// location.href = 'bbs_view.php?id=<?=$preno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>';
<?}?>
// } );

//$('#home<?=$data['id']?>').live('swipeleft', function(event){ 
<? if( $nextno != 0 ){?>
 //$.mobile.changePage('bbs_view.php?id=<?=$nextno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>', { transition : "slide", reverse: false } );
// location.href = 'bbs_view.php?id=<?=$nextno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>';
<?}?>

// } );
	function replybtn(){
		getReply2('<?=$community?>','<?=$board?>', <?=$data['no']?> , '#reply<?=$data['id']?>');
	}
	</script>

	<div data-role="header">
		<a href='bbs_list.php?comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>' rel=external data-icon="back">List</a>
		<h1><img src='<?=$community?>.gif' height=16 class='ui-icon'> <?=$data['title']?> </h1>
			<a href='<?=$data['link']?>' data-icon="grid" class="ui-btn-right" target=_blank>원본</a>
		</div>
                <div data-role="footer">
                <? if( $preno != 0 ){?>
                <a href='bbs_view.php?id=<?=$preno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>' data-icon="arrow-l" style='font-size:13pt' rel=external>Prev</a>
                <?}?>
                <? if( $nextno != 0 ){?>
                <a href='bbs_view.php?id=<?=$nextno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>' data-icon="arrow-r" style='font-size:13pt' rel=external>Next</a>
                <?}?>
                </div>

		<div data-role="content" data-theme='<?=$viewtheme?>'>
		<?if($viewmode2 == 'indirect') {?>

		<?=$data['name']?>
		<h2><?=$data['title']?> </h2>
		<?=$data['content']?>
        <br>
<?

if( $community == 'slr' ){
    $series_query = "select * from crdata_popular where name like '".$data['name']."' and id >= ".$current_id."-200 and id <= ".$current_id."+200";
    $series_result = mysql_query($series_query);
    while( $series_data = mysql_fetch_array($series_result)) {
	if( $data['id'] == $series_data['origin_id'] )
		continue;
	$len = strlen($data['title']);
	$len = $len/3;
	if( strncmp($data['title'], $series_data['title'], $len) == 0 )
		echo "<a href=bbs_view.php?id=".$series_data['origin_id']."&comm=".$comm."&board=".$gboard."&page=".$page."&type=".$type."&key=".$key.">".$series_data['title']."</a><br>";
    }
}
?>
		<hr width=100%>
		<div data-role="footer">
		<?//<a href='javascript:goListPage()' data-icon="back" style='font-size:13pt'>List</a>?>
		<a href='bbs_list.php?comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>' data-icon="back" style='font-size:13pt' rel=external>List</a>
		<? if( $preno != 0 ){?>
		<a href='bbs_view.php?id=<?=$preno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>' data-icon="arrow-l" style='font-size:13pt' rel=external>Prev</a>
		<?}?>
		<? if( $nextno != 0 ){?>
		<a href='bbs_view.php?id=<?=$nextno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>' data-icon="arrow-r" style='font-size:13pt' rel=external>Next</a>
		<?}?>
		</div>

		<a href='javascript:replybtn()' data-role=button data-inline="true"> 리플 새로고침 </a>
		<div id='reply<?=$data['id']?>'></div>
		<?}else {?>
		<iframe src=<?=$mobileurl ? $mobileurl : $data['link']?> width=100% height=800 scrolling=yes marginwidth="0" marginheight="0" style="background:white"></iframe>
		<?}?>
		</div>
		<div data-role="footer">
		<a href='bbs_list.php?comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>' rel=external data-icon="back" data-icon="back" style='font-size:13pt'>List</a>
		<? if( $preno != 0 ){?>
		<a href='bbs_view.php?id=<?=$preno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>' data-icon="arrow-l" style='font-size:13pt' rel=external>Prev</a>
		<?}?>
		<? if( $nextno != 0 ){?>
		<a href='bbs_view.php?id=<?=$nextno?>&comm=<?=$comm?>&board=<?=$gboard?>&page=<?=$page?>&type=<?=$type?>&key=<?=$key?>' data-icon="arrow-r" style='font-size:13pt' rel=external>Next</a>
		<?}?>
		</div>
	</div>
															  
</body>
</html>
