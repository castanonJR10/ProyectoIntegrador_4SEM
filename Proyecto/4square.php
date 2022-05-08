<?php

  $v = "20210519";
  //$clientId = "3JJTB0HVUUHA5EHNOZ5NUVVEAXZEPA0BVVM4BWKA5LBHRB1L"; @gmail.com
  //$clientSecret = "IO3DLGV45W31ZVW4NBJDJP40RDDJQUD22WRC1ZRQIHANPA0H"; 
  $clientId = "3NSSSXAMH54FVUXUQU0TIHKC3GZPUDCFNQV4LCIPLMC25VTE"; //@x.edu.mx
  $clientSecret = "KMRUNKWHNMHX2VJZM3YYXJP44SEIDOAILBYJQIZ3JK0BWSYU"; 
  $locale = "en";

  function getVenueDetails($evaluate_response) {
    global $v, $clientId, $clientSecret, $locale;
    $results = array();
    for($num = 0; $num < count($evaluate_response); $num = $num+1){
      $id_venue = $evaluate_response[$num]->id;
      $distance_id = $evaluate_response[$num]->location->distance;
      $name_id = $evaluate_response[$num]->name;

      $urlSearchVenue = 'https://api.foursquare.com/v2/venues/'.$id_venue.'?client_id='.$clientId.'&client_secret='.$clientSecret.'&locale='.$locale.'&v='.$v;
      
      $chVenue = curl_init();
      curl_setopt($chVenue, CURLOPT_URL, $urlSearchVenue); 
      curl_setopt($chVenue, CURLOPT_HEADER, 0); 
      curl_setopt($chVenue, CURLOPT_RETURNTRANSFER, true); 
      //$dataVenue = curl_exec($chVenue);
      curl_close($chVenue);
      //Hardcode json, because 4square api limit premium calls to 50 every day
      $dataVenue = '{"meta":{"code":200,"requestId":"60a7dcf80dd7e82825f34e6a"},"notifications":[{"type":"notificationTray","item":{"unreadCount":0}}],"response":{"venue":{"id":"5172fdf2498e2885e2510ad7","name":"Villagia In The Glebe","contact":{"phone":"6135655212","formattedPhone":"(613) 565-5212"},"location":{"address":"480 Metcalfe St.","crossStreet":"OConnor And Pretoria","lat":45.410277,"lng":-75.687101,"labeledLatLngs":[{"label":"display","lat":45.410277,"lng":-75.687101}],"postalCode":"K1S 3N6","cc":"CA","neighborhood":"Glebe-Dows Lake","city":"Ottawa","state":"ON","country":"Canada","contextLine":"Glebe-Dows Lake","contextGeoId":31190,"formattedAddress":["<span itemprop=\"streetAddress\">480 Metcalfe St.<\/span> (O&#39;Connor And Pretoria)","<span itemprop=\"addressLocality\">Ottawa<\/span> <span itemprop=\"addressRegion\">ON<\/span> <span itemprop=\"postalCode\">K1S 3N6<\/span>"]},"canonicalUrl":"https:\/\/foursquare.com\/v\/villagia-in-the-glebe\/5172fdf2498e2885e2510ad7","canonicalPath":"\/v\/villagia-in-the-glebe\/5172fdf2498e2885e2510ad7","categories":[{"id":"5032891291d4c4b30a586d68","name":"Assisted Living","pluralName":"Assisted Living","shortName":"Assisted Living","icon":{"prefix":"https:\/\/ss3.4sqi.net\/img\/categories_v2\/building\/apartment_","mapPrefix":"https:\/\/ss3.4sqi.net\/img\/categories_map\/building\/default","suffix":".png"},"primary":true}],"verified":false,"stats":{"tipCount":0,"usersCount":18,"checkinsCount":44,"visitsCount":64},"url":"http:\/\/www.villagiaintheglebe.com\/?gclid=CNS67MmK_NMCFQctaQoddT8B-g","urlSig":"ul+3CJMnanwqAcGhs0+0APk8JLg=","likes":{"count":2,"groups":[{"type":"others","count":2,"items":[{"id":"563251442","firstName":"Don","lastName":"Frisby","gender":"none","countryCode":"CA","canonicalUrl":"https:\/\/foursquare.com\/user\/563251442","canonicalPath":"\/user\/563251442","photo":{"prefix":"https:\/\/fastly.4sqi.net\/img\/user\/","suffix":"\/563251442_qfCVAZSh_wSoXSQIiUD1KHhWR1igyeKAVxkm3DT5JUqWeZJXqAwdzU9rJZk57Uc7-PM8SuGgB.jpg"},"isAnonymous":false},{"id":"6643336","firstName":"Chris","lastName":"Lawson","gender":"male","address":"","city":"","state":"","countryCode":"CA","canonicalUrl":"https:\/\/foursquare.com\/user\/6643336","canonicalPath":"\/user\/6643336","photo":{"prefix":"https:\/\/fastly.4sqi.net\/img\/user\/","suffix":"\/6643336_yKldP-1K_v1orFwOZE_wCNQ1L_miLg4upReomEHOXT1xQr4R9GrhGjAP1n1-emusicOis5eYm.jpg"},"isAnonymous":false}]}],"summary":"Don F & Chris L"},"like":false,"dislike":false,"ok":false,"venueRatingBlacklisted":true,"beenHere":{"count":0,"unconfirmedCount":0,"marked":false,"lastCheckinExpiredAt":0},"specials":{"count":0,"items":[]},"photos":{"count":1,"groups":[{"type":"venue","name":"Venue photos","count":1,"items":[{"id":"599752d7f8cbd46f1c226a54","createdAt":1503089367,"prefix":"https:\/\/fastly.4sqi.net\/img\/general\/","suffix":"\/212169387_2rsvsAdsnsW93s-IDVQuRpTELmMlq3yznq7-rQqx67M.jpg","width":2560,"height":1440,"user":{"id":"212169387","firstName":"pivot_photos","gender":"none","countryCode":"US","canonicalUrl":"https:\/\/foursquare.com\/p\/pivotphotos\/212169387","canonicalPath":"\/p\/pivotphotos\/212169387","photo":{"prefix":"https:\/\/fastly.4sqi.net\/img\/user\/","suffix":"\/blank_boy.png","default":true},"type":"page","isAnonymous":false},"visibility":"public"}]}]},"isPhotosensitive":true,"reasons":{"count":0,"items":[]},"hereNow":{"count":0,"summary":"Nobody here","groups":[]},"createdAt":1366490610,"tips":{"count":0,"groups":[]},"shortUrl":"http:\/\/4sq.com\/15uZoai","timeZone":"America\/Toronto","listed":{"count":0,"groups":[]},"seasonalHours":[],"pageUpdates":{"count":0,"items":[]},"structuredLocation":[{"cc":"CA","name":"Glebe-Dows Lake","displayName":"Glebe-Dows Lake","woeType":22,"slug":"glebe---dows-lake-ontario-canada","longId":"31190","allowExplore":true,"encumbered":true},{"cc":"CA","name":"Ottawa","displayName":"Ottawa","woeType":7,"slug":"ottawa-ontario-canada","longId":"72057594044022753","hasCityPage":true,"allowExplore":true},{"cc":"CA","name":"","woeType":10,"longId":"-897836295","allowExplore":true},{"cc":"CA","name":"","woeType":9,"longId":"9151314442846368938","allowExplore":true},{"cc":"CA","name":"Ontario","displayName":"Ontario","woeType":8,"slug":"ontario","longId":"72057594044021879"},{"cc":"CA","name":"Canada","displayName":"Canada","woeType":12,"slug":"canada","longId":"72057594044179935","hasCityPage":true,"allowExplore":true}],"permissions":{"addTips":true,"showPhotos":false,"flagTips":true,"editHours":true,"editVenue":false,"seeEditVenuePage":false,"editCategories":true,"viewEditHistory":false,"viewGeoAdminPage":false,"viewShapesAdminPage":false,"viewSubvenueEditorAdminPage":false,"viewEventsAdminPage":false,"flagVenue":true,"viewFlags":false,"hasRestrictedAddress":false,"indexable":true,"isCreator":false,"sendUpdates":false,"canEditAttributes":true,"usesState":true,"usesAddress":true,"usesPhone":true,"canRollbackEdits":false},"inbox":{"count":0,"items":[]},"venueChains":[],"attributes":{"groups":[]},"bestPhoto":{"id":"599752d7f8cbd46f1c226a54","createdAt":1503089367,"prefix":"https:\/\/fastly.4sqi.net\/img\/general\/","suffix":"\/212169387_2rsvsAdsnsW93s-IDVQuRpTELmMlq3yznq7-rQqx67M.jpg","width":2560,"height":1440,"visibility":"public"},"colors":{"highlightColor":{"photoId":"599752d7f8cbd46f1c226a54","value":-15198184},"highlightTextColor":{"photoId":"599752d7f8cbd46f1c226a54","value":-1},"algoVersion":3},"aliases":["palasades","palisades","the palisades","villagia in the glebe"],"metaTags":{"description":"See 1 photo from 4 visitors to Villagia In The Glebe.","title":"Villagia In The Glebe - Assisted Living in Ottawa"}},"pageConfig":{"hideTastepile":true,"showSeeAllTipsButton":true,"tipCountMax":5}}}';
      $elementsVenue = json_decode($dataVenue);
      $description = $elementsVenue->response->venue->metaTags->description;
      if($description !== "" && $name_id !== "" && $distance_id !== "") {
        $element = array();
        $element["description"] = $description;
        $element["name"] = $name_id;
        $element["distance"] = $distance_id;
        array_push($results, $element);
      }
    }
    return $results;
  }

  function get4squareResults($lat, $lon, $radius, $searchTheme) {
    global $v, $clientId, $clientSecret, $locale;
    
    $url = 'https://api.foursquare.com/v2/venues/search?client_id='.$clientId.'&client_secret='.$clientSecret.'&locale='.$locale.'&v='.$v.'&limit=5&radius='.$radius.'&ll='.$lat.','.$lon.'&query='.$searchTheme;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_HEADER, 0); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
    $data = curl_exec($curl);
    curl_close($curl);
    $elements = json_decode($data);
    
    $evaluate_response = $elements->response->venues;

    return getVenueDetails($evaluate_response);
  }
