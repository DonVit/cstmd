function DirectionsMapViewOnMapLoad() {
	var start = L.latLng(
		parseFloat(document.getElementById('fromlat').value),
		parseFloat(document.getElementById('fromlng').value)
	);
	var end = L.latLng(
		parseFloat(document.getElementById('tolat').value),
		parseFloat(document.getElementById('tolng').value)
	);
	var map = L.map('map', { scrollWheelZoom: false });
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

	var bounds = L.latLngBounds(start, end);
	map.fitBounds(bounds, { padding: [36, 36], maxZoom: 13 });
	L.polyline([start, end], {
		color: '#b91c1c',
		weight: 3,
		dashArray: '7 7',
		interactive: false
	}).addTo(map);

	var earthRadius = 6371000;
	var latDelta = (end.lat - start.lat) * Math.PI / 180;
	var lngDelta = (end.lng - start.lng) * Math.PI / 180;
	var haversine = Math.sin(latDelta / 2) * Math.sin(latDelta / 2) +
		Math.cos(start.lat * Math.PI / 180) * Math.cos(end.lat * Math.PI / 180) *
		Math.sin(lngDelta / 2) * Math.sin(lngDelta / 2);
	document.getElementById('directdistance').textContent = Math.round(
		2 * earthRadius * Math.atan2(Math.sqrt(haversine), Math.sqrt(1 - haversine)) / 1000
	) + ' km';

	var route = L.Routing.control({
		waypoints: [start, end],
		router: L.Routing.osrmv1({
			serviceUrl: 'https://router.project-osrm.org/route/v1'
		}),
		show: false,
		addWaypoints: false,
		draggableWaypoints: false,
		fitSelectedRoutes: false,
		createMarker: function (index, waypoint) {
			var label = index === 0 ? 'Plecare' : 'Destinație';
			var icon = L.divIcon({
				className: 'distance-waypoint',
				html: '<span></span>',
				iconSize: [20, 20],
				iconAnchor: [10, 10]
			});
			return L.marker(waypoint.latLng, { icon: icon }).bindPopup(label);
		},
		lineOptions: {
			styles: [{ color: '#991b1b', opacity: 0.9, weight: 5 }]
		}
	}).on('routesfound', function (event) {
		var meters = event.routes[0].summary.totalDistance;
		document.getElementById('roaddistance').textContent = (meters >= 1000 ?
			(meters / 1000).toFixed(0) + ' km' : Math.round(meters) + ' m');
	}).on('routingerror', function () {
		document.getElementById('roaddistance').textContent = 'indisponibil';
	}).addTo(map);
	route.getContainer().style.display = 'none';
}

function FillLocationsDropDown(parentDropdown, childDropdown) {
	var selectedRaion = parentDropdown.options[parentDropdown.selectedIndex].value;
	var child = document.getElementById(childDropdown);
	fetch('xml.php?raion_id=' + encodeURIComponent(selectedRaion)).then(function (response) {
		if (!response.ok) {
			throw new Error('Localitățile nu au putut fi încărcate');
		}
		return response.text();
	}).then(function (xmlText) {
		var xml = new DOMParser().parseFromString(xmlText, 'application/xml');
		if (xml.getElementsByTagName('parsererror').length) {
			throw new Error('Răspuns XML invalid');
		}
		child.replaceChildren();
		Array.prototype.forEach.call(xml.getElementsByTagName('location'), function (location) {
			child.add(new Option(location.getAttribute('name'), location.getAttribute('id')));
		});
	}).catch(function (error) {
		console.error(error);
	});
}

function ShowDirections(start, end) {
	window.location.href = 'index.php?from=' + encodeURIComponent(document.getElementById(start).value) +
		'&to=' + encodeURIComponent(document.getElementById(end).value);
}