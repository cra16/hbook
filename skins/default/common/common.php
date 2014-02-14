<?php
ini_set("session.cache_expire", 180);
ini_set("session.gc_maxlifetime", 10800);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 100);
session_set_cookie_params(0, "/");
ini_set("session.cookie_domain", "");
session_save_path("../data/session");

@session_start();


//DBconnect
	function sql_con($sv, $id, $pw, $dbm = 'my'){
		if($dbm == 'ms'){
			return @mssql_connect($sv, $id, $pw);
		}elseif($dbm =='my'){
			return @mysql_connect($sv, $id, $pw);
		}elseif($dbm =='ora'){
			return @OCIlogon($id, $pw, $sv);
		}
	}

// DB 선택
	function sql_select_db($db, $connect, $dbm = 'my')
	{
		if (strtolower($g1['charset']) == 'utf-8') @mysql_query(" set names utf8 ");
		else if (strtolower($g1['charset']) == 'euc-kr') @mysql_query(" set names euckr ");

		if($dbm == 'ms'){
			return @mssql_select_db($db, $connect);
		}elseif($dbm =='my'){
			return @mysql_select_db($db, $connect);
		}elseif($dbm =='ora'){
			return 1;
		}
		
	}


// query 와 error 를 한꺼번에 처리
	function sql_query($sql, $error=TRUE, $dbm='my')
	{
		if($dbm == 'ms'){
			$result = @mssql_query($sql);// or die("<p>$sql<p>" . msql_error() . "<p>error file : $_SERVER[PHP_SELF]");
		}elseif($dbm =='my'){
			$result = @mysql_query($sql);// or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
		}elseif($dbm =='ora'){
			//oci_parse($c, $sql);
		}
		return $result;
	}


// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
	function sql_fetch($sql, $error=TRUE, $dbm='my')
	{
		$result = sql_query($sql, $error, $dbm);
		//$row = @sql_fetch_array($result) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
		$row = sql_fetch_array($result, $dbm);
		return $row;
	}


// 결과값에서 한행 연관배열(이름으로)로 얻는다.
	function sql_fetch_array($result, $dbm = 'my')
	{
		if($dbm == 'ms'){
			$row = @mssql_fetch_assoc($result);
		}elseif($dbm =='my'){
			$row = @mysql_fetch_assoc($result);
		}elseif($dbm =='ora'){
			//oci_parse($c, $sql);
		}
		
		return $row;
	}

// rows획득
	function sql_num_rows($sql, $error=TRUE, $dbm='my')
	{
		if($dbm == 'ms'){
			$result = @mssql_query($sql);// or die("<p>$sql<p>" . msql_error() . "<p>error file : $_SERVER[PHP_SELF]");
			$crows = @mssql_num_rows($result);
		}elseif($dbm =='my'){
			$result = @mysql_query($sql);// or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
			$crows = @mysql_num_rows($result);
		}elseif($dbm =='ora'){
			$crows = @oci_num_rows($result);
			//oci_parse($c, $sql);
		}
		return $crows;
	}
// 현재페이지, 총페이지수, 한페이지에 보여줄 행, URL
// 총페이지 수 / 현재페이지 위치 / 한줄에 보여줄 행 갯수 / URL
function get_paging($tot_page_cnt, $now_page, $how_many, $go_url, $add="")
{
	$str = "";
	if ($now_page > 1) {
		$str .= "<a href='".$go_url."1{$add}'>처음</a>";
	}

	$start_page = ((int)(($now_page-1)/$tot_page_cnt)*$tot_page_cnt)+1;
	$end_page = $start_page + $tot_page_cnt - 1;

	if($end_page >= $how_many){
		$end_page = $how_many;
	}

	if ($how_many > 1) {
		for ($k=$start_page; $k<=$end_page; $k++) {
			if ($now_page != $k){
				$str .= " &nbsp;<a href='$go_url$k{$add}'><span>$k</span></a>";
			}else{
				$str .= " &nbsp;<b>$k</b> ";
			}
		}
	}

	if ($how_many > $end_page){
		$str .= " &nbsp;<a href='".$go_url.($end_page+1)."{$add}'>다음</a>";
	}

	if ($now_page < $how_many) {
		$str .= " &nbsp;<a href='$go_url$how_many{$add}'>맨끝</a>";
	}
	
	return $str;
}

// 변수 또는 배열의 이름과 값을 얻어냄. print_r() 함수의 변형
function print_r2($var)
{
    ob_start();
    print_r($var);
    $str = ob_get_contents();
    ob_end_clean();
    $str = preg_replace("/ /", "&nbsp;", $str);
    echo nl2br("<span style='font-family:Tahoma, 굴림; font-size:9pt;'>$str</span>");
}


	function utf8_cut_str($str, $len, $rep="..."){
	 $sub_str = iconv_substr($str, 0, $len, "utf-8");
	 if( strlen($sub_str) >= strlen($str) ){ 
	  $rep = "";
	 }

	 return $sub_str . $rep;

	}

	function euc_cut_str($str, $len, $rep="..."){
	 $sub_str = iconv_substr($str, 0, $len, "euc-kr");
	 if( strlen($sub_str) >= strlen($str) ){ 
	  $rep = "";
	 }

	 return $sub_str . $rep;

	}



function lastrow($tablename, $rows=10, $str_len=40, $vskin='basic', $options="")
{
	global $vskin_path;

	$list = array();

    $sql = " select * from $tablename order by IDX desc limit 0, $rows ";

    $result = sql_query($sql);
    for ($i=0; $row = sql_fetch_array($result); $i++) {
//			alert($sql);
        $list[$i] = $row;
	}

	$fpath = $vskin_path."/".$vskin."_lastrow.skin.php";
    ob_start();
	include $fpath;
    $content = ob_get_contents();
    ob_end_clean();

	//alert($content);
    return $content;
	//return $vskin_path."/".$vskin."_lastrow.skin.php";
} 

function alert($msg='', $url='')
{
	global $g1;

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=$g1[charset]\">";
	echo "<script type='text/javascript'>alert('$msg');";
    if (!$url)
        echo "history.go(-1);";
    echo "</script>";
    if ($url)
        goto_url($url);
    exit;
}

function goto_url($url)
{
    echo "<script type='text/javascript'> location.replace('$url'); </script>";
    exit;
}

function historygo(){
	echo "<script type='text/javascript'> history.go(-1);</script>";
	exit;
}

	$mysql_host = 'localhost';
	$mysql_user = 'root';
	$mysql_password = 'apmsetup';
	$mysql_db = 'test';

    $connect_db = sql_con($mysql_host, $mysql_user, $mysql_password);
    $select_db = sql_select_db($mysql_db, $connect_db);
?>