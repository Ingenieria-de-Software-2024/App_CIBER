<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?= asset('build/js/app.js') ?>"></script>
    <link rel="shortcut icon" href="<?= asset('images/CiberEscudo.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title> App Ciber</title>
</head>

<body>
    <div class="row justify-content-center bg-dark bg-gradient text-light">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient p-1">

            <div class="container-fluid">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

                <a class="navbar-brand" href="/App_CIBER/"><img src="<?= asset('./images/Ciber.png') ?>" width="65px'" alt="cit"> CIBER</a>

                <div class="collapse navbar-collapse" id="navbarToggler">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: 0;">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/App_CIBER"><i class="bi bi-house-fill me-2"></i>Inicio</a>
                        </li>

                        <div class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-gear me-2"></i>Servicio
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-dark " id="dropwdownRevision" style="margin: 0;">
                                <li>
                                    <a class="dropdown-item nav-link text-white " href="/App_CIBER/bitacora"><i class="ms-lg-0 ms-2 bi bi-plus-circle me-2"></i> Bitácora</a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link text-white " href="/aplicaciones/nueva"><i class="ms-lg-0 ms-2 bi bi-plus-circle me-2"></i> Acumulado</a>
                                </li>
                            </ul>
                        </div>
                        <div class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-card-checklist"></i> Registros
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-dark " id="dropwdownRevision" style="margin: 0;">
                                <li>
                                    <a class="dropdown-item nav-link text-white " href="/App_CIBER/ciberataque"><i class="bi bi-virus2"></i> Malwares</a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link text-white " href="/aplicaciones/nueva"><i class="bi bi-microsoft"></i> Softwares</a>
                                </li>
                            </ul>
                        </div>
                    </ul>

                    <div class="col-lg-1 d-grid mb-2">
                        <a href="/App_CIBER/" class="btn btn-lg btn-danger"><i class="bi bi-door-open-fill"></i></a>
                    </div>
                </div>

            </div>
        </nav>
        <div class="progress fixed-bottom" style="height: 6px;">
            <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">

            <?php echo $contenido; ?>
        </div>
        <div class="container-fluid bg-primary bg-gradient text-light">
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <p style="font-size:xx-small; font-weight: bold;">
                        Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>