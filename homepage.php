<?php
include __DIR__ . '/partials/init.php';
$title = '首頁';
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>好好食飯</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
<style>
    .homePage {
        text-align: center;
        margin-top: 5px;
        width: 100%;
    }

    .homePage img {
        width: 100%;
        height: auto;
    }
</style>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="homepageIcon">
            <a class="text-success fs-1" href="homepage.php">好好食飯</a>
        </div>
        <div class="container">
            <!-- <a class="navbar-brand" href="#">好好食飯</a> -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">商城</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="data-list.php">好食專欄</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">外送餐廳列表</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link text-danger" href="#">購物車</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="data-list.php">會員資料</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="homePage">
        <img src="https://d3gjxtgqyywct8.cloudfront.net/o2o/image/b7d8f137-0529-4263-a2cd-71f0bd762010.jpg" alt="">
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>

</html>