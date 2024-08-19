<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registro</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
    <!-- Agrega tus estilos CSS personalizados aquí -->
    <style>
        /* ===== Google Font Import - Poformsins ===== */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

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
            background-color:#f3f4f6;
           /* background: linear-gradient(to bottom, #1254a1 23%, #facc15 95%);*/

        }


        .containerRegister {
            position: relative;
            max-width: 910px;
            height: 570px;
            width: 90%;
            border-radius: 6px;
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .containerRegister h1 {
            position: relative;
            font-size: 18px;
            font-weight: 500;
            color: #333;
        }

        .containerRegister form {
            position: relative;
            margin-top: 16px;
            min-height: 440px;
            background-color: #ffffff;
            overflow: hidden;
        }

        .containerRegister form .formRegister {
            position: absolute;
            background-color: #ffffff;
            transition: 0.2s ease;
        }

        .containerRegister form .formRegister.second {
            opacity: 0;
            pointer-events: none;
            transform: translateX(100%);
        }

        .containerRegister form.secActive .formRegister.second {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
        }

        .containerRegister form.secActive .formRegister.first {
            opacity: 0;
            pointer-events: none;
            transform: translateX(-100%);
        }

        .containerRegister form .title {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 500;
            margin: 6px 0;
            color: #facc15;
        }

        .containerRegister form .fields {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .containerRegister form .fields .inputRegister {
            display: flex;
            width: calc(100% / 3 - 15px);
            flex-direction: column;
            margin: 4px 0;
        }


        .containerRegister form .fields .inputRegister .direccion {
            display: flex;
            width: 840px;
            flex-direction: column;
            margin: 4px 0;
        }

        .containerRegister .inputRegister label {
            font-size: 12px;
            font-weight: 500;
            color: #2e2e2e;
        }

        .containerRegister .inputRegister input,
        select,
        textarea {
            outline: none;
            font-size: 14px;
            font-weight: 400;
            color: #333;
            border-radius: 5px;
            border: 1px solid;
            padding: 0 15px;
            height: 42px;
            margin: 8px 0;
        }

        .containerRegister .inputRegister input :focus,
        .containerRegister .inputRegister select:focus .inputRegister textarea:focus {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.13);
        }

        .containerRegister .inputRegister select,
        .inputRegister input .inputRegister textarea[type="date"] {
            color: #707070;
        }

        .containerRegister .inputRegister input[type="date"]:valid {
            color: #333;
        }

        .containerRegister form button,
        .backBtn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 45px;
            max-width: 266px;
            width: 100%;
            border: none;
            outline: none;
            color: #000;
            font-weight: 500;
            border-radius: 5px;
            margin: 18px 0;
            --tw-bg-opacity: 1;
            background-color: rgb(250 204 21 / var(--tw-bg-opacity))
                /* #facc15 */
            ;
            transition: all 0.3s linear;
            cursor: pointer;
        }

        .containerRegister form .btnText {
            font-weight: 500;
            font-size: 14px;
        }

        .containerRegister form button:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(202 138 4 / var(--tw-bg-opacity))
                /* #ca8a04 */
            ;
        }

        .containerRegister form button i,
        form .backBtn i {
            margin: 0 6px;
        }

        .containerRegister form .backBtn i {
            transform: rotate(180deg);
        }

        .containerRegister form .buttons {
            display: flex;
            align-items: center;
        }

        .containerRegister form .buttons button,
        .backBtn {
            margin-right: 14px;
            margin-left: 10px;
        }

        @media (max-width: 750px) {
            .containerRegister form {
                overflow-y: scroll;
            }

            .containerRegister form::-webkit-scrollbar {
                display: none;
            }

            .containerRegister form .fields .inputRegister {
                width: calc(100% / 2 - 15px);
            }
        }

        @media (max-width: 550px) {
            .containerRegister form .fields .inputRegister {
                width: 100%;
            }
        }

        .max {
            max-width: 430px;
        }

        .container {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            width: 900px;
            margin: 0 20px;
        }

        .container .forms {
            display: flex;
            align-items: center;
            height: 540px;
            width: 200%;
            transition: height 0.2s ease;
        }


        .container .form {
            width: 50%;
            height: 540px;
            padding: 30px;
            background-color: #fff;
            transition: margin-left 0.10s ease;
        }

        .container.active .login {
            margin-left: -50%;
            opacity: 0;
            transition: margin-left 0.10s ease, opacity 0.12s ease;
        }

        .container .signup {

            opacity: 0;
            transition: opacity 0.08s ease;
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

        .container .form .title::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 30px;
            background-color: #1254a1;
            border-radius: 25px;
        }

        .container .form .input-field {
            position: relative;
            height: 50px;
            width: 100%;
            margin-top: 30px;
        }

        .container .input-field input {
            position: absolute;
            height: 100%;
            width: 100%;
            padding: 0 35px;
            border: none;
            outline: none;
            font-size: 16px;
            border-bottom: 2px solid #000;
            border-top: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .container .input-field input:is(:focus, :valid) {
            border-bottom-color: #facc15;
        }

        .container .input-field i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #000;
            font-size: 23px;
            transition: all 0.2s ease;
        }

        .container .input-field input:is(:focus, :valid)~i {
            color: #facc15;
        }

        .input-field i.icon {
            left: 0;
        }

        .container .input-field i.showHidePw {
            right: 0;
            cursor: pointer;
            padding: 10px;
        }

        .container .form .checkbox-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .container .checkbox-text .checkbox-content {
            display: flex;
            align-items: center;
        }

        .container .checkbox-content input {
            margin-right: 10px;
            accent-color: #facc15;
        }

        .container .form .text {
            color: #333;
            font-size: 14px;
        }

        .container .form a.text {
            color: #1254a1;
            text-decoration: none;
        }

        .container .form a:hover {
            text-decoration: underline;
        }

        .container .form .button {
            margin-top: 35px;
        }

        .container .form .button input {
            border: none;
            color: #000;
            font-size: 17px;
            font-weight: 500;
            letter-spacing: 1px;
            border-radius: 6px;
            --tw-bg-opacity: 1;
            background-color: rgb(250 204 21 / var(--tw-bg-opacity))
                /* #facc15 */
            ;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .container .button input:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(202 138 4 / var(--tw-bg-opacity))
                /* #ca8a04 */
            ;
        }


        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            z-index: 1;
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
            <a class="logo" href="#">EVENTIS</a>
            <ul class="menu-links">
                <span id="close-menu-btn" class="material-symbols-outlined">close</span>
                <li><a href="/">Home</a></li>
            </ul>
            <span id="hamburger-btn" class="material-symbols-outlined">menu</span>
        </nav>
    </header>
    <div class="container max ">
        <div class="forms">
            <div class="form login active dark:bg-gray-900">
                <span class="title">Registro De Usuario</span>

                <form method="POST" action="{{ route('registerpost') }}">
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
                            id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                            required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>

                    <div class="input-field button text">
                        <input type="submit" style="text-align: center;" value="Siguiente">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tipoPerfil = document.getElementById('IdTipoPerfil');
        const estudianteFields = document.querySelectorAll('.estudiante');
        const label1 = document.getElementById('correoLabel');
        const input1 = document.getElementById('correo_institucional');
        const label2 = document.getElementById('numeroLabel');
        const input2 = document.getElementById('cuenta_estudiante');

        tipoPerfil.addEventListener('change', () => {
            if (tipoPerfil.value === 'Estudiante') {
                estudianteFields.forEach(field => field.style.display = 'flex');
                label1.textContent = 'Correo Institucional';
                input1.placeholder = 'Ingrese correo institucional';
                label2.textContent = 'Número de Cuenta';
                input2.placeholder = 'Número de cuenta';
            } else if (tipoPerfil.value === 'Docente') {
                estudianteFields.forEach(field => field.style.display = 'flex');
                label1.textContent = 'Correo Institucional';
                input1.placeholder = 'Ingrese correo Institucional';
                label2.textContent = 'Identificación de docente';
                input2.placeholder = 'Numero de empleado';
            } else {
                estudianteFields.forEach(field => field.style.display = 'none');
            }
        });
    });
</script>

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
        container.classList.remove("max");
    });

    login.addEventListener("click", () => {
        container.classList.remove("active");
        container.classList.add("max");

    });
</script>
<script>
    const header = document.querySelector("header");
    const hamburgerBtn = document.querySelector("#hamburger-btn");
    const closeMenuBtn = document.querySelector("#close-menu-btn");

    // Toggle mobile menu on hamburger button click
    hamburgerBtn.addEventListener("click", () => header.classList.toggle("show-mobile-menu"));

    // Close mobile menu on close button click
    closeMenuBtn.addEventListener("click", () => hamburgerBtn.click());
</script>
