
/*jshint jquery:true*/
/*globals Validator, alert*/
var AddBook = (function() {
	"use strict";
	var pub = {};

	function changeFormData(id, response) {

        $(response).find("library").each(function () {

            // get the relevant information from the xml
            var foundId = $(this).find('id')[0].textContent;

            if (foundId === id) {

                var info = $(this).find("info")[0];
                
             
                // address of the library
                var address = $(info).find("address")[0].textContent;

                $("#libId").val(id);
            }
        });
    }

	/**
     * The function that is called when the xml is retrieved 
     * from the backend, this parses the XML and places each item
     * in a select list ready to be edited.
     * 
     * @param response
     */
	function parseLibraries(response) {

		 $(response).find("library").each(function () {
            
            // get the relevant information from the xml
            var id = $(this).find('id')[0].textContent;
            var info = $(this).find("info")[0];

            // address of the library
            var address = $(info).find("address")[0].textContent;

            $("#libraries").append(
                "<option value='" + id + "'>" + address + "</option>"
            );
        });

        $('#libraries').on('change', function() {

            var id = $(this).val();
            $.ajax({
                type: "GET",
                cache: false,
                url: "libraries/libs.xml",
                success: function(response) {
                    changeFormData(id, response);
                },
                error: function(response) {
                    alert('There was an error showing the page. Please reload and try again');
                }
            });
        });
	}

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

	}

	return pub;

})();

$(document).ready(AddBook.setup);