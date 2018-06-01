<?php
/**
 * Unique number of guests.
 */
function uniqueGuestsCount() {
    global $json, $link;

    $query = "SELECT COUNT(DISTINCT guest_id) AS unique_guests FROM guests";

    $result = mysqli_query($link, $query);

    if (!$result) {
        $json->uniqueGuests = 'N/A';
        return;
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $json->uniqueGuests = $row['unique_guests'];
    } else {
        $json->uniqueGuests = 0;
    }
}

/**
 * Total number of bookings.
 */
function totalBookingsCount() {
    global $json, $link;

    $query = "SELECT COUNT(booking_id) AS total_bookings FROM bookings";

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
 * Total number of rooms.
 */
function totalRoomsCount() {
    global $json, $link;

    $query = "SELECT COUNT(room_number) AS total_rooms FROM rooms";

    $result = mysqli_query($link, $query);

    if (!$result) {
        $json->totalRooms = 'N/A';
        return;
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $json->totalRooms = $row['total_rooms'];
    } else {
        $json->totalRooms = 0;
    }
}

/**
 * Total number of rooms occupied today.
 */
function currentOccupiedRooms() {
    global $json, $link;

    $query = "SELECT COUNT(booking_id) AS occupied_today FROM bookings WHERE check_in <= NOW() AND check_out >= NOW()";

    $result = mysqli_query($link, $query);

    if (!$result) {
        $json->currentOccupiedRooms = null;
        return;
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $json->currentOccupiedRooms = $row['occupied_today'];
    } else {
        $json->currentOccupiedRooms = 0;
    }
}