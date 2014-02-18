
<html>
	<head>
		<title>nummar.fo</title>
		<meta content="text/html"; charset="iso-8859-1" http-equiv="Content-Type" />
	</head>
	<body>

<?php
   	
   	//Used to search nummar.fo
   	include "simple_html_dom.php";
   	//Get search string
   	$nameToSearch = str_replace(" ", "+", $_GET['s']);
   	
   	//fetch site number 1s
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
   	
   	
   	/*
   	* Result class. 
   	* Each object of this class holds a phone record
   	*/
   	class Result 
   	{
	 	private $name;
	 	private $phone;
	 	private $address;
	 	private $mobile;
	
	 	public function __construct($name, $phone, $address, $mobile) 
	 	{
		 	$this->name 	= $name;
		 	$this->phone 	= $phone;
		 	$this->address 	= $address;
		 	$this->mobile	= $mobile;
	 	}
   	}
   		
	//Array holding Result objects   	
   	$resultArr = array();
   	
   	$doContinue = true;
   	while($doContinue) 
   	{
	   	foreach($html->find('div[class=mdCardData]') as $key => $info) 
	   	{
	   		$name;
	   		$phone;
	   		$address;
			$type;
				   		
	   		//Fetch name
	   		foreach($info->find('h2[class=fn n]') as $key => $info_name)
	   		{
	   			$name = (utf8_decode($info_name->plaintext));
	   		}
	   		
	   		//Fetch address
	   		foreach($info->find('address[class=adr]') as $key => $info_address) 
	   		{
				$address = (utf8_decode($info_address->plaintext));
			}	   		   		

			//Fetch phonenumber
			foreach($info->find('p[class=tel]') as $key => $info_phone) 
			{
				$info_phone = ($info_phone->plaintext);
				$info_type = substr($info_phone, 13, 4);
				
				$numberWithSpaces = substr($info_phone, -15);
				$phone = "+298 ".substr($numberWithSpaces, 0, 10);
							
				if($info_type == "Fart") 
				{			
					$mobile = true;
				}
				else 
				{
					$mobile = false;
				}
			}
			//Add result to ResultArray
			$resultArr[] = new Result($name, $phone, $address, $mobile);
	   	}
	   	
	   	$doContinue = false;
   	}
   		   	
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