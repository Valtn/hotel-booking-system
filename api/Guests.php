<?php
/**
 * Searches guests that match the criteria passed.
 * Criteria not used for the search must be an empty string or null.
 * @param string $firstName
 * @param string $lastName
 * @param string $phone
 * @param string $email
 */
function getGuests($firstName, $lastName, $phone, $email) {
    global $json, $link;

    $firstName = mysqli_real_escape_string($link, $firstName);
    $lastName = mysqli_real_escape_string($link, $lastName);
    $phone = mysqli_real_escape_string($link, $phone);
    $email = mysqli_real_escape_string($link, $email);

    $whereConditions = (!empty($firstName) ? "AND first_name LIKE '$firstName%' " : null) .
        (!empty($lastName) ? "AND last_name LIKE '$lastName%' " : null) .
        (!empty($phone) ? "AND phone LIKE '%$phone%' " : null) .
        (!empty($email) ? "AND email LIKE '$email%' " : null);

    if (empty($whereConditions)) {
        $json->success = false;
        $json->errorMessage = 'Please fill in at least one field partially';
        return;
    }

    // Remove first 'OR '
    $whereConditions = mb_substr($whereConditions, 4);

    $query = "SELECT * FROM guests WHERE " . $whereConditions;

    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        $json->success = true;
        $json->guests = array();
        while ($row = mysqli_fetch_assoc($result)) {
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
            array_push($json->guests, $guest);
        }
    } else {
        $json->success = false;
        $json->errorMessage = 'No guests found';
    }
}