<?php require_once '../../../main.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once '../../../modules/header.php'; ?>
        <title><?php echo APP_NAME ?> - Guest History</title>
        <style>
            .searchErrorMsg {
                padding-top: 4px;
            }
            .table > tbody > tr > td {
                vertical-align: middle;
            }
        </style>
    </head>
    <body class="hold-transition skin-blue-light sidebar-mini">
        <div class="wrapper">
            <?php require_once '../../../modules/navbar.php'; ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>Guest History</h1>
                </section>

                <section class="content">

                    <div class="row" id="step1">
                        <div class="col-md-6">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Search Guest</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstNameInput">First name</label>
                                                <input type="text" class="form-control" id="firstNameInput">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastNameInput">Last name</label>
                                                <input type="text" class="form-control" id="lastNameInput">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phoneInput">Phone number</label>
                                                <input type="text" class="form-control" id="phoneInput">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="emailInput">Email address</label>
                                                <input type="email" class="form-control" id="emailInput">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <p class="text-red pull-left searchErrorMsg" data-forms="error-msg"></p>
                                    <button type="button" class="btn btn-primary pull-right" onclick="searchGuests()">Search</button>
                                </div>
                                <div class="overlay" style="display:none" data-forms="search-spinner">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="step2" style="display:none">
                        <div class="col-md-6">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Select Guest</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>First name</th>
                                                <th>Last name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th style="width: 40px"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="guestsTableBody">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="overlay" id="searchSpinner" style="display:none">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="step3" style="display: none;">
                        <div class="col-md-6">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Guest History</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>First name</th>
                                                        <th>Last name</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th>Total visits</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td id="historyFirstName"></td>
                                                        <td id="historyLastName"></td>
                                                        <td id="historyPhone"></td>
                                                        <td id="historyEmail"></td>
                                                        <td id="historyTotalVisits"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 90px">Booking ID</th>
                                                        <th>Reservation date</th>
                                                        <th>Check-in</th>
                                                        <th>Check-out</th>
                                                        <th>Room(s)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="historyBookingsTableBody">
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
                </section>
            </div>
        </div>
        <?php require_once '../../../modules/footer.php'; ?>
        <script src="../../js/main.js"></script>
        <script>
            let guests;

            function searchGuests() {
                let formData = {
                    action: "getGuests",
                    firstName: document.getElementById("firstNameInput").value,
                    lastName: document.getElementById("lastNameInput").value,
                    phone: document.getElementById("phoneInput").value,
                    email: document.getElementById("emailInput").value
                };

                submitForm(formData, function done(json) {
                    if (json.success) {
                        displayStep(2);

                        let tBody = $("#guestsTableBody");
                        tBody.html("");

                        json.guests.forEach(function (guest) {
                            let tr = `
                                            <tr>
                                                <td>` + guest.firstName + `</td>
                                                <td>` + guest.lastName + `</td>
                                                <td>` + guest.phone + `</td>
                                                <td>` + guest.email + `</td>
                                                <td><button type="button" class="btn btn-primary btn-sm" onclick="selectGuest(` + guest.guestId + `)">Select</button></td>
                                            </tr>
                            `;

                            tBody.append(tr);
                        });

                        guests = json.guests;
                    } else {
                        displayStep(1);
                    }
                }, function fail() {
                    displayStep(1);
                }, document.getElementById("step1"));
            }

            function selectGuest(id) {
                submitForm({action: "guestHistory", guestId: id}, function done(json) {
                    let guest;
                    for (let i = 0; i < guests.length; i++) {
                        if (guests[i].guestId == id) {
                            guest = guests[i];
                            break;
                        }
                    }

                    $("#historyFirstName").html(guest.firstName);
                    $("#historyLastName").html(guest.lastName);
                    $("#historyPhone").html(guest.phone);
                    $("#historyEmail").html(guest.email);
                    $("#historyTotalVisits").html(json.totalBookings);

                    if (json.totalBookings != 0) {
                        let historyBookingsTableBody = $("#historyBookingsTableBody");
                        historyBookingsTableBody.html("");

                        for (let bookingKey in json.bookings) {
                            let booking = json.bookings[bookingKey];
                            let tr = `
                                            <tr>
                                                <td>` + bookingKey + `</td>
                                                <td>` + booking.reservationDate + `</td>
                                                <td>` + booking.checkIn + `</td>
                                                <td>` + booking.checkOut + `</td>
                                                <td>` + booking.rooms.join(", ") + `</td>
                                            </tr>
                            `;
                            historyBookingsTableBody.append(tr);
                        }

                    }
                    displayStep(3);

                }, null, document.getElementById("step2"));
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