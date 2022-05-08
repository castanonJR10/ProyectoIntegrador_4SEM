<?php

	function getScore($xpath, $score_div, $num) {
		$h5 = $xpath->query('.//h5', $score_div->item($num));
		$p = $xpath->query('.//p', $score_div->item($num));
		$img = $xpath->query('.//div//img', $score_div->item($num));
		$points = "0";
		if ($img->length > 0) {
			$string = $img->item(0)->getAttribute('alt');//88 Walk Score of 75 Strathcona Avenue Ottawa ON Canada
			$words = explode(" ", $string);
			$points = $words[0];
		}
		$title = "";
		if ($h5->length > 0) {
			$title = $h5->item(0)->nodeValue;
		}
		$subtitle = "";
		if ($p->length > 0) {
			$subtitle = $p->item(0)->nodeValue;
		}
		return $points."|".$title."|".$subtitle."|";
	}

	function getWalkScore($addr) {
		$addr = strtolower($addr);
		$addr = str_replace(",", "", $addr);
		$addr = str_replace(".", "", $addr);
		$addr = str_replace(";", "", $addr);
		$addr = str_replace(":", "", $addr);
		$addr = str_replace("_", "", $addr);
		$addr = str_replace("+", "-", $addr);
		
		$url = 'https://www.walkscore.com/score/'.$addr;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		$html = curl_exec($ch); 
		curl_close($ch); 
		if(strpos($html,"Redirecting...")) {
			$dom_aux = new DOMDocument();
			if($dom_aux->loadHTML($html)) {
				$xpath_aux = new DOMXpath($dom_aux);
				$a = $xpath_aux->query("//a");
				if ($a->length > 0) {
					$url = 'https://www.walkscore.com'.$a->item(0)->getAttribute('href');
				}
			}
			$cu = curl_init();
			curl_setopt($cu, CURLOPT_URL, $url); 
			curl_setopt($cu, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($cu, CURLOPT_HEADER, 0); 
			$html = curl_exec($cu); 
			curl_close($cu); 	
		}
		$dom = new DOMDocument();
		if($dom->loadHTML($html)) {
			$xpath = new DOMXpath($dom);
			$score_div = $xpath->query("//div[contains(@class,'score-div')]");
			$walkscore = "";
			$transit = "";
			$biker = "";
			if ($score_div->length > 0) {
				$walkscore = getScore($xpath, $score_div, 0);
			}
			if ($score_div->length > 1) {
				$transit = getScore($xpath, $score_div, 1);
			}
			if ($score_div->length > 2) {
				$biker = getScore($xpath, $score_div, 2);
			}
		}
		return $walkscore.$transit.$biker;
	}
	