<?php header("HTTP/1.0 404 Not Found"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error 404 - Página no encontrada</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;600&display=swap">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #eef1f6, #dce3ec);
            color: #2c3e50;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            border: 1px solid #e1e5eb;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.07);
            border-radius: 16px;
            padding: 50px 70px;
            max-width: 620px;
            text-align: center;
            animation: fadeInScale 0.5s ease-out;
        }

        .logo {
            margin-bottom: 25px;
        }

        .logo img {
            height: 50px;
        }

        .icon {
            font-size: 50px;
            margin-bottom: 20px;
            color: #0d47a1;
        }

        .error-code {
            font-size: 80px;
            font-weight: 700;
            color: #0d47a1;
            margin: 0;
            letter-spacing: 2px;
        }

        .error-title {
            font-size: 24px;
            font-weight: 500;
            color: #34495e;
            margin-top: 10px;
        }

        .error-description {
            font-size: 16px;
            color: #7f8c8d;
            margin: 20px 0 30px;
            line-height: 1.6;
        }

        .back-home a {
            text-decoration: none;
            color: white;
            background-color: #0d47a1;
            padding: 12px 26px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }

        .back-home a:hover {
            background-color: #08306b;
        }

        .back-home a svg {
            height: 18px;
            width: 18px;
            fill: white;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.96);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }

            .error-code {
                font-size: 64px;
            }

            .error-title {
                font-size: 20px;
            }

            .error-description {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="logo">
            <img src="<?php echo URL.VIEWS.DTF; ?>img/logo-sahm.png" alt="Logo SAHM">
        </div>

        <div class="icon">⚠️</div>

        <div class="error-code">403</div>
        <div class="error-title">Acceso no autorizado</div>
        <div class="error-description">
            El registro se encuentra en un estado diferente a RECHAZADO, por esa razón no se puede editar.
        </div>
        <div class="buttons-container">
            <div class="back-home">
                <button id="regresar_menu" class="btn btn-primary">Regresar al Menú Principal</button>
            </div>
        </div>
    </div>
</body>
</html>
<script>
    $("#regresar_menu").click(function() {
        localStorage.removeItem("contratosState");
        localStorage.removeItem("firmaMaestra");
        localStorage.removeItem("datosPersonales");
        localStorage.removeItem("foto_trabajador");
        window.location.href = "<?php echo URL; ?>";
    });
</script>