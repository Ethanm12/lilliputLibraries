

/*jshint jquery:true*/
/*globals Validator, alert*/
var EditLibraries = (function() {
    "use strict";
    var pub = {};
    
    function changeFormData(id, response) {

        $(response).find("library").each(function () {

            // get the relevant information from the xml
            var foundId = $(this).find('id')[0].textContent;

            if (foundId === id) {

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
                var numBooks = 0;
                
                $("#libCapacity").val(capacity);
                $("#lat").val(lat);
                $("#lng").val(lng);
                $("#address").val(address);
                $("#libId").val(id);

                var booksHTML = "<h5>Books</h5><hr>";
                $(books).find("book").each(function (index, book) {

                    // get the current book in iteration
                    var currentBook = $(book);
                    
                    // add the title to the html string
                    booksHTML += 
                        '<div class="group">' + 
                            '<label>Title</label>' + 
                            '<input name="books[' + numBooks +'][title]" style="margin-left: 4px;" value="' + currentBook.find("title")[0].textContent + '">' + 
                        '</div>';

                    // add the author to the html string
                    booksHTML +=
                        '<div class="group">' +
                        '<label>Author</label>' +
                        '<input name="books[' + numBooks + '][author]" style="margin-left: 4px;" value="' + currentBook.find("author")[0].textContent + '">' +
                        '</div>';

                    // add the year to the html string
                    booksHTML +=
                        '<div class="group">' +
                        '<label>Year</label>' +
                        '<input name="books[' + numBooks +'][year]" style="margin-left: 4px;" value="' + currentBook.find("year")[0].textContent + '">' +
                        '</div>';
                        
                    // add a small separator for visibility
                    booksHTML += "<hr>";
                    numBooks ++;
                });

                // set the numBooks value on the form 
                $("#numBooks").val(numBooks);

                // only show books if we have some to show
                if (numBooks !== 0) {
                    $("#books").html(booksHTML);
                }
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

            // addToForm(address, numBooks, capacity, id);
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
    
    /**
     * Called when the page is loaded.
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

$(document).ready(EditLibraries.setup);