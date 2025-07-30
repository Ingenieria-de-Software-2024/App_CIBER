<?php http_response_code(404); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Error 404 - Página no encontrada</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      background-color: #1c1c1c !important; /* Fondo oscuro */
      font-family: 'Segoe UI', sans-serif;
      color: #ffffff; /* Texto blanco */
    }

    .btn-tristeza {
      background-color: #4a90e2;
      color: white;
      border-radius: 30px;
      padding: 10px 25px;
      font-weight: 500;
      border: none;
      transition: background-color 0.3s;
    }

    .btn-tristeza:hover {
      background-color: #357ab8;
    }

    .notfound-container {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 85vh;
      padding: 20px;
      flex-wrap: wrap;
      text-align: left;
      background-color: transparent; /* Quitamos fondo blanco */
    }

    .notfound-image {
      max-width: 600px;
      width: 100%;
      margin-right: 40px;
    }

    .notfound-text h1 {
      font-size: 2.5rem;
      font-weight: bold;
      letter-spacing: 0.2rem;
      margin-bottom: 20px;
      color: #ffffff;
    }

    .notfound-text h3 {
      font-size: 1.2rem;
      color: #f0f0f0;
    }

    @media (max-width: 768px) {
      .notfound-container {
        flex-direction: column;
        text-align: center;
      }

      .notfound-image {
        margin-right: 0;
        margin-bottom: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="notfound-container">
    <img src="./images/Error404.png" alt="404 Sad Character" class="notfound-image">
    <div class="notfound-text">
      <h1>A W W W . . . D O N’ T &nbsp; C R Y.</h1>
      <h3>Es solo un error 404.<br>
      Lo que estás buscando quizá fue archivado en la memoria a largo plazo 🧠</h3>
      <br><br>
      <a href="/App_CIBER/" class="btn-tristeza mt-4">Volver al Inicio</a>
    </div>
  </div>
</body>
</html>
