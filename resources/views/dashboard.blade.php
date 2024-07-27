<x-layouts.app>

    <head>
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
        <!-- Boxiocns CDN Link -->
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <style>
            .overview-boxes {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                padding: 0 20px;
                margin-bottom: 26px;
            }

            .overview-boxes .box {
                display: flex;
                align-items: center;
                justify-content: center;
                width: calc(100% / 4 - 15px);
                padding: 15px 14px;
                border-radius: 12px;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            }

            .overview-boxes .box-topic {
                font-size: 20px;
                font-weight: 500;
            }

            .box .number {
                display: inline-block;
                font-size: 35px;
                margin-top: -6px;
                font-weight: 500;
            }

            .box .indicator {
                display: flex;
                align-items: center;
            }

            .box .indicator i {
                height: 20px;
                width: 20px;
                background: #8FDACB;
                line-height: 20px;
                text-align: center;
                border-radius: 50%;
                color: #fff;
                font-size: 20px;
                margin-right: 5px;
            }

            .box .indicator i.down {
                background: #e87d88;
            }

            .box .indicator .text {
                font-size: 12px;
            }

            .box .cart {
                display: inline-block;
                font-size: 32px;
                height: 50px;
                width: 50px;
                background: #cce5ff;
                line-height: 50px;
                text-align: center;
                color: #66b0ff;
                border-radius: 12px;
                margin: -15px 0 0 6px;
            }

            .box .cart.two {
                color: #2BD47D;
                background: #C0F2D8;
            }

            .box .cart.three {
                color: #ffc233;
                background: #ffe8b3;
            }

            .box .cart.four {
                color: #9b6dff;
                background: #d8c7ff;
            }

            .total-order {
                font-size: 20px;
                font-weight: 500;
            }

            .sales-boxes {
                display: flex;
                justify-content: space-between;
                /* padding: 0 20px; */
            }

            /* left box */
            .sales-boxes .recent-sales {
                width: 65%;
                padding: 20px 30px;
                margin: 0 20px;
                border-radius: 12px;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            }

            .sales-boxes .sales-details {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .sales-boxes .box .title {
                font-size: 24px;
                font-weight: 500;
                /* margin-bottom: 10px; */
            }

            .sales-boxes .sales-details li.topic {
                font-size: 20px;
                font-weight: 500;
            }

            .sales-boxes .sales-details li {
                list-style: none;
                margin: 8px 0;
            }

            .sales-boxes .sales-details li a {
                font-size: 18px;
                font-size: 400;
                text-decoration: none;
            }

            .sales-boxes .box .button {
                width: 100%;
                display: flex;
                justify-content: flex-end;
            }

            .sales-boxes .box .button a {
                color: #000;
                --tw-bg-opacity: 1;
                background-color: rgb(249 250 251 / var(--tw-bg-opacity))
                    /* #f9fafb */
                ;
                ;
                padding: 4px 12px;
                font-size: 15px;
                font-weight: 400;
                border-radius: 4px;
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .sales-boxes .box .button a:hover {
                --tw-bg-opacity: 1;
                background-color: rgb(243 244 246 / var(--tw-bg-opacity))
                    /* #f3f4f6 */
                ;

            }

            /* Right box */
            .sales-boxes .top-sales {
                width: 35%;
                padding: 20px 30px;
                margin: 0 20px 0 0;
                border-radius: 12px;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            }

            .sales-boxes .top-sales li {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin: 10px 0;
            }

            .sales-boxes .top-sales li a img {
                height: 40px;
                width: 40px;
                object-fit: cover;
                border-radius: 12px;
                margin-right: 10px;
                background: #fff;
            }

            .sales-boxes .top-sales li a {
                display: flex;
                align-items: center;
                text-decoration: none;
            }

            .sales-boxes .top-sales li .product,
            .price {
                font-size: 17px;
                font-weight: 400;
            }

            @media (max-width: 1150px) {
                .sales-boxes {
                    flex-direction: column;
                }

                .sales-boxes .box {
                    width: 100%;
                    overflow-x: scroll;
                    margin-bottom: 30px;
                }

                .sales-boxes .top-sales {
                    margin: 0;
                }
            }

            @media (max-width: 1000px) {
                .overview-boxes .box {
                    width: calc(100% / 2 - 15px);
                    margin-bottom: 15px;
                }
            }

            @media (max-width: 700px) {

                .sales-boxes .sales-details {
                    width: 560px;
                }
            }

            @media (max-width: 550px) {
                .overview-boxes .box {
                    width: 100%;
                    margin-bottom: 15px;
                }

            }

            @media (max-width: 400px) {
                .home-section nav {
                    width: 100%;
                    left: 0;
                }

                .sidebar.active~.home-section nav {
                    left: 60px;
                    width: calc(100% - 60px);
                }
            }
        </style>
    </head>

    <body>
        <div class="flex align-center mb-2  mr-6" style="align-items: center; justify-content: space-between;">
            <h2 class=" inline-block font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-6 ml-5">
                Dashboard
            </h2>
            <h2 class=" inline-block font-medium text-xl text-gray-800 leading-tight dark:text-white text-right mb-6">
                {{ $now->translatedFormat('l, d F Y') }}
            </h2>
        </div>

        <div class="overview-boxes">
            <div
                class="box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Eventos</div>
                    <div class="number">{{ $cantidadEventos }}</div>
                    <div class="indicator">
                        <i class='bx bx-up-arrow-alt'></i>
                        <span class="text">Registrados</span>
                    </div>
                </div>
                <svg class="cart w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div
                class="box transition bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Presenciales</div>
                    <div class="number">{{ $eventosPresenciales }}</div>
                    <div class="indicator">
                        <i class='bx bx-up-arrow-alt'></i>
                        <span class="text">En localidades</span>
                    </div>
                </div>
                <svg class="cart two w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm4.996 2a1 1 0 0 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 8a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 11a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 14a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Z"
                        clip-rule="evenodd" />
                </svg>

            </div>

            <div
                class="box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Virtuales</div>
                    <div class="number">{{$eventosVirtuales}}</div>
                    <div class="indicator">
                        <i class='bx bx-up-arrow-alt'></i>
                        <span class="text">En plataformas</span>
                    </div>
                </div>
                <svg class="cart four w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M5 3a2 2 0 0 0-2 2v5h18V5a2 2 0 0 0-2-2H5ZM3 14v-2h18v2a2 2 0 0 1-2 2h-6v3h2a1 1 0 1 1 0 2H9a1 1 0 1 1 0-2h2v-3H5a2 2 0 0 1-2-2Z"
                        clip-rule="evenodd" />
                </svg>

            </div>

            <div
                class="box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Usuarios</div>
                    <div class="number">{{ $cantidadEventos }}</div>
                    <div class="indicator">
                        <i class='bx bx-up-arrow-alt'></i>
                        <span class="text">Registrados</span>
                    </div>
                </div>
                <svg class="w-6 h-6 cart three text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <div class="sales-boxes">
            <div
                class="recent-sales box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="title">Próximas Conferencias</div>
                <div class="sales-details">


                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 dark:text-gray-100 bg-gray-50 dark:bg-gray-500">
                                        Fecha
                                    </th>
                                    <th scope="col" class="px-6 py-3 dark:text-gray-100 bg-gray-50 dark:bg-gray-500">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3 dark:text-gray-100 bg-gray-50 dark:bg-gray-500">
                                        Lugar
                                    </th>
                                    <th scope="col" class="px-6 py-3 dark:text-gray-100 bg-gray-50 dark:bg-gray-500">
                                        Conferencista
                                    </th>
                                    <th scope="col" class="px-6 py-3 dark:text-gray-100 bg-gray-50 dark:bg-gray-500">
                                        Opciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($conferencias as $conferencia)
                                    <tr class="bg-white dark:bg-gray-800 text-gray-600">
                                        <td class="px-6 py-4 ">
                                            <div class="flex items
                                                        -center">
                                                <div>
                                                    <p class="font-medium dark:text-gray-400">{{ $conferencia->fecha }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items
                                                        -center">
                                                <div>
                                                    <p class="font-medium dark:text-gray-400">{{ $conferencia->nombre }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items
                                                        -center">
                                                <div>
                                                    <p class="font-medium dark:text-gray-400">{{ $conferencia->lugar }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items
                                                        -center">
                                                <div>
                                                    <p class="font-medium dark:text-gray-400">
                                                        {{ $conferencia->conferencista->persona->nombre }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items
                                                        -center">
                                                <div>
                                                    <button class="button"><a
                                                            href="{{ route('conferencia', $conferencia->id) }}">Ir
                                                        </a></button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
            <div
                class="top-sales box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="title">Diplomas por usuario</div>
                <ul class="top-sales-details">
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    Kite Siki
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    email@gmail.com
                                </p>
                            </div>
                        </a>
                        <span class="price">10</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-1.jpg" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    Oli Manu
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    email@gmail.com
                                </p>
                            </div>
                        </a>
                        <span class="price">8</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    Cornert Sims
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    email@yahoo.com
                                </p>
                            </div>
                        </a>
                        <span class="price">8</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    Joser Olet
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    Olet@gmail.com
                                </p>
                            </div>
                        </a>
                        <span class="price">7</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-1.jpg" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    Mani Sims
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    email@gmail.com
                                </p>
                            </div>
                        </a>
                        <span class="price">5</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    Osmeru Sone
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    email@gmail.com
                                </p>
                            </div>
                        </a>
                        <span class="price">5</span>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-4.jpg" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    Neil Sims
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    nelik@gmail.com
                                </p>
                            </div>
                        </a>
                        <span class="price">4</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-3.jpg" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    Masi Sims
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    email@gmail.com
                                </p>
                            </div>
                        </a>
                        <span class="price">2</span>
                    </li>
                </ul>
            </div>
        </div>

</x-layouts.app>
</body>