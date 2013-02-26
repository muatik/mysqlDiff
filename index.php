<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
<head>
	<title>Db Diffs</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="view.css" type="text/css" />
</head>
<body>

<?php
require_once('dbDiffs.php');
require_once('showDiff.php');
$r=$_REQUEST;
$dd=new dbDiffs();

/**
 * veritabanı kullanıcı bilgileri
 * */
$dd->db->username='root';
$dd->db->password='root';

$dbs=$dd->getDbList();


$select='<select name="%s">';
foreach($dbs as $i)
	$select.='<option value="'.$i->name.'">'.$i->name.'</option>';
$select.='</select>';

echo '<form action="?" method="post">';
echo 'db1: '.sprintf($select,'db1').' db2: '.sprintf($select,'db2');
echo '<input type="submit" value="compare" />
</form>';

if(isset($r['db1'],$r['db2'])){
	$diff=$dd->check($r['db2'],$r['db1']);
	echo '<hr />
	<h1>DIFFERENCES: </h1>
	<div class="legend">
		<span class="db1">Tables absent in '.$r['db2'].'</span>
		<span class="db2">Tables absent in '.$r['db1'].'</span>
	</div>';
	showDiffs($diff);
}
?>
<hr class="footer" />
<a href="http://coding.mustafaatik.com/a148_Veritabanlari-Arasindaki-Farklar-dbDiffs">mustafaatik.com</a>
</body>
</html>
