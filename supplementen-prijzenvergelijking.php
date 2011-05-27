<?php
/*
 *      supps.php
 *      
 *      Copyright 2011 Vlad Polianskii <tuplad@gmail.com>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Supplementen Prijzenophaling</title>
	</head>
	<body>
		<?php
		// nodig om te scrapen
		include ("simple_html_dom.php");
		// verbinden met database
		// MySQL connectie
		$mysql_server = 'localhost';
		$mysql_user = 'root';
		$mysql_pass = '';
		$mysql_db = 'db_school';
		// Eventueel foutmelding
		@mysql_connect($mysql_server, $mysql_user, $mysql_pass) or die("Er kon geen verbinding worden gemaakt met de MySQL database.");
		@mysql_select_db($mysql_db) or die("De database kon niet geselecteerd worden!");

		/************************************************
		 *					Variabelen					*
		 * 					----------					*
		 * 	$wp22kg	= Whey Perfection 2,2kg				*
		 * 	$wp45kg	= Whey Perfection 4,5kg				*
		 * 	$wd1kg	= Whey Delicious 1,0kg				*
		 * 	$wd25kg	= Whey Delicious 2,5kg				*
		 * 	$sf2kg	= Iron Whey 2,0kg					*
		 *	$bl2kg	= Whey Pro 2,0kg					*
		 * 												*
		 * 												*
		 ************************************************/

		// DOM object creeëren
		$html = new simple_html_dom();

		// HTML ophalen Bodyenfitshop
		$html -> load_file('http://bodyenfitshop.nl/whey-proteine/body-fit-sportsnutrition/whey-perfection');

		// Prijs ophalen van B&FShop Whey Perfection dmv <td class=r>
		// 2,2kg whey perfection
		$wp22kg = $html -> find(".r", 2) -> innertext;
		// in mysql
		$sql = "INSERT INTO T_supplementen (D_id, D_producent, D_product, D_gewicht, D_prijs) VALUES (1, \'Bodyenfitshop\', \'Whey Perfection\', \'2,2kg\', '".$wp22kg."');";
		// 4,4kg whey perfection
		$wp45kg = $html -> find(".r", 4) -> innertext;
		// in mysql
		$sql = "INSERT INTO T_supplementen (D_id, D_producent, D_product, D_gewicht, D_prijs) VALUES (2, \'Bodyenfitshop\', \'Whey Perfection\', \'4,5kg\', '".$wp45kg."');";

		// HTML ophalen XXL Nutrition
		$html -> load_file('http://www.xxlnutrition.nl/whey-delicious/xxl-nutrition');

		// Gewicht er uit halen, die schrijven wij er zelf bij
		$pattern = array("/1000 gram &raquo; /", "/2500 gram &raquo; /");
		// array maken voor info die wij er uit gaan laten

		$html = preg_replace($pattern, '', $html);
		// $pattern vervangen door niks
		$html = str_get_html($html);
		// DOM object maken van een string

		// Prijs ophalen van XXL Nutrition Whey Delicious dmv <option value=*>
		// 1kg whey delicious
		$wd1kg = $html -> find('option[value=435]', 0) -> innertext;
		// in mysql
		$sql = "INSERT INTO T_supplementen (D_id, D_producent, D_product, D_gewicht, D_prijs) VALUES (3, \'XXL Nutrition\', \'Whey Delicious\', \'1,0kg\', '".$wd1kg."');";
		// 2,5kg whey delicious
		$wd25kg = $html -> find('option[value=437]', 0) -> innertext;
		// in mysql
		$sql = "INSERT INTO T_supplementen (D_id, D_producent, D_product, D_gewicht, D_prijs) VALUES (4, \'XXL Nutrition\', \'Whey Delicious\', \'2,5kg\', '".$wd25kg."');";

		// HTML ophalen Sportsfood
		$html -> load_file('http://www.sportfood.nl/shop/product_info.php?products_id=373');

		// Prijs ophalen van Sportsfood Iron Whey dmv <td class=pageHeading>
		// 2kg iron whey
		$sf2kg = $html -> find('td[class=pageHeading]', 1) -> innertext;
		// in mysql
		$sql = "INSERT INTO T_supplementen (D_id, D_producent, D_product, D_gewicht, D_prijs) VALUES (5, \'Sportfood\', \'Iron Whey\', \'2,0kg\', '".$sf2kg."');";

		// HTML ophalen Bodylab
		$html -> load_file('http://www.bodylab.nl/?pid=483');

		// Prijs ophalen van Bodylab Whey Pro dmv class .rbprice
		// 2kg whey pro
		$bl2kg = $html -> find('.rbprice', 0) -> innertext;
		// in mysql
		$sql = "INSERT INTO T_supplementen (D_id, D_producent, D_product, D_gewicht, D_prijs) VALUES (6, \'Bodylab\', \'Whey Pro\', \'2,0kg\', '".$bl2kg."');";
				
		// geheugen vrijmaken
			$html->clear();
			unset($html);
		?>
	</body>
</html>
