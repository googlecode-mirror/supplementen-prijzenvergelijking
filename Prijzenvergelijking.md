# Hoe te gebruiken ? #

1) Download beide php bestanden en zet ze samen in een map.

Voer de volgende query uit in phpMyAdmin:
```
-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 02, 2011 at 11:31 
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `db_school`
--

-- --------------------------------------------------------

--
-- Table structure for table `T_supplementen`
--

CREATE TABLE IF NOT EXISTS `T_supplementen` (
  `D_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `D_producent` tinytext NOT NULL,
  `D_product` tinytext NOT NULL,
  `D_gewicht` tinytext NOT NULL,
  `D_prijs` tinytext NOT NULL,
  PRIMARY KEY (`D_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;
```

2) Open **supplementen-prijzenvergelijking.php** en verander je MySQL gegevens:

`$sqlhost = "127.0.0.1";`

`$sqluser = "root";`

`$sqlpass = "";`

`$sqldb = "db_school";`

3) Lees de code en gebruik de ingebouwde variabelen:

`$wp22kg` voor B&F Whey Perfection 2,2kg

`$wp45kg` voor B&F Whey Perfection 4,5kg

`$wd1kg` voor XXL Whey Delicious 1,0kg

`$wd25kg` voor XXL Whey Delicious 2,5kg

`$sf2kg` voor Sportfood Iron Whey 2,0kg

`$bl2kg` voor Bodylab Whey Pro 2,0kg

`$ps25kg` voor Powersupplements Whey Isolate 2,5kg

4) Om een prijs op te halen, werk je met simplehtmldom.
Voorbeeld:
```
<?php
// Whey Isolate van Powersupplements
$html->load_file('http://www.powersupplements.nl/pure-whey-protein-isolate-2500g-38');

// Onnodige info weg, die schrijven wij er zelf bij
// array maken voor info die wij er uit gaan laten
$pattern = array("/Prijs:&nbsp;/");
// $pattern vervangen door niks
$html = preg_replace($pattern, '', $html);
// DOM object maken van een string
$html = str_get_html($html);

// Prijs in HTML staat hier tussen:
// <td class="main_table" align="center" colspan="2">Prijs:&nbsp;€48,90</td>
$ps25kg = $html->find('td[class=main_table]', 0)->innertext; // '0' staat voor de eerste child die wij vinden
// in mysql
// ID, Producent, Product, Gewicht, Variabele
addToDB('7', 'Powersupplements', 'Whey Isolate', '2,5kg', $ps25kg);
?>
```