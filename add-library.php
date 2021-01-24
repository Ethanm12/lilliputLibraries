<?php

// require the bootstrapping script
require 'core/init.php';

if (isset($_POST['submit'])) {

    // set up empty array, to hold validation error messages if there are any
    $messages = [];

    // get the incoming values from the user
    // escape each value as they come in

    $libAddress = e($_POST['address']);
    $libLat = e($_POST['lat']);
    $libLng = e($_POST['lng']);
    $libCapacity = e($_POST['capacity']);

    // validate each field
    if (isEmpty($libAddress)) {
        $messages[] = "Please enter an address for the library";
    } else {
        addToSession('address', $libAddress);
    }

    if (isEmpty($libLat)) {
        $messages[] = "Please enter a latitude value for the library";
    } elseif(! is_numeric($libLat)) {
        $messages[] = "Please enter a numeric value for latitude";
    } else {
        addToSession('lat', $libLat);
    }

    if (isEmpty($libLng)) {
        $messages[] = "Please enter a longitude value for the library";
    } elseif(! is_numeric($libLng)) {
        $messages[] = "Please enter a numeric value for longitude";
    } else {
        addToSession('lng', $libLng);
    }

    if (isEmpty($libCapacity)) {
        $messages[] = "Please enter the new libraries capacity";
    } else {
        addToSession('capacity', $libCapacity);
    }

    // validate lat and lng
    if (! validateDistanceToOctogan($libLat, $libLng)) {
        $messages[] = "Please ensure the distance to the Octogan is within a radius of 50km";
    }

    // check if we have passed validation
    if (count($messages) == 0) {
    
        // we have passed validation - add the
        // library to the xml file
        if(addLibraryToXML($libAddress, $libLat, $libLng, $numBooks, $libCapacity)) {
            // added the library correctly
            // clear the session and redirect
            session_destroy();

            header("Location: add-library.php?success");
            exit();
        } else {
            header("Location: add-library.php?failure");
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
                    echo "<div id='success' style='display: block; padding: 15px; margin-top: 10px;'>Successfully added library</div>";
                }

                // check if we should show an error message
                if (isset($_GET['failure'])) {
                    echo "<div id='errors' style='display: block; padding: 15px; margin-top: 10px;'>Something went wrong, please try again</div>";
                }

            ?>
            <div id="errors"></div>
            <div id="success"></div>
            <form method="post" action="add-library.php">
                <h3>Add a library</h3>
                <div class="group">
                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo old('address'); ?>">    
                </div>
                <div class="group">
                    <label>Latitude</label>
                    <input type="text" name="lat" value="<?php echo old('lat'); ?>">
                </div>
                <div class="group">
                    <label>Longitude</label>
                    <input type="text" name="lng" value="<?php echo old('lng'); ?>">
                </div>
                <div class="group">
                    <label>Library capacity</label>
                    <input type="text" name="capacity" value="<?php echo old('capacity'); ?>">
                </div> 
                <input type="hidden" id="libName">
                <button type="submit" name="submit" value="submit">Add library</button>
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