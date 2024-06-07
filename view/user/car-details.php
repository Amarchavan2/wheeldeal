<?php
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Start the session
session_start();

// Include the config file
include '../../config.php';

// Check if the logout link is clicked
if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the index.html page
    header("Location: index.html");
    exit;
}

// Check if the car ID is provided in the URL
if (isset($_GET['id'])) {
    $carId = $_GET['id'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->bind_param("i", $carId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Car Details</title>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
            <link rel="stylesheet" href="../../css/bootstrap.min.css">
            <link rel="stylesheet" href="../../css/font-awesome.min.css">
            <link rel="stylesheet" href="../../css/owl.carousel.css">
            <link rel="stylesheet" href="../../css/owl.theme.default.min.css">
            <link rel="stylesheet" href="../../css/style.css">
        </head>
        <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
            <!-- PRE LOADER -->
            <section class="preloader">
                <div class="spinner">
                    <span class="spinner-rotate"></span>
                </div>
            </section>

             <!-- MENU -->
    <section class="navbar custom-navbar navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                </button>

                <!-- lOGO TEXT HERE -->
                <a href="../../index.html" class="navbar-brand">A.S AutoZone</a>
            </div>

            <!-- MENU LINKS -->
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-nav-first">
                    <li><a href="../../index.html">Home</a></li>
                    <li class="active"><a href="viewcar.php">Cars</a></li>
                    <li><a href="about-us.html">About Us</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">More <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="blog-posts.html">Blog</a></li>
                            <li><a href="team.html">Team</a></li>
                            <li><a href="testimonials.html">Testimonials</a></li>
                            <li><a href="../admin/admin.php">Admin</a></li>
                        </ul>
                    </li>
                    <li><a href="contact.html">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </section>
            <div class="container">
                <?php if ($message): ?>
                    <div class="alert alert-success mt-3" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
            </div>

            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div>
                                <?php
                                $images = explode(',', $row['images']); // Assuming images are stored as comma-separated values
                                if (!empty($images[0])) {
                                    // Display only the first image
                                    $imageUrl = '../../' . trim($images[0]); // Adjust the path to point to your images folder
                                    echo "<img src='$imageUrl' alt='Car Image' class='img-responsive wc-image'>";
                                } else {
                                    echo "<p>No images available.</p>";
                                }
                                ?>
                            </div>
                            <br>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                            <p class="lead">
                                <strong class="text-primary">
                                    From ₹<?php echo number_format($row['showroom_price'] / 100000, 2); ?>
                                    to ₹<?php echo number_format($row['onroadprice'] / 100000, 2); ?> Lakhs
                                </strong>
                            </p>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4>Vehicle Description</h4>
                                    </div>
                                    <div class="panel-body">
                                        <p><span>Max Power</span><br><strong><?php echo htmlspecialchars($row['max_power']); ?></strong></p>
                                        <p><span>Displacement</span><br><strong><?php echo htmlspecialchars($row['displacement']); ?></strong> cc</p>
                                        <p><span>Fuel Tank Capacity</span><br><strong><?php echo htmlspecialchars($row['fuel_tank']); ?></strong> liters</p>
                                        <p><span>Engine</span><br><strong><?php echo htmlspecialchars($row['engine']); ?></strong></p>
                                        <p><span>Fuel Type</span><br><strong><?php echo htmlspecialchars($row['fuel_type']); ?></strong></p>
                                        <p><span>Emission Norms</span><br><strong><?php echo htmlspecialchars($row['emission_norms']); ?></strong></p>
                                        <p><span>Max Torque</span><br><strong><?php echo htmlspecialchars($row['max_torque']); ?></strong></p>
                                        <p><span>Mileage</span><br><strong><?php echo htmlspecialchars($row['mileage']); ?></strong> kmpl</p>
                                        <p><span>Gradeability</span><br><strong><?php echo htmlspecialchars($row['gradeability']); ?></strong>%</p>
                                        <p><span>Max Speed</span><br><strong><?php echo htmlspecialchars($row['max_speed']); ?></strong> km/h</p>
                                        <p><span>Engine Cylinders</span><br><strong><?php echo htmlspecialchars($row['engine_cylinders']); ?></strong></p>
                                        <p><span>Battery Capacity</span><br><strong><?php echo htmlspecialchars($row['battery_capacity']); ?></strong> kWh</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="section-btn btn btn-primary" data-toggle="modal" data-target="#mainModal">Book Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FOOTER -->
    <footer id="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-sm-6">
            <div class="footer-info">
              <div class="section-title">
                <h2>Headquarter</h2>
              </div>
              <address>
                <p>
                  Mohd Hasan Bakwal<br />
                  Shop no. 10, Asalpha Village<br />
                  Ghatkopar(W)<br />
                  Mumbai, 400084
                </p>
              </address>
              

              <ul class="social-icon">
                <li>
                  <a
                    href="#"
                    class="fa fa-facebook-square"
                    attr="facebook icon"
                  ></a>
                </li>
                <li><a href="#" class="fa fa-twitter"></a></li>
                <li><a href="#" class="fa fa-instagram"></a></li>
              </ul>

              <div class="copyright-text">
                <p>Copyright &copy; A.S AutoZone</p>
                
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6">
            <div class="footer-info">
              <div class="section-title">
                <h2>Contact Info</h2>
              </div>
              <address>
                <p>+91 9594652780</p>
                <p>
                  <a href="ankitachoubey1521@gmail.com">ankitachoubey1521@gmail.com</a>
                </p>
              </address>

              <div class="footer_menu">
                <h2>Quick Links</h2>
                <ul>
                  <li><a href="../../index.html">Home</a></li>
                  <li><a href="about-us.html">About Us</a></li>
                  <li><a href="terms.html">Terms & Conditions</a></li>
                  <li><a href="contact.html">Contact Us</a></li>
                </ul>
              </div>
            </div>
          </div>

          
        </div>
      </div>
    </footer>

            <!-- Main Modal -->
            <div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mainModalLabel">Choose an Option</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <button type="button" class="btn btn-primary" onclick="makeCall()">Call</button>
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#formModal">Get Call</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Modal -->
            <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Request a Call</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="callRequestForm" action="submit_form.php?id=<?php echo htmlspecialchars($carId); ?>" method="POST">
                    <div class="form-group">
                        <label for="carName">Car Selected</label>
                        <input type="text" class="form-control" id="carName" name="carName" value="<?php echo htmlspecialchars($row['name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="userName">Name</label>
                        <input type="text" class="form-control" id="userName" name="userName" required minlength="2" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="userPhone">Phone Number</label>
                        <input type="tel" class="form-control" id="userPhone" name="userPhone" required pattern="[0-9]{10}" title="Please enter a 10-digit phone number">
                    </div>
                    <div class="form-group">
                        <label>Purchase Period</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="within10Days" name="purchasePeriod[]" value="Within 10 Days">
                            <label class="form-check-label" for="within10Days">Within 10 Days</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="withinMonth" name="purchasePeriod[]" value="Within a Month">
                            <label class="form-check-label" for="withinMonth">Within a Month</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="withinYear" name="purchasePeriod[]" value="Within This Year">
                            <label class="form-check-label" for="withinYear">Within This Year</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


            

            <!-- SCRIPTS -->
            <script src="../../js/jquery.js"></script>
            <script src="../../js/bootstrap.min.js"></script>
            <script src="../../js/owl.carousel.min.js"></script>
            <script src="../../js/smoothscroll.js"></script>
            <script src="../../js/custom.js"></script>
            <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script>
                function makeCall() {
                    // Replace with the actual phone number
                    window.location.href = 'tel:+911234567890';
                }
            </script>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Car not found.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Car ID is missing.</p>";
}

$conn->close();
?>
