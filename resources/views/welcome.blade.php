<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio RRHH</title>
  <link rel="stylesheet" href="">
  <!-- Google Fonts Links For Icon -->
  <style>
    /* Importing Google font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      padding: 20px;
    }

    header .navbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1200px;
      margin: 0 auto;
    }

    .navbar .logo {
      color: #fff;
      font-weight: 600;
      font-size: 2.1rem;
      text-decoration: none;
    }

    .navbar .logo span {
      color: #facc15;
    }

    .navbar .menu-links {
      display: flex;
      list-style: none;
      gap: 35px;
    }

    .navbar a {
      color: #fff;
      text-decoration: none;
      transition: 0.2s ease;
    }

    .navbar a:hover {
      color: #facc15;
    }

    .hero-section {
      height: 100vh;
      background: linear-gradient(to bottom, #1254a1 23%, #facc15 95%);
      background-position: center;
      background-size: cover;
      display: flex;
      align-items: center;
      padding: 0 20px;
    }

    .hero-section .content {
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
      color: #fff;
    }

    .hero-section .content h2 {
      font-size: 3rem;
      max-width: 600px;
      line-height: 70px;
    }

    .hero-section .content p {
      font-weight: 300;
      max-width: 600px;
      margin-top: 15px;
    }

    .hero-section .content button {
      background: #facc15;
      color: #fff;
      padding: 12px 30px;
      border: none;
      font-size: 1rem;
      border-radius: 6px;
      margin-top: 38px;
      cursor: pointer;
      font-weight: 500;
      transition: 0.2s ease;
    }

    .menu-links li button {
      background: #facc15;
      color: #fff;
      padding: 8px 15px;
      border: none;
      font-size: 1rem;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: 0.2s ease;
    }

    .hero-section .content button:hover {
      background: #ca8a04;
    }

    #close-menu-btn {
      position: absolute;
      right: 20px;
      top: 20px;
      cursor: pointer;
      display: none;
    }

    #hamburger-btn {
      color: #fff;
      cursor: pointer;
      display: none;
    }

    @media (max-width: 768px) {
      header {
        padding: 10px;
      }

      header.show-mobile-menu::before {
        content: "";
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(5px);
      }

      .navbar .logo {
        font-size: 1.7rem;
      }


      #hamburger-btn,
      #close-menu-btn {
        display: block;
      }

      .navbar .menu-links {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100vh;
        background: #fff;
        flex-direction: column;
        padding: 70px 40px 0;
        transition: left 0.2s ease;
      }

      header.show-mobile-menu .navbar .menu-links {
        left: 0;
      }

      .navbar a {
        color: #000;
      }

      .hero-section .content {
        text-align: center;
      }

      .hero-section .content :is(h2, p, img) {
        max-width: 100%;
      }

      .hero-section .content h2 {
        font-size: 2.3rem;
        line-height: 60px;
      }

      .hero-section .content button {
        padding: 9px 18px;
      }
    }
  </style>
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>
<body>
  <section>
    <header>
      <nav class="navbar">
        <a class="logo" href="#">EVENTOS <span>UNAH</span></a>
        <ul class="menu-links">
          <span id="close-menu-btn" class="material-symbols-outlined">close</span>
          <li><a href="/login">Acceder</a></li>
          <li><a href="/register">Registrarme</a></li>
        </ul>
        <span id="hamburger-btn" class="material-symbols-outlined">menu</span>
      </nav>
    </header>

    <section class="hero-section">
      <div class="content">
        <h2>Gestión De Eventos De UNAH-Choluteca</h2>
        <p>
          La gestión de eventos es una parte fundamental de la administración de una organización.
        </p>
        <button><a href="/login" style="text-decoration: none; color: #000; font-weight: 500;">Acceder
            ahora</a></button>
      </div>
    </section>

    <script>
      const header = document.querySelector("header");
      const hamburgerBtn = document.querySelector("#hamburger-btn");
      const closeMenuBtn = document.querySelector("#close-menu-btn");

      // Toggle mobile menu on hamburger button click
      hamburgerBtn.addEventListener("click", () => header.classList.toggle("show-mobile-menu"));

      // Close mobile menu on close button click
      closeMenuBtn.addEventListener("click", () => hamburgerBtn.click());
    </script>
  </section>
</body>

</html>