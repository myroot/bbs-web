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



$url = 'http://openapi.naver.com/search?key=e2acbd41371499eb65167075303fbf7e&target=rank&query=nexearch';
$handle = fopen($url, "rb");
$data = stream_get_contents($handle);
Header("Pragma: no-cache");
$parser = xml_parser_create() or die ("XML 파서를 생성하지 못했습니다.");
xml_set_element_handler($parser, "StartTag", "EndTag");
xml_set_character_data_handler($parser, "Data");
function StartTag($parser, $name, $attr)
{
	global $depth, $current_rank, $current_field, $result;
	if( $depth==2)
	{
		$result[$name] = array();
		$current_rank = $name;
	}else if( $depth == 3)
	{
		$result[$current_rank][$name] = '';
		$current_field = $name;
		
	}
	$depth++;
}

function EndTag($parser, $name)
{
	global $depth;
	$depth--;
}

function Data($parser, $data)
{
	global $current_rank , $current_field, $result; 
    $result[$current_rank][$current_field] = $data;
}

$depth = 0;
$result=array();
$current_rank = '';
$current_field = '';
xml_parse($parser, $data, true);

$viewmode = $_GET['viewmode'];
?>
<body>
	<div id="<?=$pageid?>" data-role="page" data-add-back-btn="true">
	<div data-role="header">
		<a href='#home' data-icon="home">home</a>
		<!--<a href='javascript:history.back(1)' data-icon="back">back</a>-->
		<h1> 실시간 인기 검색어 </h1>
	</div>
	<div data-role="content">
		<ul data-role="listview" id='naverrank' data-theme='<?=$viewtheme?>'>
<?
$count = 0;
foreach($result as $item)
{
	$count++;
?>
		<li data-role="list-divider"><?=$count?>. <?=$item['K']?> </li>
<?
		$query = "select * from crdata_article where (title like '%".$item['K']."%') order by date desc limit 5";
		$qresult = mysql_query($query);
		$data_num = mysql_num_rows($qresult);
		while($data = mysql_fetch_array($qresult)){
			$tmp = explode('/', $data['bbs']);
			$community = $tmp[0];
?>
		<li><a href="bbs_view.php?id=<?=$data['id']?>&viewmode=<?=$viewmode?>"><img src='<?=$community?>.gif' height=16 class='ui-li-icon'><?=$data['title']?></a><span class="ui-li-count"><?=$data['reply_count']?></span></li>
<?
		}
		if( $data_num == 0 )
			echo "<li> 관련 글이 없습니다. </li>";
}
?>
		</ul>
	</div>

	</div>
</body>
