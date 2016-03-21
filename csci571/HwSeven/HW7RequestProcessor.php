<?php
	$street = $city = $state = "";
	$zstreet = $zcity = $zstate = $zzipcode = "";
	$propertyType = $yearBuilt = $lotSize = $finishArea = $bathrooms = $bedrooms = $taxAssessmentYear = $taxAssessment = "";
	$lastSoldPrice = $lastSoldDate = $zamount = $zlastUpdated = $zvalueChange = $zlow = $zhigh = $rzamount = $rzlastUpdated =    $rzvalueChange = $rzlow = $rzhigh = "";
	$homeDetails = "";
    $z = $rz = false;
    $invalidxml = false;
	
	$zpid = $zcurl1 = $zcurl2 = $zcurl3 = $zcurl =  "";
	
	if(isset($_GET['street'])) 
		$street = htmlspecialchars(strip_tags($_GET["street"]));
	if(isset($_GET['city'])) 
		$city = htmlspecialchars(strip_tags($_GET["city"]));
	if(isset($_GET['state'])) 
		$state = htmlspecialchars(strip_tags($_GET["state"]));
	  
	/*$response = array('street' => $street, 'city' => $city, 'state'=>$state);
	echo json_encode($response);*/
	
    $street = preg_replace('/\s/', '+', $street);
    $city = preg_replace('/\s/', '+', $city);
  
    $urls = 'http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1b2e4iu88wb_1r7kw&address='.$street.'&citystatezip='.$city.'%2C+'.$state.'&rentzestimate=true';
    $xmls = simplexml_load_file($urls);
    getDeepSearch($xmls);  
	$urlc1 = 'http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1b2e4iu88wb_1r7kw&zpid='.$zpid.'&unit-type=percent&width=600&height=300&chartDuration=1year';
	$xmlc1 = simplexml_load_file($urlc1);
	getChartDetails($xmlc1);
	$zcurl1 = $zcurl;
	$urlc2 = 'http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1b2e4iu88wb_1r7kw&zpid='.$zpid.'&unit-type=percent&width=600&height=300&chartDuration=5years';
	$xmlc2 = simplexml_load_file($urlc2);
	getChartDetails($xmlc2);
	$zcurl2 = $zcurl;
	$urlc3 = 'http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1b2e4iu88wb_1r7kw&zpid='.$zpid.'&unit-type=percent&width=600&height=300&chartDuration=10years';
	$xmlc3 = simplexml_load_file($urlc3);
	getChartDetails($xmlc3);
	$zcurl3 = $zcurl;

	
	function getDeepSearch($var) {
		global $zpid, $zstreet , $zcity , $zstate , $zzipcode , $propertyType , $yearBuilt , $lotSize , $finishArea , $bathrooms , $bedrooms , 
		       $taxAssessmentYear , $taxAssessment ,    $lastSoldPrice , $lastSoldDate , $zamount , $zlastUpdated , $zvalueChange ,
			   $zlow , $zhigh , $rzamount , $rzlastUpdated , $rzvalueChange , $rzlow , $rzhigh,  $z, $rz, $homeDetails;
		  if($var->count() == 0)
			 return;
		  for($i=0; $i < $var->count(); $i = $i + 1){
			 $temp = $var->children()[$i];
			 if($temp->getName() == "zpid")
				$zpid = $temp;
			 if($temp->getName() == "zestimate")
				$z = true;
			 if($temp->getName() == "rentzestimate")
				$rz = true;
			 if($temp->getName() == "homedetails")
				$homeDetails = $temp;
			 if($temp->getName() == "street")
				$zstreet = $temp;
			 if($temp->getName() == "city")
				$zcity = $temp;
			 if($temp->getName() == "state")
				$zstate = $temp;
			  if($temp->getName() == "zipcode")
				$zzipcode = $temp;
			 if($temp->getName() == "useCode")
				$propertyType = $temp;
			 if($temp->getName() == "yearBuilt")
				$yearBuilt = $temp;
			 if($temp->getName() == "lotSizeSqFt")
				$lotSize = $temp;
			 if($temp->getName() == "finishedSqFt")
				$finishArea = $temp;
			 if($temp->getName() == "bathrooms")
				$bathrooms = $temp;
			 if($temp->getName() == "bedrooms")
				$bedrooms = $temp;
			 if($temp->getName() == "taxAssessmentYear")
				$taxAssessmentYear = $temp;
			 if($temp->getName() == "taxAssessment")
				$taxAssessment = $temp;
			 if($temp->getName() == "lastSoldPrice")
				$lastSoldPrice = $temp;
			 if($temp->getName() == "lastSoldDate")
				$lastSoldDate = $temp;
			if($temp->getName() == "amount" && $z==true && $rz==false)
				$zamount = $temp;
			 if($temp->getName() == "last-updated" && $z==true && $rz==false)
				$zlastUpdated = $temp;
			 if($temp->getName() == "valueChange" && $z==true && $rz==false)
				$zvalueChange = $temp;
			 if($temp->getName() == "low" && $z==true && $rz==false)
				$zlow = $temp;
			 if($temp->getName() == "high" && $z==true && $rz==false)
				$zhigh = $temp;
			 if($temp->getName() == "amount" && $z==true && $rz==true)
				$rzamount = $temp;
			 if($temp->getName() == "last-updated" && $z==true && $rz==true)
				$rzlastUpdated = $temp;
			 if($temp->getName() == "valueChange" && $z==true && $rz==true)
				$rzvalueChange = $temp;
			 if($temp->getName() == "low" && $z==true && $rz==true)
				$rzlow = $temp;
			 if($temp->getName() == "high" && $z==true && $rz==true)
				$rzhigh = $temp;
			 getDeepSearch($temp);
		  }
    }
	
	function getChartDetails($var) {
		global $zcurl;
		if($var->count() == 0) 
			return;			
		for($i=0; $i < $var->count(); $i = $i + 1){
			$temp = $var->children()[$i];
			 if($temp->getName() == "url")
					$zcurl = $temp;
			getChartDetails($temp);
		  }
    }

	$response = array('zstreet'=> $zstreet ." ", 'zcity'=> $zcity ." ", 'zstate'=> $zstate ." ", 'zzipcode'=> $zzipcode ." ", 'propertyType'=> $propertyType." ",
                      'yearBuilt'=> $yearBuilt ." ", 'lotSize'=> $lotSize ." ", 'finishArea'=> $finishArea ." ", 'bathrooms'=> $bathrooms ." ", 
					  'bedrooms'=> $bedrooms." ", 'tasAssessmentYear'=> $taxAssessmentYear ." ", 'taxAssessment'=> $taxAssessment ." ", 
					  'lastSoldPrice'=> $lastSoldPrice ." ", 'lastSoldDate'=> $lastSoldDate ." ", 'zamount'=> $zamount ." ", 'zlastUpdated'=> $zlastUpdated ." ", 
					  'zvalueChange'=> $zvalueChange ." ", 'zlow'=> $zlow ." ", 'zhigh'=> $zhigh ." ", 'rzamount'=> $rzamount ." ", 'zlastUpdated'=> $rzlastUpdated ." ", 
					  'rzvalueChange'=> $rzvalueChange ." ", 'rzlow'=> $rzlow ." ", 'rzhigh'=> $rzhigh." ",'homeDetails'=>$homeDetails." ",'zcurl1'=>$zcurl1." ",
					  'zcurl2'=>$zcurl2." ", 'zcurl3'=>$zcurl3." ");

	//echo $response;
	echo json_encode($response);
?>
