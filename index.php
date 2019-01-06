
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>SOS Hot Spots</title>
	<link rel="stylesheet" type="text/css" href="lib/leaflet.css">
	<link rel="stylesheet" type="text/css" href="lib/leaflet-search.css">
	<link rel="stylesheet" type="text/css" href="lib/map.css">
	<script src="lib/leaflet.js"></script>
	<script src="lib/jquery-3.3.1.min.js"></script>
	<script src="lib/leaflet-search.js"></script>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />

</head>

<body>

	<div id="map"></div>

	<script type="text/javascript">

	 var poi=["data/police.geojson","data/firebrigade.geojson","data/hospital.geojson","data/municipality.geojson"];

	 var police_icon = L.icon({

               iconUrl: 'lib/images/police.png',
               iconSize: [32, 37],
               iconAnchor: [16, 37],
               popupAnchor: [0, -28]

          });

     var firetruck_icon = L.icon({

               iconUrl: 'lib/images/firetruck.png',
               iconSize: [32, 37],
               iconAnchor: [16, 37],
               popupAnchor: [0, -28]

          });

      var hospital_icon = L.icon({

               iconUrl: 'lib/images/hospital.png',
               iconSize: [32, 37],
               iconAnchor: [16, 37],
               popupAnchor: [0, -28]

          });

       var townhall_icon = L.icon({

               iconUrl: 'lib/images/townhall.png',
               iconSize: [32, 37],
               iconAnchor: [16, 37],
               popupAnchor: [0, -28]

          });


	 function Popup(feature,layer){

             var label=null;

             label="<b>" + feature.properties.name + "</b><br/>";

             if  (feature.properties.phone)

             label+="<b> Tel:" + feature.properties.phone + "</b><br/>";

             if  (feature.properties.fax)

             label+="<b> Fax:" + feature.properties.fax + "</b><br/>";

             if  (feature.properties.url)

             label+="<b> Portal:" + feature.properties.url + "</b><br/>";

             layer.bindPopup(label);


       }


	 var map = new L.Map('map', {zoom: 9, center: new L.latLng([37.5190, 22.4217]) });
     var mapLink =
            '<a href="http://openstreetmap.org">OpenStreetMap</a>';
        L.tileLayer(
            'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; ' + mapLink,
            maxZoom: 18,
            }).addTo(map);

        for (var i in poi){

             $.getJSON(poi[i], function(area) {

                 var geo_layer = L.geoJson(area,{
                      onEachFeature:Popup,
                      pointToLayer: function (feature, latlng) {

						 if (feature.properties.amenity=="police")

                            return L.marker(latlng, {icon: police_icon});

                         else if (feature.properties.amenity=="fire_station")

                            return L.marker(latlng, {icon: firetruck_icon});

                         else if (feature.properties.amenity=="hospital")

                            return L.marker(latlng, {icon: hospital_icon});

                         else if (feature.properties.amenity=="townhall")

                            return L.marker(latlng, {icon: townhall_icon});
                         }

                      }).addTo(map);

                var searchControl = new L.Control.Search({
					       position:'topright',
		                   layer: geo_layer,
		                   propertyName: 'name',
		                   //marker: true,
		                   moveToLocation: function(latlng, title, map) {

			                  //  var zoom = map.getBoundsZoom(latlng.layer.getBounds());

  			                  map.setView(latlng, 22); // access the zoom
		                  }
	                  });

	            searchControl.on('search:locationfound', function(e) {


				     if (e.layer._popup)

			             e.layer.openPopup();

				});

				searchControl.on('search:collapsed', function(e) {

				   map.closePopup();
				   map.setView([37.5190, 22.4217], 9,{ animation: true });

			  });

	            map.addControl(searchControl);


               });


           }

           var legend = L.control({position: 'bottomright'});

           legend.onAdd = function (map) {

           var div = L.DomUtil.create('div', ' legend'),
               grades = [1,2,3,4],
               labels=["Πυροσβεστικά Τμήματα Πελοποννήσου","Νοσοκομεία - Κέντρα Υγείας Πελοποννήσου","Δήμοι Πελοποννήσου","Αστυνομικά Τμήματα Πελοποννήσου"];

           for (var i = 0; i < grades.length; i++) {
               div.innerHTML +=
                  '<b >'+ grades[i] +'</b>' + '.&nbsp;' +
                  '<b>'+ labels[i] + '</b><br>';
           }

           return div;
         }

           legend.addTo(map);

	</script>


</body>

</html>
