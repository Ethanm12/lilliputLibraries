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
        <a class="nav-btn active">Administration</a>
    </div>
</header>

<section class="admin">
    <div class="container-small">
        <div class="boxes">
            <div class="box">
                <a href="add-library.php">
                    <img src="assets/images/plus.png">
                    <h4>Add a library</h4>
                </a>
            </div>
            <div class="box">
                <a href="edit-library.php">
                    <img src="assets/images/edit.png">
                    <h4>Edit a library</h4>
                </a>
            </div>
            <div class="box">
                <a href="add-book-to-library.php">
                    <img src="assets/images/open-book.png">
                    <h4>Add a book to a library</h4>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Place footer accordingly, with licenses to avoid misuse of content -->
<footer>
    <small>Icons made by <a href="https://www.flaticon.com/authors/zlatko-najdenovski" title="Zlatko Najdenovski">Zlatko Najdenovski</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small><br>
    <small>Icons made by <a href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small>
</footer>

<!-- Include the required script files -->
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/edit-libraries.js"></script>
<script type="text/javascript" src="assets/js/leaflet.js"></script>
<script type="text/javascript" src="assets/js/validator.js"></script>
</body>
</html>