<?php
session_start();

// load the other required files
require_once __DIR__ . '/validator.php';

/**
* ----------------------
* Helper functions.    
* ----------------------
*/

/**
* Escape the given value.
*
* @param $value
* @return string
*/
function e($value) {
	return htmlentities($value);
}

/**
* Add a value to the session.
* 
* @return bool
*/
function addToSession($key, $value) {
	$_SESSION[$key] = $value;
	return true;
}

/**
* Checks if the given key exists in the session, if
* it does return it.
*
* @param key
* @return mixed
*/
function old($key) {
	if (isset($_SESSION[$key])) {
		return $_SESSION[$key];
	}
}

/**
* Returns the last and greatest ID in the xml file, 
* so we can increment and add a new one.
* 
* @return int
*/
function getLastId() {

	// the xml file to load
	$xmlFile = simplexml_load_file(__DIR__ . "../../libraries/libs.xml");

	// store the numbers in this array
	$numbers = [];

	// get the ids out of the xml file
	foreach ($xmlFile->xpath("//id") as $id) {
		$numbers[] = (int) $id->__toString();
	}

	// return the maximum id
	return max($numbers);
}

/**
* Add the given library details to the XML file.
* 
* @return boolean
*/
function addLibraryToXML($libraryAddress, $libraryLat, $libraryLng, $numBooks, $libraryCapacity) {

	$xmlFile = simplexml_load_file(__DIR__ . "../../libraries/libs.xml");

	$newLibrary = $xmlFile->addChild('library');

	// increment the last id by 1
	$newLibrary->addChild("id", (getLastId() + 1));

	// add the info block
	$info = $newLibrary->addChild('info');
	$info->addChild('lat', $libraryLat);
	$info->addChild('lng', $libraryLng);
	$info->addChild('address', $libraryAddress);
	$info->addChild('capacity', $libraryCapacity);

	return $xmlFile->saveXML(__DIR__ . "../../libraries/libs.xml");
}

/**
* Add a book to the given library.
*
* @return boolean
*/
function addBookToLibrary($libId, $title, $author, $year) {

	$xmlFile = simplexml_load_file(__DIR__ . "../../libraries/libs.xml");

	$library = $xmlFile->xpath('//library[id="' . $libId . '"]');

	// check if we have books
	if (count($library[0]->books) == 0) {
		$library = $library[0]->addChild('books');
	} else {
		$library = $library[0]->books;
	}

	$book = $library->addChild('book');
	$book->addChild('title', $title);
	$book->addChild('author', $author);
	$book->addChild('year', $year);

	return $xmlFile->saveXML(__DIR__ . "../../libraries/libs.xml");
}

/**
* This function will validate the distance between the octogan and the given 
* lat/lng, we only allow 50km radius.
*
* @return boolean
*/
function validateDistanceToOctogan($lat2, $lon2) {

	// octogan lat and lng
	$lat1 = -45.866891; 
	$lon1 = 170.518202;

	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;

	$distance = $miles * 1.609344;

	return $distance < 50;
}