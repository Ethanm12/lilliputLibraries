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
        <a class="nav-btn active">Home</a>
        <a class="nav-btn" href="admin.php">Administration</a>
    </div>
</header>

<!-- Show the Map in a generously sized area -->
<section id="map"></section>

<!-- Section to show all of the libraries and their book counts -->
<section class="libraries">
    <h3>Information</h3>
    <table>
        <thead>
        <tr>
            <th>Address</th>
            <th>Number of books present</th>
            <th>Capacity of library</th>
        </tr>
        </thead>
        <tbody id="libraries-tbody"></tbody>
    </table>
</section>

<!-- Place footer accordingly, with licenses to avoid misuse of content -->
<footer>
    <small>Icons made by <a href="https://www.flaticon.com/authors/zlatko-najdenovski" title="Zlatko Najdenovski">Zlatko Najdenovski</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small><br>
    <small>Icons made by <a href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small>
</footer>

<!-- Include the required script files -->
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/leaflet.js"></script>
<script type="text/javascript" src="assets/js/map-viewer.js"></script>
</body>
</html>