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

$id = $_GET['id'];
$viewmode = $_GET['viewmode'];
$result = mysql_query("select * from crdata_article where id=".$id);
$data = mysql_fetch_array($result);
$tmp = explode('/', $data['bbs']);
$community = $tmp[0];
$board = $tmp[1];

$mobileurl = '';
if( $community == 'ppomppu' )
	$mobileurl = 'http://m.ppomppu.co.kr/new/bbs_view.php?id='.$board.'&no='.$data['no'];
else if( $community =='slr')
	$mobileurl = 'http://m.slrclub.com/v/'.$board.'/'.$data['no'];
else if( $community == 'clien' )
	$mobileurl = 'http://m.boxweb.net/c/clien/list.php?bo_table='.$board.'&wr_id='.$data['no'];

$result = array();
$result['name'] = $data['name'];
$result['title'] = $data['title'];
$result['community'] = $community;
$result['board'] = $board;
$result['no'] = $data['no'];
if( $viewmode == 'indirect')
	$result['content'] = $data['content'];
else
	$result['content'] = "<iframe src=".($mobileurl ? $mobileurl : $data['link']).' width=100% height=100% scrolling=yes marginwidth="0" marginheight="0" style="background:white"></iframe>';

echo json_encode($result);

