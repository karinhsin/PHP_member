<?php
//資料表要增加一個存放圖片檔名的欄位
// ALTER TABLE `members` ADD `avatar` VARCHAR(255) NULL DEFAULT '' AFTER `id`;

include __DIR__ . '/partials/init.php';
$title = '修改個人資料';

//先判斷你有沒有登入，如果沒有登入就離開
if (!isset($_SESSION['user'])) {
    header('Location: homepage.php');
    exit;
}

$sql = "SELECT * FROM `members` WHERE sid=" . intval($_SESSION['user']['sid']); //如果有登入就會拿到他的個人資料

$r = $pdo->query($sql)->fetch();
//只有一筆直接下fetch拿資料 不用再下stmt變數 一行解決

if (empty($r)) {
    header('Location: homepage.php'); //沒資料就轉到首頁
    exit;
}

//echo json_encode($r, JSON_UNESCAPED_UNICODE);
?>
<?php include __DIR__ . '/partials/html-head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<style>
    form .form-group small {
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">修改個人資料</h5>

                    <form name="form1" onsubmit="checkForm(); return false;">
                        <input type="hidden" name="sid" value="<?= $r['sid'] ?>">
                        <div class="form-group">
                            <label for="avatar">頭像</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" onchange="readURL(this)" targetID="preview-img" />
                            <?php if (empty($r['avatar'])) : ?>
                                <!-- 預設的大頭貼 -->
                                <img id="preview-img" src="#" alt="" width="300px" style="display:none">
                            <?php else : ?>
                                <!-- 顯示原本的大頭貼 -->
                                <img id="preview-img" src="imgs/<?= $r['avatar'] ?>" alt="" width="300px">
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="email">email (帳號不能修改)</label>
                            <input type="text" class="form-control" disabled value="<?= htmlentities($r['email']) ?>">
                            <small class="form-text "></small>
                        </div>
                        <div class="form-group">
                            <label for="name">姓名*</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlentities($r['name']) ?>">
                            <small class="form-text "></small>
                        </div>
                        <div class="form-group">
                            <label for="mobile">手機</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="<?= htmlentities($r['mobile']) ?>">
                            <small class="form-text "></small>
                        </div>
                        <div class="form-group">
                            <label for="birthday">生日</label>
                            <input type="date" class="form-control" id="birthday" name="birthday" value="<?= htmlentities($r['birthday']) ?>">
                            <small class="form-text "></small>
                        </div>
                        <div class="form-group">
                            <label for="address">地址</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= htmlentities($r['address']) ?>">
                            <small class="form-text "></small>
                        </div>

                        <button type="submit" class="btn btn-primary">修改</button>
                    </form>


                </div>
            </div>
        </div>
    </div>


</div>
<?php include __DIR__ . '/partials/script.php'; ?>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var imageTagID = input.getAttribute("targetID");
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById(imageTagID);
                img.setAttribute("src", e.target.result);
                img.style.display = "block";
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


    function checkForm() {

        const fd = new FormData(document.form1);
        fetch('profile-edit-api.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    alert('修改成功');
                } else {
                    alert(obj.error);
                }
            })
            .catch(error => {
                console.log('error:', error);
            });


    }
</script>
<?php include __DIR__ . '/partials/html-foot.php'; ?>