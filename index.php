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
$dolar = 0;
if ($vef_en_pesos > 0) {
	$dolar = $dolar_en_pesos / $vef_en_pesos;
}

// Registrando cambios si es necesario
registrarCambioDolar($link, $dolar, $dolar_en_pesos, $vef_en_pesos, date('Y-m-d H:i:s'));


// Para llenar la tabla
$sql = "SELECT * FROM ( SELECT * FROM dl_dolar ORDER BY id DESC LIMIT 8 ) sub ORDER BY id ASC";
$response = mysql_query($sql, $link);
$data = array();
echo mysql_error($link);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Hourly Lechuga</title>
		<meta name="description" content="Conozca el precio de la lechuga verde en todo momento, un registro actualizado de los cambios en los precios de la mencionada divisa americana">
		<meta name="keywords" content="Lechuga, Control Cambiario, Dolar, Precio Dolar, USD, Lechuga Verde, Lechugas, Precio Lechuga, Precio de la lechuga, Dolar paralelo, Divisa, Divisas">
		<meta name="author" content="Wuilliam Lacruz">
		<meta charset="UTF-8">
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			
			ga('create', 'UA-26375499-2', 'wlacruz.com.ve');
			ga('send', 'pageview');
			
			FB.Event.subscribe('edge.create', function(targetUrl) {
				ga('send', 'social', 'facebook', 'like', targetUrl);
			});
		</script>
		<style>
			/** Tables **/
			h2, h1, hr, p {width: 700px; margin-left: 0}
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
			.current {
				color: green;
			}
			h3 { display: inline }
			h2, h1, p { text-align:  center }
			p a { text-decoration: none }
		</style>
	</head>
	<body>
		<img src="img/generate.php?usd=<?php echo $dolar; ?>" alt="Grafica de los cambios del precio de dolar organizados por hora" />
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
				<?php while ($row = mysql_fetch_array($response)): ?>
					<tr>
						<td><?php echo number_format($row['cop'], 2, ',', '.'); ?></td>
						<td><?php echo number_format($row['vef'], 2, ',', '.'); ?></td>
						<td><?php echo number_format($row['usd'], 4, ',', '.'); ?></td>
						<td><?php echo date('d M, H:ia', strtotime($row['created'])); ?></td>
					</tr>
				<?php endwhile; ?>
				<tr class="current">
					<td><?php echo number_format($dolar_en_pesos, 2, ',', '.'); ?></td>
					<td><?php echo number_format($vef_en_pesos, 2, ',', '.'); ?></td>
					<td><?php echo number_format($dolar, 4, ',', '.'); ?></td>
					<td><?php echo date('d M, H:ia'); ?></td>
				</tr>
			</tbody>
		</table>
		<div itemscope itemtype="http://data-vocabulary.org/Product">
			<h1>
				Precio de la
				<strong><span itemprop="name">Lechuga Verde</span></strong>
			</h1>
			<h2>Actualiza cada hora y registra su historial</h2>
			<p>
				<span itemprop="description">
					El vegetal mas buscado en Venezuela, es el que provee de alimentos, vestimenta, medicina, juguetes y un largo etcétera.
				</span>
				Es una
				<span itemprop="brand">Moneda</span>
				<span itemprop="review" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
					<strong>producto
					<span itemprop="rating">5</span>
					estrellas</strong>
				</span>
				cuyo precio actual es de
				<span itemprop="offerDetails" itemscope itemtype="http://data-vocabulary.org/Offer">
					<meta itemprop="currency" content="VEF" />
					<strong class="current"><span itemprop="price"><?php echo number_format($dolar, 4, ',', '.'); ?></span>
					Bolívares Fuertísimos</strong> al
					<time itemprop="priceValidUntil" datetime="<?php echo date("Y-m-d"); ?>">día de hoy <?php echo date("Y-m-d"); ?>.</time>
					<br />
					<strong><span itemprop="availability" content="in_stock">¡Búscalos mientras puedas!</span></strong>
				</span>
			</p>
			<p><a href="#" 
					onclick="
					  window.open(
						'https://www.facebook.com/sharer/sharer.php?u='+'http://www.wlacruz.com.ve/dolar/', 
						'facebook-share-dialog', 
						'width=626,height=436');
					  ga('send', 'event', 'btn-sharer', 'click', 'fb', 1);
					  return false;">¡Cuéntale a tus amigos de nosotros!</a>
			</p>
			<p>
				<script src="//connect.facebook.net/es_ES/all.js#xfbml=1"></script>
				<fb:like></fb:like>
			</p>
			<p>
				<a href="http://jigsaw.w3.org/css-validator/check/referer">
					<img style="border:0;width:88px;height:31px"
						src="http://jigsaw.w3.org/css-validator/images/vcss"
						alt="¡CSS Válido!" />
				</a>
				<a href="http://jigsaw.w3.org/css-validator/check/referer">
					<img style="border:0;width:88px;height:31px"
						src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
						alt="¡CSS Válido!" />
				</a>
			</p>
		</div>
	</body>
</html>