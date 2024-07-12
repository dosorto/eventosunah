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
                color: #e05260;
                background: #f7d4d7;
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
                color: #fff;
                --tw-bg-opacity: 1;
                background-color: rgb(250 204 21 / var(--tw-bg-opacity))
                    /* #facc15 */
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
                background-color: rgb(202 138 4 / var(--tw-bg-opacity))
                    /* #ca8a04 */
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

        <div class="overview-boxes ml-0">
            <div
                class="box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Eventos</div>
                    <div class="number">40</div>
                    <div class="indicator">
                        <i class='bx bx-up-arrow-alt'></i>
                        <span class="text">registrados</span>
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
                class="box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Presenciales</div>
                    <div class="number">3</div>
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
                    <div class="number">16</div>
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
                    <div class="box-topic">Finalizados</div>
                    <div class="number">12</div>
                    <div class="indicator">
                        <i class='bx bx-up-arrow-alt'></i>
                        <span class="text">Finalizados</span>
                    </div>
                </div>
                <svg class="cart three w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11 9a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z" />
                    <path fill-rule="evenodd"
                        d="M9.896 3.051a2.681 2.681 0 0 1 4.208 0c.147.186.38.282.615.255a2.681 2.681 0 0 1 2.976 2.975.681.681 0 0 0 .254.615 2.681 2.681 0 0 1 0 4.208.682.682 0 0 0-.254.615 2.681 2.681 0 0 1-2.976 2.976.681.681 0 0 0-.615.254 2.682 2.682 0 0 1-4.208 0 .681.681 0 0 0-.614-.255 2.681 2.681 0 0 1-2.976-2.975.681.681 0 0 0-.255-.615 2.681 2.681 0 0 1 0-4.208.681.681 0 0 0 .255-.615 2.681 2.681 0 0 1 2.976-2.975.681.681 0 0 0 .614-.255ZM12 6a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"
                        clip-rule="evenodd" />
                    <path
                        d="M5.395 15.055 4.07 19a1 1 0 0 0 1.264 1.267l1.95-.65 1.144 1.707A1 1 0 0 0 10.2 21.1l1.12-3.18a4.641 4.641 0 0 1-2.515-1.208 4.667 4.667 0 0 1-3.411-1.656Zm7.269 2.867 1.12 3.177a1 1 0 0 0 1.773.224l1.144-1.707 1.95.65A1 1 0 0 0 19.915 19l-1.32-3.93a4.667 4.667 0 0 1-3.4 1.642 4.643 4.643 0 0 1-2.53 1.21Z" />
                </svg>


            </div>
        </div>
        <div class="sales-boxes">
            <div
                class="recent-sales box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="title">Próximos Eventos</div>
                <div class="sales-details">
                    <ul class="details">
                        <li class="topic">Date</li>
                        <li><a href="#">02 Jan 2021</a></li>
                        <li><a href="#">02 Jan 2021</a></li>
                        <li><a href="#">02 Jan 2021</a></li>
                        <li><a href="#">02 Jan 2021</a></li>
                        <li><a href="#">02 Jan 2021</a></li>
                        <li><a href="#">02 Jan 2021</a></li>
                        <li><a href="#">02 Jan 2021</a></li>
                        <li><a href="#">02 Jan 2021</a></li>
                        <li><a href="#">02 Jan 2021</a></li>
                    </ul>
                    <ul class="details">
                        <li class="topic">Conferencista</li>
                        <li><a href="#">Alex Doe</a></li>
                        <li><a href="#">David Mart</a></li>
                        <li><a href="#">Roe Parter</a></li>
                        <li><a href="#">Diana Penty</a></li>
                        <li><a href="#">Martin Paw</a></li>
                        <li><a href="#">Doe Alex</a></li>
                        <li><a href="#">Aiana Lexa</a></li>
                        <li><a href="#">Rexel Mags</a></li>
                        <li><a href="#">Tiana Loths</a></li>
                    </ul>
                    <ul class="details">
                        <li class="topic">Evento</li>
                        <li><a href="#">Delivered</a></li>
                        <li><a href="#">Pending</a></li>
                        <li><a href="#">Returned</a></li>
                        <li><a href="#">Delivered</a></li>
                        <li><a href="#">Pending</a></li>
                        <li><a href="#">Returned</a></li>
                        <li><a href="#">Delivered</a></li>
                        <li><a href="#">Pending</a></li>
                        <li><a href="#">Delivered</a></li>
                    </ul>
                    <ul class="details">
                        <li class="topic">Inscritos</li>
                        <li><a href="#">204</a></li>
                        <li><a href="#">24</a></li>
                        <li><a href="#">25</a></li>
                        <li><a href="#">70</a></li>
                        <li><a href="#">56</a></li>
                        <li><a href="#">44</a></li>
                        <li><a href="#">6</a></li>
                        <li><a href="#">23</a></li>
                        <li><a href="#">46</a></li>
                    </ul>
                </div>
                <div class="button">
                    <a href="#">Todos</a>
                </div>
            </div>
            <div
                class="top-sales box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="title">Diplomas por usuario</div>
                <ul class="top-sales-details">
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="">
                            <span class="product">Vuitton Sungses</span>
                        </a>
                        <span class="price">10</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-1.jpg" alt="">
                            <span class="product">Hourglass Jeans </span>
                        </a>
                        <span class="price">8</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="">
                            <span class="product">Nike lop</span>
                        </a>
                        <span class="price">8</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="">
                            <span class="product">Hermesi Scarves.</span>
                        </a>
                        <span class="price">7</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-1.jpg" alt="">
                            <span class="product">Ladies hutdd</span>
                        </a>
                        <span class="price">5</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="">
                            <span class="product">Womens tur</span>
                        </a>
                        <span class="price">5</span>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-4.jpg" alt="">
                            <span class="product">Addidas Running</span>
                        </a>
                        <span class="price">4</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="https://flowbite.com/docs/images/people/profile-picture-3.jpg" alt="">
                            <span class="product">Bilack Osort</span>
                        </a>
                        <span class="price">2</span>
                    </li>
                </ul>
            </div>
        </div>

</x-layouts.app>
</body>