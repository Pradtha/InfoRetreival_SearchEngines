<!DOCTYPE html>

<html>
<head>
   <title>Real Estate Search </title>
   <meta charset='UTF-8'>
</head>

<body>
<div class='estatesearch' style="border: 1px solid; width:40vw; margin-left:20vw;" align="center">
   <form id="estateform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return(validate())">
      Street Address * : <input id ="txtstreet" type="text" name="street" rows="1" style="overflow:'hidden;"> </input> <br/><br/>
      City * : <input id="txtcity" type="text" name="city" rows="1" style="overflow:'hidden;" > </input> <br/><br/>
      State * :
      <select id="selectstate" name="state">
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District Of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
  	<option selected value="--">-----</option>
      </select> <br/><br/>				
       <input id="btnsubmit" type="submit" name="submit" value="Submit" style="margin-left:10vh;"> 
      <img id="zillowimage" src="http://www.zillow.com/widgets/GetVersionedResource.htm?path=/static/logos/Zillowlogo_150x40_rounded.gif" width="150" height="40" alt="Zillow Real Estate Search" /> <br/><br/>
      *-Mandatory Fields
  <form>
</div>

<script>

	function validate() {
      var txt = " ";
      var returnval = true;

      var street = document.getElementById("txtstreet").value;   
      var city = document.getElementById("txtcity").value;   
      var state = document.getElementById("selectstate").value;

      var streetpatt = /^(\d|[a-zA-Z])([a-zA-Z]|\d|\s)+[a-zA-Z]$/g;
      var citypatt    =  /^([a-zA-Z])([a-zA-Z]|\s)+[a-zA-Z]$/g;
     
      if(street.length == 0 || street=="") {
         returnval = returnval && false;      
         txt += "Street Address Required\n";
      }	
      if(city.length == 0 || city=="") {
         returnval = returnval && false;      
         txt += "City Required\n";
      }	
      if(state=="--") {
         returnval = returnval && false;      
         txt += "State Required\n";
      }	
       if(!streetpatt.test(street)) {
         returnval = returnval && false;      
         txt += "Only letters, digits, and spaces are allowed and Minimum 3 characters are required for street address.\n";
      }	
       if(!citypatt.test(city)) {
         returnval = returnval && false;      
         txt += "Only letters, and spaces are allowed and Minimum 3 characters are required for city.\n";
      }	
   
      if(!returnval)
         alert(txt);

      return returnval;
   }
</script>

<?php
	$zstreet = $zcity = $zstate = $zzipcode = "";
	$propertyType = $yearBuilt = $lotSize = $finishArea = $bathrooms = $bedrooms = $taxAssessmentYear = $taxAssessment = "";
	$lastSoldPrice = $lastSoldDate = $zamount = $zlastUpdated = $zvalueChange = $zlow = $zhigh = $rzamount = $rzlastUpdated =    $rzvalueChange = $rzlow = $rzhigh = "";
	$homeDetails = "";
    $z = $rz = false;
    $invalidxml = false;
	if($_SERVER["REQUEST_METHOD"] == "POST") {
      global $invalidxml;

      $street = trim($_POST["street"]);
      $city = trim($_POST["city"]);
      $state = trim($_POST["state"]);

      $street = preg_replace('/\s/', '+', $street);
      $city = preg_replace('/\s/', '+', $city);
  
      $url = 'http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1b2e4iu88wb_1r7kw&address='.$street.'&citystatezip='.$city.'%2C+'.$state.'&rentzestimate=true';
      $xml = simplexml_load_file($url);
       
      getAll($xml);       
 }


   function getAll($var) {
   global $zstreet , $zcity , $zstate , $zzipcode , $propertyType , $yearBuilt , $lotSize , $finishArea , $bathrooms , $bedrooms , $taxAssessmentYear , $taxAssessment ,    $lastSoldPrice , $lastSoldDate , $zamount , $zlastUpdated , $zvalueChange ,$zlow , $zhigh , $rzamount , $rzlastUpdated , $rzvalueChange , $rzlow , $rzhigh,  $z, $rz, $homeDetails;
      if($var->count() == 0)
         return;
      for($i=0; $i < $var->count(); $i = $i + 1){
         $temp = $var->children()[$i];
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
         getAll($temp);
      }
    }  
 
  if($_SERVER["REQUEST_METHOD"] == "POST" && $zzipcode!="") {
   echo "<br/><br/><br/>";
   echo "<div style='margin-left:15vw;'>";
   echo "<h2 style='margin-left:20vw;'>Search Results</h2>";
   echo "<table>";
      echo "<tr bgcolor='#deb887'>";
      echo "<td colspan='4'>  See more details for <a href=".$homeDetails.">".$zstreet.", ".$zcity.", ".$zstate."-".$zzipcode."</a> on Zillow </td>";
      echo "</tr>";
      echo "<tr>";
         echo "<td>  Property Type    </td>";
         echo "<td>". $propertyType."</td>";
         echo "<td>  Last Sold Price    </td>";
         echo "<td>".number_format(round((float)$lastSoldPrice,2))."</td>";
      echo "</tr>";
      echo "<tr>";
         echo "<td>  Year Built    </td>";
         echo "<td> ".$yearBuilt."    </td>";
         echo "<td>  Last Sold Date    </td>";
         echo "<td>  ".number_format(round((float)$lastSoldDate,2))."   </td>";
      echo "</tr>";
      echo "<tr>";
         echo "<td>  Lot Size   </td>";
         echo "<td> ".$lotSize."   </td>";
         echo "<td>  Zestimate &reg Property Estimate as of ".$zlastUpdated."    </td>";
         echo "<td>  ".number_format(round((float)$zamount,2))."   </td>";
      echo "</tr>";
      echo "<tr>";
         echo "<td>  Finished Area    </td>";
         echo "<td> ".$finishArea."    </td>";
         echo "<td>  30 Days Overall Change";
         if(round((float)$zvalueChange,2) < 0)
           echo   "<img src='down_r.gif'> </td>";
         else
           echo   "<img src='up_g.gif'> </td>";
         echo "<td>  ".abs(number_format(round((float)$zvalueChange,2)))."    </td>";
      echo "</tr>";
      echo "<tr>";
         echo "<td>  Bathrooms   </td>";
         echo "<td>  ".$bathrooms."   </td>";
         echo "<td>  All Time Property Range    </td>";
         echo "<td>  ".number_format(round((float)$zlow,2))." - ".number_format(round((float)$zhigh,2))."    </td>";
      echo "</tr>";
      echo "<tr>";
         echo "<td>  Bedrooms   </td>";
         echo "<td>  ".$bedrooms."    </td>";
         echo "<td>  Rent Zestimate &reg Property Estimate as of ".$rzlastUpdated."    </td>";
         echo "<td>  ".number_format(round((float)$rzamount,2))."    </td>";
      echo "</tr>";
      echo "<tr>";
         echo "<td>  Tax Assessment Year  </td>";
         echo "<td>  ".$taxAssessmentYear."    </td>";
         echo "<td>  30 Days Overall Change";
         if(round((float)$rzvalueChange,2) < 0)
           echo   "<img src='down_r.gif'> </td>";
         else
           echo   "<img src='up_g.gif'> </td>";
         echo "<td>  ".abs(number_format(round((float)$rzvalueChange,2)))."    </td>";
      echo "</tr>";
      echo "<tr>";
         echo "<td>  Tax Assessment   </td>";
         echo "<td>  ".$taxAssessment."   </td>";
         echo "<td>  All Time Property Range     </td>";
         echo "<td>  ".number_format(round((float)$rzlow,2))." - ".number_format(round((float)$rzhigh,2))."   </td>";
      echo "</tr>";
   echo "</table>";

   echo "<br/>";
   echo "&copy Zillow, Inc., 2006-2014. Use is subject to";
   echo "<a href='http://www.zillow.com/corp/Terms.htm'> Terms of Use </a>";
   echo "<br/>";
   echo "<a href='http://www.zillow.com/zestimate/'> What's a Zestimate </a>";
   
   echo "</div>";
   }

   else if(isset($_POST["street"]) && isset($_POST["city"]))
   {
      echo "<div style='margin-left:25vw';>";
      echo "No Exact Match Found - Verify that the given address is correct";
   }
?>
<noscript>

</body>
</html>