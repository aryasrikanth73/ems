<?php
session_start();
error_reporting(0);
include('../includes/dbconn.php');

if (strlen($_SESSION['emplogin']) == 0) {   
    header('location:../index.php');
} else {
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Attendance Management System</title>
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
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src="../assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="../assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->

    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="attendance.php"><img src="../assets/images/icon/logo.png" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="active">
                                <a href="attendance.php" aria-expanded="true"><i class="ti-agenda"></i><span>Attendance</span></a>
                            </li>
                            <li class="#">
                                <a href="leave.php" aria-expanded="true"><i class="ti-user"></i><span>Apply Leave</span></a>
                            </li>
                            <li class="#">
                                <a href="permission.php" aria-expanded="true"><i class="ti-user"></i><span>Apply Permission</span></a>
                            </li>
                            <li class="#">
                                <a href="leave-history.php" aria-expanded="true"><i class="ti-agenda"></i><span>View My Leave History</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->

        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- profile info & task notification -->
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
                            <h4 class="page-title pull-left">Attendance</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><span>Attendance Calendar</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix">
                        <?php include '../includes/employee-profile-section.php' ?>
                    </div>
                </div>
            </div>
            <!-- page title area end -->

            <div class="main-content-inner">
                <div class="row">
                    <div class="col-lg-12 col-ml-12">
                        <div class="card mt-5">
                            <div class="card-body">
                                <h4 class="header-title">Attendance Calendar</h4>
                                <button id="log-attendance" class="btn btn-primary">Log Attendance</button>
                                <div id="calendar-container">
                                    <div id='calendar'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer area start-->
        <?php include '../includes/footer.php' ?>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->

    <!-- offset area start -->
    <div class="offset-area">
        <div class="offset-close"><i class="ti-close"></i></div>
    </div>
    <!-- offset area end -->

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
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: 'fetch_attendance.php',
            height: 'auto', // Adjust the height to fit content
            headerToolbar: {
                left: 'prev,next today', // Keep only these buttons
                center: 'title',
                right: '' // Remove month, week, and day buttons
            },
            contentHeight: 'auto',
            aspectRatio: 1.5, // Adjust the aspect ratio
            eventContent: function(arg) {
                return {
                    html: '<div class="fc-event-main-frame"><span>' + arg.event.title + '</span></div>'
                };
            }
        });
        calendar.render();

        document.getElementById('log-attendance').addEventListener('click', function() {
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();

            // Allow logging only between 10:00 AM and 10:50 PM, and not on Sundays
            if (hours === 10 && minutes <= 59 || (hours === 01 && minutes >= 0 && minutes <= 50)) {
                $.ajax({
                    url: 'log_attendance.php',
                    type: 'POST',
                    success: function(response) {
                        var result = JSON.parse(response);
                        alert(result.message);
                        if (result.status === 'success') {
                            calendar.refetchEvents();
                        }
                    },
                    error: function() {
                        alert('Failed to log attendance');
                    }
                });
            } else {
                alert('You can only log your attendance between 10:00 AM and 11:00 AM, and not on Sundays');
            }
        });
    });
    </script>
</body>
</html>

<?php } ?>
