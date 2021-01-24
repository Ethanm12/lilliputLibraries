<?php

// require the bootstrapping script
require 'core/init.php';

if (isset($_POST['submit'])) {

    // set up empty array, to hold validation error messages if there are any
    $messages = [];

    // get the incoming values from the user
    // escape each value as they come in

    $title = e($_POST['title']);
    $author = e($_POST['author']);
    $year = e($_POST['year']);
    $libId = $_POST['libId'];

    // validate the id first
    if (isEmpty($libId))
    {
        $messages[] = "Please select a library";
    } else {

        // add the id and the address to the session 
        addToSession('libId', $libId);

        // validate each field
        if (isEmpty($title)) {
            $messages[] = "Please enter a title for the book";
        } else {
            addToSession('title', $title);
        }

        if (isEmpty($author)) {
            $messages[] = "Please enter an author for the book";
        } else {
            addToSession('author', $author);
        }

        if (isEmpty($year)) {
            $messages[] = "Please enter the year the book was published";
        } else {
            addToSession('year', $year);
        }

        if (count($messages) == 0) {

            // we have passed validation - add the book to the xml file
            if(addBookToLibrary($libId, $title, $author, $year)) {
                // added the book correctly
                // clear the session and redirect
                session_destroy();

                header("Location: add-book-to-library.php?success");
                exit();
            } else {
                header("Location: add-book-to-library.php?failure");
                exit();
            }

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
                echo "<div id='success' style='display: block; padding: 15px; margin-top: 10px;'>Successfully added book to library</div>";
            }

            // check if we should show an error message
            if (isset($_GET['failure'])) {
                echo "<div id='errors' style='display: block; padding: 15px; margin-top: 10px;'>Something went wrong, please try again</div>";
            }

        ?>
        <div id="errors"></div>
        <div id="success"></div>
        <form id="addBookToLibrary" method="post" action="add-book-to-library.php">
            <h3>Add a book to a library</h3>
            <div class="group">
                <label>Select a library</label>
                <select id="libraries" name="libAddress">
                    <option selected disabled>--</option>
                    <?php if (isset($_SESSION['libAddress'])) echo "<option value='{$_SESSION['libAddress']}' selected>{$_SESSION['libAddress']}</option>"; ?>
                </select>
            </div>
            <div class="group">
                <label>Title</label>
                <input type="text" id="title" name="title">
            </div>
            <div class="group">
                <label>Author</label>
                <input type="text" id="author" name="author">
            </div>
            <div class="group">
                <label>Year</label>
                <input type="text" id="year" name="year">
            </div>
            <input type="hidden" name="libId" id="libId" value="<?php echo old('libId'); ?>">
            <button type="submit" name="submit" value="submit">Submit</button>
        </form>
    </div>
</section>

<!-- Place footer accordingly, with licenses to avoid misuse of content -->
<footer>
    <small>Icons made by <a href="https://www.flaticon.com/authors/zlatko-najdenovski" title="Zlatko Najdenovski">Zlatko Najdenovski</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small>
</footer>

    <!-- Include the required script files -->
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/add-book-to-library.js"></script>
    <script type="text/javascript" src="assets/js/leaflet.js"></script>
    <!-- <script type="text/javascript" src="assets/js/validator.js"></script>
     -->
</body>
</html>