<?php

// require the bootstrapping script
require 'core/init.php';

if (isset($_POST['submit'])) {

    // set up empty array, to hold validation error messages if there are any
    $messages = [];

    // get the incoming values from the user
    // escape each value as they come in

    $libAddress     = e($_POST['editAddress']);
    $libLat         = e($_POST['editLat']);
    $libLng         = e($_POST['editLng']);
    $libCapacity    = e($_POST['editCapacity']);
    $incomingBooks  = $_POST['books'];
    $libId          = $_POST['libId'];

     // validate the id first
    if (isEmpty($libId) && isEmpty($libAddress))
    {
        $messages[] = "Please select a library";
    } else {

        // validate each field
        if (isEmpty($libAddress)) {
            $messages[] = "Please enter an address for the library";
        } else {
            addToSession('editAddress', $libAddress);
        }

        if (isEmpty($libLat)) {
            $messages[] = "Please enter a latitude value for the library";
        } else {
            addToSession('editLat', $libLat);
        }

        if (isEmpty($libLng)) {
            $messages[] = "Please enter a longitude value for the library";
        } else {
            addToSession('editLng', $libLng);
        }

        if (isEmpty($libCapacity)) {
            $messages[] = "Please enter the new libraries capacity";
        } else {
            addToSession('editCapacity', $libCapacity);
        }

         // validate lat and lng
        if (! validateDistanceToOctogan($libLat, $libLng)) {
            $messages[] = "Please ensure the distance to the Octogan is within a radius of 50km";
        }

        // check if we have passed validation
        if (count($messages) == 0) {

            $xml = simplexml_load_file("libraries/libs.xml");

            foreach ($xml->xpath('//library[id="' . $libId . '"]') as $desc) {
                $desc->info->address    = $libAddress;
                $desc->info->lat        = $libLat;
                $desc->info->lng        = $libLng;
                $desc->info->capacity   = $libCapacity;
            }

            // remove all books then add the incoming books..
            // I had to do it this way because the XML provided does not include a valid unique identifier
            // if there was an id field I could just edit the book by id
            $books = $xml->xpath('//library[id="' . $libId . '"]/books'); 
            
            // remove the books 
            foreach ($books as $book) {
                $reference = dom_import_simplexml($book);
                $reference->parentNode->removeChild($reference);
            }
            
            // then, add the "edited books" back from the form

            $books = $xml->xpath('//library[id="' . $libId . '"]')[0];

            $book = $books->addChild('books');

            foreach ($incomingBooks as $incomingBook) {
                $theBook = $book->addChild('book');
                $theBook->addChild('title', $incomingBook['title']);
                $theBook->addChild('author', $incomingBook['author']);
                $theBook->addChild('year', $incomingBook['year']);                
            }

            $xml->saveXML("libraries/libs.xml");

            header("Location: edit-library.php?success");
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lilliput Locator</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/leaflet.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
<header>
    <div class="container">
        <img src="assets/images/lilliput-locator.png" alt="Lilliput Logo">
        <a class="nav-btn" href="index.php">Home</a>
        <a class="nav-btn active" href="admin.php">Administration</a>
    </div>
</header>

<!-- Section to house the edit library form -->
<section class="edit-libraries">
    <div class="container-small">
        <div>
            <a href="admin.php">
                < Back
            </a>
        </div>
        <?php 

            // check if there are any errors with the post
            if (isset($messages) && count($messages) != 0) {
                echo "<div id='errors' style='display: block; margin-top: 10px;'><ul>";
                foreach($messages as $message) {
                    echo "<li>" . $message . "</li>";
                }
                echo "</ul></div>";
            }

            // check if we should show a success message
            if (isset($_GET['success'])) {
                echo "<div id='success' style='display: block; padding: 15px; margin-top: 10px;'>Successfully updated library</div>";
            }

        ?>
        <div id="errors"></div>
        <div id="success"></div>
        <form id="editLibrary" method="post" action="edit-library.php">
            <h3>Edit a library</h3>
            <div class="group">
                <label>Select a library</label>
                <select id="libraries" name="library">
                    <option selected disabled>--</option>
                </select>
            </div>
            <div class="group">
                <label>Address</label>
                <input type="text" id="address" name="editAddress">
            </div>
            <div class="group">
                <label>Latitude</label>
                <input type="text" id="lat" name="editLat">
            </div>
            <div class="group">
                <label>Longitude</label>
                <input type="text" id="lng" name="editLng">
            </div>
            <div class="group">
                <label>Library capacity</label>
                <input type="text" id="libCapacity" name="editCapacity">
            </div>
            <div id="books"></div>
            <input type="hidden" name="libId" id="libId">
            <button type="submit" name="submit" value="submit">Update</button>
        </form>
    </div>
</section>

<!-- Place footer accordingly, with licenses to avoid misuse of content -->
<footer>
    <small>Icons made by <a href="https://www.flaticon.com/authors/zlatko-najdenovski" title="Zlatko Najdenovski">Zlatko Najdenovski</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small>
</footer>

    <!-- Include the required script files -->
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/edit-libraries.js"></script>
    <script type="text/javascript" src="assets/js/leaflet.js"></script>
</body>
</html>