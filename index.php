<?php
$link = mysql_connect('h0$t', 'u$er', 'pAs$');
mysql_select_db('databa$e', $link);
include('simple_html_dom.php');

$n = (isset($_GET['val']) && !empty($_GET['val'])) ? $_GET['val'] : 1;
$html = file_get_html("http://www.preciodolar.com/convert.php?from=USD&to=COP&val=$n");
$pesos = $html->plaintext;
$pesos = substr($pesos, 4, 7);
echo "$n USD = " . number_format($pesos, 2, ".", "") . " COP";
echo "<br />";
$pesos = number_format($pesos, 2, ".", "");

$html = file_get_html("http://www.laopinion.com.co/demo/");
foreach($html->find('table.indicadores tbody tr.fondo td') as $e) {
	$a = $e->plaintext;
	break;
}
$dolar = substr($a, 1);
echo "1 VEF = " . number_format($dolar, 2, ".", "") . " COP";
echo "<br />";
$dolar = number_format($dolar, 2, ".", "") ;

$resultado = $pesos / $dolar;
echo "$pesos COP / $dolar COP = $resultado VEF";

$sql = "SELECT * FROM `dl_dolar` order by id desc limit 1";
$r = mysql_query($sql);
$row = mysql_fetch_array($r);
#echo number_format($row['usd'], 12, ".", "") ==  number_format($resultado, 12, ".", "");
if ( number_format($row['usd'], 12, ".", "") != number_format($resultado, 12, ".", "")) {
$sql = "INSERT INTO dl_dolar (usd, cop, vef, created) VALUES ($resultado, $pesos, $dolar, '" . date('Y-m-d H:i:s') . "')";
mysql_query($sql);
}
echo mysql_error($link);
echo '<h1 style="font-size: 6em;margin: 0;">' . number_format($resultado, 2, ".", "") . "</h1>";
