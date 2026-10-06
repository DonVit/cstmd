(function () {
	function mapCenter() {
		return [
			parseFloat(document.getElementById('centerlat').value) || 47.0105,
			parseFloat(document.getElementById('centerlng').value) || 28.8638
		];
	}

	function createMap(container, center, zoom) {
		var map = L.map(container).setView(center, zoom);
		map.getContainer().style.cursor = 'default';
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		return map;
	}

	function stationIcon(color, size) {
		size = size || 26;
		var inset = Math.round(size * 0.14);
		var centerSize = Math.round(size * 0.25);
		return L.divIcon({
			className: 'election-marker-icon',
			html: '<span style="display:flex;width:' + (size - inset * 2) + 'px;height:' + (size - inset * 2) + 'px;margin:' + inset + 'px;align-items:center;justify-content:center;box-sizing:border-box;border:3px solid #fff;border-radius:50%;background:' + color + ';box-shadow:0 0 0 2px #202020,0 2px 8px rgba(0,0,0,.75)"><span style="display:block;width:' + centerSize + 'px;height:' + centerSize + 'px;border-radius:50%;background:#fff;box-shadow:0 0 2px #202020"></span></span>',
			iconSize: [size, size],
			iconAnchor: [size / 2, size / 2]
		});
	}

	function addLegend(map) {
		var legend = L.control({ position: 'bottomright' });
		legend.onAdd = function () {
			var element = L.DomUtil.create('div', 'election-map-legend');
			[
				['#c83f49', 'Candidat 8'],
				['#d9a300', 'Candidat 7']
			].forEach(function (item) {
				var row = document.createElement('div');
				var swatch = document.createElement('span');
				swatch.style.backgroundColor = item[0];
				row.appendChild(swatch);
				row.appendChild(document.createTextNode(item[1]));
				element.appendChild(row);
			});
			return element;
		};
		legend.addTo(map);
	}

	function loadStations(map) {
		var stationLayer = L.markerClusterGroup({
			showCoverageOnHover: false,
			spiderfyOnMaxZoom: true,
			maxClusterRadius: 40,
			iconCreateFunction: function (cluster) {
				var count = cluster.getChildCount();
				var size = count < 10 ? 'small' : (count < 100 ? 'medium' : 'large');
				var background = size === 'small' ? '#a43a3a' : (size === 'medium' ? '#b86b16' : '#62482f');
				return L.divIcon({
					className: 'election-marker-cluster election-marker-cluster-' + size,
					html: '<span style="display:flex;width:38px;height:38px;align-items:center;justify-content:center;box-sizing:border-box;border:2px solid #fff;border-radius:50%;background:' + background + ';box-shadow:0 1px 5px rgba(0,0,0,.45);color:#fff;font-size:12px;font-weight:bold">' + count + '</span>',
					iconSize: [38, 38]
				});
			}
		});
		fetch('json.php').then(function (response) {
			if (!response.ok) {
				throw new Error('Secțiile de votare nu au putut fi încărcate');
			}
			return response.json();
		}).then(function (stations) {
			if (!Array.isArray(stations)) {
				throw new Error('Datele secțiilor de votare nu sunt valide');
			}
			stations.forEach(function (station) {
				var lat = parseFloat(station.lat);
				var lng = parseFloat(station.lng);
				if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
					return;
				}
				var color = station.type === 'red' ? '#c83f49' : '#d9a300';
				var marker = L.marker([lat, lng], {
					icon: stationIcon(color),
					title: station.title || 'Secție de votare',
					alt: station.title || 'Secție de votare'
				});
				marker.on('click', function () {
					if (station.link) {
						window.location.href = station.link;
					}
				});
				marker.addTo(stationLayer);
			});
			map.addLayer(stationLayer);
		}).catch(function (error) {
			console.error(error);
		});
	}

	window.AlegeriLeaflet = {
		initOverview: function () {
			var map = createMap('map', mapCenter(), parseInt(document.getElementById('zoom').value, 10) || 7);
			addLegend(map);
			loadStations(map);
			return map;
		},
		initStation: function () {
			var element = document.getElementById('map');
			var center = [parseFloat(element.dataset.lat), parseFloat(element.dataset.lng)];
			var map = createMap(element, center, 15);
			L.marker(center, { icon: stationIcon('#c83f49', 40) }).addTo(map);
			return map;
		},
		initSaved: function () {
			var center = mapCenter();
			var map = createMap('map', center, parseInt(document.getElementById('zoom').value, 10) || 12);
			var lat = parseFloat(document.getElementById('lat').value);
			var lng = parseFloat(document.getElementById('lng').value);
			if (Number.isFinite(lat) && Number.isFinite(lng) && (lat !== 0 || lng !== 0)) {
				L.marker([lat, lng], { icon: stationIcon('#c83f49') }).addTo(map);
			}
			return map;
		},
		initPoi: function () {
			var center = mapCenter();
			var map = createMap('map', center, parseInt(document.getElementById('zoom').value, 10) || 16);
			L.marker(center, { icon: stationIcon('#c83f49') }).addTo(map);
			return map;
		}
	};
}());