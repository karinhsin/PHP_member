<?php
include __DIR__ . '/partials/init.php';
$title = '註冊';

if (isset($_SESSION['user'])) {
    header('Location:homepage.php');
    exit();
}
?>
<?php include __DIR__ . '/partials/html-head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<style>
    form .form-group small {
        color: red;
        display: none;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">註冊</h5>
                    <form name="form1" onsubmit="sendForm(); return false;">
                        <div class="form-group">
                            <label for="account">帳號</label>
                            <input type="text" class="form-control" id="account" name="account">
                            <small class="form-text">請填寫帳號</small>
                        </div>
                        <div class="form-group">
                            <label for="password">密碼</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="form-text">請填寫密碼</small>
                        </div>

                        <button type="submit" class="btn btn-primary">確認送出</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/partials/script.php'; ?>
<script>
    const email_re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    function sendForm() {
        let isPass = true;
        document.form1.account.nextElementSibling.style.display = 'none';
        document.form1.password.nextElementSibling.style.display = 'none';
        document.form1.account.style.border = '1px #CCCCCC solid';
        if (!document.form1.account.value) {
            document.form1.account.nextElementSibling.style.display = 'block';
            isPass = false;
        }
        if (!document.form1.password.value) {
            document.form1.password.nextElementSibling.style.display = 'block';
            isPass = false;
        }

        if (!email_re.test(document.form1.account.value)) {
            isPass = false;
            document.form1.account.nextElementSibling.innerHTML = '請填寫正確的 Email 格式';
            document.form1.account.nextElementSibling.style.display = 'block';
            document.form1.account.style.border = '1px red solid';
        }

        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('register-api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(obj => {
                    console.log('result:', obj);
                    if (obj.success) {
                        location.href = `login.php`;
                    } else {
                        alert(obj.error);
                    }
                });
        }
    }
</script>
<?php include __DIR__ . '/partials/html-foot.php';
