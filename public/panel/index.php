<?php require_once '../../main.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once '../../modules/header.php'; ?>
        <title><?php echo APP_NAME ?> - Dashboard</title>
    </head>
    <body class="hold-transition skin-blue-light sidebar-mini">
        <div class="wrapper">
            <?php require_once '../../modules/navbar.php'; ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>Dashboard</h1>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-book" aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Bookings</span>
                                    <span class="info-box-number" id="totalBookings"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-users" aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Unique Guests</span>
                                    <span class="info-box-number" id="uniqueGuests"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-bed" aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Rooms</span>
                                    <span class="info-box-number" id="totalRooms"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Rooms Availability Today</h3>
                                </div>
                                <div class="box-body">
                                    <canvas id="occupiedStatusCanvas" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?php require_once '../../modules/footer.php'; ?>
        <script src="../js/main.js"></script>
        <script>
            $(document).ready(function() {
                let totalBookings = $("#totalBookings");
                let uniqueGuests = $("#uniqueGuests");
                let totalRooms = $("#totalRooms");
                let occupiedStatusCanvas = $("#occupiedStatusCanvas");

                submitForm({action: "statistics"}, function done(json) {
                    if (json.success) {
                        totalBookings.html(json.totalBookings);
                        uniqueGuests.html(json.uniqueGuests);
                        totalRooms.html(json.totalRooms);

                        let occupied = json.currentOccupiedRooms;
                        let available = json.totalRooms - occupied;
                        drawChart(document.getElementById("occupiedStatusCanvas"), occupied, available);
                    } else {
                        totalBookings.html("N/A");
                        uniqueGuests.html("N/A");
                        totalRooms.html("N/A");
                    }
                }, function fail() {
                    totalBookings.html("N/A");
                    uniqueGuests.html("N/A");
                    totalRooms.html("N/A");
                });
            });

            function drawChart(canvas, occupied, available) {
                new Chart(canvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [
                                available,
                                occupied
                            ],
                            backgroundColor: [
                                "rgb(0, 166, 90)",
                                "rgb(245, 105, 84)"
                            ]
                        }],
                        labels: [
                            'Available',
                            'Occupied'
                        ]
                    }
                });
            }
        </script>
    </body>
</html>