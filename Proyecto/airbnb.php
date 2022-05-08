<?php

  function getAirbnbPhotos($addr) {
    $addr = str_replace(",", "-", $addr);
    $addr = str_replace(".", "-", $addr);
    $addr = str_replace(";", "-", $addr);
    $addr = str_replace(":", "-", $addr);
    $addr = str_replace("_", "-", $addr);
    $addr = str_replace("+", "-", $addr);
    $url = 'https://www.airbnb.com/s/'.$addr.'/homes';
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_HEADER, 0); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
    $html = curl_exec($curl);
    curl_close($curl);
    
    $dom = new DOMDocument();
    $imgArray = array();
    array_push($imgArray, $url);
    if($dom->loadHTML($html)) {
      $xpath = new DOMXpath($dom);
      $imgs = $xpath->query("//img");
      foreach ($imgs as $img) {
        array_push($imgArray, $img->getAttribute('src'));
      }

    }

    return $imgArray;
  }
