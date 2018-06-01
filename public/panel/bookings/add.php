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
                        <div class="col-md-6">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Search Dates</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="dateRangeInput">Dates</label>
                                                <input type="text" class="form-control" id="dateRangeInput">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="roomsInput">Rooms</label>
                                                <input type="number" class="form-control" id="roomsInput" value="1" min="1" max="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <p class="text-red pull-left searchErrorMsg" data-forms="error-msg"></p>
                                    <button type="button" class="btn btn-primary pull-right" onclick="searchDates()">Search</button>
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

                    <div class="row" id="step3" style="display:none">
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

                    <div class="row" id="step4" style="display:none">
                        <div class="col-md-6">
                            <div class="box box-default">
                                <div class="box-body">
                                    <h4 class="text-center"><i class="fa fa-calendar" aria-hidden="true"></i> <span id="confirmDates"></span></h4>
                                    <h4 class="text-center"><i class="fa fa-bed" aria-hidden="true"></i> <span id="confirmRooms"></span> room(s)</h4>
                                    <br>
                                    <p class="text-center"><button type="button" class="btn btn-success" onclick="confirm()">Confirm</button></p>
                                </div>
                                <div class="overlay" style="display:none" data-forms="search-spinner">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="step5" style="display: none;">
                        <div class="col-md-6">
                            <div class="box box-default">
                                <div class="box-body">
                                    <h4 class="text-center" id="bookingResult"><span style="height: 80px; display: block;"></span></h4>
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
            $(document).ready(function() {
                $("#dateRangeInput").daterangepicker({
                    endDate: moment().add(2, "day"),
                    locale: {
                        format: "YYYY-MM-DD"
                    }
                });
            });

            let dateRange;
            let rooms;
            let guestId;

            function searchDates() {
                dateRange = document.getElementById("dateRangeInput").value;
                rooms = document.getElementById("roomsInput").value;

                let formData = {
                    action: "searchAvailability",
                    dateRange: dateRange,
                    rooms: rooms
                };

                submitForm(formData, function done(json) {
                    if (json.success) {
                        displayStep(2);
                    } else {
                        displayStep(1);
                    }
                }, function fail() {
                    displayStep(1);
                }, document.getElementById("step1"));
            }

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
                        displayStep(3);

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
                    } else {
                        displayStep(2);
                    }
                }, function fail() {
                    displayStep(2);
                }, document.getElementById("step2"));
            }

            function selectGuest(id) {
                guestId = id;
                $("#confirmDates").html(dateRange);
                $("#confirmRooms").html(rooms);
                displayStep(4);
            }

            function confirm() {
                displayStep(5);

                let formData = {
                    action: "book",
                    dateRange: dateRange,
                    rooms: rooms,
                    guestId: guestId
                };

                let bookingResult = $("#bookingResult");

                submitForm(formData, function done(json) {
                    if (json.success) {
                        bookingResult.html('<i class="fa fa-check-square-o" aria-hidden="true"></i> Room(s) booked successfully!');
                    } else {
                        bookingResult.html('<i class="fa fa-times" aria-hidden="true"></i> ' + json.errorMessage);
                    }
                }, function fail() {
                    bookingResult.html('<i class="fa fa-times" aria-hidden="true"></i> Could not submit booking request');
                }, document.getElementById("step5"));
            }

            let step1 = $("#step1");
            let step2 = $("#step2");
            let step3 = $("#step3");
            let step4 = $("#step4");
            let step5 = $("#step5");

            function displayStep(step) {
                switch (step) {
                    case 1:
                        step1.show();
                        step2.hide();
                        step3.hide();
                        step4.hide();
                        step5.hide();
                        break;
                    case 2:
                        step1.hide();
                        step2.show();
                        step3.hide();
                        step4.hide();
                        step5.hide();
                        break;
                    case 3:
                        step1.hide();
                        step2.show();
                        step3.show();
                        step4.hide();
                        step5.hide();
                        break;
                    case 4:
                        step1.hide();
                        step2.hide();
                        step3.hide();
                        step4.show();
                        step5.hide();
                        break;
                    case 5:
                        step1.hide();
                        step2.hide();
                        step3.hide();
                        step4.hide();
                        step5.show();
                        break;
                }
            }
        </script>
    </body>
</html>