SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE bookings (
  booking_id int(11) NOT NULL,
  guest_id int(11) NOT NULL,
  reservation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  check_in timestamp NULL DEFAULT NULL,
  check_out timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE booking_rooms (
  booking_id int(11) NOT NULL,
  room_number int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE guests (
  guest_id int(11) NOT NULL,
  first_name varchar(25) NOT NULL,
  last_name varchar(25) NOT NULL,
  phone varchar(15) DEFAULT NULL,
  email varchar(320) DEFAULT NULL,
  address varchar(100) DEFAULT NULL,
  city varchar(100) DEFAULT NULL,
  province varchar(100) DEFAULT NULL,
  country varchar(100) DEFAULT NULL,
  postal_code varchar(20) DEFAULT NULL,
  notes text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE rooms (
  room_number int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE bookings
  ADD PRIMARY KEY (booking_id),
  ADD KEY fk_bookings_guest_id (guest_id);

ALTER TABLE booking_rooms
  ADD KEY room_number (room_number),
  ADD KEY booking_id (booking_id) USING BTREE;

ALTER TABLE guests
  ADD PRIMARY KEY (guest_id),
  ADD KEY last_name (last_name);

ALTER TABLE rooms
  ADD PRIMARY KEY (room_number);


ALTER TABLE bookings
  MODIFY booking_id int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE guests
  MODIFY guest_id int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE bookings
  ADD CONSTRAINT fk_bookings_guest_id FOREIGN KEY (guest_id) REFERENCES guests (guest_id);

ALTER TABLE booking_rooms
  ADD CONSTRAINT fk_booking_rooms_booking_id FOREIGN KEY (booking_id) REFERENCES bookings (booking_id),
  ADD CONSTRAINT fk_booking_rooms_room_number FOREIGN KEY (room_number) REFERENCES rooms (room_number);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
