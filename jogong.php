<?
session_start();
require_once("../dbconn.php");

mysql_query("set character_set_connection=utf8;");
mysql_query("set character_set_server=utf8;");
mysql_query("set character_set_client=utf8;");
mysql_query("set character_set_results=utf8;");
mysql_query("set character_set_database=utf8;");
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
	<link rel="stylesheet" href="js/jquery.mobile-1.0b1.min.css" />
	<script src="js/jquery-1.6.1.min.js"></script>
	<script src="js/jquery.mobile-1.0b1.min.js"></script>
	<style>
	body { font-size:0.8em; }
	</style>
</head>
<script>
var last = 99999999;

function read_more()
{
    $.mobile.showPageLoadingMsg();
    $.get('jogong_ajax.php', { 'last': last ,'rnd' : Math.floor(Math.random() * 99999) } , function(html){
		$.mobile.hidePageLoadingMsg();
		$('#main').append(html);
			});	
}

</script>
<body>
	<div id='home' data-role="page">
		<div data-role="header">
			<h1>  </h1>
		</div>
		<div id='main' data-role="content">
<?
$query = "select * from crdata_article where bbs like 'slr/free' and has_image > 0 and title like '%조공%' order by id desc limit 15";

$result = mysql_query($query);
$lastid = 0;
while($data = mysql_fetch_array($result)){
	$sub_query = "select * from crdata_imgs where parent like 'slr/free/".$data['no']."'";
	$img_result = mysql_query($sub_query);
	while($imgs = mysql_fetch_array($img_result)){
		echo "<img src=/service/crawler/".$imgs['path']." style='max-width:100%'><br>\n";
	}
	$lastid = $data['id'];
}
?>

	<script>
		last = <?=$lastid?>;
	</script>
		</div>
		<div>
		<input type='button' onclick=read_more() data-role="button" value="더 읽어 오기">
		</div>
	</div>
</body>
</html>
