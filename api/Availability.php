<?php
/**
 * @param DateRange $dateRange Check-in and check-out
 * @param int $rooms Amount of rooms
 * @param int $ignoredBookingId Booking ID to ignore as booked (useful for a booking update)
 */
function searchAvailability($dateRange, $rooms, $ignoredBookingId = null) {
    global $json, $link;

    $checkIn = mysqli_real_escape_string($link, $dateRange->getStartSqlTimestamp());
    $checkOut = mysqli_real_escape_string($link, $dateRange->getFinishSqlTimestamp());
    $rooms = mysqli_real_escape_string($link, $rooms);

    if ($ignoredBookingId != null) {
        $ignoredBookingId = mysqli_real_escape_string($link, $ignoredBookingId);
    }

    // Sub query selects all occupied rooms
    $query = "SELECT room_number FROM rooms WHERE room_number NOT IN (
                SELECT DISTINCT room_number FROM bookings
                RIGHT JOIN booking_rooms
                ON bookings.booking_id = booking_rooms.booking_id
                WHERE '$checkIn' < bookings.check_out AND '$checkOut' > bookings.check_in
                " . ($ignoredBookingId == null ? '' : "AND bookings.booking_id != '$ignoredBookingId'") . "
            ) LIMIT $rooms";

    $result = mysqli_query($link, $query);

    $numRows = mysqli_num_rows($result);
    if ($rooms <= $numRows) {
        $json->success = true;
        $json->roomNumbers = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($json->roomNumbers, $row['room_number']);
        }
    } else {
        $json->success = false;
        $json->errorMessage = 'Only ' . $numRows . ' room(s) available.';
    }
}