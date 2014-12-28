function initialize() {
  var mapOptions = {
    zoom: 8,
    center: new google.maps.LatLng(-34.397, 150.644)
  };
  map = new google.maps.Map(document.getElementById('map'),
      mapOptions);
}

function MapViewOnMapLoad(isFull) {
	
	isFull = typeof isFull !== 'undefined' ? isFull : false;	
	
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

	var mapOptions = {
			zoom: zoom,
			center:centerlatlng,
			panControl: false,
			streetViewControl: false,
			mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR},
			mapTypeId: mapTypeIdValue,
			overviewMapControl: true,
			overviewMapControlOptions:{opened:true},
			scaleControl: true,
			zoomControl: true,
			zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
		};	
	
	if (isFull){
		mapOptions = {
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
	}
	
	var map;
	var pmc;
	var xmldata;
	var markersArray1 = [];
	 
	map = new google.maps.Map(document.getElementById("map"),mapOptions);
	map.mapTypes.set(OSM_MAPTYPE_ID, osmMapType);

	if (!isFull){	
		var latlng = new google.maps.LatLng(lat,lng);
		var marker = new google.maps.Marker({position: latlng,map: map});
		google.maps.event.addListener(marker, 'click', function() {
	        window.location = "http://maps.casata.md/index.php?action=viewpoi&lat="+marker.getPosition().lat()+"&lng="+marker.getPosition().lng();
	 });	
	}
	var myLinkControl = new LinkControl('Adauga','add.php','Adauga un punct pe harta');
	map.controls[google.maps.ControlPosition.TOP_CENTER].push(myLinkControl);
	
	var newsCheckBoxDiv = document.createElement('DIV');
	var newsCheckBox = new CheckBoxControl(newsCheckBoxDiv,'Stiri',isFull,'yellow','Afiseaza stiri');	
	
	var imobilCheckBoxDiv = document.createElement('DIV');
	var imobilCheckBox = new CheckBoxControl(imobilCheckBoxDiv,'Imobil',isFull,'red','Afiseaza ofertele imobiliare');
	
	var chirieCheckBoxDiv = document.createElement('DIV');	
	var chirieCheckBox = new CheckBoxControl(chirieCheckBoxDiv,'Chirie',isFull,'green','Afiseaza ofertele de chirii');

	var photoCheckBoxDiv = document.createElement('DIV');	
	var photoCheckBox = new CheckBoxControl(photoCheckBoxDiv,'Foto',isFull,'blue','Afiseaza imagini');
	

	map.controls[google.maps.ControlPosition.RIGHT_TOP].push(newsCheckBoxDiv);
	map.controls[google.maps.ControlPosition.RIGHT_TOP].push(imobilCheckBoxDiv);
	map.controls[google.maps.ControlPosition.RIGHT_TOP].push(chirieCheckBoxDiv);
	map.controls[google.maps.ControlPosition.RIGHT_TOP].push(photoCheckBoxDiv);
	
	google.maps.event.addDomListener(newsCheckBoxDiv, 'click', function() {
		showdata();
	})
	google.maps.event.addDomListener(imobilCheckBoxDiv, 'click', function() {
		showdata();
	})	
	google.maps.event.addDomListener(chirieCheckBoxDiv, 'click', function() {
		showdata();
	})
	google.maps.event.addDomListener(photoCheckBoxDiv, 'click', function() {
		showdata();
	})

	if (isFull){
		showdata();
	}	
	
	function showdata(){
		if (typeof xmldata == 'undefined'){
			$(document).ready(function(){
			  $.ajax({
			    type: "GET",
			    url: "xml.php",
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

		deleteOverlays();

		$(xmldata).find("marker").each(function(){
			lat=$(this).attr("lat");
			lng=$(this).attr("lng");
			var title=$(this).attr("title");
			var description=$(this).attr("description");
			var link=$(this).attr("link");
			var content='<strong>'+title+'</strong><br/>'+description+'<br/><a href="'+link+'">'+link+'</a>';
			var type=$(this).attr("type");
			var Latlng = new google.maps.LatLng(lat,lng);
			//alert(newsCheckBox.getCheckedState());
			if ((type=='news')&&(newsCheckBox.getCheckedState())){
				var mi=new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_yellow.png');
				addMarker(mi,Latlng,content);
			}
			if ((type=='imobil')&&(imobilCheckBox.getCheckedState())){
				var mi=new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_red.png');
				addMarker(mi,Latlng,content);
			}
			if ((type=='chirie')&&(chirieCheckBox.getCheckedState())){
				var mi=new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_green.png');
				addMarker(mi,Latlng,content);
			}
			if ((type=='link')&&(photoCheckBox.getCheckedState())){
				var mi=new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_blue.png');
				addMarker(mi,Latlng,content);
			}
			if ((type=='photo')&&(photoCheckBox.getCheckedState())){
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
	  
	  markersArray1.push(marker);
	}
	// Deletes all markers in the array by removing references to them
	function deleteOverlays() {
	  if (markersArray1.length>0) {
	    for (i in markersArray1) {
	      markersArray1[i].setMap(null);
	    }
	    markersArray1.length = 0;
	  }
	}	
}
function MapViewOnMapLoad1() {
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
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR},
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
	google.maps.event.addListener(marker, 'click', function() {
        window.location = "http://maps.casata.md/index.php?action=viewpoi&lat="+marker.getPosition().lat()+"&lng="+marker.getPosition().lng();
 });	
}
function MapViewPoiOnMapLoad() {
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

	var latlng = new google.maps.LatLng(lat,lng);
	
    
	var OSM_MAPTYPE_ID = 'OSM';
	//var mapTypeIdValue=GetMapTypeConstant(maptype);
	var centerlatlng = new google.maps.LatLng(centerlat,centerlng);
	var OSMMapOptions = {
		zoom: zoom,
		center:centerlatlng,
		panControl: false,
		streetViewControl: false,
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR},
		mapTypeId: 'OSM',
		overviewMapControl: true,
		overviewMapControlOptions:{opened:true},
		scaleControl: true,
		zoomControl: true,
		zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
	};
	var osmmap;
	osmmap = new google.maps.Map(document.getElementById("osmmap"),OSMMapOptions);	
	osmmap.mapTypes.set(OSM_MAPTYPE_ID, osmMapType);
	var marker = new google.maps.Marker({position: latlng});
	marker.setMap(osmmap);
	
	var RoadMapOptions = {
			zoom: zoom,
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

	var roadmap; 
	roadmap = new google.maps.Map(document.getElementById("roadmap"),RoadMapOptions);	
	roadmap.setMapTypeId(google.maps.MapTypeId.ROADMAP);
	var marker = new google.maps.Marker({position: latlng});
	marker.setMap(roadmap);

	var SatelliteMapOptions = {
			zoom: zoom,
			center:centerlatlng,
			panControl: false,
			streetViewControl: false,
			mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR},
			mapTypeId: google.maps.MapTypeId.HYBRID,
			overviewMapControl: true,
			overviewMapControlOptions:{opened:true},
			scaleControl: true,
			zoomControl: true,
			zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
		};	

	var satellitemap; 
	satellitemap = new google.maps.Map(document.getElementById("satellitemap"),SatelliteMapOptions);	
	satellitemap.setMapTypeId(google.maps.MapTypeId.HYBRID);
	var marker = new google.maps.Marker({position: latlng});
	marker.setMap(satellitemap);	

	var TerrainMapOptions = {
			zoom: zoom,
			center:centerlatlng,
			panControl: false,
			streetViewControl: false,
			mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR},
			mapTypeId: google.maps.MapTypeId.TERRAIN,			
			overviewMapControl: true,
			overviewMapControlOptions:{opened:true},
			scaleControl: true,
			zoomControl: true,
			zoomControlOptions: {style:google.maps.ZoomControlStyle.LARGE}
		};	

	var terrainmap; 
	terrainmap = new google.maps.Map(document.getElementById("terrainmap"),TerrainMapOptions);	
	terrainmap.setMapTypeId(google.maps.MapTypeId.TERRAIN);
	var marker = new google.maps.Marker({position: latlng});
	marker.setMap(terrainmap);	
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
		mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.ROADMAP,google.maps.MapTypeId.SATELLITE,google.maps.MapTypeId.TERRAIN,OSM_MAPTYPE_ID],style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR},
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
function GetMapTypeNumber(maptypeid){
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
function GetMapTypeConstant(maptypenumber){
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