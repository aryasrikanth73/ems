<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Attendance Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="../assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/css/metisMenu.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/slicknav.min.css">
    <link rel="stylesheet" href="../assets/css/typography.css">
    <link rel="stylesheet" href="../assets/css/default-css.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="dashboard.php"><img src="../assets/images/icon/logo.png" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <?php
                        $page='attendance';
                        include '../includes/admin-sidebar.php';
                    ?>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->

        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-4 clearfix">
                        <ul class="notification-area pull-right">
                            <li id="full-view"><i class="ti-fullscreen"></i></li>
                            <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- header area end -->

            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Attendance Report</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="dashboard.php">Home</a></li>
                                <li><span>Attendance Report</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix">
                        <div class="user-profile pull-right">
                            <img class="avatar user-thumb" src="../assets/images/admin.png" alt="avatar">
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown">ADMIN <i class="fa fa-angle-down"></i></h4>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="logout.php">Log Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page title area end -->

            <div class="main-content-inner">
                <div class="row">
                    <div class="col-lg-12 col-ml-12">
                        <div class="card mt-5">
                            <div class="card-body">
                                <h4 class="header-title">Attendance Report</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button class="btn btn-primary period-button" data-period="day">Day</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary period-button" data-period="week">Week</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary period-button" data-period="month">Month</button>
                                    </div>
                                </div>
                                <div id="report-container" class="mt-4">
                                    <!-- Data will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer area start -->
        <?php include '../includes/footer.php' ?>
        <!-- footer area end -->
    </div>
    <!-- main content area end -->

    <!-- jquery latest version -->
    <script src="../assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/owl.carousel.min.js"></script>
    <script src="../assets/js/metisMenu.min.js"></script>
    <script src="../assets/js/jquery.slimscroll.min.js"></script>
    <script src="../assets/js/jquery.slicknav.min.js"></script>
    <!-- others plugins -->
    <script src="../assets/js/plugins.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <script>
    $(document).ready(function() {
        $('.period-button').click(function() {
            var period = $(this).data('period');
            fetchAttendanceData(period);
            fetchLeaveData(period);
        });

        function fetchAttendanceData(period) {
            $.ajax({
                url: 'fetch_attendance_data.php',
                type: 'GET',
                data: { period: period },
                success: function(response) {
                    var data = JSON.parse(response);
                    var reportHtml = '<h5>Attendance Data</h5><table class="table"><thead><tr><th>Employee ID</th><th>Date</th><th>Time</th><th>Status</th></tr></thead><tbody>';
                    data.forEach(function(record) {
                        reportHtml += '<tr><td>' + record.employee_id + '</td><td>' + record.date + '</td><td>' + record.time + '</td><td>' + record.status + '</td></tr>';
                    });
                    reportHtml += '</tbody></table>';
                    $('#report-container').html(reportHtml);
                },
                error: function() {
                    alert('Failed to fetch attendance data');
                }
            });
        }

        function fetchLeaveData(period) {
            $.ajax({
                url: 'fetch_leave_data.php',
                type: 'GET',
                data: { period: period },
                success: function(response) {
                    var data = JSON.parse(response);
                    var reportHtml = '<h5>Leave Data</h5><table class="table"><thead><tr><th>Employee ID</th><th>Date</th><th>Leave Type</th><th>Status</th></tr></thead><tbody>';
                    data.forEach(function(record) {
                        reportHtml += '<tr><td>' + record.employee_id + '</td><td>' + record.date + '</td><td>' + record.leave_type + '</td><td>' + record.status + '</td></tr>';
                    });
                    reportHtml += '</tbody></table>';
                    $('#report-container').append(reportHtml);
                },
                error: function() {
                    alert('Failed to fetch leave data');
                }
            });
        }
    });
    </script>
</body>

</html>
