<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo SITENAME; ?></title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js">
    </script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLAPP; ?>/public/css/style.css">

</head>

<body class="<?= CONTROLADOR_ACTUAL ?>">


    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">

        <div class="container-fluid">

            <a class="navbar-brand fw-bold" href="<?= URLAPP ?>/dashboard/">
                <i class="fa-solid fa-book"></i> Biblioteca Escolar
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link <?= $paginaActual == 'dashboard' ? 'active' : '' ?>"
                            href="<?= URLAPP ?>/dashboard/">
                            Dashboard
                        </a>
                    </li>


                    <?php if (tieneRol(['Docente', 'Bibliotecario', 'Administrador'])): ?>

                        <li class="nav-item">
                            <a class="nav-link <?= $paginaActual == 'reservas' ? 'active' : '' ?>"
                                href="<?= URLAPP ?>/reserva/nueva">
                                Reservar
                            </a>
                        </li>

                    <?php endif; ?>


                    <?php if (tieneRol(['Bibliotecario', 'Administrador'])): ?>

                        <li class="nav-item">
                            <a class="nav-link <?= $paginaActual == 'reportes' ? 'active' : '' ?>"
                                href="<?= URLAPP ?>/reporte/">
                                Reportes
                            </a>
                        </li>

                    <?php endif; ?>


                    <?php if (tieneRol(['Administrador'])): ?>

                        <li class="nav-item">
                            <a class="nav-link <?= $paginaActual == 'usuarios' ? 'active' : '' ?>"
                                href="<?= URLAPP ?>/usuario/listar">
                                Usuarios
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>

                <div class="d-flex align-items-center gap-3">

                    <span class="text-white">
                        <i class="fa-solid fa-user"></i> <?php echo $_SESSION['nombre']; ?>
                    </span>

                    <a class="btn btn-danger btn-sm" href="<?php echo URLAPP; ?>/auth/logout">
                        Cerrar Sesión
                    </a>

                </div>

            </div>

        </div>

    </nav>