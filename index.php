<?php
    // header("location:Login.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestoName</title>

    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <script src="js/bootstrap 5/bootstrap.min.js"></script>
    <!-- css online icon bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <style>
    .map-container {
        overflow: hidden;
        padding-bottom: 56.25%;
        position: relative;
        height: 0;
    }

    .map-container iframe {
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        position: absolute;
    }
    </style>
</head>

<body class="bg-dark">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container py-3">
            <a class="navbar-brand fs-4" href="#">RESTONAME</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#home">
                            <i class="bi bi-house-door"></i>
                            HOME
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#menu">
                            <i class="bi bi-list"></i>
                            MENU
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#about">
                            <i class="bi bi-info-circle"></i>
                            ABOUT
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#contact">
                            <i class="bi bi-telephone"></i>
                            CONTACT
                        </a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <a class="btn btn-outline-light" type="button" href="Login.php">
                        <i class="bi bi-person-circle"></i>
                        LOGIN
                    </a>
                </form>
            </div>
        </div>
    </nav>
    <hr>

    <!-- home -->
    <section id="home" class="container mb-5" style="margin-top:80px;">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://media.self.com/photos/622912847b959736301bfb91/2:1/w_2119,h_1059,c_limit/GettyImages-1301412050.jpg"
                        class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="card bg-dark border-secondary pt-2">
                            <h5>First slide label</h5>
                            <p>Some representative placeholder content for the first slide.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://media.self.com/photos/622912847b959736301bfb91/2:1/w_2119,h_1059,c_limit/GettyImages-1301412050.jpg"
                        class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="card bg-dark border-secondary pt-2">
                            <h5>Second slide label</h5>
                            <p>Some representative placeholder content for the first slide.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://media.self.com/photos/622912847b959736301bfb91/2:1/w_2119,h_1059,c_limit/GettyImages-1301412050.jpg"
                        class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="card bg-dark border-secondary pt-2">
                            <h5>Third slide label</h5>
                            <p>Some representative placeholder content for the first slide.</p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- menu -->
    <section id="menu" class="container text-white mb-5">
        <p class="fs-1 text-center">OUR MENU</p>
        <div class="row g-3">
            <div class="col-sm-3">
                <div class="card h-100 bg-transparent border-secondary">
                    <img src="https://food.fnr.sndimg.com/content/dam/images/food/fullset/2021/02/05/Baked-Feta-Pasta-4_s4x3.jpg.rend.hgtvcom.616.493.suffix/1615916524567.jpeg"
                        alt="" wdith="100">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card h-100 bg-transparent border-secondary">
                    <img src="https://food.fnr.sndimg.com/content/dam/images/food/fullset/2021/02/05/Baked-Feta-Pasta-4_s4x3.jpg.rend.hgtvcom.616.493.suffix/1615916524567.jpeg"
                        alt="" wdith="100">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card h-100 bg-transparent border-secondary">
                    <img src="https://food.fnr.sndimg.com/content/dam/images/food/fullset/2021/02/05/Baked-Feta-Pasta-4_s4x3.jpg.rend.hgtvcom.616.493.suffix/1615916524567.jpeg"
                        alt="" wdith="100">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card h-100 bg-transparent border-secondary">
                    <img src="https://food.fnr.sndimg.com/content/dam/images/food/fullset/2021/02/05/Baked-Feta-Pasta-4_s4x3.jpg.rend.hgtvcom.616.493.suffix/1615916524567.jpeg"
                        alt="" wdith="100">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- about -->
    <section id="about" class="container mb-5 text-white">
        <p class="fs-1 text-center">LEARN ABOUT US</p>
        <div class="card mb-3 bg-transparent border-secondary">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="https://images.unsplash.com/photo-1578474846511-04ba529f0b88?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTB8fHJlc3RhdXJhbnR8ZW58MHx8MHx8&w=1000&q=80"
                        class="img-fluid" alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h3 class="card-title fw-normal">RESTONAME</h3> <br>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. In egestas erat imperdiet sed euismod nisi porta lorem
                            mollis. In
                            dictum non consectetur a erat nam at lectus urna. Pharetra pharetra massa massa ultricies mi
                            quis hendrerit. Porta lorem mollis aliquam ut porttitor leo. Et leo duis ut diam. Malesuada
                            fames ac turpis egestas. Purus sit amet volutpat consequat. Mattis nunc sed blandit libero
                            volutpat sed cras ornare arcu. Lectus sit amet est placerat in egestas erat. Pharetra
                            convallis
                            posuere morbi leo urna molestie at elementum eu. Ac tortor vitae purus faucibus ornare.
                            Gravida
                            rutrum quisque non tellus. Proin fermentum leo vel orci porta non pulvinar neque laoreet.
                            Lacus
                            vel facilisis volutpat est velit. Facilisis sed odio morbi quis. Sit amet consectetur
                            adipiscing
                            elit ut aliquam purus. Maecenas sed enim ut sem. <br><br>

                            Lacus luctus accumsan tortor posuere ac ut consequat semper. Lobortis elementum nibh tellus
                            molestie nunc non blandit massa. Blandit turpis cursus in hac habitasse platea dictumst.
                            Rutrum
                            tellus pellentesque eu tincidunt. Aenean sed adipiscing diam donec adipiscing. Id diam vel
                            quam
                            elementum pulvinar etiam non. Elit at imperdiet dui accumsan sit amet nulla facilisi morbi.
                            Lectus urna duis convallis convallis tellus. Vitae congue eu consequat ac felis. Id diam vel
                            quam elementum pulvinar etiam non quam. Sed nisi lacus sed viverra. At auctor urna nunc id
                            cursus metus aliquam. Sed viverra ipsum nunc aliquet bibendum. Nec dui nunc mattis enim ut
                            tellus elementum sagittis vitae. Vestibulum lorem sed risus ultricies tristique nulla
                            aliquet
                            enim tortor. Euismod lacinia at quis risus sed vulputate. Convallis convallis tellus id
                            interdum
                            velit laoreet. Nulla posuere sollicitudin aliquam ultrices sagittis orci a. <br><br>

                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                            voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                            cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- contact -->
    <section id="contact" class="container mb-5 text-white">
        <p class="fs-1 text-center">CONTACT US</p>
        <div class="card mb-3 bg-transparent border-secondary">
            <div class="row g-0">
                <div class="container col-md-4 mt-4 mb-4">
                    <input type="text" class="form-control form-control-lg mb-3" placeholder="YOUR NAME">
                    <input type="text" class="form-control form-control-lg mb-3" placeholder="YOUR EMAIL">
                    <input type="text" class="form-control form-control-lg mb-3" placeholder="SUBJECT">
                    <textarea name="" id="" cols="30" rows="6" class="form-control form-control-lg mb-3"
                        placeholder="ENTER MESSAGE"></textarea>
                    <button class="btn btn-lg btn-danger col-12">SUBMIT</button>
                </div>
                <div class="col-md-8">
                    <div id="map-container-google-1" class="z-depth-1-half map-container" style="height: 500px">
                        <iframe src="https://maps.google.com/maps?q=manhatan&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <section class="">
        <footer class="bg-black text-center text-white">
            <div class="container py-4 pb-0">
                reviews dito
            </div>
            <div class="container p-4 pb-0">
                <section class="mb-4">
                    <a href="#" class="text-decoration-none text-white me-3">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-white me-3">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-white me-3">
                        <i class="bi bi-google"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-white me-3">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-white me-3">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-white">
                        <i class="bi bi-github"></i>
                    </a>
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