// Leaflet POIs Map with Markers and Filters
var map, markerLayers = {}, photoId, photoLat, photoLng;

var markerColors = {
  'imobil': 'red',
  'chirie': 'blue',
  'photo': 'green',
  'link': 'purple'
};

function initialize(id, lat, lng) {
  photoId = id;
  photoLat = lat;
  photoLng = lng;

  // Create Leaflet map centered on photo location
  map = L.map('map').setView([photoLat, photoLng], 10);

  // OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  // Add marker for the photo location
  L.marker([photoLat, photoLng]).addTo(map)
    .bindPopup('Photo Location')
    .openPopup();

  loadPOIs();
  setupFilters();
}

function loadPOIs() {
  $.ajax({
    type: "GET",
    url: "pois.php",
    dataType: "json",
    success: function(data) {
      processPOIs(data);
      $('#filters').css('pointer-events', 'auto').css('opacity', '1');
      $('#filters input[type="checkbox"]').prop('disabled', false);
    }
  });
}

function processPOIs(pois) {
  pois.forEach(function(poi) {
    var lat = parseFloat(poi.lat);
    var lng = parseFloat(poi.lng);
    if (isNaN(lat) || isNaN(lng)) return;

    if (!markerLayers[poi.type]) {
      markerLayers[poi.type] = L.layerGroup();
    }

    var color = markerColors[poi.type] || 'blue';
    var icon = L.divIcon({
      html: '<div style="background-color: ' + color + '; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>',
      className: 'custom-marker',
      iconSize: [20, 20],
      iconAnchor: [10, 10]
    });

    var marker = L.marker([lat, lng], {icon: icon})
      .bindPopup('<b>' + poi.title + '</b><br>' + poi.description + '<br><a href="' + poi.link + '" target="_blank">View</a>');
    markerLayers[poi.type].addLayer(marker);
  });
}

function setupFilters() {
  $('#filters input[type="checkbox"]').change(function() {
    var type = this.id.replace('filter-', '');
    if (this.checked) {
      if (markerLayers[type]) {
        if (!map.hasLayer(markerLayers[type])) {
          map.addLayer(markerLayers[type]);
        }
      }
    } else {
      if (markerLayers[type]) {
        if (map.hasLayer(markerLayers[type])) {
          map.removeLayer(markerLayers[type]);
        }
      }
    }
  });
}