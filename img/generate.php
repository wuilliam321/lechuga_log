<?php
/* CAT:Line chart */ 

/* pChart library inclusions */ 
include("../class/pData.class.php"); 
include("../class/pDraw.class.php"); 
include("../class/pImage.class.php");
include("../includes/database.php");

// Conectando a la base de datos
$link = mysql_connect(HOST, USER, PASS);

// Seleccionando la base de datos
mysql_select_db(DATABASE, $link);

$sql = "SELECT * FROM ( SELECT * FROM dl_dolar ORDER BY id DESC LIMIT 8 ) sub ORDER BY id ASC";
$response = mysql_query($sql, $link);
$data = array();
while ($row = mysql_fetch_array($response)) {
    $data[] = $row;
}

$precios = array();
$horas = array();

foreach ($data as $item) {
    $usd = number_format($item['usd'], 4);
    $precios[] = sprintf("%s", $usd);
    $horas[] = date("d h:ia", strtotime($item['created']));
}
/* Create and populate the pData object */ 
$MyData = new pData();   
$MyData->addPoints($precios,"Lechuga Verde");
$MyData->setSerieWeight("Lechuga Verde",2); 
$MyData->setAxisName(0,"Monto en Bs."); 
$MyData->addPoints($horas,"Labels"); 
$MyData->setSerieDescription("Labels","Horas"); 
$MyData->setAbscissa("Labels"); 

/* Create the pChart object */ 
$myPicture = new pImage(700,230,$MyData); 

/* Turn of Antialiasing */ 
$myPicture->Antialias = FALSE; 

/* Draw the background */ 
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
$myPicture->drawFilledRectangle(0,0,700,230,$Settings); 

/* Overlay with a gradient */ 
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50); 
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings); 
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80)); 

/* Add a border to the picture */ 
$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0)); 

/* Write the chart title */  
$myPicture->setFontProperties(array("FontName"=>"../fonts/Forgotte.ttf","FontSize"=>8,"R"=>255,"G"=>255,"B"=>255)); 
$myPicture->drawText(10,19,"Historial del Precio de la Lechuga por Hora",array("FontSize"=>14,"Align"=>TEXT_ALIGN_BOTTOMLEFT)); 

/* Set the default font */ 
$myPicture->setFontProperties(array("FontName"=>"../fonts/calibri.ttf","FontSize"=>8,"R"=>0,"G"=>0,"B"=>0)); 

/* Define the chart area */ 
$myPicture->setGraphArea(60,55,680,200); 

/* Draw the scale */ 
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE); 
$myPicture->drawScale($scaleSettings); 

/* Turn on Antialiasing */ 
$myPicture->Antialias = TRUE; 

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10)); 

/* Draw the line chart */ 
$myPicture->drawLineChart(); 
$myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)); 

/* Write the chart legend */ 
$myPicture->drawLegend(590,9,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>255,"FontG"=>255,"FontB"=>255)); 

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20)); 
$TextSettings = array("R"=>201,"G"=>230,"B"=>40,"FontSize"=>40); 
$myPicture->drawText(575,60, number_format($_GET['usd'], 2) ,$TextSettings);
$myPicture->drawArrowLabel(570,45,"Precio Actual",array("Length"=>20,"Angle"=>90,"RoundPos"=>TRUE));

/* Render the picture (choose the best way) */ 
$myPicture->autoOutput("lechuga_today.png"); 
?>