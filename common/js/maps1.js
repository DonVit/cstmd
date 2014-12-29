function FullMapViewOnMapLoad() {
	
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
	var lat=document.getElementById("lat").value;
	var lng=document.getElementById("lng").value;
	var zoom=parseInt(document.getElementById("zoom").value);			
	var maptype=parseInt(document.getElementById("maptype").value);
    var centerlat=document.getElementById("centerlat").value;
    var centerlng=document.getElementById("centerlng").value;
	
	var OSM_MAPTYPE_ID = 'OSM';
	var mapTypeIdValue=GetMapTypeConstant(maptype);
	var centerlatlng = new google.maps.LatLng(centerlat,centerlng);
	var mapOptions = {
		zoom: zoom,
		center:centerlatlng,
		panControl: true,
		streetViewControl: true,
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR},
		mapTypeId: mapTypeIdValue,
		overviewMapControl: true,
		overviewMapControlOptions:{opened:true},
		scaleControl: true,
		zoomControl: true,
		zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
	};
	
	var map;
	var pmc;
	 
	map = new google.maps.Map(document.getElementById("map"),mapOptions);
	map.mapTypes.set(OSM_MAPTYPE_ID, osmMapType);
	

	//var controlDiv = document.createElement('DIV');
	var myLinkControl = new LinkControl('Adauga','add.php','Adauga un punct pe harta');
	
	// We don't really need to set an index value here, but
	// this would be how you do it. Note that we set this
	// value as a property of the DIV itself.
	//controlDiv.index = 4;
	
	// Add the control to the map at a designated control position
	// by pushing it on the position's array. This code will
	// implicitly add the control to the DOM, through the Map
	// object. You should not attach the control manually.
	map.controls[google.maps.ControlPosition.TOP_CENTER].push(myLinkControl);
/*	
	$(document).ready(function(){
	  $.ajax({
	    type: "GET",
	    url: "xml.php",
	    dataType: "xml",
	    success: parseXml
	  });
	});
*/		
	function parseXml(xml){
	  $(xml).find("marker").each(function(){
		lat=$(this).attr("lat");
		lng=$(this).attr("lng");
		var title=$(this).attr("title");
		var description=$(this).attr("description");
		var link=$(this).attr("link");
		var content='<strong>'+title+'</strong><br/>'+description+'<br/><a href="'+link+'">'+link+'</a>';
		var type=$(this).attr("type");
		var Latlng = new google.maps.LatLng(lat,lng);
		if (type=='news'){
			var mi=new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_yellow.png');
			addMarker(mi,Latlng,content);
		}
		if (type=='imobil'){
			var mi=new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_red.png');
			addMarker(mi,Latlng,content);
		}
		if (type=='chirie'){
			var mi=new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_green.png');
			addMarker(mi,Latlng,content);
		}
		if (type=='link'){
			var mi=new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_blue.png');
			addMarker(mi,Latlng,content);
		}						
	  });
	}		
	function addMarker(mi, location,content) {
	  var ms=new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_shadow.png');
	  var mc=new google.maps.InfoWindow({content:content});
	  var marker = new google.maps.Marker({position: location, map: map, icon: mi, shadow: ms});
	  google.maps.event.addListener(marker, 'click', function() {
       if (pmc!=undefined) {
        pmc.close();
       }
       mc.open(map,this);
       pmc=mc;
	  });
	}
}
function MapViewOnMapLoad() {
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

	var lat=document.getElementById("lat").value;
	var lng=document.getElementById("lng").value;
	var zoom=parseInt(document.getElementById("zoom").value);			
	var maptype=parseInt(document.getElementById("maptype").value);
    var centerlat=document.getElementById("centerlat").value;
    var centerlng=document.getElementById("centerlng").value;
	
	var OSM_MAPTYPE_ID = 'OSM';
	var mapTypeIdValue=GetMapTypeConstant(maptype);
	var centerlatlng = new google.maps.LatLng(centerlat,centerlng);
	var mapOptions = {
		zoom: zoom,
		center:centerlatlng,
		panControl: false,
		streetViewControl: false,
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		mapTypeId: mapTypeIdValue,
		overviewMapControl: true,
		overviewMapControlOptions:{opened:true},
		scaleControl: true,
		zoomControl: true,
		zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
	};
	
	var map;
	 
	map = new google.maps.Map(document.getElementById("map"),mapOptions);
	map.mapTypes.set(OSM_MAPTYPE_ID, osmMapType);
	var latlng = new google.maps.LatLng(lat,lng);
	var marker = new google.maps.Marker({position: latlng,map: map});
}
function MapEditOnMapLoad(){
	var osmOptions = {
	  getTileUrl: function(coord, zoom) {
	    return "http://a.tile.openstreetmap.org/"+zoom+"/"+coord.x+"/"+coord.y+".png";
	  },
	  tileSize: new google.maps.Size(256, 256),
	  maxZoom: 19,
	  isPng: true,
	  name: "OSM"
	};
	var osmMapType = new google.maps.ImageMapType(osmOptions);

	var lat=document.getElementById("lat").value;
	var lng=document.getElementById("lng").value;
	var zoom=parseInt(document.getElementById("zoom").value);			
	var maptype=parseInt(document.getElementById("maptype").value);
    var centerlat=document.getElementById("centerlat").value;
    var centerlng=document.getElementById("centerlng").value;
	
	var OSM_MAPTYPE_ID = 'OSM';
	var mapTypeIdValue=GetMapTypeConstant(maptype);
	var centerlatlng = new google.maps.LatLng(centerlat,centerlng);
	var mapOptions = {
		zoom: zoom,
		center:centerlatlng,
		panControl: false,
		streetViewControl: false,
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		mapTypeId: mapTypeIdValue,
		overviewMapControl: true,
		overviewMapControlOptions:{opened:true},
		scaleControl: true,
		zoomControl: true,
		zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
	};
	
	var map;
	var marker;
	map = new google.maps.Map(document.getElementById("map"),mapOptions);
	map.mapTypes.set(OSM_MAPTYPE_ID, osmMapType);

	var latlng = new google.maps.LatLng(lat,lng);
	marker = new google.maps.Marker({position:latlng, map:map, draggable:true});	
	google.maps.event.addListener(marker, 'dragend', function() {
		document.getElementById("lat").value=marker.getPosition().lat();
		document.getElementById("lng").value=marker.getPosition().lng();
		document.getElementById("zoom").value=map.getZoom();	
		document.getElementById("maptype").value=GetMapTypeNumber(map.getMapTypeId());	
		document.getElementById("centerlat").value=map.getCenter().lat();
		document.getElementById("centerlng").value=map.getCenter().lng();
	});
	
	google.maps.event.addListener(map,'click',function(event){placeMarker(event.latLng);});

	function placeMarker(location) {
		if (marker!=undefined){
			marker.setMap(null);
		}	
		marker = new google.maps.Marker({position: location, map: map,draggable: true});
		//map.setCenter(location);
		document.getElementById("lat").value=location.lat();
		document.getElementById("lng").value=location.lng();
		document.getElementById("zoom").value=map.getZoom();	
		document.getElementById("maptype").value=GetMapTypeNumber(map.getMapTypeId());	
		document.getElementById("centerlat").value=map.getCenter().lat();
		document.getElementById("centerlng").value=map.getCenter().lng();
		google.maps.event.addListener(marker, 'dragend', function() {
			document.getElementById("lat").value=marker.getPosition().lat();
			document.getElementById("lng").value=marker.getPosition().lng();
			document.getElementById("zoom").value=map.getZoom();	
			document.getElementById("maptype").value=GetMapTypeNumber(map.getMapTypeId());	
			document.getElementById("centerlat").value=map.getCenter().lat();
			document.getElementById("centerlng").value=map.getCenter().lng();
			
		});
	}		
}
function GetMapTypeNumber(maptypeid)
{
	var typenumber=-1;
	var maptypes=new Array();
	maptypes[0]='roadmap';
	maptypes[1]='satellite';
	maptypes[2]='hybrid';
	maptypes[3]='OSM';
	maptypes[4]='terrain';
  
	for (i in maptypes){
		if(maptypes[i]==maptypeid)
		typenumber=i;
	}
	return typenumber;
}
function GetMapTypeConstant(maptypenumber)
{
	var typenumber=-1;
	var maptypes=new Array();
	maptypes[0]=google.maps.MapTypeId.ROADMAP;
	maptypes[1]=google.maps.MapTypeId.SATELLITE;
	maptypes[2]=google.maps.MapTypeId.HYBRID;
	maptypes[3]='OSM';
	maptypes[4]=google.maps.MapTypeId.TERRAIN;

	if (maptypenumber==undefined){
		return maptypes[3]
	} else {
		return maptypes[maptypenumber];
	}
}
function MapViewOnMapLoad11() {
	var osmOptions = {
	  getTileUrl: function(coord, zoom) {
	    return "http://a.tile.openstreetmap.org/"+zoom+"/"+coord.x+"/"+coord.y+".png";
	  },
	  tileSize: new google.maps.Size(256, 256),
	  maxZoom: 19,
	  isPng: true,
	  name: "OSM"
	};
	var osmMapType = new google.maps.ImageMapType(osmOptions);

	var lat=document.getElementById("lat").value;
	var lng=document.getElementById("lng").value;
	var zoom=parseInt(document.getElementById("zoom").value);			
	var maptype=parseInt(document.getElementById("maptype").value);
    var centerlat=document.getElementById("centerlat").value;
    var centerlng=document.getElementById("centerlng").value;
	
	var OSM_MAPTYPE_ID = 'OSM';
	var centerlatlng = new google.maps.LatLng(centerlat,centerlng);
	var mapOptions = {
		zoom: zoom,
		center:centerlatlng,
		panControl: false,
		streetViewControl: false,
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		mapTypeId: OSM_MAPTYPE_ID,
		overviewMapControl: true,
		overviewMapControlOptions:{opened:true},
		scaleControl: true,
		zoomControl: true,
		zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
	};
	
	var map;
	 
	map = new google.maps.Map(document.getElementById("map"),mapOptions);
	map.mapTypes.set(OSM_MAPTYPE_ID, osmMapType);
	var latlng = new google.maps.LatLng(lat,lng);
	var marker = new google.maps.Marker({position: latlng,map: map});
}
function ImobilViewOnMapLoad11() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		tilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl(new GSize(180,120)));
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
	}
}
function MapViewOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		tilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl(new GSize(180,90)));
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
	}
}

function MapViewOnMapLoad2() {
	var osmOptions = {
	  getTileUrl: function(coord, zoom) {
	    return "http://a.tile.openstreetmap.org/"+zoom+"/"+coord.x+"/"+coord.y+".png";
	  },
	  tileSize: new google.maps.Size(256, 256),
	  maxZoom: 19,
	  isPng: true,
	  name: "OSM"
	};
	var osmMapType = new google.maps.ImageMapType(osmOptions);

	var lat=document.getElementById("lat").value;
	var lng=document.getElementById("lng").value;
	var zoom=parseInt(document.getElementById("zoom").value);			
	var maptype=parseInt(document.getElementById("maptype").value);
    var centerlat=document.getElementById("centerlat").value;
    var centerlng=document.getElementById("centerlng").value;
	
	var OSM_MAPTYPE_ID = 'OSM';
	var centerlatlng = new google.maps.LatLng(centerlat,centerlng);
	var mapOptions = {
		zoom: zoom,
		center:centerlatlng,
		panControl: false,
		streetViewControl: false,
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		mapTypeId: OSM_MAPTYPE_ID,
		overviewMapControl: true,
		overviewMapControlOptions:{opened:true},
		scaleControl: true,
		zoomControl: true,
		zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
	};
	
	var map;
	 
	map = new google.maps.Map(document.getElementById("map"),mapOptions);
	map.mapTypes.set(OSM_MAPTYPE_ID, osmMapType);
	var latlng = new google.maps.LatLng(lat,lng);
	var marker = new google.maps.Marker({position: latlng,map: map});
	google.maps.event.addListener(marker, 'click', function() {
    alert(map.getMapTypeId());
  });
}
function LocalitatiViewOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		tilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl(new GSize(180,90)));
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
		GSMMapViewOnMapLoad();
	}
}
function GSMMapViewOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var osmtilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		osmtilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		osmtilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var osmcustommap = new GMapType(osmtilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var moldcelltilelayers = [new GTileLayer(new GCopyrightCollection("Moldcell"),0,16)];
		moldcelltilelayers[0].getCopyright = function(a,b) {return "Moldcell"; }
		var z;
		moldcelltilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var moldcellcustommap = new GMapType(moldcelltilelayers, new GMercatorProjection(10), "Moldcell",{errorMessage:"No Data Available"});

		var orangetilelayers = [new GTileLayer(new GCopyrightCollection("Orange"),0,16)];
		orangetilelayers[0].getCopyright = function(a,b) {return "Orange"; }
		var z;
		orangetilelayers[0].getTileUrl = function (a,b) {
			//return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
			return "http://www.orange.md:8399/arcgis/rest/services/final_public/mapserver/tile/"+b+"/"+a.x+"/"+a.y+".png";
					//http://www.orange.md:8399/arcgis/rest/services/final_public/mapserver/tile/4/282/340.png
		};
		
		var orangecustommap = new GMapType(orangetilelayers, new GMercatorProjection(17), "Orange",{errorMessage:"No Data Available"});



		var map = new GMap2(document.getElementById("gsmmap"));

		map.enableContinuousZoom();
		map.addMapType(moldcellcustommap);
		map.addMapType(custommap);
		map.addMapType(osmcustommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl(new GSize(180,90)));
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
	}
}
function SmallViewOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		tilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		
		_mPreferMetric = true;
		//map.addControl(new GScaleControl());
		//map.addControl(new GOverviewMapControl);
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
	}
}
function MapEditOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		tilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap);  
		
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl);
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);		
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat), {draggable: true});
			map.addOverlay(marker);
		}

		GEvent.addListener(map, 'click', function(overlay, point) 
		{
			if (marker!=undefined){
				map.removeOverlay(marker);
			}
			marker = new GMarker(point, {draggable: true});
			map.addOverlay(marker);
			
			document.getElementById("lat").value=point.y;
			document.getElementById("lng").value=point.x;
			document.getElementById("zoom").value=map.getZoom();	
			document.getElementById("maptype").value=WizardGetCurrentMapTypeNumber(map);	
			document.getElementById("centerlat").value=map.getCenter().lat();
			document.getElementById("centerlng").value=map.getCenter().lng();
			
		});
		GEvent.addListener(marker, 'dragend', function() {

          	document.getElementById("lat").value=marker.getPoint().lat();
			document.getElementById("lng").value=marker.getPoint().lng();
			document.getElementById("zoom").value=map.getZoom();	
			document.getElementById("maptype").value=WizardGetCurrentMapTypeNumber(map);	
			document.getElementById("centerlat").value=map.getCenter().lat();
			document.getElementById("centerlng").value=map.getCenter().lng();
        });	
}
}


function WizardGetCurrentMapTypeNumber1(map)
{
  var type=-1;
  for(var ix=0;ix<map.getMapTypes().length;ix++)
  {
    if(map.getMapTypes()[ix]==map.getCurrentMapType())
      type=ix;
  }
  return type;
}  


function ImobilViewOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		tilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl(new GSize(180,120)));
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
	}
}
function MapViewOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		tilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl(new GSize(180,90)));
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
	}
}

function MapViewOnMapLoad2() {
	var osmOptions = {
	  getTileUrl: function(coord, zoom) {
	    return "http://a.tile.openstreetmap.org/"+zoom+"/"+coord.x+"/"+coord.y+".png";
	  },
	  tileSize: new google.maps.Size(256, 256),
	  maxZoom: 19,
	  isPng: true,
	  name: "OSM"
	};
	var osmMapType = new google.maps.ImageMapType(osmOptions);

	var lat=document.getElementById("lat").value;
	var lng=document.getElementById("lng").value;
	var zoom=parseInt(document.getElementById("zoom").value);			
	var maptype=parseInt(document.getElementById("maptype").value);
    var centerlat=document.getElementById("centerlat").value;
    var centerlng=document.getElementById("centerlng").value;
	
	var OSM_MAPTYPE_ID = 'OSM';
	var centerlatlng = new google.maps.LatLng(centerlat,centerlng);
	var mapOptions = {
		zoom: zoom,
		center:centerlatlng,
		panControl: false,
		streetViewControl: false,
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		mapTypeId: OSM_MAPTYPE_ID,
		overviewMapControl: true,
		overviewMapControlOptions:{opened:true},
		scaleControl: true,
		zoomControl: true,
		zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
	};
	
	var map;
	 
	map = new google.maps.Map(document.getElementById("map"),mapOptions);
	map.mapTypes.set(OSM_MAPTYPE_ID, osmMapType);
	var latlng = new google.maps.LatLng(lat,lng);
	var marker = new google.maps.Marker({position: latlng,map: map});
	google.maps.event.addListener(marker, 'click', function() {
    alert(map.getMapTypeId());
  });
}
function LocalitatiViewOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		tilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl(new GSize(180,90)));
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
		GSMMapViewOnMapLoad();
	}
}
function GSMMapViewOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var osmtilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		osmtilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		osmtilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var osmcustommap = new GMapType(osmtilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var moldcelltilelayers = [new GTileLayer(new GCopyrightCollection("Moldcell"),0,16)];
		moldcelltilelayers[0].getCopyright = function(a,b) {return "Moldcell"; }
		var z;
		moldcelltilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var moldcellcustommap = new GMapType(moldcelltilelayers, new GMercatorProjection(10), "Moldcell",{errorMessage:"No Data Available"});

		var orangetilelayers = [new GTileLayer(new GCopyrightCollection("Orange"),0,16)];
		orangetilelayers[0].getCopyright = function(a,b) {return "Orange"; }
		var z;
		orangetilelayers[0].getTileUrl = function (a,b) {
			//return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
			return "http://www.orange.md:8399/arcgis/rest/services/final_public/mapserver/tile/"+b+"/"+a.x+"/"+a.y+".png";
					//http://www.orange.md:8399/arcgis/rest/services/final_public/mapserver/tile/4/282/340.png
		};
		
		var orangecustommap = new GMapType(orangetilelayers, new GMercatorProjection(17), "Orange",{errorMessage:"No Data Available"});



		var map = new GMap2(document.getElementById("gsmmap"));

		map.enableContinuousZoom();
		map.addMapType(moldcellcustommap);
		map.addMapType(custommap);
		map.addMapType(osmcustommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		
		_mPreferMetric = true;
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl(new GSize(180,90)));
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
	}
}
function SmallViewOnMapLoad1() {
	var marker;
	if (GBrowserIsCompatible()) {

		var tilelayers = [new GTileLayer(new GCopyrightCollection("OpenStreetMap"),0,16)];
		tilelayers[0].getCopyright = function(a,b) {return "OSM"; }
		var z;
		tilelayers[0].getTileUrl = function (a,b) {
			return "http://a.tile.openstreetmap.org/"+b+"/"+a.x+"/"+a.y+".png";
		};
		
		var custommap = new GMapType(tilelayers, new GMercatorProjection(17), "OSM",{errorMessage:"No Data Available"});

		var map = new GMap2(document.getElementById("map"));

		map.enableContinuousZoom();
		map.addMapType(custommap); 

		map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		
		_mPreferMetric = true;
		//map.addControl(new GScaleControl());
		//map.addControl(new GOverviewMapControl);
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);			
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lng,lat));
			map.addOverlay(marker);
		}
}
}
