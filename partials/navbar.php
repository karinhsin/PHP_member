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
                <!-- <li class="nav-item active">
                    <a class="nav-link text-danger" href="#">購物車</a>
                </li> -->
                <li class="nav-item active">
                    <a class="nav-link" href="data-list.php">會員資料</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                
                <?php if (isset($_SESSION['user'])) : ?>
                    <li class="nav-item active">
                        <a class="nav-link"><?= $_SESSION['user']['name'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile-edit.php">編輯個人資料</a>
                    </li>
                    <li class="nav-item">
                        <?php if (!empty($_SESSION['user']['avatar'])) : ?>
                            <img src="imgs/<?= $_SESSION['user']['avatar'] ?>" alt="" width="50px">
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">登出</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="login.php">登入</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">註冊</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>