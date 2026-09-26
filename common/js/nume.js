// Leaflet Heatmap for Nume
var map, heatLayer, nid;

function initialize(id) {
  nid = id;

  // Create Leaflet map
  map = L.map('map').setView([47.0200004577636719, 28.5458335876464844], 7);

  // Add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  showdata();
}

function showdata() {
  $.ajax({
    type: "GET",
    url: "json.php?id=" + nid,
    dataType: "json",
    success: parseJson
  });
}

function parseJson(data) {
  var heatData = [];
  data.markers.forEach(function(marker) {
    heatData.push([parseFloat(marker.lat), parseFloat(marker.lng)]);
  });

  // Create heatmap layer
  heatLayer = L.heatLayer(heatData, {
    radius: 25,
    blur: 15,
    maxZoom: 10
  }).addTo(map);
}	