<?php
if (!isset($_POST['action']) || mb_strlen($_POST['action']) > 20) {
    // 400 Bad Request
    exit400();
}

require_once '../main.php';

try {
    connectToDB();
} catch (Exception $e) {
    http_response_code(500);
    exit();
}

$action = $_POST['action'];

header('Content-Type: application/json');

$json = new stdClass();
$json->success = false;

if ($action === 'bookings') {

    require_once '../api/GuestHistory.php';

    guestBookings();

    exitJson();

} else if ($action === 'searchAvailability') {

    // Requires: dateRange, rooms
    if (isset($_POST['dateRange']) && isset($_POST['rooms'])) {

        $dateRangeString = $_POST['dateRange'];
        $rooms = $_POST['rooms'];

        // Validate data
        if (!SyntaxValidator::dateRange($dateRangeString)) {
            $json->errorMessage = 'Invalid date range';
            exitJson();
        }

        if (!SyntaxValidator::roomNumber($rooms)) {
            $json->errorMessage = 'Min 1 room, max 10 rooms per booking';
            exitJson();
        }

        $dateRange = new DateRange($dateRangeString);
        if (!$dateRange->isValid()) {
            $json->errorMessage = 'Check-in date must be before check-out date';
            exitJson();
        }

        require_once '../api/Availability.php';

        searchAvailability($dateRange, $rooms);

        exitJson();

    } else {
        exit400();
    }

} else if ($action === 'getGuest') {

    // Requires: guestId
    if (isset($_POST['guestId'])) {

        $guestId = $_POST['guestId'];
        if (!SyntaxValidator::guestId($guestId)) {
            $json->errorMessage = 'Invalid guest ID';
            exitJson();
        }

        require_once '../api/Guest.php';

        getGuest($guestId);

        exitJson();
    }

} else if ($action === 'getGuests') {

    // Requires at least one: firstName, lastName, phone, email
    if (isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['phone']) && isset($_POST['email'])) {

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        require_once '../api/Guests.php';

        getGuests($firstName, $lastName, $phone, $email);

        exitJson();

    } else {
        exit400();
    }

} else if ($action === 'book') {

    // Requires: dateRange, rooms, guestId
    if (isset($_POST['dateRange']) && isset($_POST['rooms']) && isset($_POST['guestId'])) {

        $dateRangeString = $_POST['dateRange'];
        $rooms = $_POST['rooms'];
        $guestId = $_POST['guestId'];

        if (!SyntaxValidator::dateRange($dateRangeString)) {
            $json->errorMessage = 'Invalid date range';
            exitJson();
        }

        if (!SyntaxValidator::roomNumber($rooms)) {
            $json->errorMessage = 'Min 1 room, max 10 rooms per booking';
            exitJson();
        }

        $dateRange = new DateRange($dateRangeString);
        if (!$dateRange->isValid()) {
            $json->errorMessage = 'Check-in date must be before check-out date';
            exitJson();
        }

        if (!SyntaxValidator::guestId($guestId)) {
            $json->errorMessage = 'Invalid guest ID';
            exitJson();
        }

        require_once '../api/Availability.php';

        searchAvailability($dateRange, $rooms);
        if (!$json->success) {
            $json->errorMessage = 'No longer available';
            exitJson();
        }

        require_once '../api/Bookings.php';

        newBooking($dateRange, $json->roomNumbers, $guestId);

        exitJson();

    } else {
        exit400();
    }

} else if ($action === 'updateBooking') {

    // Requires: dateRange, rooms, bookingId
    if (isset($_POST['dateRange']) && isset($_POST['rooms']) && isset($_POST['bookingId'])) {

        $dateRangeString = $_POST['dateRange'];
        $rooms = $_POST['rooms'];
        $bookingId = $_POST['bookingId'];

        // Validate data
        if (!SyntaxValidator::dateRange($dateRangeString)) {
            $json->errorMessage = 'Invalid date range';
            exitJson();
        }

        if (!SyntaxValidator::roomNumber($rooms)) {
            $json->errorMessage = 'Invalid number of rooms associated with this booking';
            exitJson();
        }

        $dateRange = new DateRange($dateRangeString);
        if (!$dateRange->isValid()) {
            $json->errorMessage = 'Check-in date must be before check-out date';
            exitJson();
        }

        if (!SyntaxValidator::bookingId($bookingId)) {
            $json->errorMessage = 'Invalid booking ID';
            exitJson();
        }

        require_once '../api/Availability.php';

        searchAvailability($dateRange, $rooms, $bookingId);
        if (!$json->success) {
            exitJson();
        }

        require_once '../api/Bookings.php';

        updateBooking($bookingId, $dateRange);

        exitJson();

    } else {
        exit400();
    }

} else if ($action === 'guestHistory') {

    // Requires: guestId
    if (isset($_POST['guestId'])) {

        $guestId = $_POST['guestId'];
        if (!SyntaxValidator::guestId($guestId)) {
            $json->errorMessage = 'Invalid guest ID';
            exitJson();
        }

        require_once '../api/GuestHistory.php';

        guestBookings($guestId);

        exitJson();
    }

} else if ($action === 'statistics') {

    require_once '../api/Statistics.php';

    $json->success = true;

    uniqueGuestsCount();
    totalBookingsCount();
    totalRoomsCount();
    currentOccupiedRooms();

    exitJson();

} else if ($action === 'deleteBooking') {

    // Requires: bookingId
    if (isset($_POST['bookingId'])) {

        $bookingId = $_POST['bookingId'];
        if (!SyntaxValidator::bookingId($bookingId)) {
            $json->errorMessage = 'Invalid booking ID';
            exitJson();
        }

        require_once '../api/Bookings.php';

        deleteBooking($bookingId);

        exitJson();
    }

}

// If action requested does not exist
exit400();


function exitJson() {
    global $json;
    echo json_encode($json);
    exit();
}

function exit400() {
    // 400 Bad Request
    http_response_code(400);
    exit();
}