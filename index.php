<?php
// IS RECEIVED SHORTCUT
if (isset($_GET['q'])) {

    // VARIABLE
    $shortcut = htmlspecialchars($_GET['q']);

    // IS A SHORTCUT ?
    $bdd = new PDO('mysql:host=localhost;dbname=dbname;charset=utf8', 'root', 'password');
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
    $req->execute(array($shortcut));

    while ($result = $req->fetch()) {

        if ($result['x'] != 1) {
            header('location: index.php?error=true&message=Adresse url non connue');
            exit();
        }

    }

    // REDIRECTION
    $req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
    $req->execute(array($shortcut));

    while ($result = $req->fetch()) {

        header('location: ' . $result['url']);
        exit();

    }

}

// IS SENDING A FORM
if (isset($_POST['url'])) {

    // VARIABLE
    $url = $_POST['url'];

    // VERIFICATION
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        // PAS UN LIEN
        header('location: index.php?error=true&message=Adresse url non valide');
        exit();
    }

    // SHORTCUT
    $shortcut = crypt($url, rand());

    // HAS BEEN ALREADY SEND ?
    $bdd = new PDO('mysql:host=localhost;dbname=dbname;charset=utf8', 'root', 'password');
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
    $req->execute(array($url));

    while ($result = $req->fetch()) {

        if ($result['x'] != 0) {
            header('location: index.php?error=true&message=Adresse déjà raccourcie');
            exit();
        }

    }

    // SENDING
    $req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES(?, ?)');
    $req->execute(array($url, $shortcut));

    header('location: index.php?short=' . $shortcut);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Shortcut your url friendly">
    <title>Minify</title>
    <!--favicon-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">
    <link rel="manifest" href="assets/img/site.webmanifest">
    <!--favicon/-->
    <!--style-->
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <!--style/-->

</head>
<body>

<!--header-->
<header class="container-fluid">

    <div class="row mx-auto">

        <!--logo-->
        <div class="col-8 col-sm-9 col-md-10 col-lg-10 col-xl-8 pt-5 pl-5">
            <a href="index.php">
                <img src="assets/img/logo.png" alt="logo"
                     class="img-fluid text-left col-3 col-sm-3 col-md-2 col-lg-2 col-xl-1">
            </a>
        </div>
        <!--logo/-->

        <!--title1 & title2-->
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-left mt-3 mx-auto text-center">
            <h1 class="text-uppercase mx-auto text-center display-4">
                Minify
            </h1>
            <br>
            <h2 class="text-uppercase mx-auto text-center h4">
                Shortcut your url friendly
            </h2>
        </div>
        <!--title1 & title2 /-->
    </div>

    <div class="row">
        <!--form-->
        <form method="post" action="../"
              class="mx-auto text-center my-5 col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <input type="url" name="url" placeholder="Collez un lien à raccourcir"
                   class="mx-auto text-center col-11 col-sm-11 col-md-11 col-lg-10 col-xl-8 py-2 px-3 border border-primary rounded">
            <br>
            <input type="submit" value="Raccourcir"
                   class="mx-auto text-center mt-3 btn btn-lg btn-primary px-3 py-2 text-uppercase">
        </form>
        <!--form/-->

        <?php if (isset($_GET['error']) && isset($_GET['message'])) { ?>

            <div class="row mx-auto col-11 col-sm-11 col-md-11 col-lg-10 col-xl-8 mb-5">
                <div class="mx-auto text-center col-11 col-sm-11 col-md-11 col-lg-11 col-xl-11 bg-primary py-3 px-4 border border-white rounded">
                    <p class="success-message h6 mx-auto text-center text-uppercase text-white font-weight-bold">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </p>

                </div>
            </div>


        <?php } else if (isset($_GET['short'])) { ?>
            <div class="row mx-auto col-11 col-sm-11 col-md-11 col-lg-10 col-xl-8 mb-5">
                <div class="mx-auto text-center col-11 col-sm-11 col-md-11 col-lg-11 col-xl-11 bg-primary py-3 px-4 border border-white rounded">
                    <p class="success-message h6 mx-auto text-center text-uppercase text-white font-weight-bold">
                        URL RACCOURCIE :
                    </p>
                    <br>

                    <a href="https://minify.eosia.dev/?q=<?php echo htmlspecialchars($_GET['short']); ?>"
                       class="h6 mx-auto text-center text-uppercase text-white text-decoration-none font-weight-bold col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"
                       rel="noopener noreferrer nofollow" target="_blank">
                        https://minify.eosia.dev/?q=<?php echo htmlspecialchars($_GET['short']); ?>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</header>
<!--header/-->

<!--section partners-->
<section class="partners container-fluid">
    <div class="row">

        <div class="mx-auto text-center col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5">
            <h3 class="mx-auto text-center h5 text-uppercase text-primary">
                ils nous ont fait confiance
            </h3>
        </div>

        <div class="container-img-partners row mx-auto text-center mb-5">
            <div class="mx-auto text-center col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                <img src="assets/img/1.png" alt="partners 1 logo"
                     class="img-fluid col-6 col-sm-8 col-md-6 col-lg-6 col-xl-8">
            </div>
            <div class="mx-auto text-center col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                <img src="assets/img/2.png" alt="partners 2 logo"
                     class="img-fluid col-6 col-sm-8 col-md-6 col-lg-6 col-xl-8">
            </div>
            <div class="mx-auto text-center col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                <img src="assets/img/3.png" alt="partners 3 logo"
                     class="img-fluid col-6 col-sm-8 col-md-6 col-lg-6 col-xl-8">
            </div>
            <div class="mx-auto text-center col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                <img src="assets/img/4.png" alt="partners 4 logo"
                     class="img-fluid col-6 col-sm-8 col-md-6 col-lg-6 col-xl-8">
            </div>
        </div>
    </div>
</section>
<!--section partners/-->

<!--footer-->
<footer class="container-fluid bg-secondary">
    <p class="mx-auto text-left pl-5 py-5 text-uppercase text-white h6">
        2021©Minify.eosia.dev
    </p>
</footer>
<!--footer/-->

<!--js bootstrap-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"
        integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF"
        crossorigin="anonymous"></script>
<!--js bootstrap/-->

</body>
</html>