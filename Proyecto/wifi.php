<?php

	function searchResultPage($addr) {
		$url = 'https://www.ic.gc.ca/app/sitt/bbmap/geoSearch.html?address='.$addr;
		$next = "";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		$html = curl_exec($ch); 
		if (curl_error($ch)) {
			die(curl_error($ch));
		}
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$dom = new DOMDocument();
		if($dom->loadHTML($html)) {
			$xpath = new DOMXpath($dom);
			$result = $xpath->query('//ol[@class="content"]');
			/*
			<ol class="content">
				<li>
					<a href="ON45390757/service.html?lang=eng" title="ON45390757, ON @ 45.39, -75.67 - opens in a new window" target="_blank">ON45390757, ON @ 45.39, -75.67</a>
				</li>
			</ol>
			*/
			if ($result->length > 0) {
				$li = $xpath->query('li', $result->item(0));
				if ($li->length > 0){
					$a = $xpath->query('a', $li->item(0));
					if ($a->length > 0) {
						$next = "https://www.ic.gc.ca/app/sitt/bbmap/".$a->item(0)->getAttribute('href');
					}
				}
			}	
		}
		curl_close($ch); 
		return $next;
	} 

	function searchWifiResults($nextPage) {
		$newUrl = $nextPage;
		$ch = curl_init($newUrl);
		curl_setopt($ch, CURLOPT_URL, $newUrl); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		$html = curl_exec($ch); 
		if (curl_error($ch)) {
			die(curl_error($ch));
		}
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$dom = new DOMDocument();
		$dom->loadHTML($html);
		$xpath = new DOMXpath($dom);
		$rows = $xpath->query('//table[@id="service"]/tbody/tr');
		/*
		<tr>
			<td>
				<a href="https://www.bell.ca" title="Bell - opens in a new window" target="_blank">
				Bell</a>
			</td>
			<td>
				<span>DSL</span>
			</td>
		</tr>
		*/
		$result = array();
		foreach($rows as $tr) {
			$raw_string = $tr->nodeValue;
			$trimmed = trim(preg_replace('/\s+/', ' ', $raw_string));
			$split_trimmed = explode(" ", $trimmed);
			$counting = count($split_trimmed);
			if($counting > 0) {
				$connectionType = "";
				$provider = $split_trimmed[0];
				$start = 1;
				if($split_trimmed[0] == "Storm" || $split_trimmed[0] == "Freedom"){
					$provider = $split_trimmed[0]." ".$split_trimmed[1];
					$start = 2;
				}
				for($i = $start; $i < $counting; $i= $i+1){
					$connectionType = $connectionType.$split_trimmed[$i]." ";
				}
				$connectionType = trim($connectionType);
				if($provider !== "" && $connectionType !== "") {
					$element = array();
					$element["provider"] = $provider;
					$element["connectionType"] = $connectionType;
					array_push($result, $element);
				}
			}
		}
		curl_close($ch); 
		return $result;
	}
