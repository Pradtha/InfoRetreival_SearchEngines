
<!DOCTYPE html>
<html>
	<title> CSCI 572 HW3 Page Ranking Comparison </title>
	<meta name="viewport" content="width=device-width">
	<meta charset="utf-8">

	<body>
		<div class='queryProcessing' 
			  style="border: 1px solid; width:40vw; margin-left:20vw;"
		     align="center">
   		<form id="search" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
				Search Word  * : 
				<input id ="txtSearchWord" type="text" name="searchWord" rows="1" style="overflow:hidden" />
				<t/><t/>
				<input id="btnSearch" type="submit" name="search" value="Search" style="margin-left:10vh;"/> 
			</form>
		</div>

	<?php
		require './solr-php-client/Apache/Solr/Service.php';
		header('Content-­‐Type:text/html; charset=utf-­‐8');
		$start = 0;
		$rows = 10;	
		$query = isset($_POST['searchWord'])?$_REQUEST['searchWord']:false;
		$results	= false;

		print $query;
	  
		if($query)	
		{
			require_once('./solr-php-client/Apache/Solr/Service.php');	
	  		$solr =	new Apache_Solr_Service('localhost', 8983,'/solr/TikaExample/');	
			$additionalParameters = array('fl' => 'id,content_type', 'wt'=>'json', 'indent'=>'true'); 
	  		
			if(get_magic_quotes_gpc()==1)	
				$query = stripslahes($query);
	
	 		try	
			{
				
				$results = $solr->search($query,$start,$rows,$additionalParameters);
				$total = (int)$results->response->numFound;
				print $total." ";	
				print $results->response->docs[0]->id;
			}
			catch(Exception $e)
			{	
				print "dying";
				die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");	
	  
			}
	  	}	
	?>
	<noscirpt>

	</body>
</html>



