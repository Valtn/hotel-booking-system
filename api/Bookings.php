<?php
/**
 * Obtains all bookings.
 * @deprecated See GuestHistory.php#guestBookings(null)
 */
function bookings() {
    global $json, $link;

    $query = "SELECT b.booking_id, b.guest_id, g.first_name, g.last_name, b.reservation_date, b.check_in, b.check_out
                FROM bookings b LEFT JOIN guests g ON b.guest_id = g.guest_id";

    $result = mysqli_query($link, $query);

    if (!$result) {
        return;
    }

    $json->success = true;
    $json->bookings = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $booking = new stdClass();
        $booking->bookingId = $row['booking_id'];
        $booking->guestId = $row['guest_id'];
        $booking->firstName = $row['first_name'];
        $booking->lastName = $row['last_name'];
        $booking->reservationDate = $row['reservation_date'];
        $booking->checkIn = substr($row['check_in'], 0, -9);
        $booking->checkOut = substr($row['check_out'], 0, -9);

        array_push($json->bookings, $booking);
    }
}

/**
 * Makes a new booking.
 * @param DateRange $dateRange
 * @param array $roomNumbers
 * @param int $guestId
 */
function newBooking($dateRange, $roomNumbers, $guestId) {
    global $json, $link;

    $guestId = mysqli_real_escape_string($link, $guestId);
    $checkIn = mysqli_real_escape_string($link, $dateRange->getStartSqlTimestamp());
    $checkOut = mysqli_real_escape_string($link, $dateRange->getFinishSqlTimestamp());

    $insertBooking = "INSERT INTO bookings (guest_id, check_in, check_out) VALUES ('$guestId', '$checkIn', '$checkOut')";

    mysqli_query($link, $insertBooking);

    $bookingId = mysqli_insert_id($link);

    if ($bookingId === 0) {
        $json->errorMessage = 'Could not create new booking';
        return;
    }

    $values = array();
    foreach ($roomNumbers as $room) {
        $room = mysqli_real_escape_string($link, $room);
        array_push($values, "('$bookingId', '$room')");
    }

    $insertBookingRoom = "INSERT INTO booking_rooms (booking_id, room_number) VALUES " . implode(',', $values);

    $result = mysqli_query($link, $insertBookingRoom);

    if (!$result) {
        $json->errorMessage = 'Could not book rooms';
        return;
    }

    $json->success = true;
}

/**
 * @param int $bookingId
 * @param DateRange $dateRange
 */
function updateBooking($bookingId, $dateRange) {
    global $json, $link;

    $bookingId = mysqli_real_escape_string($link, $bookingId);
    $checkIn = mysqli_real_escape_string($link, $dateRange->getStartSqlTimestamp());
    $checkOut = mysqli_real_escape_string($link, $dateRange->getFinishSqlTimestamp());

    $query = "UPDATE bookings SET check_in = '$checkIn', check_out = '$checkOut' WHERE booking_id = '$bookingId' LIMIT 1";

    $result = mysqli_query($link, $query);
    if (!$result) {
        $json->errorMessage = 'Could not update booking';
        return;
    }

    $json->success = true;
}

/**
 * Deletes an existing booking.
 * @param $bookingId
 */
function deleteBooking($bookingId) {
    global $json, $link;

    $bookingId = mysqli_real_escape_string($link, $bookingId);

    $query = "DELETE FROM booking_rooms WHERE booking_id = '$bookingId'";

    $result = mysqli_query($link, $query);
    if (!$result) {
        $json->errorMessage = 'Could not disassociate room(s) from booking';
        return;
    }

    $query = "DELETE FROM bookings WHERE booking_id = '$bookingId'";

    $result = mysqli_query($link, $query);
    if (!$result) {
        $json->errorMessage = 'Could not delete booking';
        return;
    }

    $json->success = true;
}