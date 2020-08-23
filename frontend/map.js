import mapStyle from './mapStyle';
import { Map, View, Feature } from 'ol';
import { Point } from 'ol/geom';
import { Style, Icon } from 'ol/style';
import * as olProj from "ol/proj"
import olms from 'ol-mapbox-style';
import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
import GeoJSON from 'ol/format/GeoJSON';
import applyStyle from 'ol-mapbox-style';

$(document).ready(function () {

	const $mapDiv = $('#map');

	if ($mapDiv.length) {

		const mapLayer = new VectorLayer({
			source: new VectorSource({
				format: new GeoJSON()
			}),
		});

		const map = new Map({
			target: 'map',
			view: new View({
				constrainResolution: true,
				center: olProj.fromLonLat([19.503736, 47.180086]),
				zoom: 7,
			}),
		});
		map.addLayer(mapLayer);
		applyStyle(mapLayer, mapStyle);



		const pointLayer = new VectorLayer({
			zIndex: 20,
			opacity: 1,
			style: function (feature) {
				return feature.get('style');
			},
			source: new VectorSource(),
		});
		map.addLayer(pointLayer)

		addMarker(olProj.fromLonLat([19.339750, 47.489320]), pointLayer);
	}

});

function addMarker (coordinates, layer) {

	const iconFeature = new Feature({
		geometry: new Point(coordinates),
		name: 'Árvíztűrő tükörfúrógép',
	});
	const iconStyle = new Style({
		image: new Icon({
			anchor: [0.5, 0],
			anchorOrigin: 'bottom-left',
			crossOrigin: 'anonymous',
			scale: 0.3,
			src: '/assets/img/marker_ot_1.png',
		}),
	});
	iconFeature.set('style', iconStyle);

	layer.getSource().addFeature(iconFeature);
}
