<?php
// Conexion a la base de datos
include('includes/database.php');

// Funciones
include('includes/functions.php');

// Incluyendo la libreria
include('simple_html_dom.php');

// Conectando a la base de datos
$link = mysql_connect(HOST, USER, PASS);

// Seleccionando la base de datos
mysql_select_db(DATABASE, $link);

// Obteniendo los montos necesarios
$dolar_en_pesos = getDolarEnPesos();
$vef_en_pesos = getBolivarEnPesos();

// Calculando el dolar que es (dolar en pesos / bolivar en pesos) = asi se obtiene el monto del dolar en vef
$dolar = $dolar_en_pesos / $vef_en_pesos;

// Registrando cambios si es necesario
registrarCambioDolar($link, $dolar, $dolar_en_pesos, $vef_en_pesos, date('Y-m-d H:i:s'));


// Para llenar la tabla
$sql = "SELECT * FROM `dl_dolar` order by id desc limit 8";
$response = mysql_query($sql, $link);
$data = array();
echo mysql_error($link);
?>
<html>
	<head>
		<title>Hourly Lechuga</title>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			
			ga('create', 'UA-26375499-2', 'wlacruz.com.ve');
			ga('send', 'pageview');
		</script>
		<style>
			/** Tables **/
			hr {width: 700px; margin-left: 0}
			table {
				border-right:0;
				clear: both;
				color: #333;
				margin-bottom: 10px;
				width: 700px;
			}
			th {
				border:0;
				border-bottom:2px solid #555;
				text-align: left;
				padding:4px;
			}
			th a {
				display: block;
				padding: 2px 4px;
				text-decoration: none;
			}
			th a.asc:after {
				content: ' ⇣';
			}
			th a.desc:after {
				content: ' ⇡';
			}
			table tr td {
				padding: 6px;
				text-align: left;
				vertical-align: top;
				border-bottom:1px solid #ddd;
			}
			table tr:nth-child(even) {
				background: #f9f9f9;
			}
			td.actions {
				text-align: center;
				white-space: nowrap;
			}
			table td.actions a {
				margin: 0px 6px;
				padding:2px 5px;
			}
		</style>
	</head>
	<body>
		<img src="img/generate.php?usd=<?php echo $dolar; ?>" />
		<hr />
		<table cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>1 USD en COP</th>
					<th>1 VEF en COP</th>
					<th>1 USD en VEF</th>
					<th>Fecha</th>
				</tr>
			</thead>
			<tbody>
				<tr class="current">
					<td><?php echo number_format($dolar_en_pesos, 2, ',', '.'); ?></td>
					<td><?php echo number_format($vef_en_pesos, 2, ',', '.'); ?></td>
					<td><?php echo number_format($dolar, 2, ',', '.'); ?></td>
					<td><?php echo date('d M, H:ia'); ?></td>
				</tr>
				<?php while ($row = mysql_fetch_array($response)): ?>
					<tr>
						<td><?php echo number_format($row['cop'], 2, ',', '.'); ?></td>
						<td><?php echo number_format($row['vef'], 2, ',', '.'); ?></td>
						<td><?php echo number_format($row['usd'], 2, ',', '.'); ?></td>
						<td><?php echo date('d M, H:ia', strtotime($row['created'])); ?></td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</body>
</html>