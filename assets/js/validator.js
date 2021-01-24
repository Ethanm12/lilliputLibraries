
/*jshint jquery:true*/
var Validator = (function() {
    "use strict";
    var pub = {};

    /**
     * Computes the difference in distance for a given set of coordinates.
     * Adapted from https://stackoverflow.com/a/21623206/4935317
     * 
     * @param lat1
     * @param lon1
     * @param lat2
     * @param lon2
     * @returns {number}
     */
    function distance(lat1, lon1, lat2, lon2) {
        var p = 0.017453292519943295;
        var c = Math.cos;
        var a = 0.5 - c((lat2 - lat1) * p)/2 +
            c(lat1 * p) * c(lat2 * p) *
            (1 - c((lon2 - lon1) * p))/2;

        return 12742 * Math.asin(Math.sqrt(a));
    }
    
    /**
     * Validates the given lat and lng to see if it is 
     * within 50 km to Dunedin's Octogan.
     * 
     * @param lat
     * @param lng
     */
    function validateLocation(lat, lng) {
        return distance(-45.866891, 170.518202, lat, lng) > 50;
    }

    /**
     * Validate the edit library form.
     * 
     * @param messages
     */
    function validateEdit(messages) {
        
        var selectedId = $("#libraries").val();
        var capacity = $("#libCapacity").val();
        var numBooks = $("#numBooks").val();
        var lat = $("#lat").val();
        var lng = $("#lng").val();
        
        if (selectedId === null || selectedId === undefined) {
            messages.push("Please select a library");
        } else {
            if ( capacity > 0 === false ) {
                messages.push("Please enter a capacity larger than zero");
            } else if (parseInt(numBooks) > parseInt(capacity)) {
                messages.push("The number of books must not be larger than the capacity of the library");
            }   
            
            if (validateLocation(lat, lng)) {
                messages.push("Please enter a location within 50 km of the Octogan");
            }
        }
    }

    /**
     * Setup the validator module.
     */
    pub.setup = function(form) {
        // define the local variables to use
        var messages, errorHTML;
        var successHTML = "";
        
        messages = [];
        
        // validate the edit library request
        validateEdit(messages);

        // check the status of the validation
        if (messages.length === 0) {

            // clear the residual errors html
            $("#errors").html("").hide();
            
            return true;
            
        } else {

            // clear the residual success html
            $("#success").html("").hide();

            // build the errorHTML variable up 
            errorHTML = "<p><strong>There were errors processing your form</strong></p>";
            errorHTML += "<ul>";
            $(messages).each(function (index, msg) {
                errorHTML += "<li>" + msg;
            });
            errorHTML += "</ul>";
            
            // add the error html to the page
            $("#errors").html(errorHTML).css({
                display: "block"
            });
        }
    };
    
    return pub;
}());