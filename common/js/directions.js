function DirectionsMapViewOnMapLoad() {
	var osmOptions = {
	  getTileUrl: function(coord, zoom) {
	    return "http://a.tile.openstreetmap.org/"+zoom+"/"+coord.x+"/"+coord.y+".png";
	  },
	  tileSize: new google.maps.Size(256, 256),
	  maxZoom: 19,
	  isPng: true,
	  name: "OSM",
	  alt: "OpenStreetMap"
	};
	var osmMapType = new google.maps.ImageMapType(osmOptions);
	var startlat=document.getElementById("fromlat").value;
	var startlng=document.getElementById("fromlng").value;	
	var endlat=document.getElementById("tolat").value;
	var endlng=document.getElementById("tolng").value;	
	
	var startlatlng = new google.maps.LatLng(startlat,startlng);
	var endlatlng = new google.maps.LatLng(endlat,endlng);
	var bound = new google.maps.LatLngBounds();
	var b1=bound.extend(startlatlng);
	var b2=b1.extend(endlatlng);
	
	var OSM_MAPTYPE_ID = 'OSM';
	//var mapTypeIdValue=GetMapTypeConstant(0);
	var centerlatlng = new google.maps.LatLng(startlat,startlng);
	var mapOptions = {
		zoom: 13,
		center:centerlatlng,
		panControl: false,
		streetViewControl: false,
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR},
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		overviewMapControl: true,
		overviewMapControlOptions:{opened:true},
		scaleControl: true,
		zoomControl: true,
		zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
	};
	
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	var map;
	var oldDirections = [];
	var currentDirections = null;
	 
	map = new google.maps.Map(document.getElementById("map"),mapOptions);
	map.mapTypes.set(OSM_MAPTYPE_ID, osmMapType);
	map.fitBounds(b2);

    directionsDisplay = new google.maps.DirectionsRenderer({
        'map': map,
        'preserveViewport': true,
        'draggable': true
    });
    //directionsDisplay.setPanel(document.getElementById("dir"));

	var request = {
	    origin:startlatlng,
	    destination:endlatlng,
	    travelMode: google.maps.DirectionsTravelMode.DRIVING
	};
	directionsService.route(request, function(response, status) {
	  if (status == google.maps.DirectionsStatus.OK) {
	    directionsDisplay.setDirections(response);
	    //alert(response.routes[0].legs[0].distance.value);
	    document.getElementById("roaddistance").innerHTML=response.routes[0].legs[0].distance.text;
	  }
	});

  var geodesic;
  var geodesicOptions = {
    strokeColor: '#CC0099',
    strokeOpacity: 1.0,
    strokeWeight: 3,
    geodesic: true
  }
  geodesic = new google.maps.Polyline(geodesicOptions);
  geodesic.setMap(map);
  var path = [startlatlng, endlatlng];
  geodesic.setPath(path);
  //google.load("google.maps.geometry");
  document.getElementById("directdistance").innerHTML=Math.round(google.maps.geometry.spherical.computeDistanceBetween(startlatlng, endlatlng)/1000)+' km';
  //alert();
}
function FillLocationsDropDown(parentdropdown,childdropdown){
	$(document).ready(function(){
	  $.ajax({
	    type: "GET",
		url: "xml.php?raion_id="+parentdropdown.options[parentdropdown.selectedIndex].value,	    
	    dataType: "xml",
	    success: ReplaceItems
	  });
	});
	function ReplaceItems(xml){
	  $('#'+childdropdown+' >option').remove();
	  $(xml).find("location").each(function(){
		id=$(this).attr("id");
		name=$(this).attr("name");
		$('#'+childdropdown).append("<option value='"+id+"'>"+name+"</option>");
	  });
	}	
}
function ShowDirections(start,end){
	window.location = "index.php?from="+$('#'+start+' :selected').val()+"&to="+$('#'+end+' :selected').val();
}