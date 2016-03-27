<?php
header( 'Cache-Control: no-store, no-cache, must-revalidate' ); 
header( 'Cache-Control: post-check=0, pre-check=0', false ); 
header( 'Pragma: no-cache' ); 
?>

<?php
		require './solr-php-client/Apache/Solr/Service.php';
		header('Content-­‐Type:text/html; charset=utf-­‐8');
		$start = 0;
		$rows = 10;	
		$query = (isset($_GET['searchQuery']))?htmlspecialchars(strip_tags($_GET["searchQuery"])):false;
		//echo $query;
		$results	= " ";

		if($query)	
		{
			require_once('./solr-php-client/Apache/Solr/Service.php');	
	  		$solr =	new Apache_Solr_Service('localhost', 8983,'/solr/TikaCore1/');	
			$additionalParameters = array('fl' => 'id,tile,stream_size', 'wt'=>'json', 'indent'=>'true'); 
	  		
			if(get_magic_quotes_gpc()==1)	
				$query = stripslahes($query);
	
	 		try	
			{
				
				$results = $solr->search($query,$start,$rows,$additionalParameters);
			}
			catch(Exception $e)
			{	
				print "dying";
				die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");	
	  
			}
		}
		if($results != " ")
		{	//print "super";
			//echo $query;
			//echo (array)$results;
			//echo json_encode((array)$results,JSON_PRETTY_PRINT); 
			//echo get_object_vars($results->response->docs);
		
			//echo gettype($results->response->docs[1]);
			//$json = json_encode($results->response->docs);
			//echo $json;
			/*$total = (int)$results->response->numFound;
			$begin = min($start,$total);
			$end = min($rows,$total);
			$json = array();
			$k = 0;
			$json[$k++] = array('total' => $total);
			foreach($results->response->docs as $doc)	
			{		$j = 0;
					$temp = array();	
					foreach($doc as $field => $value) {
						echo gettype(htmlspecialchars($field))."  ".gettype(htmlspecialchars($value));
						$temp[$j++] = array((string)htmlspecialchars($field) => (string)htmlspecialchars($value));
					}
					$json[$k++] = $temp;
			}
			*/
			$total = (int)$results->response->numFound;
			$begin = min($start,$total);
			$end = min($rows,$total);
			$json = array();
			$json['total'] = $total." ";
			$k=0;
			foreach($results->response->docs as $doc)	
			{		foreach($doc as $field => $value) {
						$json[htmlspecialchars($field).($k)] = htmlspecialchars($value);
					}
					$k++;					
			}
			
			echo json_encode($json);
			
		}
		else
		{
			echo "false";
		}
?>




