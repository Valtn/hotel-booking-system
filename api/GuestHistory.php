<?php
/**
 * Obtains total amount of bookings of a guest.
 * @param int $guestId
 */
function totalBookings($guestId) {
    global $json, $link;

    $guestId = mysqli_real_escape_string($link, $guestId);

    $query = "SELECT COUNT(booking_id) AS total_bookings FROM bookings WHERE guest_id = '$guestId'";

    $result = mysqli_query($link, $query);

    if (!$result) {
        $json->totalBookings = 'N/A';
        return;
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $json->totalBookings = $row['total_bookings'];
    } else {
        $json->totalBookings = 0;
    }
}

/**
 * Obtains bookings of all guests or of the one specified.
 * @param null $guestId
 */
function guestBookings($guestId = null) {
    global $json, $link;

    if ($guestId != null) {
        $guestId = mysqli_real_escape_string($link, $guestId);
    }

    $query = "SELECT b.booking_id, b.guest_id, g.first_name, g.last_name,
                b.reservation_date, b.check_in, b.check_out, check_out, br.room_number
                FROM bookings b
                LEFT JOIN guests g ON b.guest_id = g.guest_id
                LEFT JOIN booking_rooms br ON b.booking_id = br.booking_id
                WHERE br.room_number IS NOT NULL
                " . ($guestId == null ? '' : "AND b.guest_id = '$guestId'") . "
                ORDER BY b.check_out DESC";

    $result = mysqli_query($link, $query);

    if (!$result) {
        $json->bookings = null;
        return;
    }

    $json->totalBookings = 0;
    $json->bookings = array();

    if (mysqli_num_rows($result) > 0) {
        $json->success = true;
        while ($row = mysqli_fetch_assoc($result)) {
            if (array_key_exists($row['booking_id'], $json->bookings)) {
                array_push($json->bookings[$row['booking_id']]->rooms, $row['room_number']);
            } else {
                $booking = new stdClass();
                $booking->guestId = $row['guest_id'];
                $booking->firstName = $row['first_name'];
                $booking->lastName = $row['last_name'];
                $booking->reservationDate = $row['reservation_date'];
                $booking->checkIn = substr($row['check_in'], 0, -9);
                $booking->checkOut = substr($row['check_out'], 0, -9);
                $booking->rooms = array($row['room_number']);

                $json->bookings[$row['booking_id']] = $booking;

                $json->totalBookings++;
            }
        }
    }
}