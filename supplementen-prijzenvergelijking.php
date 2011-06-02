<?php

/*
 *      supplementen-prijzenvergelijking.php
 *      
 *      Copyright 2011 Tuplad <tuplad@gmail.com>
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
 *
 *
 * **********************************************
 *              	Variabelen              	*
 *                  ----------        			*
 *     $wp22kg    = Whey Perfection 2,2kg       *
 *     $wp45kg    = Whey Perfection 4,5kg       *
 *     $wd1kg    = Whey Delicious 1,0kg         *
 *     $wd25kg    = Whey Delicious 2,5kg        *
 *     $sf2kg    = Iron Whey 2,0kg        		*
 *     $bl2kg    = Whey Pro 2,0kg				*
 *     $ps25kg	= Whey Isolate 2,5kg			*
 * ******************************************** */

// config
$sqlhost = "127.0.0.1";
$sqluser = "root";
$sqlpass = "";
$sqldb = "db_school";

// verbinden met database
$oPDO = new PDO('mysql:host=' . $sqlhost . ';dbname=' . $sqldb . '', $sqluser, $sqlpass);
// error handling
$oPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// functie om SQL inserting te vergemakkelijken
function addToDB($id, $producent, $product, $gewicht, $prijs) {
    // variabels global maken om ze in functie te gebruiken
    global $oPDO;

    // query klaarmaken en escapen dmv prepare
    $oResult = $oPDO->prepare("INSERT INTO T_supplementen (D_id, D_producent, D_product, D_gewicht, D_prijs) VALUES (:id, :producent, :product, :gewicht, :prijs) ON DUPLICATE KEY UPDATE D_prijs=VALUES(D_prijs)");

    // resultaat weergeven
    if ($oResult) {
        echo("<p>Query voor " . $producent . " / " . $product . " / " . $gewicht . " uitgevoerd!</p> \n");
    } else {
        echo("<p>Geen nieuwe queries uitgevoerd!</p>");
    }

    // vars binden
    $oResult->bindParam(':id', $id);
    $oResult->bindParam(':producent', $producent);
    $oResult->bindParam(':product', $product);
    $oResult->bindParam(':gewicht', $gewicht);
    $oResult->bindParam(':prijs', $prijs);

    // query uitvoeren
    $oResult->execute();
}

// nodig om te scrapen
include ("simple_html_dom.php");

echo <<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Supplementen Prijzenophaling</title>
    </head>
    <body>
        <h1>Prijzen Vergelijking Demo</h1>
        <form name="action" action="supplementen-prijzenvergelijking.php" method="POST">
            <button type="submit" name="update" value="update">Update!</button>
            <button type="submit" name="view" value="view">View!</button>
        </form>
END;
if (isset($_POST['update'])) {
    try {
        // DOM object creeëren
        $html = new simple_html_dom();

        // HTML ophalen Bodyenfitshop - Whey Perfection
        $html->load_file('http://bodyenfitshop.nl/whey-proteine/body-fit-sportsnutrition/whey-perfection');

        // Prijs ophalen van B&FShop Whey Perfection dmv <td class=r>
        // 2,2kg Whey Perfection
        $wp22kg = $html->find(".r", 2)->innertext;
        // in db steken
        addToDB('1', 'Bodyenfitshop', 'Whey Perfection', '2,2kg', $wp22kg);
        // 4,5kg Whey Perfection
        $wp45kg = $html->find(".r", 4)->innertext;
        // in db steken
        addToDB('2', 'Bodyenfitshop', 'Whey Perfection', '4,5kg', $wp45kg);

        // HTML ophalen XXL Nutrition
        $html->load_file('http://www.xxlnutrition.nl/whey-delicious/xxl-nutrition');

        // Gewicht er uit halen, die schrijven wij er zelf bij
        $pattern = array("/1000 gram &raquo; /", "/2500 gram &raquo; /");
        // array maken voor info die wij er uit gaan laten

        $html = preg_replace($pattern, '', $html);
        // $pattern vervangen door niks
        $html = str_get_html($html);
        // DOM object maken van een string
        // Prijs ophalen van XXL Nutrition Whey Delicious dmv <option value=*>
        // 1kg whey delicious
        $wd1kg = $html->find('option[value=435]', 0)->innertext;
        // in mysql
        addToDB('3', 'XXL Nutrition', 'Whey Delicious', '1,0kg', $wd1kg);
        // 2,5kg whey delicious
        $wd25kg = $html->find('option[value=437]', 0)->innertext;
        // in mysql
        addToDB('4', 'XXL Nutrition', 'Whey Delicious', '2,5kg', $wd25kg);

        // HTML ophalen Sportfood
        $html->load_file('http://www.sportfood.nl/shop/product_info.php?products_id=373');

        // Prijs ophalen van Sportfood Iron Whey dmv <td class=pageHeading>
        // 2kg iron whey
        $sf2kg = $html->find('td[class=pageHeading]', 1)->innertext;
        // in mysql
        addToDB('5', 'Sportfood', 'Iron Whey', '2,0kg', $sf2kg);

        // HTML ophalen Bodylab
        $html->load_file('http://www.bodylab.nl/?pid=483');

        // Prijs ophalen van Bodylab Whey Pro dmv class .rbprice
        // 2kg whey pro
        $bl2kg = $html->find('.rbprice', 0)->innertext;
        // in mysql
        addToDB('6', 'Bodylab', 'Whey Pro', '2,0kg', $bl2kg);
        
        // HTML ophalen Powersupplements
		$html->load_file('http://www.powersupplements.nl/pure-whey-protein-isolate-2500g-38');

		// Onnodige info weg, die schrijven wij er zelf bij
		// array maken voor info die wij er uit gaan laten
		$pattern = array("/Prijs:&nbsp;/");
		// $pattern vervangen door niks
		$html = preg_replace($pattern, '', $html);
		// DOM object maken van een string
		$html = str_get_html($html);

		// Prijs ophalen van Powersupplements Whey Isolate dmv <td class=main_table>
		// 2,5kg isolaat
		$ps25kg = $html->find('td[class=main_table]', 0)->innertext;
		// in mysql
		addToDB('7', 'Powersupplements', 'Whey Isolate', '2,5kg', $ps25kg);
    } catch (PDOException $e) {
        echo '<pre>';
        echo 'Regelnummer: ' . $e->getLine() . '<br>';
        echo 'Bestand: ' . $e->getFile() . '<br>';
        echo 'Foutmelding: ' . $e->getMessage() . '<br>';
        echo '</pre>';
    }
} elseif (isset($_POST['view'])) {
    echo "<p>Hier wordt informatie uit de DB gehaald!</p>";
} else {
    echo "<p>Kies een actie.</p>";
}
echo <<<END
    </body>
</html>
END;
?>
