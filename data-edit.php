<?php
include __DIR__ . '/partials/init.php';
$title = '修改資料';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0; //跟data-delete一樣要先拿到primary key

$sql = "SELECT * FROM `members` WHERE sid=$sid";

$r = $pdo->query($sql)->fetch();
//只有一筆直接下fetch拿資料 不用再下stmt變數 一行解決

if (empty($r)) {
    header('Location:data-list.php');  //沒資料就轉到列表頁
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
                    <h5 class="card-title">修改資料</h5>
                    <form name="form1" onsubmit="checkForm(); return false;">
                        <input type="hidden" name="sid" value="<?= $r['sid'] ?>">
                        <div class="form-group">
                            <label for="name">姓名 *</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlentities($r['name']) ?>">
                            <small class="form-text "></small>
                        </div>
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
                            <label for="email">E-mail *</label>
                            <input type="text" class="form-control" id="email" name="email" value="<?= htmlentities($r['email']) ?>">
                            <small class="form-text "></small>
                        </div>
                        <div class="form-group">
                            <label for="password">密碼 *</label>
                            <input type="text" class="form-control" id="password" name="password" value="<?= htmlentities($r['password']) ?>">
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
                            <textarea class="form-control" id="address" name="address" cols="30" rows="3"><?= htmlentities($r['address']) ?></textarea>
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
    const email_re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const mobile_re = /^09\d{2}-?\d{3}-?\d{3}$/;

    const name = document.querySelector('#name');
    const email = document.querySelector('#email');

    function readURL(input) {
        if (input.files && input.files[0]) {
            var imageTagID = input.getAttribute("targetID");
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById(imageTagID);
                img.setAttribute("src", e.target.result);
                img.style.display = "block"; //顯示要預覽的圖片
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function checkForm() {
        // 欄位的外觀要回復原來的狀態
        name.nextElementSibling.innerHTML = '';
        name.style.border = '1px #CCCCCC solid';
        email.nextElementSibling.innerHTML = '';
        email.style.border = '1px #CCCCCC solid';

        let isPass = true;
        if (name.value.length < 2) {
            isPass = false;
            name.nextElementSibling.innerHTML = '請填寫正確的姓名';
            name.style.border = '1px red solid';
        }

        if (!email_re.test(email.value)) {
            isPass = false;
            email.nextElementSibling.innerHTML = '請填寫正確的 Email 格式';
            email.style.border = '1px red solid';
        }

        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('data-edit-api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        alert('修改成功');
                        location.href = 'data-list.php'
                    } else {
                        alert(obj.error);
                    }
                })
                .catch(error => {
                    console.log('error:', error);
                });
        }
    }
</script>
<?php include __DIR__ . '/partials/html-foot.php'; ?>