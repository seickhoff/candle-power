<?php

date_default_timezone_set('America/Chicago');

require_once("../php-include/incl.yahoo.historical.data.php");
require_once("../php-include/incl.candles.php");


ini_set('max_execution_time', 0); //no limit 

$head = <<<HEND
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>CandlePower</title>
</head>
<body bgcolor="#ffffff">
HEND;

/*
$flash1 = <<<FSTART
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="995" height="395" id="CandlePower20-Day" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="CandlePower20-Day.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#dddddd" />
FSTART;
*/

$flash2 = <<<FEND
<embed src="CandlePower20-Day.swf" quality="high" bgcolor="#dddddd" width="995" height="395" name="CandlePower20-Day" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" 
FEND;

/*
$flash3 = <<<FSTART
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="995" height="395" id="CandlePower90-DayTall" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="CandlePower90-DayTall.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#dddddd" />
FSTART;
*/

$flash4 = <<<FEND
<embed src="CandlePower90-DayTall.swf" quality="high" bgcolor="#dddddd" width="995" height="395" name="CandlePower90-DayTall" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" 
FEND;

$foot = <<<FEND
</body>
</html>
FEND;


$timestamp = ($_REQUEST['date'] != "") ? strtotime($_REQUEST['date'] . " 12:00 AM") : strtotime("today 12:00 AM");
$date = date("Ymd", $timestamp);


$syms = array();
if ($_REQUEST['symbols'] != "") {
  $syms = preg_split("/[\s,]+/", $_REQUEST['symbols']);
}
else {
  $syms = preg_split("/[\s,]+/", "^gspc ^dji ^ixic AA AAPL ABT ADCT AMD AMZN AXP BAC BSX BX BXP C CAT DD DRYS EA EBAY EGO ETFC EMR F FB GOOG HBAN IBM JNJ KO NFLX NKE OXY PG PLUG PYPL SWKS UNP V WYNN XOMA");
}

echo $head;
echo "<h3>CandlePower &copy;SCE 2009-" . date("Y", strtotime("now")) . "</h3>\n";

echo "<table>";

$cnt = 0;

foreach ($syms as $symbol) {

	
	// get data: symbol,date,volumne,high,low,open,close"
	$yahoo_asc = yahoo(array(
		"symbol" => $symbol, 
		"start" => $date, 
		"end" => $date, 
		"offset" => 160
	));	
	
	$yahoo_desc = array_reverse($yahoo_asc);
	
	if (count($yahoo_asc) >= 20) {
	
		//$array_ref, $index_open, $index_close, $index_high, $index_low
		$candle_results = Candles($yahoo_asc, 5, 6, 3, 4);
		
		$candle_color = $candle_results[0];
		$candle_trend = $candle_results[1];
		$patterns = $candle_results[2];
		
		$candle_asc = array_reverse($patterns);
	
		$cnt++;
		if ($cnt == 1) {
			echo "<tr>\n";
		}
		
		$flash_index = 0;
		$values = "";
		
		$values .= 'Symbol='.	$symbol . '&';
		$values .= 'Date='.	$date . '&';
		
		$days = 20;
		
		for ($i = 0; $i < $days; $i++) {
			
			$candle = urlencode($candle_asc[$i]);
					
			list ($sym, $Date, $Volume, $High, $Low, $Open, $Close) = explode(',', $yahoo_desc[$i]);
			
			$flash_index++;
			$flash_index = $days - $i;
			$values .= 'Date' . $flash_index . '='.	$Date . '&';
			$values .= 'Open' . $flash_index . '='.	urlencode(sprintf("%01.2f", $Open)) . '&';
			$values .= 'Close' . $flash_index . '='. urlencode(sprintf("%01.2f", $Close)) . '&';
			$values .= 'High' . $flash_index . '=' . urlencode(sprintf("%01.2f", $High)) . '&';
			$values .= 'Low' . $flash_index . '=' . urlencode(sprintf("%01.2f", $Low)) . '&';
			$values .= 'Vol' . $flash_index . '=' . urlencode(sprintf("%01.0f", $Volume)) . '&';
			$values .= 'Candle' . $flash_index . '=' . $candle. '&';
		}
		
		$values = substr($values, 0, -1);
		
		//echo "<td>\n" . $flash1;
		//echo '<param name="FlashVars" value="' . $values, '" />' . "\n";
		//echo $flash2 . 'flashvars="' . $values . "\" />\n</object>\n</td>\n";
		echo "<td>\n" . $flash2 . 'flashvars="' . $values . "\" />\n</td>\n";
		
		if ($cnt == 1) {
			echo "</tr>\n";
			$cnt = 0;
		}
	

		$cnt++;
		if ($cnt == 1) {
			echo "<tr>\n";
		}
	
		$values = "";
		$values .= 'Symbol='. $symbol . '&';
		$values .= 'Date='.	$date . '&';
		
		$days = 90;
		
		for ($i = 0; $i < $days; $i++) {
			
			$candle = urlencode($candle_asc[$i]);
					
			list ($sym, $Date, $Volume, $High, $Low, $Open, $Close) = explode(',', $yahoo_desc[$i]);
			
			$flash_index++;
			$flash_index = $days - $i;
			$values .= 'Date' . $flash_index . '='.	$Date . '&';
			$values .= 'Open' . $flash_index . '='.	urlencode(sprintf("%01.2f", $Open)) . '&';
			$values .= 'Close' . $flash_index . '='. urlencode(sprintf("%01.2f", $Close)) . '&';
			$values .= 'High' . $flash_index . '=' . urlencode(sprintf("%01.2f", $High)) . '&';
			$values .= 'Low' . $flash_index . '=' . urlencode(sprintf("%01.2f", $Low)) . '&';
			$values .= 'Vol' . $flash_index . '=' . urlencode(sprintf("%01.0f", $Volume)) . '&';
			$values .= 'Candle' . $flash_index . '=' . $candle. '&';
		}
		
		$values = substr($values, 0, -1);
		
		//echo "<td>\n" . $flash3;
		//echo '<param name="FlashVars" value="' . $values . '" />' . "\n";
		//echo $flash4 . 'flashvars="' . $values . "\" />\n</object>\n</td>\n";
		echo "<td>\n" . $flash4 . 'flashvars="' . $values . "\" />\n</td>\n";
		
		if ($cnt == 1) {
			echo "</tr>\n";
			$cnt = 0;
		}
		
	}
}
echo "</table>";
echo	$foot;
//close(OUT);

//<param name="FlashVars" value="Open1=203.67&High1=206.75&Low1=200.10&Close1=202.48&Vol1=17248500&Open2=201.66&High2=202.81&Low2=196.45&Close2=197.37&Vol2=26985800&Open3=197.71&High3=198.02&Low3=191.10&Close3=192.40&Vol3=29162000&Open4=195.00&High4=196.81&Low4=192.14&Close4=196.35&Vol4=20366800&Open5=196.06&High5=196.80&Low5=188.17&Close5=188.50&Vol5=25576300" />


?>