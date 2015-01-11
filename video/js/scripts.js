

var map;
var type=""; //1-marker, 2-line, 3-polygon   	
var marker="";

function OnMapLoad() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,18)];
		tilelayers[0].getCopyright = function(a,b) {return "OpenStreetMap.com"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(19), "OSM",{errorMessage:"No Data Available"});

		map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap);  
		map.addMapType(G_PHYSICAL_MAP); 
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl(new GSize(250,200)));
		

		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);		
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
     	var title="";//document.getElementById("title").value;
    	var description="";//document.getElementById("description").value;
    	
    	
    			
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			var m = new GMarker(new GPoint(lng,lat));
			map.addOverlay(m);
			var html='<b>'+title+'</b><br>'+description+'<br>';
			var opts = new Object(); 
			opts.noCloseOnClick = true; 
			m.openInfoWindowHtml(html,opts); 
		}
		 // Add click listener
 		GEvent.addListener(map, "click", leftClick);
		GEvent.addDomListener(map, "dragend", function() {
			document.getElementById("zoom").value=map.getZoom();	
			document.getElementById("centerlat").value=map.getCenter().lat();
			document.getElementById("centerlng").value=map.getCenter().lng();
		});
		GEvent.addDomListener(map, "maptypechanged", function() {
			document.getElementById("maptype").value=WizardGetCurrentMapTypeNumber(map);
		});	 
		GEvent.addDomListener(map, "zoomend", function() {
			document.getElementById("zoom").value=map.getZoom();
		});
		
		GDownloadUrl("xml.php", function(data) {
			var xml = GXml.parse(data);
			var markers = xml.documentElement.getElementsByTagName("marker");
			for (var i = 0; i < markers.length; i++) {
				var title = markers[i].getAttribute("title");
				var description = markers[i].getAttribute("description");
				var type = markers[i].getAttribute("type");
				var link = markers[i].getAttribute("link");
				var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")), parseFloat(markers[i].getAttribute("lng")));
				var marker = createMarker(point, title, description, type,link);
				map.addOverlay(marker);
			}
		});	 						 		

	}
}

function createMarker(point, name, address, type, link) {
  var marker = new GMarker(point, customIcons[type]);
  var html = "<b>" + name + "</b> <br/>" + address+ "<br/><a href=\""+link+"\">"+link+"</a>";
  GEvent.addListener(marker, 'click', function() {
    marker.openInfoWindowHtml(html);
  });
  return marker;
}
function leftClick(overlay, point) {
	if ((marker=="")&&(type=="button1")){
		marker = new GMarker(point, {draggable: true});		
		var html='Titlu<br><input type="text" id="ptitle" name="ptitle" style="width:200px;"/><br>Descriere<br><textarea id="pdescription" name="pdescription" style="width:200px;height:100;"></textarea><br><input type="button" name="save" value="Salveaza" class="button" onclick=savepoi();>';
		var opts = new Object(); 
		opts.noCloseOnClick = true; 
		map.openInfoWindowHtml(point,html,opts);
		map.addOverlay(marker);	
		GEvent.addDomListener(marker, "click", function() {
			map.closeInfoWindow();
		});
		GEvent.addDomListener(marker, "dragstart", function() {
			map.closeInfoWindow();
		});		
		GEvent.addDomListener(marker, "dragend", function() {
			map.openInfoWindowHtml(marker.getLatLng(),html,opts);
		});									
	}
}
function savepoi() {
	document.getElementById("lat").value=marker.getLatLng().lat();
	document.getElementById("lng").value=marker.getLatLng().lng();	
	document.getElementById("title").value=document.getElementById("ptitle").value;
	document.getElementById("description").value=document.getElementById("pdescription").value;
	document.getElementById("poiform").submit();		
}