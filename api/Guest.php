<?php
/**
 * Obtains guest information.
 * @param int $guestId
 */
function getGuest($guestId) {
    global $json, $link;

    $guestId = mysqli_real_escape_string($link, $guestId);

    $query = "SELECT * FROM guests WHERE guest_id = '$guestId'";

    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        $json->success = true;
        if ($row = mysqli_fetch_assoc($result)) {
            $guest = new stdClass();
            $guest->guestId = $row['guest_id'];
            $guest->firstName = $row['first_name'];
            $guest->lastName = $row['last_name'];
            $guest->phone = ($row['phone'] != null ? $row['phone'] : '');
            $guest->email = ($row['email'] != null ? $row['email'] : '');
            $guest->address = ($row['address'] != null ? $row['address'] : '');
            $guest->city = ($row['city'] != null ? $row['city'] : '');
            $guest->province = ($row['province'] != null ? $row['province'] : '');
            $guest->country = ($row['country'] != null ? $row['country'] : '');
            $guest->postalCode = ($row['postal_code'] != null ? $row['postal_code'] : '');
            $guest->notes = $row['notes'];
            $json->guest = $guest;
        }
    } else {
        $json->success = false;
        $json->errorMessage = 'Guest not found';
    }
}