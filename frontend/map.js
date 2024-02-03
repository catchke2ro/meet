import mapStyle from './mapStyle';
import { Feature, Map, Overlay, View } from 'ol';
import { defaults } from 'ol/interaction';
import { Point } from 'ol/geom';
import { Icon, Style } from 'ol/style';
import * as olProj from "ol/proj"
import applyStyle from 'ol-mapbox-style';
import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';

$(document).ready(function () {

	const $mapDiv = $('#map');

	if ($mapDiv.length) {

		const map = new Map({
			target: 'map',
			interactions: defaults({
				mouseWheelZoom: false,
			}),
			view: new View({
				constrainResolution: true,
				center: olProj.fromLonLat([19.503736, 47.180086]),
				zoom: 7,
			}),
		});
		applyStyle(map, mapStyle);

		const pointLayer = new VectorLayer({
			zIndex: 20,
			opacity: 1,
			style: function (feature) {
				return feature.get('style');
			},
			source: new VectorSource(),
		});
		map.addLayer(pointLayer)

		//addMarker(olProj.fromLonLat([19.339750, 47.489320]), pointLayer);

		const $popupElement = $('#mapPopup');
		$popupElement.addClass('d-none');

		const popup = new Overlay({
			zIndex: 15,
			element: $popupElement[0],
			positioning: 'top-left',
			stopEvent: false,
			offset: [0, -41],
		});
		map.addOverlay(popup);

		map.on('click', function (evt) {
			const feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {
				return feature;
			});
			const $popupEl = $(popup.getElement());
			if (feature) {
				const coordinates = feature.getGeometry().getCoordinates();
				popup.setPosition(coordinates);
				console.log(feature);
				if (typeof feature.getProperties().name !== 'undefined') {
					$popupEl.find('span.name').text(feature.getProperties().name);
				}
				if (typeof feature.getProperties().address !== 'undefined') {
					$popupEl.find('span.address').text(feature.getProperties().address);
				}
				if (typeof feature.getProperties().lastModuleName !== 'undefined') {
					$popupEl.find('span.moduleName').text(feature.getProperties().lastModuleName);
				}
				if (typeof feature.getProperties().markerIcon !== 'undefined') {
					$popupEl.append('<img src="' + feature.getProperties().markerIcon + '" class="markerIcon"/>');
				}
				if (typeof feature.getProperties().orgId !== 'undefined' && $popupEl.find('span.contact').length) {
					$popupEl.find('span.contact').removeClass('d-none');
					$popupEl.find('span.contact a').attr('href', $popupEl.find('.contact a').data('url') + feature.getProperties().orgId);
				} else {
					$popupEl.find('span.contact').addClass('d-none');
				}
				$popupEl.removeClass('d-none');
			} else {
				$popupEl.find('img.markerIcon').remove();
				$popupEl.addClass('d-none');
			}
		});

		if ($mapDiv.data('default-poi')) {
			const defaultPoiData = $mapDiv.data('default-poi');
			addMarker(olProj.fromLonLat([
				defaultPoiData.lng,
				defaultPoiData.lat,
			]), defaultPoiData, pointLayer)
		}

		$.ajax({
			url: '/_orgs',
			success: function (data) {
				data.forEach(function (row) {
					if (row.coordinates && row.coordinates.lat && row.coordinates.lng) {
						addMarker(olProj.fromLonLat([
							row.coordinates.lng,
							row.coordinates.lat,
						]), row, pointLayer)
					}
				});
			},
		});
	}

});

function addMarker (coordinates, data, layer) {
	const iconFeature = new Feature({
		geometry: new Point(coordinates)
	});
	iconFeature.setProperties(data);
	const iconStyle = new Style({
		zIndex: 20,
		image: new Icon({
			anchor: [0.5, 0],
			anchorOrigin: 'bottom-left',
			crossOrigin: 'anonymous',
			scale: 0.3,
			src: data.markerIcon,
		}),
	});
	iconFeature.set('style', iconStyle);

	layer.getSource().addFeature(iconFeature);
}
