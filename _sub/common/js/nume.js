// Adding 500 Data Points
var map, pointarray, heatmap, nid;

var taxiData1 = [
  new google.maps.LatLng(37.782551, -122.445368),
  new google.maps.LatLng(37.782745, -122.444586),
  new google.maps.LatLng(37.782842, -122.443688)
  ];

var taxiData = new Array();

function initialize(id) {
nid=id;  
var mapOptions = {
    zoom: 7,
    center: new google.maps.LatLng(47.0200004577636719, 28.5458335876464844),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  map = new google.maps.Map(document.getElementById('map'),
      mapOptions);


  showdata();

}

function toggleHeatmap() {
  heatmap.setMap(heatmap.getMap() ? null : map);
}

function changeGradient() {
  var gradient = [
    'rgba(0, 255, 255, 0)',
    'rgba(0, 255, 255, 1)',
    'rgba(0, 191, 255, 1)',
    'rgba(0, 127, 255, 1)',
    'rgba(0, 63, 255, 1)',
    'rgba(0, 0, 255, 1)',
    'rgba(0, 0, 223, 1)',
    'rgba(0, 0, 191, 1)',
    'rgba(0, 0, 159, 1)',
    'rgba(0, 0, 127, 1)',
    'rgba(63, 0, 91, 1)',
    'rgba(127, 0, 63, 1)',
    'rgba(191, 0, 31, 1)',
    'rgba(255, 0, 0, 1)'
  ]
  heatmap.setOptions({
    gradient: heatmap.get('gradient') ? null : gradient
  });
}

function changeRadius() {
  heatmap.setOptions({radius: heatmap.get('radius') ? null : 20});
}

function changeOpacity() {
  heatmap.setOptions({opacity: heatmap.get('opacity') ? null : 0.2});
}

function showdata(){
	if (typeof xmldata == 'undefined'){
		$(document).ready(function(){
		  $.ajax({
		    type: "GET",
		    url: "xml.php?id="+nid,
		    dataType: "xml",	    
		    success: parseXml
		  });
		});
	} else {
		parseXml();
	}
}

function parseXml(xml){
	xmldata = typeof xml !== 'undefined' ? xml : xmldata;	

	$(xmldata).find("marker").each(function(){
		lat=$(this).attr("lat");
		lng=$(this).attr("lng");
		taxiData.push(new google.maps.LatLng(lat, lng));
	  });
	
	  var pointArray = new google.maps.MVCArray(taxiData);

	  heatmap = new google.maps.visualization.HeatmapLayer({
	    data: pointArray
	  });
	  
	  
	  heatmap.setMap(map);
	
}	