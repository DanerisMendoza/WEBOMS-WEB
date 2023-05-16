<?php 
    $page = 'notLogin';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
    include_once('general/connection.php');
    $query = "select * from weboms_company_tb";
    $resultSet = getQuery($query);
    if($resultSet!=null){
        foreach($resultSet as $row){
            $name = $row['name'];
            $address = $row['address'];
            $tel = $row['tel'];
            $description = $row['description'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
</head>
<body>

    <center>
        <nav id="navbar" class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand animate__animated animate__fadeInLeft" href="#"><?php echo strtoupper($name);?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item animate__animated animate__fadeInLeft"><a class="nav-link" href="#home">HOME</a></li>
                        <li class="nav-item animate__animated animate__fadeInLeft"><a class="nav-link" href="#menu">MENU</a></li>
                        <li class="nav-item animate__animated animate__fadeInLeft"><a class="nav-link" href="#about">ABOUT</a></li>
                        <li class="nav-item animate__animated animate__fadeInLeft"><a class="nav-link" href="general/ordersQueue.php">ORDERS QUEUE</a></li>
                        <li class="nav-item animate__animated animate__fadeInLeft"><a class="nav-link" href="general/ordersQueueOnline.php">ORDERS QUEUE ONLINE</a></li>
                        <li class="nav-item animate__animated animate__fadeInLeft"><a class="nav-link" href="general/qrcode.php">QR CODE</a></li>
                    </ul>
                    <a class="btn btn-login btn-outline-light  animate__animated animate__fadeInLeft" type="button" href="general/login.php">LOGIN</a>
                </div>
            </div>
        </nav>

        <section id="home" class="container">
            <div id="carouselExampleCaptions" class="carousel slide animate__animated animate__fadeInRight" data-bs-ride="false">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active shadow">
                        <img src="image/foods.png" class="carousel-slide-image shadow" alt="Foods">
                        <div class="carousel-caption">
                            <div class="card carousel-card">
                                <a href="" class="company-name"><?php echo strtoupper($name); ?></a>
                                <label for="" class="telephone">TELEPHONE: <?php echo $tel; ?></label>
                                <label for="" class="address">ADDRESS: <?php echo strtoupper($address); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="image/foods.png" class="carousel-slide-image shadow" alt="Foods">
                        <div class="carousel-caption">
                            <div class="card carousel-card">
                                <a href="" class="company-name"><?php echo strtoupper($name); ?></a>
                                <label for="" class="telephone">TELEPHONE: <?php echo $tel; ?></label>
                                <label for="" class="address">ADDRESS: <?php echo strtoupper($address); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="image/foods.png" class="carousel-slide-image shadow" alt="Foods">
                        <div class="carousel-caption">
                            <div class="card carousel-card">
                                <a href="" class="company-name"><?php echo strtoupper($name); ?></a>
                                <label for="" class="telephone">TELEPHONE: <?php echo $tel; ?></label>
                                <label for="" class="address">ADDRESS: <?php echo strtoupper($address); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <section id="menu" class="container">
            <label for="" class="our-menu animate__animated animate__fadeInLeft">OUR MENU</label>
            <div class="col-sm-12">
                <div class="row g-1">
                    <?php 
                        $query = "select * from weboms_menu_tb limit 6";
                        $resultSet =  getQuery($query);
                        if($resultSet != null){
                            foreach($resultSet as $row){
                    ?>

                    <div class="col-sm-2 animate__animated animate__fadeInRight">
                        <div class="card menu-card h-100">
                            <?php $pic = $row['picName']; echo "<img src='dishesPic/$pic' class='menu-pic shadow-sm'>";?> 
                            <div class="card-body">
                                <label for="" class="card-title"><?php echo strtoupper($row['dish']); ?></label> <br>
                                <label for="" class="card-text"><?php echo '₱' . number_format($row['price'],2); ?></label>
                            </div>
                        </div>
                    </div>
                    
                    <?php } 
                    }?>
                </div>
            </div>
        </section>

        <section id="about" class="container">
            <label for="" class="about-us animate__animated animate__fadeInLeft">ABOUT US</label>
            <div class="card about-card animate__animated animate__fadeInRight">
                <img src="image/chef.png" class="about-us-photo shadow-sm" alt="About Us">
                <label for="" class="company-name2"><?php echo strtoupper($name); ?></label>
                <label for="" class="description"><?php echo $description ?></label>
            </div>
        </section>

        <footer>
            <div class="container">
                <section>
                    <a href="#" class="footer-link"><i class="bi-facebook"></i></a>
                    <a href="#" class="footer-link"><i class="bi-twitter"></i></a>
                    <a href="#" class="footer-link"><i class="bi-google"></i></a>
                    <a href="#" class="footer-link"><i class="bi-instagram"></i></a>
                    <a href="#" class="footer-link"><i class="bi-linkedin"></i></a>
                    <a href="#" class="footer-link"><i class="bi-github"></i></a>
                </section>
            </div>
            <hr>
            <label for="">© 2023 Copyright: <a class="mdbootstrap-link" href="https://mdbootstrap.com/">MDBootstrap.com</a></label> 
        </footer>
    </center>

</body>
</html>