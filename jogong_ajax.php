<?
Header("Pragma: no-cache");
require_once("../dbconn.php");

mysql_query("set character_set_connection=utf8;");
mysql_query("set character_set_server=utf8;");
mysql_query("set character_set_client=utf8;");
mysql_query("set character_set_results=utf8;");
mysql_query("set character_set_database=utf8;");

$last = $_GET['last'];

$query = "select * from crdata_article where bbs like 'slr/free' and has_image > 0 and title like '%조공%' and id < ".$last." order by id desc limit 15";

$result = mysql_query($query);
$lastid = 99999999;
while($data = mysql_fetch_array($result)){
	$sub_query = "select * from crdata_imgs where parent like 'slr/free/".$data['no']."'";
	$img_result = mysql_query($sub_query);
	while($imgs = mysql_fetch_array($img_result)){
		echo "<img src=/service/crawler/".$imgs['path']." style='max-width:100%'><br>";
	}
	$lastid = $data['id'];
}
?>
	<script>
		last = <?=$lastid?>;
	</script>