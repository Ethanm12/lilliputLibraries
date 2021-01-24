
/*jshint jquery:true*/
/*globals L, alert*/
var MapViewer = (function() {
	"use strict";

	var pub = {};

	function initMap() {
		
		// initialise the map
		var mymap = L.map("map").setView([-45.86661, 170.51699], 13);

		// override the default location to provide support for my directory structure
		L.Icon.Default.imagePath = "assets/images/";

		// add the overlay to the map 
		L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
	    	maxZoom: 18,
	        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
	            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
	            'Imagery © <a href="http://mapbox.com">Mapbox</a>',
	        id: 'mapbox.streets'
        }).addTo(mymap);

		// return the map so we can use it later
        return mymap;
	}

    /**
	 * Converts the given xml to geoJSON
	 * 
     * @param libraries
     * @returns {{type: string}}
     */
	function xmlToGeoJson(libraries) {
		var json = {
			"type": "FeatureCollection",
			"features": []
        };

        $(libraries).find("library").each(function () {
        	
            // get the relevant information from the xml
			var id = $(this).find('id')[0].textContent;
            var info = $(this).find("info")[0];

            // get the lat and long
            var lat = $(info).find("lat")[0].textContent;
            var lng = $(info).find("lng")[0].textContent;
            
            // address of the library
			var address = $(info).find("address")[0].textContent;
			
			// capacity of library
            var capacity = $(info).find("capacity")[0].textContent;
            
            // loop through tbe books, if any
			var books = $(this).find("books")[0];
			var listBooks = [];
			var numBooks = 0;
			
			// loop through the books and add them to geoJSON.
			// I had to do this to be able to show the books in the popup...
            $(books).find("book").each(function (index, book) {
            	
            	var currentBook = $(book);
				listBooks.push({
					"title": currentBook.find("title")[0].textContent,
					"author": currentBook.find("author")[0].textContent,
					"year": currentBook.find("year")[0].textContent
				});
            	numBooks ++;
			});

            addToTable(address, numBooks, capacity);
			
            json.features.push({
				"type": "Feature",
				"geometry": {
					"type": "Point",
					"coordinates": [
						lng,
                        lat
					]
				},
				"properties": {
					"id": id,
					"description": "Little Library",
					"name": address,
					"capacity": capacity,
					"numBooks": numBooks,
					"books": listBooks
				}
			});
            
        });
        
        return json;
	}

    /**
	 * Callback used for adding a popup window to each feature.
	 * 
     * @param feature
     * @param layer
     */
	function displayLibraryInfo(feature, layer) {
        var popupContent = "<p class='feature-title'><b>" + feature.properties.description + " - " + feature.properties.name + "</b></p>";
        
        // construct the top of the table
        var table = "<table><thead><th>Title</th><th>Author</th><th>Year</th></thead><tbody>";
		
        // the amount of books 
        var bookCount = 0;
        
        $(feature.properties.books).each(function(index, book) {
        	table += "<tr><td>" + book.title + "</td><td>" + book.author + "</td><td>" + book.year + "</td></tr>";
        	bookCount++;
		});
        
        if (bookCount === 0) {
        	table += "<tr><td colspan='3'>There are no books to display</td></tr>";
		}
        
        table += "</tbody></table>";

		popupContent += table;
        layer.bindPopup(popupContent);
    }

	/**
	* Parses the given libraries and displays the map with the markers.
	*/
	function parseLibraries(libraries) {
		
		var geoJson = xmlToGeoJson(libraries);
		
		var map = initMap();

        var popup = L.popup();

        function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent("<pre>" + JSON.stringify(geoJson, null, 2) +"</pre>")
                .openOn(map);
        }
        
        map.on('click', onMapClick);

        L.geoJSON(geoJson, {
            onEachFeature: displayLibraryInfo
        }).addTo(map);
	}
	
	function addToTable(address, numbooks, capacity) {
		var table = $("#libraries-tbody");
		
		table.append("<tr><td>" + address + "</td><td>" + numbooks + "</td><td>" + capacity + "</td></tr>");
	}

 	/**
 	* Called when the home page is loaded.
	*/
	pub.setup = function() {
		
		$.ajax({
			type: "GET",
			cache: false,
			url: "libraries/libs.xml",
			success: function(response) {
				parseLibraries(response);
			},
			error: function(response) {
				alert('There was an error showing the page. Please reload and try again');
			}
		});
	};

	return pub;
}());
 
// initialise the MapViewer module
$(document).ready(MapViewer.setup);