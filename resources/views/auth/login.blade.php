<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- Agrega tus estilos CSS personalizados aquí -->
    <style>
        /* ===== Google Font Import - Poformsins ===== */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom, #1254a1 23%, #ffd973 95%);
            ;
        }

        .container {
            position: relative;
            max-width: 430px;
            width: 100%;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 0 20px;
        }

        .container .forms {
            display: flex;
            align-items: center;
            height: 440px;
            width: 200%;
            transition: height 0.2s ease;
        }


        .container .form {
            width: 50%;
            padding: 30px;
            background-color: #fff;
            transition: margin-left 0.18s ease;
        }

        .container.active .login {
            margin-left: -50%;
            opacity: 0;
            transition: margin-left 0.18s ease, opacity 0.15s ease;
        }

        .container .signup {
            opacity: 0;
            transition: opacity 0.09s ease;
        }

        .container.active .signup {
            opacity: 1;
            transition: opacity 0.2s ease;
        }

        .container.active .forms {
            height: 600px;
        }

        .container .form .title {
            position: relative;
            font-size: 27px;
            font-weight: 600;
        }

        .form .title::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 30px;
            background-color: #1254a1;
            border-radius: 25px;
        }

        .form .input-field {
            position: relative;
            height: 50px;
            width: 100%;
            margin-top: 30px;
        }

        .input-field input {
            position: absolute;
            height: 100%;
            width: 100%;
            padding: 0 35px;
            border: none;
            outline: none;
            font-size: 16px;
            border-bottom: 2px solid #ccc;
            border-top: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .input-field input:is(:focus, :valid) {
            border-bottom-color: #000000;
        }

        .input-field i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 23px;
            transition: all 0.2s ease;
        }

        .input-field input:is(:focus, :valid)~i {
            color: #000000;
        }

        .input-field i.icon {
            left: 0;
        }

        .input-field i.showHidePw {
            right: 0;
            cursor: pointer;
            padding: 10px;
        }

        .form .checkbox-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .checkbox-text .checkbox-content {
            display: flex;
            align-items: center;
        }

        .checkbox-content input {
            margin-right: 10px;
            accent-color: #1967b1;
        }

        .form .text {
            color: #333;
            font-size: 14px;
        }

        .form a.text {
            color: #1254a1;
            text-decoration: none;
        }

        .form a:hover {
            text-decoration: underline;
        }

        .form .button {
            margin-top: 35px;
        }

        .form .button input {
            border: none;
            color: #fff;
            font-size: 17px;
            font-weight: 500;
            letter-spacing: 1px;
            border-radius: 6px;
            background-color: #1967b1;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .button input:hover {
            background-color: #1254b2;
        }

        .form .login-signup {
            margin-top: 30px;
            text-align: center;
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
            color: #ffd973;
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
            color: #ffd973;
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
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <a class="logo" href="#">EVENTOS <span>UNAH</span></a>
            <ul class="menu-links">
                <span id="close-menu-btn" class="material-symbols-outlined">close</span>
                <li><a href="/">Home</a></li>
            </ul>
            <span id="hamburger-btn" class="material-symbols-outlined">menu</span>
        </nav>
    </header>
    <div class="container">
        <div class="forms">
            <div class="form login active">
                <span class="title">Iniciar sesión</span>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-field">
                        <input type="email" id="email" name="email" placeholder="Correo electrónico" required autofocus>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password" name="password" class="password" placeholder="Contraseña"
                            required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>

                    <div class="checkbox-text">
                        <div class="checkbox-content">
                            <input type="checkbox" id="remember_me" name="remember">
                            <label for="remember_me" class="text">Recuérdame</label>
                        </div>

                        <a href="{{ route('password.request') }}" class="text">¿No recuerdas la contraseña?</a>
                    </div>

                    <div class="input-field button">
                        <input type="submit" value="Iniciar">
                    </div>
                </form>

                <div class="login-signup">
                    <span class="text">¿No tienes cuenta?
                        <a href="#" class="text signup-link">Registrarse</a>
                    </span>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="form signup">
                <span class="title">Registro</span>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="input-field">
                        <input type="text" placeholder="Nombre completo" id="name" name="name" required>
                        <i class="uil uil-user"></i>
                    </div>
                    <div class="input-field">
                        <input type="email" placeholder="Correo electrónico" id="email" name="email" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" class="password" placeholder="Crear contraseña" id="password"
                            name="password" required>
                        <i class="uil uil-lock icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" class="password" placeholder="Confirmar contraseña"
                            id="password_confirmation" name="password_confirmation" required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>

                    <div class="input-field button">
                        <input type="button" value="Registrar">
                    </div>
                </form>

                <div class="login-signup">
                    <span class="text">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text login-link">Iniciar
                            ahora</a></span>
                </div>
            </div>

        </div>
    </div>

    <script src="Login.js"></script>
    <script>
        const pwShowHide = document.querySelectorAll(".showHidePw");
        const pwFields = document.querySelectorAll(".password");

        pwShowHide.forEach(eyeIcon => {
            eyeIcon.addEventListener("click", () => {
                pwFields.forEach(pwField => {
                    if (pwField.type === "password") {
                        pwField.type = "text";
                        eyeIcon.classList.replace("uil-eye-slash", "uil-eye");
                    } else {
                        pwField.type = "password";
                        eyeIcon.classList.replace("uil-eye", "uil-eye-slash");
                    }
                });
            });
        });

        const signUp = document.querySelector(".signup-link");
        const login = document.querySelector(".login-link");
        const container = document.querySelector(".container");

        signUp.addEventListener("click", () => {
            container.classList.add("active");
        });

        login.addEventListener("click", () => {
            container.classList.remove("active");
        });
    </script>
</body>

</html>