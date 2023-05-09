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

    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <script src="js/bootstrap 5/bootstrap.min.js"></script>
    <!-- css online icon bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body class="bg-dark">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container py-3">
            <a class="navbar-brand fs-4" href="#"><?php echo $name;?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item me-2"><a class="nav-link" href="#home"><i class="bi bi-house-door-fill"></i> HOME</a></li>
                    <li class="nav-item me-2"><a class="nav-link" href="#menu"><i class="bi bi-book-fill"></i> MENU</a></li>
                    <li class="nav-item me-2"><a class="nav-link" href="#about"><i class="bi bi-info-circle-fill"></i> ABOUT</a></li>
                    <li class="nav-item me-2"><a class="nav-link" href="general/ordersQueue.php"><i class="bi bi-clock-fill"></i> ORDERS QUEUE</a></li>
                    <li class="nav-item me-2"><a class="nav-link" href="general/ordersQueueOnline.php"><i class="bi bi-clock-fill"></i> ORDERS QUEUE ONLINE</a></li>
                    <li class="nav-item me-2"><a class="nav-link" href="general/qrcode.php"><i class="bi bi-qr-code-scan"></i> QR CODE PAGE</a></li>
                </ul>
                <a class="btn btn-outline-light" type="button" href="general/login.php"><i class="bi bi-person-circle"></i> LOGIN</a>
            </div>
        </div>
    </nav>

    <!-- home -->
    <section id="home" class="container mb-5" style="margin-top:60px;">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://media.cntraveler.com/photos/61f975646ddac9f81df58a62/16:9/w_2560%2Cc_limit/GettyImages-1280180647.jpg"
                        class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="card bg-dark border-secondary pt-2">
                            <h5><?php echo $name;?></h5>
                            <p>
                                <i class="bi bi-telephone"></i> TELEPHONE: <?php echo $tel;?> <br>
                                <i class="bi bi-pin-map"></i> ADDRESS: <?php echo ucwords($address);?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.everydayhealth.com/images/ww-guide-how-winter-affects-our-eating-1440x810.jpg"
                        class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="card bg-dark border-secondary pt-2">
                            <h5><?php echo $name;?></h5>
                            <p>
                                <i class="bi bi-telephone"></i> TELEPHONE: <?php echo $tel;?> <br>
                                <i class="bi bi-pin-map"></i> ADDRESS: <?php echo ucwords($address);?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.thequint.com/thequint-fit%2F2021-12%2Fc35dfeb7-a734-41b4-b85d-6a2ff043f75e%2FiStock_1064522252.jpg?auto=format%2Ccompress&fmt=webp&width=720"
                        class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="card bg-dark border-secondary pt-2">
                            <h5><?php echo $name;?></h5>
                            <p>
                                <i class="bi bi-telephone"></i> TELEPHONE: <?php echo $tel;?> <br>
                                <i class="bi bi-pin-map"></i> ADDRESS: <?php echo ucwords($address);?>
                            </p>
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

    <!-- menu -->
    <section id="menu" class="container text-white mb-5">
        <p class="fs-1 text-center">OUR MENU</p>
        <div class="row g-3">
            <?php 
                $query = "select * from weboms_menu_tb limit 6";
                $resultSet =  getQuery($query);
                if($resultSet != null){
                    foreach($resultSet as $row){
            ?>

            <div class="col-sm-2">
                <div class="card h-100 bg-transparent border-secondary">
                    <?php $pic = $row['picName']; echo "<img src='dishesPic/$pic' style='width:auto; height:200px;'>";?> 
                    <div class="card-body">
                        <h5 class="card-title"><?php echo ucwords($row['dish']);?></h5>
                        <p class="card-text">
                            <?php echo '₱'. number_format($row['price'],2); ?> <br>
                        </p>
                    </div>
                </div>
            </div>
            
            <?php } 
            }?>
        </div>
    </section>

    <!-- about -->
    <section id="about" class="container mb-5 text-white">
        <p class="fs-1 text-center">LEARN ABOUT US</p>
        <div class="card mb-3 bg-transparent border-secondary">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="https://images.unsplash.com/photo-1578474846511-04ba529f0b88?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTB8fHJlc3RhdXJhbnR8ZW58MHx8MHx8&w=1000&q=80" class="img-fluid" alt="..." style="width:100%; height:100%;">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h3 class="card-title fw-normal"><?php echo $name;?></h3> <br>
                        <p>
                            <?php echo $description;?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <section class="">
        <footer class="bg-black text-center text-white">
            <div class="container p-4 pb-0">
                <section class="mb-4">
                    <a href="#" class="text-decoration-none text-white me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-decoration-none text-white me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-decoration-none text-white me-3"><i class="bi bi-google"></i></a>
                    <a href="#" class="text-decoration-none text-white me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-decoration-none text-white me-3"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="text-decoration-none text-white"><i class="bi bi-github"></i></a>
                </section>
            </div>
            <hr>
            <div class="text-center pb-4">
                © 2020 Copyright:
                <a class="text-white text-decoration-none" href="https://mdbootstrap.com/">MDBootstrap.com</a>
            </div>
        </footer>
    </section>

</body>

</html>