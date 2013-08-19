<?php 
	function exportResults( $request, $site, $query ){
		
		$data = getData($site, $query, $request);
		
		$export_data = "";
		switch( $request ){
			case 'matches':
				$export_data = "URLs WITH LINK \t NO FOLLOW \t ANCHOR TEXT \t LINK \t \n";
				if (count($data) == 0){
					$export_data .= "no data found... \t \n";
				}else{
					for ($i = 0; $i <= count( $data )-1; $i++ ){
					$export_data .= $data[$i][0] . " \t" . $data[$i][1] . " \t" . $data[$i][2] . "\t" . $data[$i][3] .  "\t \n";	
					}
				}
				
			break;
			case 'no_matches':
				$export_data = "URLs WITHOUT SPECIFIED LINK \t \n";
				if (count($data) == 0){
					$export_data .= "no data found... \t \n";
				}else{
					for ($i = 0; $i <= count( $data ); $i++ ){
						$export_data .= $data[$i][0]. " \t \n";	
					}
				}
			break;
			case 'invalid_urls':
				$export_data = "URLs NOT CRAWLED: Page not found or invalid \t \n";
				if (count($data) == 0){
					$export_data .= "no data found... \t \n";
				}else{
					for ($i = 0; $i <= count( $data ); $i++ ){
						$export_data .= $data[$i][0]. " \t \n";	
					}
				}
			break;
			case 'all':
				$export_data = "URL WITH LINK \t NO FOLLOW \t ANCHOR TEXT \t LINK \t \n";
				if (count($data['matches']) == 0){
					$export_data .= "no data found... \t \n";
				}else{
					for ($i = 0; $i <= count( $data['matches'] ); $i++ ){
						$export_data .= $data['matches'][$i][0]. " \t \n";	
					}
				}
				
				$export_data .= "URLs WITHOUT SPECIFIED LINK \t \n";				
				if (count($data['no_matches']) == 0){
					$export_data .= "no data found... \t \n";
				}else{
					for ($i = 0; $i <= count( $data['no_matches'] ); $i++ ){
						$export_data .= $data['no_matches'][$i][0]. " \t \n";	
					}
				}
				
				$export_data .= "URLs NOT CRAWLED: Page not found or invalid \t \n";
				if (count($data['invalid_urls']) == 0){
					$export_data .= "no data found... \t \n";
				}else{
					for ($i = 0; $i <= count( $data['invalid_urls'] ); $i++ ){
						$export_data .= $data['invalid_urls'][$i][0]. " \t \n";	
					}
				}
			break;
		}
		
		return $export_data;		
		
			
	}	
	/**
	* Formats and populates the tables
	* 
	* @param $site (Array) URL or sets of URLs to crawl.
	* @param $query (String) link url to look for.
	* @param $return (String) specifies what data to return.
	*		1. matches - all matches found.
	*		2. no matches - list of urls that did not have any match
	*		3. invalid urls - urls with pages that cannot be found, not existing or malformed.
	* 
	* @return (Array) matches/no matches/invalid urls
	*/ 	
	function getData( $site, $query, $request ){
		
		$rows = array();
		$false_rows = array();
		$invalid_rows = array();
		$data = array();
		
		$urls = explode( "\r\n", $site ); 
		
		// validates each url and crawls them one by one for validated links.
		for( $i = 0; $i <= count($urls)-1; $i++ ){	
			if ( substr( $urls[$i], -1 ) == "/"){
				$urls[$i] = substr( $urls[$i], 0, -1);
			}
						
			if( validateURL( $urls[$i] ) == 1){						
				array_push( $data, checkLink( $urls[$i], $query ) ); 				
			}else{				
				array_push( $invalid_rows, $urls[$i] );								
			}
		}	
		
		// separates data for urls with matches and urls without matches.
		for ( $i = 0; $i <= count( $data )-1; $i++ ){		
			for ( $ii = 0; $ii <= count( $data[$i] )-1; $ii++ ){			
				if( $data[$i][$ii][1] == "not found" ){		
					array_push( $false_rows, $data[$i][$ii] ); 	
				}else{	
					array_push( $rows, $data[$i][$ii]);	
				}				
			}				
		}
		
		switch( $request ){
			case "matches":
				return $rows;
			break;
			case "no_matches":
				return $false_rows;
			break;
			case "invalid_urls":
				return $invalid_urls;
			break;
			case "all":
			default:
				$all_data = array(
					"matches" => $rows ,
					"no_matches" => $false_rows,
					"invalid_urls" => $invalid_rows
					);
				return $all_data;
		}
		
	}
	function setTables( $site, $query ){
		
		$data = getData( $site, $query );	
		
		$results = "<tr><th>URLS WITH LINK</th><th align=\"center\">NO FOLLOW</th><th>ANCHOR TEXT</th><th>LINK</th></tr>";
		$false_results = "<tr><th>URLS WITHOUT LINK</th></tr>";
		$invalid_urls = "<tr><th>LINKS NOT CRAWLED</th></tr>";
		
		// filters and displays urls that cannot be crawled
		if( count( $data['invalid_urls'] ) == 0 ){
			$invalid_urls .= "<tr><td colspan=\"4\" align=\"center\">ALL URLS have been crawled.</td></tr>";
		}else{
			for ( $i = 0; $i <= count( $data['invalid_urls'] )-1; $i++ ){
				if ($data['invalid_urls'][$i] == ""){
					$invalid_urls .= "<tr><td>You haven't crawled anything...</td></tr>";
				}else{
					$invalid_urls .= "<tr><td><a href=\"" . $data['invalid_urls'][$i] . "\"  target=\"_blank\"/>" . $data['invalid_urls'][$i] . "</a></td></tr>";	
				}
						
			}
		}			
		
		// populates results table for matches.
		if ( count( $data['matches'] ) == 0 ){
			$results .= "<tr><td colspan=\"4\" align=\"center\"><i>No matches found.</i></td></tr>";
		}else{		
			for( $i = 0; $i <= count($data['matches'])-1; $i++ ){			
				if( $data['matches'][$i][1] != "not found" ){	
					for( $j = 0; $j <= count($data['matches'][$i])-1; $j++ ){
					}
					$results .= "<tr><td><a href=\"" . $data['matches'][$i][0] . "\"  target=\"_blank\"/>" . $data['matches'][$i][0] . "</a></td><td>" . $data['matches'][$i][1] . "</td><td>" . $data['matches'][$i][2] . "</td><td><a href=\"" . $data['matches'][$i][3] . "\"  target=\"_blank\"/>" . $data['matches'][$i][3] . "</a></td></tr>";	
				}
			}	
		}	
		
		// populates results table for urls with no matches.
		if( count( $data['no_matches'] ) == 0 ){
			$false_results .= "<tr><td colspan=\"4\" align=\"center\"><i>ALL URLs have a match.</i></td></tr>";
		}else{
			for ( $i = 0; $i <= count( $data['no_matches'] )-1; $i++ ){
				$false_results .= "<tr><td><a href=\"" . $data['no_matches'][$i][0] . "\"  target=\"_blank\"/>" . $data['no_matches'][$i][0] . "</a></td></tr>"; 			
			}	
		}
		
		$summary = array(
					"matches" => count($data['matches']),
					"no_matches" => count($data['no_matches']),
					"invalid_urls" => count($data['invalid_urls'])
					);	
		
		$results_all = array( 
					"summary"		=> $summary,
					"matches" 		=> $results, 
					"no_matches" 	=> $false_results,
					"invalid_urls" 	=> $invalid_urls 
					);
					
		return $results_all;
	}
	/**
	* Crawls a specified url for links via the <a> tag. and filters for 
	*    rel="nofollow" tag and urls without matches.
	* 
	* @param $url (String) url to crawl in.
	* @param $query (String) link url to look for.
	* 
	* @return (Array) matches and/or urls with no matches
	*/ 
	function checkLink( $url, $query ){	
		$contents = file_get_contents( $url, true );		
		if( $contents == NULL ){ $row[] = array( $url, "not found"); }
		$stripped = strip_tags( $contents, "<a>" );
		
		preg_match_all( "/<a(?:[^>]*)href=\"([^\"]*)\"(?:[^>]*)>([^\"]*)<\/a>/is", $stripped, $matches ); 		
		
		$row = array();
		for ( $i = 0; $i <= count( $matches[0] )-1; $i++ ){	
			if ( preg_match( "<$query>", validateMatch( $matches[1][$i], $url ) ) ){			
				if ( preg_match( "<rel=\"nofollow\">", $matches[0][$i] ) ){						
					array_push ( $row, array( $url, "YES", getAnchorText($matches[0][$i]), $matches[1][$i] ) );									
				}else{
					array_push ( $row, array( $url, "NO", getAnchorText($matches[0][$i]), $matches[1][$i] ) );		
				}				
			}
		}
		if($row == NULL){$row[] = array( $url, "not found");}
		return $row;			
	}	
	/**
	* Validates for a valid url.
	* 
	* @param $url (String) url to validate.
	* 
	* @return (Int) 1 if valid; 0 if invalid
	*/ 
	function validateURL( $url ){	
	
		if(!filter_var( $url, FILTER_VALIDATE_URL )){return 0;}
		
		$regex ="^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-zA-Z0-9+\$\%_-]\.?)+)*\/?(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$^";	
		
		//$url = parse_url($url, PHP_URL_PATH);
		
		return preg_match( $regex, $url );
		
	}
	/**
	* Checks if the url to look for is in the actual format of the matches found. <br />
	*    http://wwww.domain.com/file.php can still match non absolute url like /file.php
	* 
	* @param $link (String) url of the match to compare
	* @param $url (String) url to look for.	
	* 
	* @return (String) fixed format of the match
	*/ 
	function validateMatch( $link,$url ){
		if ( validateURL( $link ) == 0 ){
			if ( $link[0] == "/" ){
				$link = $url . $link;				
			}else{						
				if ( $link[0] == "." ){
					$link = $url. substr($link, 1);						
				}else{
					$link = $url . "/" . $link;									
				}
			}		
		}
		return $link;		
	}
	/**
	* Extracts the Anchor Text from the link
	* 
	* @param $link (String) url to filter
	* 
	* @return (String) The achor text of the matched link
	*/
	function getAnchorText( $link ){
		$anchor = preg_split('/<[^>]*[^\/]>/i', $link, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
		$anchor = ( $anchor[0] ? $anchor[0] : "<i>None</i>" );
		return $anchor;
	}
?>