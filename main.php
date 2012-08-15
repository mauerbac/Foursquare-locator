<?php 
	require_once("src/FoursquareAPI.class.php"); //foursquare doc
	
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Foursquare";

	//conncect to db
$link = mysql_connect("xxDB HOSTxxx", "xxDB USER NAMExxx", "xxxDB Passxxx");
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	$db_selected = mysql_select_db("xxxDB Namexxx");
	if (!$db_selected) {
		die ('Can\'t use foo : ' . mysql_error());
	}
	
	// foursquare credentials
	$client_key = "xxxxx";  //add foursquare client key
	$client_secret = "xxxxx"; //add foursquare client secret 

	//select all users from db
	$result = mysql_query("SELECT * FROM users ORDER BY id ASC") or die(mysql_error());

	$arr;  //array for devs info
	$i = 0; //counter
		//iterate through all users 
		while ($row = mysql_fetch_assoc($result)) {
			$fname = $row['fname']; //store first name
			$lname = $row['lname']; //store last name
			$token= $row['token']; 	//store auth token
			$name="$fname $lname"; 
			$name=trim($name); 
			$twitter=$row['twitter']; //store twitter
			$twitter="<a href='http://twitter.com/$twitter' target='_blank'>"; //create twitter link
			
			//retrieve foursquare info
			$foursquare = new FoursquareAPI($client_key,$client_secret);
			$foursquare->SetAccessToken($token);
			$response = $foursquare->GetPrivate("users/self/checkins");
			$loc=json_decode($response); 
			$time=$loc->response->checkins->items[0]->createdAt; //retrieve time
			$time = date('F, jS', $time);  //convert from unix time
			$loc=$loc->response->checkins->items[0]->venue->location; //retrieve location 
			$currentcity=$loc->city; //city
			$currentstate=$loc->state; //state
			$lat=$loc->lat;  //latitude
			$long=$loc->lng; //longitude


			//store info in array for google maps
			$arr[$i]["city"]=$currentcity;
			$arr[$i]["state"]=$currentstate;
			$arr[$i]["time"]=$time;
			$arr[$i]["lat"] = $lat; 
			$arr[$i]["twitter"]=$twitter;
			$arr[$i]['name'] = $name;
			$arr[$i++]["lng"] = $long;
		}
		$length = $i; //number of devs
		$json = json_encode($arr); 
		mysql_close();
		echo<<<END
		<html>
		<head>



	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDtwRCkFydguPVyHnmX1YXiaP0bkQxdfZo&sensor=false">
    
    </script>
	<script type="text/javascript">

	var map;
	var infowindow = new google.maps.InfoWindow({});
	var markersArray = new Array();
	
	$(document).ready(function() {
		initialize();
	if ($length > 0){
			initializeMarkers();
	}

function initialize() {
		
   //map styling info
  var styles = [
    	{
     	stylers: []
    }, 
    
    {
    	elementType: "labels.text.fill",
    	stylers: [{ color: "#202020" }]},
    {
      	featureType: "poi",
      	elementType: "all",
      	stylers: [ { color: "#d5d8df"},]},

     {
      featureType: "water",
      elementType: "all",
      stylers: [{ color: "#d5d8df"},]},
    
    {
      featureType: "road",
      elementType: "labels",
      stylers: [ { visibility: "off" }]
    }];


  		var styledMap= new google.maps.StyledMapType(styles,{name: "Styled Map"});

		//create new map
        var mapOptions = {
          center: new google.maps.LatLng(38.4500,-96.5333), 
          zoom: 4,
          mapTypeControlOptions:{ mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']}
        };

        map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');

      }

      	//create markers 
      function initializeMarkers() {
      	var json = $json;
      	for (i = 0; i < $length; i++) {
      		var city=json[i].city;
      		var state=json[i].state;
      		var time=json[i].time;
      		var lng = json[i].lng;
      		var lat = json[i].lat;
      		var twitter= json[i].twitter;
      		var name = json[i].name;
      		var info= "<b><h3>" + twitter + name + "</a></h3></b><h4>" + "" + city + ", " + state + "<br />Last Checkin: " +time +"</h4>";
      		var cur = new google.maps.LatLng(lat, lng);
      		var image= 'twmarker.png'
      		var marker = new google.maps.Marker({ 
      			map: map,
  				icon: image,
      			position: cur
      		});
			
			makeInfoWindow(marker, info);
			markersArray.push(marker);
      	}
      }

    function makeInfoWindow(marker, content){ 
 		 google.maps.event.addListener(marker, 'click', function () { 
    	 infowindow.setContent(content);
    	infowindow.open(map, marker); 
  		}); 
	} 

});
	</script>
	</head>
	<body onload="initialize()">
<center><div id='map_canvas' style='width:100%; height:100%'></div></center> <!-- create full screen map -->
</body>
</html>
END;
?>
