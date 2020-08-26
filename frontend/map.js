import mapStyle from './mapStyle';
import { Map, View, Feature, Overlay } from 'ol';
import { Point } from 'ol/geom';
import { Style, Icon } from 'ol/style';
import * as olProj from "ol/proj"
import olms from 'ol-mapbox-style';
import stylefunction from 'ol-mapbox-style/dist/stylefunction';
import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
import GeoJSON from 'ol/format/GeoJSON';
import applyStyle from 'ol-mapbox-style';

$(document).ready(function () {

	const $mapDiv = $('#map');

	if ($mapDiv.length) {

		/*const mapLayer = new VectorLayer({
			zIndex: 10,
			source: new VectorSource({
				format: new GeoJSON()
			}),
		});*/

		const map = new Map({
			target: 'map',
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
				$popupEl.find('span.name').text(feature.get('data').name);
				$popupEl.find('span.address').text(feature.get('data').address);
				$popupEl.find('span.moduleName').text(feature.get('data').lastModuleName);
				$popupEl.append('<img src="' + feature.get('data').markerIcon + '" class="markerIcon"/>');
				if ($popupEl.find('a.contact').length) {
					$popupEl.find('a.contact').attr('href', $popupEl.find('a.contact').data('url')+feature.get('data').orgId);
				}
				$popupEl.removeClass('d-none');
			} else {
				$popupEl.find('img.markerIcon').remove();
				$popupEl.addClass('d-none');
			}
		});

		$.ajax({
			url: '/_orgs',
			success: function (data) {
				data.forEach(function (row) {
					if (row.coordinates.lat && row.coordinates.lng) {
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
		geometry: new Point(coordinates),
		data: data,
	});
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
