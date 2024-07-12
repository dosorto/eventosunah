<x-layouts.app>
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
            background-color: rgb(250 204 21 / var(--tw-bg-opacity)) /* #facc15 */;
            padding: 4px 12px;
            font-size: 15px;
            font-weight: 400;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sales-boxes .box .button a:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(202 138 4 / var(--tw-bg-opacity)) /* #ca8a04 */;
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
            background: #333;
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
        <div class="overview-boxes ml-0" >
            <div class="box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Total Order</div>
                    <div class="number">40,876</div>
                    <div class="indicator">
                        <i class='bx bx-up-arrow-alt'></i>
                        <span class="text">Up from yesterday</span>
                    </div>
                </div>
                <i class='bx bx-cart-alt cart'></i>
            </div>
            <div class="box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Total Sales</div>
                    <div class="number">38,876</div>
                    <div class="indicator">
                        <i class='bx bx-up-arrow-alt'></i>
                        <span class="text">Up from yesterday</span>
                    </div>
                </div>
                <i class='bx bxs-cart-add cart two'></i>
            </div>
            <div class="box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Total Profit</div>
                    <div class="number">$12,876</div>
                    <div class="indicator">
                        <i class='bx bx-up-arrow-alt'></i>
                        <span class="text">Up from yesterday</span>
                    </div>
                </div>
                <i class='bx bx-cart cart three'></i>
            </div>
            <div class="box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="right-side">
                    <div class="box-topic">Total Return</div>
                    <div class="number">11,086</div>
                    <div class="indicator">
                        <i class='bx bx-down-arrow-alt down'></i>
                        <span class="text">Down From Today</span>
                    </div>
                </div>
                <i class='bx bxs-cart-download cart four'></i>
            </div>
        </div>
        <div class="sales-boxes">
            <div class="recent-sales box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="title">Recent Sales</div>
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
                        <li class="topic">Customer</li>
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
                        <li class="topic">Sales</li>
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
                        <li class="topic">Total</li>
                        <li><a href="#">$204.98</a></li>
                        <li><a href="#">$24.55</a></li>
                        <li><a href="#">$25.88</a></li>
                        <li><a href="#">$170.66</a></li>
                        <li><a href="#">$56.56</a></li>
                        <li><a href="#">$44.95</a></li>
                        <li><a href="#">$67.33</a></li>
                        <li><a href="#">$23.53</a></li>
                        <li><a href="#">$46.52</a></li>
                    </ul>
                </div>
                <div class="button">
                    <a href="#">See All</a>
                </div>
            </div>
            <div class="top-sales box  bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                <div class="title">Top Seling Product</div>
                <ul class="top-sales-details">
                    <li>
                        <a href="#">
                            <img src="images/sunglasses.jpg" alt="">
                            <span class="product">Vuitton Sunglasses</span>
                        </a>
                        <span class="price">$1107</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="images/jeans.jpg" alt="">
                            <span class="product">Hourglass Jeans </span>
                        </a>
                        <span class="price">$1567</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="images/nike.jpg" alt="">
                            <span class="product">Nike Sport Shoe</span>
                        </a>
                        <span class="price">$1234</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="images/scarves.jpg" alt="">
                            <span class="product">Hermes Silk Scarves.</span>
                        </a>
                        <span class="price">$2312</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="images/blueBag.jpg" alt="">
                            <span class="product">Succi Ladies Bag</span>
                        </a>
                        <span class="price">$1456</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="images/bag.jpg" alt="">
                            <span class="product">Gucci Womens's Bags</span>
                        </a>
                        <span class="price">$2345</span>
                    <li>
                        <a href="#">
                            <img src="images/addidas.jpg" alt="">
                            <span class="product">Addidas Running Shoe</span>
                        </a>
                        <span class="price">$2345</span>
                    </li>
                    <li>
                        <a href="#">
                            <img src="images/shirt.jpg" alt="">
                            <span class="product">Bilack Wear's Shirt</span>
                        </a>
                        <span class="price">$1245</span>
                    </li>
                </ul>
            </div>
        </div>
    
</x-layouts.app>