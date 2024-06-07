<?php
session_start();
$configPath = dirname(__DIR__, 2) . '/config.php';
if (file_exists($configPath)) {
    include $configPath;
} else {
    die("Configuration file not found: $configPath");
}

// Check if the logout link is clicked
if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the index.html page
    header("Location: ../../index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>A.S AutoZone</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/owl.carousel.css">
    <link rel="stylesheet" href="../../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        /* Add any custom styles here */
    </style>
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

    <!-- Display available cars -->
    <section class="section-background">
        <div class="container">
            <div class="col-lg-9 col-xs-12">
                <div class="row">
                    <?php
                    if(isset($_GET['brand'])) {
                        // Retrieve the brand from the parameter
                        $selectedBrand = $_GET['brand'];
                        
                        // Modify your SQL query to filter cars by the selected brand
                        $sql = "SELECT * FROM cars WHERE brand = '$selectedBrand'";
                        // Execute the query and display the filtered cars
                    } else {
                        // If no brand parameter is set, display all cars
                        $sql = "SELECT * FROM cars";
                        // Execute the query and display all cars
                    }
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="col-lg-6 col-md-4 col-sm-6">
                                <div class="courses-thumb courses-thumb-secondary">
                                    <div class="courses-top">
                                        <div class="courses-image">
                                            <?php
                                            $images = explode(',', $row['images']); // Assuming images are stored as comma-separated values
                                            if (!empty($images[0])) {
                                                // Display only the first image
                                                $imageUrl = '../../' . trim($images[0]); // Adjust the path to point to your images folder
                                                echo "<img src='$imageUrl' alt='Car Image' class='img-responsive car-image'>";
                                            } else {
                                                echo "<p>No images available.</p>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="courses-detail">
                                        <h3><?php echo " {$row['name']}"; ?></h3>
                                        <p><strong>Price: ₹<?php echo $row['showroom_price']; ?></strong></p>
                                    </div>
                                    <div class="courses-info">
                                        <a href="../../view/user/car-details.php?id=<?php echo $row['id']; ?>" class="section-btn btn btn-primary btn-block">View More</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No cars available.</p>";
                    }
                    ?>                    
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
    <!-- Include JavaScript files -->
    <script src="../../js/jquery.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/owl.carousel.min.js"></script>
    <script src="../../js/smoothscroll.js"></script>
    <script src="../../js/custom.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
</body>
</html>
