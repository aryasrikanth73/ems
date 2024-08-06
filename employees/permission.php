<?php
    session_start();
    error_reporting(0);
    include('../includes/dbconn.php');

    // Set the default timezone to match your region
    date_default_timezone_set('Asia/Kolkata'); // Change to your timezone if necessary

    if(strlen($_SESSION['emplogin'])==0)
    {   
        header('location:../index.php');
    } 
    else 
    {
        if(isset($_POST['apply']))
        {
            $empid = $_SESSION['eid'];
            $leavetype = $_POST['leavetype'];
            $fromdate = DateTime::createFromFormat('d/m/Y', $_POST['fromdate'])->format('Y-m-d');
            $todate = DateTime::createFromFormat('d/m/Y', $_POST['todate'])->format('Y-m-d');
            $description = $_POST['description'];  
            $status = 0;
            $isread = 0;

            // Current date and time
            $currentDateTime = new DateTime();
            $currentTime = $currentDateTime->format('H:i');
            $currentDateStr = $currentDateTime->format('Y-m-d');
            
            // Define 4 PM as the cutoff time
            $cutoffTime = '16:00';
            
            // Calculate the date for tomorrow
            $tomorrow = (new DateTime())->modify('+1 day')->format('Y-m-d');
            // Calculate the date for the day after tomorrow
            $dayAfterTomorrow = (new DateTime())->modify('+2 day')->format('Y-m-d');

            // Check if the leave starts today or tomorrow and the current time is after 4 PM
            if ($fromdate == $currentDateStr) {
                $error = "You cannot apply for leave Today";
            }
            elseif ($fromdate == $tomorrow && $currentTime > $cutoffTime) 
            {
                $error = "Can't take leave for tomorrow if you request it after 4 PM today.";
            }
            elseif ($fromdate > $todate) 
            {
                $error = "Please enter proper dates.";
            } 
            else 
            {
                $sql = "INSERT INTO tblleaves (LeaveType, ToDate, FromDate, Description, Status, IsRead, empid) 
                        VALUES (:leavetype, :fromdate, :todate, :description, :status, :isread, :empid)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':leavetype', $leavetype, PDO::PARAM_STR);
                $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
                $query->bindParam(':todate', $todate, PDO::PARAM_STR);
                $query->bindParam(':description', $description, PDO::PARAM_STR);
                $query->bindParam(':status', $status, PDO::PARAM_INT);
                $query->bindParam(':isread', $isread, PDO::PARAM_INT);
                $query->bindParam(':empid', $empid, PDO::PARAM_INT);
                $query->execute();
                $lastInsertId = $dbh->lastInsertId();

                if($lastInsertId)
                {
                    $msg = "Your leave application has been applied, Thank You.";
                } 
                else 
                {
                    $error = "Sorry, could not process this time. Please try again later.";
                }
            }
        }

        // Generate the current date in DD/MM/YYYY format
        $currentDate = (new DateTime())->format('d/m/Y');
    ?>

    <!doctype html>
    <html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Employee Leave Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="../assets/images/icon/favicon.ico">
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="../assets/css/themify-icons.css">
        <link rel="stylesheet" href="../assets/css/metisMenu.css">
        <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="../assets/css/slicknav.min.css">
        <!-- amchart css -->
        <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
        <!-- others css -->
        <link rel="stylesheet" href="../assets/css/typography.css">
        <link rel="stylesheet" href="../assets/css/default-css.css">
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/responsive.css">
        <!-- modernizr css -->
        <script src="../assets/js/vendor/modernizr-2.8.3.min.js"></script>
        <!-- Include jQuery UI for datepicker -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
                        <a href="leave.php"><img src="../assets/images/icon/logo.png" alt="logo"></a>
                    </div>
                </div>
                <div class="main-menu">
                    <div class="menu-inner">
                        <nav>
                            <ul class="metismenu" id="menu">
                                <li class="#">
                                    <a href="attendance.php" aria-expanded="true"><i class="ti-agenda"></i><span>Attendance</span></a>
                                </li>
                                <li class="#">
                                    <a href="leave.php" aria-expanded="true"><i class="ti-user"></i><span>Apply Leave</span></a>
                                </li>
                                <li class="active">
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
                                <h4 class="page-title pull-left">Apply For Leave Days</h4>
                                <ul class="breadcrumbs pull-left">
                                    <li><span>Leave Form</span></li>
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
                        <div class="col-lg-6 col-ml-12">
                            <div class="row">
                                <!-- Textual inputs start -->
                                <div class="col-12 mt-5">
                                <?php if($error){?><div class="alert alert-danger alert-dismissible fade show"><strong>Info: </strong><?php echo htmlentities($error); ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div><?php } 
                                    else if($msg){?><div class="alert alert-success alert-dismissible fade show"><strong>Info: </strong><?php echo htmlentities($msg); ?> 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div><?php }?>
                                    <div class="card">
                                    <form name="addemp" method="POST">

                                        <div class="card-body">
                                            <h4 class="header-title">Employee Leave Form</h4>
                                            <p class="text-muted font-14 mb-4">Please fill up the form below.</p>

                                            <div class="form-group">
                                                <label for="example-date-input" class="col-form-label">Permisssion Date</label>
                                                <input class="form-control date-picker" type="text" value="<?php echo $currentDate; ?>" required id="example-date-input" name="fromdate" placeholder="DD/MM/YYYY">
                                            </div>

                                            <div class="form-group">
                                                <label for="example-time-input" class="col-form-label">Start Time</label>
                                                <input class="form-control time-picker" type="time" value="<?php echo $currentTime; ?>" required id="example-time-input" name="fromtime" placeholder="HH:MM">
                                            </div>

                                            <div class="form-group">
                                                <label for="example-time-input" class="col-form-label">End Time</label>
                                                <input class="form-control time-picker" type="time" value="<?php echo $currentTime; ?>" required id="example-time-input" name="endtime" placeholder="HH:MM">
                                            </div>

                                            <div class="form-group">
                                                <label for="example-text-input" class="col-form-label">Describe Your Conditions</label>
                                                <textarea class="form-control" name="description" type="text" id="example-text-input" rows="5" required id="example-date-input"></textarea>
                                            </div>

                                            <button class="btn btn-primary" name="apply" id="apply" type="submit">SUBMIT</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main content area end -->
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
        <!-- jQuery UI for datepicker -->
        <script>
            $(document).ready(function(){
                $('.date-picker').datepicker({
                    dateFormat: 'dd/mm/yy'
                });
            });
        </script>
    </body>

    </html>

    <?php } ?> 
