<?
Header("Pragma: no-cache");
require_once("../dbconn.php");

mysql_query("set character_set_connection=utf8;");
mysql_query("set character_set_server=utf8;");
mysql_query("set character_set_client=utf8;");
mysql_query("set character_set_results=utf8;");
mysql_query("set character_set_database=utf8;");

$rnd = $_GET['rnd'];
$mode = $_GET['mode'];
$comm = $_GET['comm'];
$board = $_GET['board'];
$id = $_GET['id'];
$direct = $_GET['direct'];
$viewmode = $_GET['viewmode'];

$bbs = $comm.'/'.$board;

if( $comm == 'all' && $board == 'all')
	$bbs = '%%';

$where = "";


$table = 'crdata_article';

if( strstr($mode , "pop") != FALSE ){
	$where = "1";
	$table = 'crdata_popular';
}else if ( strstr($mode , "19") != FALSE )
	$where = "title regexp '[0-9]금'";
else if ( strstr($mode , "cert") != FALSE )
	$where = "title like '%인증%'";
else if ( strstr($mode , "data") != FALSE )
	$where = "has_image > 0 or has_youtube > 0 or has_flash > 0";
else if ( $mode == 'jogong')
	$where = "has_image > 0 and title like '%조공%'";
else if ( $mode == 'magnet')
    $where = "content like '%agnet:?xt=urn:btih%'";
else
	$where = "1";

$order = 'order by date desc';
if( strstr($mode , "pop") != FALSE )
	$order = 'order by id desc';

$query = "";

if( $direct == "more" )
	$query = "select * from ".$table." where bbs like '".$bbs."' and (".$where.") and id < '".$id."' ".$order." limit 15";
else if(  $direct == "refresh"  )
	$query = "select * from ".$table." where bbs like '".$bbs."' and (".$where.") and id > '".$id."' ".$order;

$pageid = $comm.$board.$mode;


$result = mysql_query($query);
$lastid = 0;
$count = 0;

while($data = mysql_fetch_array($result)){
    $tmp = explode('/', $data['bbs']);
    $community = $tmp[0];

	$lastid = $data['id'];
	$dataid = $data['id'];
	if($mode == 'pop')
		$dataid = $data['origin_id'];
	if($count++==0 && ($direct == 'refresh')){
		?>
		<script> <?=$pageid?>_firstid = <?=$data['id']?>;</script>
		<?
	}

?>
	<li><a href="bbs_view.php?id=<?=$dataid?>&viewmode=<?=$viewmode?>"><?if($comm=='all')echo "<img src='$community.gif' height=16 class='ui-li-icon'>";?><?=$data['title']?></a><span class="ui-li-count"><?=$data['reply_count']?></span></li>
<?
}
if( $direct == 'more'){
?>
<script> <?=$pageid?>_lastid = <?=$lastid?>;</script>
<?
}
if( $count == 0 )
	echo "<li id=nomore".$rnd."> 더이상 글이 없습니다.</li>";
	echo "<script>$('#nomore".$rnd."').slideUp(2500);</script>";
?>

