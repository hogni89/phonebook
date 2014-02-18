<?php 
	$siteNumber = $_GET['sitenumber'];
	$resultsOnPage = 0;
?>
<html>
	<head>
		<meta content="text/html"; charset="iso-8859-1" http-equiv="Content-Type" />
	</head>
	<body>

<?php
   	
   	// Hetta verdur brúkt til at leita á nummar.fo
   	include "simple_html_dom.php";
   	$nameToSearch = str_replace(" ", "+", $_GET['s']);
   	
   	if($siteNumber == null) {
   		$html = file_get_html('http://nummar.fo/?s='.$nameToSearch);
   		$siteNumber = 1;
   	} else {
   		$html = file_get_html('http://nummar.fo/?s='.$nameToSearch.'&q=nummar&page='.$siteNumber);
   	}
   	
   	// LEITA END
   	
   	/*
   	foreach($html->find('h2[class=mdBoxSearchResult]') as $error => $searchResult) {
   		
 		$atVisa = substr((utf8_decode($searchResult)), 30,12);
   		if($atVisa == "Einki funnið") {
   			echo "<span class='graytitle'>Einki funnið</span>";
   		} 
   	}
   	*/
   		
   	// Nøgdin av funnum leitiúrtslitum
   	$nogd = substr((utf8_decode($searchResult)), 30,12);
   	echo "<span class='graytitle'>".$nogd."</span>";
   	
   	
   		   	
   	// Hetta verdur brúkt til at skriva alt út vid
   	foreach($html->find('div[class=mdCardData]') as $key => $info) {
   		
   		
   		echo "<ul class='pageitem'><li class='textbox'>";
   		
   		foreach($info->find('h2[class=fn n]') as $key => $navn) {
   			echo "<p><font size='4'>";
   			echo (utf8_decode($navn->plaintext));
   			echo "</font></p>";
   			$resultsOnPage++;
   		}
   		
		foreach($info->find('address[class=adr]') as $key => $adr) {
			echo "<p>".(utf8_decode($adr))."</p>";
		}
		
		foreach($info->find('p[class=tel]') as $key => $telefon) {
			echo "<p align='right'>";
			$phoneNumber = ($telefon->plaintext);
			$type = substr($phoneNumber, 13, 4);
			
			//echo substr($phoneNumber, -15, 6);
			//echo $type;
			
			if($type == "Fart") {			
				$numberWithSpaces = substr($phoneNumber, -15);
				$number = "+298 ".substr($numberWithSpaces, 0, 10);
				echo $number;
				echo "<a class='noeffect' href='sms:".$number."'> Send SMS til nummar</a>";
			}
			else {
				$numberWithSpaces = substr($phoneNumber, -15);
				$number = "+298 ".substr($numberWithSpaces, 0, 10);
				echo $number;
			}
			
			echo "</p>";
		}
		echo "</li></ul>";
   	}
   	// Skriva út lidugt!
   	
   	echo "<ul class='pageitem'><li class='textbox'>";
   	
   	echo "<p>Síða nr. ".$siteNumber."</p>";
   		
   	if($siteNumber != 1) {
   		
   		$siteNumberBack = $siteNumber - 1;
   		$backLink = "http://bstokni.fo/phonebook/?s=".$nameToSearch."&from=".$from."&sitenumber=".$siteNumberBack;
   		echo "<p><a href='$backLink'> <- Vís fyrru síðu</a></p>";
  	}
   	
   	if($resultsOnPage == 10) {
   		
   		$siteNumber++;
   		$forwardLink = "http://bstokni.fo/phonebook/?s=".$nameToSearch."&from=".$from."&sitenumber=".$siteNumber;
   		echo "<p><a href='$forwardLink'>Vís næstu síðu -> </a></p>";
   	}
   	
   	echo "</li></ul>";
?>
		</div>
		<div id="footer">
			<p>Kelda: nummar.fo - FøroyaTele</p><p>BS Tøkni sp/f, El-arbeiði og bókhald</p>	
			<p>Vitja okkara heimasíðu á <a href="http://www.bstokni.fo">bstokni.fo</a></p>
		</div>
	</body>
</html>