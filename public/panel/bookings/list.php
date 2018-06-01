<?php require_once '../../../main.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once '../../../modules/header.php'; ?>
        <title><?php echo APP_NAME ?> - Bookings</title>
        <style>
            .searchErrorMsg {
                padding-top: 4px;
            }
            .table > tbody > tr > td {
                vertical-align: middle;
            }
            .table th:nth-child(-n+2) {
                width: 80px;
            }
        </style>
    </head>
    <body class="hold-transition skin-blue-light sidebar-mini">
        <div class="wrapper">
            <?php require_once '../../../modules/navbar.php'; ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>Bookings</h1>
                </section>

                <section class="content">

                    <div class="row" id="step1">
                        <div class="col-md-8">
                            <div class="box box-default" id="bookingsList">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Search Guest</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Booking ID</th>
                                                        <th>Guest ID</th>
                                                        <th>First name</th>
                                                        <th>Last name</th>
                                                        <th>Reservation date</th>
                                                        <th>Check-in</th>
                                                        <th>Check-out</th>
                                                        <th>Room(s)</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bookingsTableBody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="overlay" style="display:none" data-forms="search-spinner">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="step2" style="display:none">
                        <div class="col-md-4">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Edit Booking</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="editDateRangeInput">Dates</label>
                                                <input type="text" class="form-control" id="editDateRangeInput">
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="display:none">
                                            <div class="form-group">
                                                <label for="editRoomsInput">Number of rooms</label>
                                                <input type="number" class="form-control" id="editRoomsInput" value="1" min="1" max="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <p class="text-red pull-left searchErrorMsg" data-forms="error-msg"></p>
                                    <button type="button" class="btn btn-success pull-right" onclick="updateBooking()">Update booking</button>
                                </div>
                                <div class="overlay" style="display:none" data-forms="search-spinner">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="step3" style="display: none;">
                        <div class="col-md-6">
                            <div class="box box-default">
                                <div class="box-body">
                                    <h4 class="text-center" id="updateBookingResult"><span style="height: 80px; display: block;"></span></h4>
                                </div>
                                <div class="overlay" style="display:none" data-forms="search-spinner">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?php require_once '../../../modules/footer.php'; ?>
        <script src="../../js/main.js"></script>
        <script>
            let bookings;
            let updatingId;

            $(document).ready(function() {
                submitForm({action: "bookings"}, function done(json) {
                    let bookingsTableBody = $("#bookingsTableBody");
                    for (let bookingKey in json.bookings) {
                        let booking = json.bookings[bookingKey];
                        bookingsTableBody.append(`
                                                    <tr>
                                                        <td>` + bookingKey + `</td>
                                                        <td>` + booking.guestId + `</td>
                                                        <td>` + booking.firstName + `</td>
                                                        <td>` + booking.lastName + `</td>
                                                        <td>` + booking.reservationDate + `</td>
                                                        <td>` + booking.checkIn + `</td>
                                                        <td>` + booking.checkOut + `</td>
                                                        <td>` + booking.rooms.join(", ") + `</td>
                                                        <td><button type="button" class="btn btn-primary btn-sm" onclick="editBooking(` + bookingKey + `)">Edit</button></td>
                                                        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteBooking(this, ` + bookingKey + `)">Delete</button></td>
                                                    </tr>
                        `);
                    }
                    bookings = json.bookings;
                });
            });

            function editBooking(id) {
                let booking = getCachedBooking(id);
                updatingId = id;

                $("#editDateRangeInput").daterangepicker({
                    startDate: booking.checkIn,
                    endDate: booking.checkOut,
                    locale: {
                        format: "YYYY-MM-DD"
                    }
                });
                $("#editRoomsInput").val(booking.rooms.length);

                displayStep(2);
            }

            function updateBooking() {
                let formData = {
                    action: "updateBooking",
                    bookingId: updatingId,
                    dateRange: $("#editDateRangeInput").val(),
                    rooms: $("#editRoomsInput").val()
                };

                let updateBookingResult = $("#updateBookingResult");

                submitForm(formData, function done(json) {
                    if (json.success) {
                        updateBookingResult.html('<i class="fa fa-check-square-o" aria-hidden="true"></i> Booking updated successfully!');
                    } else {
                        updateBookingResult.html('<i class="fa fa-times" aria-hidden="true"></i> ' + json.errorMessage);
                    }
                }, function fail() {
                    updateBookingResult.html('<i class="fa fa-times" aria-hidden="true"></i> Could not update booking');
                }, $("#step3"));

                displayStep(3);
            }

            function deleteBooking(td, id) {
                submitForm({action: "deleteBooking", bookingId: id}, function done(json) {
                    if (json.success) {
                        $(td).closest("tr").remove();
                    }
                }, null, $("#bookingsList"));
            }

            function getCachedBooking(id) {
                for (let bookingKey in bookings) {
                    if (bookingKey == id) {
                        return bookings[bookingKey];
                    }
                }
                return null;
            }

            let step1 = $("#step1");
            let step2 = $("#step2");
            let step3 = $("#step3");

            function displayStep(step) {
                switch (step) {
                    case 1:
                        step1.show();
                        step2.hide();
                        step3.hide();
                        break;
                    case 2:
                        step1.hide();
                        step2.show();
                        step3.hide();
                        break;
                    case 3:
                        step1.hide();
                        step2.hide();
                        step3.show();
                        break;
                }
            }
        </script>
    </body>
</html>