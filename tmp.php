<?
require_once("../dbconn.php");
mysql_query("set character_set_connection=utf8;");
mysql_query("set character_set_server=utf8;");
mysql_query("set character_set_client=utf8;");
mysql_query("set character_set_results=utf8;");
mysql_query("set character_set_database=utf8;");

$sql = 'SELECT count(*) as cnt,path , md5,parent FROM `crdata_imgs` group by md5 having cnt > 1 ORDER BY `cnt`  DESC';

$result = mysql_query($sql);

while($data = mysql_fetch_array($result)){
	echo $data['cnt']."<br>";
	echo "<img src=/service/crawler/".$data['path']."><br>";
}
?>
