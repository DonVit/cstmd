(function () {
	function value(id, fallback) {
		var element = document.getElementById(id);
		return element && element.value !== '' ? element.value : fallback;
	}

	function setValue(id, nextValue) {
		var element = document.getElementById(id);
		if (element) {
			element.value = nextValue;
		}
	}

	function markerIcon(color, size) {
		var inset = Math.max(2, Math.round(size * 0.1));
		var diameter = size - inset * 2;
		return L.divIcon({
			className: 'maps-marker-icon',
			html: '<span style="display:block;width:' + diameter + 'px;height:' + diameter + 'px;margin:' + inset + 'px;box-sizing:border-box;border:2px solid #fff;border-radius:50%;background:' + color + ';box-shadow:0 0 0 1px #263238,0 2px 5px rgba(0,0,0,.7)"></span>',
			iconSize: [size, size],
			iconAnchor: [size / 2, size / 2]
		});
	}

	function createMap(options) {
		var element = document.getElementById(options.element || 'map');
		if (!element || !window.L) {
			return;
		}

		var latitude = parseFloat(value('centerlat', '47.0105')) || 47.0105;
		var longitude = parseFloat(value('centerlng', '28.8638')) || 28.8638;
		var zoom = parseInt(value('zoom', '8'), 10) || 8;
		var map = L.map(element).setView([latitude, longitude], zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);

		if (options.editable) {
			var savedLatitude = parseFloat(value('lat', '0'));
			var savedLongitude = parseFloat(value('lng', '0'));
			var marker = null;
			function savePosition(position) {
				position = L.latLng(position);
				setValue('lat', position.lat.toFixed(7));
				setValue('lng', position.lng.toFixed(7));
			}
			function placeMarker(position) {
				if (!marker) {
					marker = L.marker(position, { draggable: true, icon: markerIcon('#3479ad', 22) }).addTo(map);
					marker.on('dragend', function (event) {
						savePosition(event.target.getLatLng());
					});
				} else {
					marker.setLatLng(position);
				}
				savePosition(position);
			}
			if (Number.isFinite(savedLatitude) && Number.isFinite(savedLongitude) && (savedLatitude !== 0 || savedLongitude !== 0)) {
				placeMarker([savedLatitude, savedLongitude]);
			}
			map.on('click', function (event) {
				placeMarker(event.latlng);
			});
			map.on('moveend', function () {
				setValue('centerlat', map.getCenter().lat.toFixed(7));
				setValue('centerlng', map.getCenter().lng.toFixed(7));
				setValue('zoom', map.getZoom());
				setValue('maptype', 3);
			});
			return map;
		}

		var pointLatitude = parseFloat(value('lat', ''));
		var pointLongitude = parseFloat(value('lng', ''));
		if (!options.full && Number.isFinite(pointLatitude) && Number.isFinite(pointLongitude)) {
			var point = L.marker([pointLatitude, pointLongitude], {
				icon: markerIcon('#3479ad', 22)
			}).addTo(map);
			if (options.poiLink) {
				point.on('click', function () {
					window.location.href = 'index.php?action=viewpoi&lat=' + encodeURIComponent(point.getLatLng().lat) + '&lng=' + encodeURIComponent(point.getLatLng().lng);
				});
			}
		}

		if (options.full) {
			addMapControls(map);
			loadMarkers(map);
		}
		return map;
	}

	function addMapControls(map) {
		map.getContainer().classList.add('maps-has-centered-add');
		var addControl = L.control({ position: 'topleft' });
		addControl.onAdd = function () {
			var link = L.DomUtil.create('a', 'leaflet-bar maps-add-control');
			link.href = 'add.php';
			link.title = 'Adauga un punct pe harta';
			link.textContent = 'Adaugă punct';
			link.setAttribute('aria-label', 'Adaugă un punct pe hartă');
			link.style.cssText = 'display:flex;align-items:center;justify-content:center;min-height:38px;padding:0 12px;border:1px solid #334155;border-radius:4px;background:#fff;color:#1f2937;font-size:14px;font-weight:700;line-height:1.2;text-decoration:none;box-shadow:0 2px 7px rgba(15,23,42,.3);white-space:nowrap;';
			return link;
		};
		addControl.addTo(map);

		var categories = [
			{ type: 'news', label: 'Stiri', color: '#d9a300' },
			{ type: 'imobil', label: 'Imobil', color: '#c83f49' },
			{ type: 'chirie', label: 'Chirie', color: '#36875a' },
			{ type: 'photo', label: 'Foto', color: '#3479ad' },
			{ type: 'link', label: 'Hartă', color: '#3479ad' }
		];
		var filterControl = L.control({ position: 'topright' });
		filterControl.onAdd = function () {
			var container = L.DomUtil.create('div', 'leaflet-control-layers maps-filter-control');
			categories.forEach(function (category) {
				var label = document.createElement('label');
				var checkbox = document.createElement('input');
				checkbox.type = 'checkbox';
				checkbox.checked = true;
				checkbox.dataset.type = category.type;
				var swatch = document.createElement('span');
				swatch.className = 'maps-marker-swatch';
				swatch.style.backgroundColor = category.color;
				label.appendChild(checkbox);
				label.appendChild(swatch);
				label.appendChild(document.createTextNode(category.label));
				container.appendChild(label);
			});
			L.DomEvent.disableClickPropagation(container);
			return container;
		};
		filterControl.addTo(map);
	}

	function loadMarkers(map) {
		var layers = {};
		var colors = { news: '#d9a300', imobil: '#c83f49', chirie: '#36875a', photo: '#3479ad', link: '#3479ad' };
		function clusterIcon(cluster) {
			var count = cluster.getChildCount();
			var size = count < 10 ? 34 : (count < 100 ? 40 : 46);
			return L.divIcon({
				className: 'maps-marker-cluster',
				html: '<span style="display:flex;width:' + size + 'px;height:' + size + 'px;align-items:center;justify-content:center;box-sizing:border-box;border:2px solid #fff;border-radius:50%;background:#475569;box-shadow:0 1px 5px rgba(0,0,0,.45);color:#fff;font-size:12px;font-weight:bold">' + count + '</span>',
				iconSize: [size, size]
			});
		}
		fetch('json.php').then(function (response) {
			if (!response.ok) {
				throw new Error('Map markers could not be loaded');
			}
			return response.json();
		}).then(function (markers) {
			if (!Array.isArray(markers)) {
				throw new Error('Map marker data is not a JSON array');
			}
			markers.forEach(function (item) {
				var latitude = parseFloat(item[0]);
				var longitude = parseFloat(item[1]);
				var type = item[2];
				if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
					return;
				}
				var marker = L.marker([latitude, longitude], { icon: markerIcon(colors[type] || '#3479ad', 16) });
				var popup = document.createElement('div');
				var title = document.createElement('strong');
				title.textContent = item[3] || '';
				popup.appendChild(title);
				var description = document.createElement('p');
				description.textContent = item[4] || '';
				popup.appendChild(description);
				var link = document.createElement('a');
				link.href = item[5] || '#';
				link.textContent = 'Vezi detalii';
				popup.appendChild(link);
				marker.bindPopup(popup);
				(layers[type] = layers[type] || L.markerClusterGroup({
					showCoverageOnHover: false,
					spiderfyOnMaxZoom: true,
					maxClusterRadius: 40,
					iconCreateFunction: clusterIcon
				})).addLayer(marker);
			});

			Object.keys(layers).forEach(function (type) {
				layers[type].addTo(map);
			});
			var checkboxes = document.querySelectorAll('.maps-filter-control input[data-type]');
			Array.prototype.forEach.call(checkboxes, function (checkbox) {
				checkbox.addEventListener('change', function () {
					var layer = layers[checkbox.dataset.type];
					if (!layer) {
						return;
					}
					if (checkbox.checked) {
						layer.addTo(map);
					} else {
						map.removeLayer(layer);
					}
				});
			});
		}).catch(function (error) {
			console.error(error);
		});
	}

	window.MapsLeaflet = {
		initMain: function (full) { return createMap({ full: full }); },
		initView: function () { return createMap({ poiLink: true }); },
		initPoi: function () { return createMap({}); },
		initEdit: function () { return createMap({ editable: true }); }
	};
}());