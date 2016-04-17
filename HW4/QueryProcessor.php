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
		$query = (isset($_GET['PsearchHQueryP']))?htmlspecialchars(strip_tags($_GET["PsearchHQueryP"])):false;
		$rank = (isset($_GET['PraHnkP']))?htmlspecialchars(strip_tags($_GET["PraHnkP"])):false;
		$results	= " ";
		$server = 'localhost';
		$port = 8983;
		$core = '/solr/TikaCore3/';

		if($query && $rank)	
		{
			require_once('./solr-php-client/Apache/Solr/Service.php');	
	  		$solr =	new Apache_Solr_Service($server, $port,$core);	
			$additionalParameters = array('fl' => 'id,title,author,created,stream_size', 'wt'=>'json', 'indent'=>'true');
	  		
			if(get_magic_quotes_gpc()==1)	
				$query = stripslahes($query);
			if(get_magic_quotes_gpc()==1)	
				$rank = stripslahes($rank);

			if( strcasecmp($rank,"solr") != 0)
				$additionalParameters = array('fl' => 'id,title,author,created,stream_size', 'sort' => 'pageRank desc', 'wt'=>'json', 'indent'=>'true');

			try	
			{
				
				$results = $solr->search($query,$start,$rows,$additionalParameters);
			}
			catch(Exception $e)
			{		
				$results = " ";
	  
			}
		
			if($results != " ")
			{	
				$total = (int)$results->response->numFound;
				$begin = min($start,$total);
				$end = min($rows,$total);
				$json = array();
				$json['total'] = $total." ";
				$k = 1;
			
				foreach($results->response->docs as $doc)	{
					$checkf = array("id","title","author","created","stream_size");
					$checkv = array();
					$docv = array();
					$loc = array();
					$l = 0;
					$i = 0;
					$j = 0;
					foreach($doc as $field => $value){
						for($j=0; $j<count($checkf); $j++) {					
							if((strcasecmp(htmlspecialchars($field),$checkf[$j]) == 0))
								$loc[$l++] = $j;
						}
						$docv[$i] =  htmlspecialchars($value);	
						$i++;	
					}
				
					
					$i = 0;
					for($j=0; $j<count($checkf); $j++){
						$present = 0;
						for($l = 0; $l < count($loc); $l++) {
							if($j == $loc[$l]){
								$present = 1;
								break;
							}
						}
						if($present == 0)
							$checkv[$j] = "NOT FOUND"; 
					
						else
							$checkv[$j] = $docv[$i++];
					}	
			
					$j = 0;
					for($j = 0; $j < count($checkf); $j++) {
						$json[$checkf[$j].($k)] = $checkv[$j]." ";
					}
					$k++;
				}
				
				echo json_encode($json);
			
			}
			else
				echo "failure";	
		}

		else
			echo "failure";
?>




