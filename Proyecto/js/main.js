var eventWill;
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) 
{
    eventWill = "touchstart";
}
else
{
    eventWill = "click";
}

function countWords(string) {
  return string.split(" ").length;
}

const minWordsToSearch = 1;
const maxPlacesApiImages = 7;
const api = "api.php";
let sectionChoosed = "";

function getDetails(place_id, raw_title) {
  $.ajax({
    url: api,
    data : {action : "get_details", place_id : place_id, addr : encodeURI(raw_title)},
    dataType : "json",
    type : "GET",
    async : true,
    complete : function() {
      generateNewSessionToken();
    },
    success : function (resp){
      //$("#streetViewImage").attr("src",resp.image);
      $("#search").attr('latitude', resp.latitude);
      $("#search").attr('longitude', resp.longitude);
      //getAddrInfo(encodeURI(raw_title));
      parseWifi(resp);
      parseWalk(resp);
      parseAirbnb(resp);
      get4SquareInfo(sectionChoosed);
    }
  });
}

function parseAirbnb(resp){
  for(i = 0; i < resp.images.length && i <= maxPlacesApiImages; i++){
    $('.streetViewImage[number="'+i+'"]').attr("src",resp.images[i]);
  } 
  $("#loadingSection").addClass('hidden');
  $("#gallerySection").removeClass('hidden');
}

function parseWifi(resp) {
  var arrayWifiListConn = [];
  var arrayWifiListProv = [];
  var i = 0;
  for(i = 0; i < resp.providers.length; i++) {
    arrayWifiListConn[i] = resp.providers[i]["connectionType"];
  }
  let arrayConnList = [...new Set(arrayWifiListConn)];
  for(i = 0; i < resp.providers.length; i++){
    arrayWifiListProv[i] = resp.providers[i]["provider"];
  }
  let arrayProvList = [...new Set(arrayWifiListProv)];

  var wifi = "";
  var wifi_score = 0;
  for(i = 0; i < arrayConnList.length; i++) {
    if(i == arrayConnList.length-1){
      wifi = (wifi.concat(arrayConnList[i])).concat(" internet from ");
    } else if(i == arrayConnList.length-2){ 
      wifi = (wifi.concat(arrayConnList[i])).concat(" and ");
    } else{
      wifi = (wifi.concat(arrayConnList[i])).concat(", ");
    }
  }
  for(i = 0; i < arrayProvList.length; i++) {
    if(i == arrayProvList.length-1){
      wifi = (wifi.concat(arrayProvList[i])).concat(".");
    } else if(i == arrayProvList.length-2){ 
      wifi = (wifi.concat(arrayProvList[i])).concat(" and ");
    } else{
      wifi = (wifi.concat(arrayProvList[i])).concat(", ");
    }
  }
  if(arrayConnList.length < 5){
    wifi_score = arrayConnList.length;
  } else{
    wifi_score = 5;
  }
  if(arrayProvList.length < 5){
    wifi_score += arrayProvList.length;
  } else{
    wifi_score += 5;
  }
  rate_stat = wifi_score/2;
  $("#net").html(wifi);
  $("#wifi_score").html(wifi_score);
  $("#wifi_rating").raty({
    readOnly: true,
    score: rate_stat
  });
}

function parseWalk(resp) {
  let i = 0;
  let walk_data = resp.walk.split("|");
  let score = "", title = "", subtitle = "";
  for(i = 0; i < walk_data.length-1; i+=3) {
    score = walk_data[i], title = walk_data[i+1], subtitle = walk_data[i+2];
    if(i == 0) {
      document.getElementById("shoppingTitle").innerHTML = title;
      document.getElementById("shopping").innerHTML = ""+score+"/100 "+subtitle;
    } else {
      if(i == 3) {
        document.getElementById("transitTitle").innerHTML = title;
        document.getElementById("transit").innerHTML = ""+score+"/100 "+subtitle;
      } else {
        document.getElementById("familiesTitle").innerHTML = title;
        document.getElementById("families").innerHTML = ""+score+"/100 "+subtitle;
      }
    }
  }
}

function parse4square(resp, change_param) {
  let i = 0;
  let squareData = resp.grocery;
  let name = "", description = "", distance = "";
  var square_div = '';
  let param = "";
  let idSquareScore = "";
  var squareScore = 0;
  for(i = 0; i < squareData.length; i++){
    name = squareData[i]["name"];
    description = squareData[i]["description"];
    distance = ("It´s at ".concat(squareData[i]["distance"])).concat("mts.");
    square_div = square_div.concat('<div class="d-lg-flex">\
      <div class="d-flex p-4 text-left">\
        <div class="icon mr-3"><i class="fas fa-shield-alt"></i></div>\
          <div class="text__info">\
            <h4>'+name+'</h4>\
            <p class="m-0">'+description+'</p>\
          </div>\
      </div>\
      <div class="d-lg-flex align-items-center information-comp p-3 text-center text-md-right">\
          <button type="button" class="btn btn-lg btn-primary">'+distance+'</button>\
      </div>\
    </div>\
    <hr>');
  }
  param = change_param + "-div";
  idSquareScore = change_param + "-score";

  if(squareData.length == 1){
    squareScore = 6;
  } else if(squareData.length > 1){
    squareScore = 5 + squareData.length;
  } else {
    squareScore = 0;
  }
  $("#"+idSquareScore).html(squareScore);
  $("#"+param).html(square_div);
}

function get4SquareInfo(sectionParam, change_param) {
  var radiusVal = document.getElementById("search-radius").value;
  let latitude = document.getElementById("search").getAttribute("latitude");
  let longitude = document.getElementById("search").getAttribute("longitude");
  let section = sectionParam;
  $.ajax({
    url: api,
    data : {action : "get_4square_info", radius : radiusVal, latitude : latitude, longitude : longitude, section : section},
    dataType : "json",
    type : "GET",
    async : true,
    success : function (resp){
      parse4square(resp, change_param);
    }
  });
}

$(document).on("change", "#search-radius", function ()  {
  get4SquareInfo(sectionChoosed, change_param);
});

$(document).on(eventWill, ".local-attributes", function ()  {
  change_param = $(this).attr("id");
  sectionChoosed = $(this).attr("section");
  get4SquareInfo(sectionChoosed, change_param);
});

function getAddrInfo(addr) {
  $.ajax({
    url: api,
    data : {action : "get_addr_info", addr : addr},
    dataType : "json",
    type : "GET",
    async : true,
    success : function (resp){
      parseWifi(resp);
      parseWalk(resp);
      parseAirbnb(resp);
      get4SquareInfo(sectionChoosed);
    }
  });
}

function getImageFromStreetView(name) {
  $.ajax({
    url: api,
    data : {action : "get_image_from_street_view", name : name},
    dataType : "json",
    type : "GET",
    async : true,
    success : function (resp){
      $("#streetViewImage").attr("src",resp.image);
    }
  });
}

$(document).on("keyup", "#search", function ()  {
  if(countWords($(this).val().trim()) > minWordsToSearch) {
    var uuid = $("#result").attr("uuid");
    var searchString = encodeURI($(this).val().trim());
    $.ajax({
      url: api,
      data : {action : "get_suggestions", string : searchString, uuid : uuid},
      dataType : "json",
      type : "GET",
      async : true,
      success : function (resp) {
        var predictions = new Array();
        for(i=0;i<resp.predictions.predictions.length;i++) {
          predictions.push({label:resp.predictions.predictions[i].description, place_id:resp.predictions.predictions[i].place_id, });
        }
        $("#search").autocomplete({
          source: predictions,
            select: function(event, ui) {
              const raw_title = ui.item.label.trim();
              let words = raw_title.split(' ');
              let title = raw_title;
              let subtitle = raw_title;
              if(words.length>2) {
                subtitle = words[words.length-2]+' '+words[words.length-1];
                title="";
                for(i=0; i < words.length-2; i++) {
                  title += words[i]+" ";
                }
                //remove last character
                title = title.slice(0, -1);
              }
              $("#loadingSection").removeClass('hidden');
              $("#mainTitle").html(title);
              $("#subtitle").html(subtitle);
              //getImageFromStreetView(encodeURI(raw_title));
              getDetails(ui.item.place_id, raw_title);
              //getAddrInfo(encodeURI(raw_title));
            }
        });   
      }
    });
  }
});

function generateNewSessionToken() {
  $.ajax({
    url: api,
    data : {action : "get_new_session_token"},
    dataType : "json",
    type : "GET",
    async : true,
    success : function (resp){
      $("#result").attr("uuid", resp.uuid);
    }
  });
}

generateNewSessionToken();
