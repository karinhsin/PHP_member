<?php
include __DIR__ . '/partials/init.php';
$title = '資料列表';

// 固定每一頁最多幾筆
$perPage = 5;

//query string parameters
$qs = []; //設一個空陣列在這邊，有關鍵字的時候放進來

// 關鍵字查詢
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// 用戶決定查看第幾頁，預設值為 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$where = ' WHERE 1 '; //WHERE前後空白/
if (!empty($keyword)) { //如果關鍵字不是空的
    // $where .= " AND `name` LIKE '%{$keyword}%' "; // sql injection 漏洞
    $where .= sprintf(" AND `name` LIKE %s ", $pdo->quote('%' . $keyword . '%'));

    $qs['keyword'] = $keyword;
} //％進來後直接做跳脫

// 總共有幾筆
$totalRows = $pdo->query("SELECT count(1) FROM members $where ")
    ->fetch(PDO::FETCH_NUM)[0];
// 總共有幾頁, 才能生出分頁按鈕
$totalPages = ceil($totalRows / $perPage); // 正數是無條件進位

$rows = [];
// 要有資料才能讀取該頁的資料
if ($totalRows != 0) {

    // 讓 $page 的值在安全的範圍 防止客戶惡搞網站（ex.直接在網址把第一頁改成第0頁，出錯訊息僅會在cosole跳出）
    if ($page < 1) {
        header('Location: ?page=1');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $sql = sprintf(
        "SELECT * FROM members %s ORDER BY sid DESC LIMIT %s, %s",
        $where,
        ($page - 1) * $perPage,
        $perPage
    ); //挖三個洞 依順序放進來

    $rows = $pdo->query($sql)->fetchAll();
}

?>
<?php include __DIR__ . '/partials/html-head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<style>
    table tbody i.fas.fa-trash-alt.ajaxDelete {
        color: darkred;
        cursor: pointer;
    }

    table tbody i.fas.fa-edit {
        color: darkblue;
        cursor: pointer;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col">
            <form action="data-list.php" class="form-inline my-2 my-lg-0 d-flex justify-content-end">
                <a class="nav-link" href="data-insert.php">新增</a>
                <input class="form-control mr-sm-2" type="search" name="keyword" placeholder="Search" value="<?= htmlentities($keyword) ?>" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination d-flex justify-content-end">

                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php $qs['page'] = $page - 1;
                                                    echo http_build_query($qs); ?>">
                            <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </li>

                    <!-- active所在頁碼反白 -->
                    <!-- 分頁按鈕 -->
                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                        if ($i >= 1 and $i <= $totalPages) : $qs['page'] = $i; ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query($qs) ?>"><?= $i ?></a>
                            </li>
                    <?php endif;
                    endfor; ?>

                    <!-- 在page-item下disable 在最後一頁就沒辦法繼續往後按 -->
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php $qs['page'] = $page + 1;
                                                    echo http_build_query($qs); ?>">
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col"><i class="fas fa-trash-alt"></i></th>
                        <th scope="col">sid</th>
                        <th scope="col">姓名</th>
                        <th scope="col">頭像</th>
                        <th scope="col">E-mail</th>
                        <!-- <th scope="col">密碼</th> -->
                        <th scope="col">手機</th>
                        <th scope="col">生日</th>
                        <th scope="col">地址</th>
                        <th scope="col"><i class="fas fa-edit"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr data-sid="<?= $r['sid'] ?>">

                            <td>
                                <i class="fas fa-trash-alt ajaxDelete"></i>
                            </td>
                            <td><?= $r['sid'] ?></td>
                            <td><?= $r['name'] ?></td>
                            <td><?php if (!empty($r['avatar'])) : ?>
                                    <img src="imgs/<?= $r['avatar'] ?>" alt="" width="50px">
                                <?php endif; ?>
                            </td>
                            <td><?= $r['email'] ?></td>
                            <!-- <td><?= $r['password'] ?></td> -->
                            <td><?= $r['mobile'] ?></td>
                            <td><?= $r['birthday'] ?></td>
                            <!--
                    <td><?= strip_tags($r['address']) ?></td>
                    -->
                            <td><?= htmlentities($r['address']) ?></td>
                            <td>
                                <a href="data-edit.php?sid=<?= $r['sid'] ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<?php include __DIR__ . '/partials/script.php'; ?>
<script>
    const myTable = document.querySelector('table');
    const modal = $('#exampleModal');
    myTable.addEventListener('click', function(event) {
        //console.log(event.target);
        // 判斷有沒有點到垃圾筒
        if (event.target.classList.contains('ajaxDelete')) {
            //console.log(event.target.closest('tr'));
            const tr = event.target.closest('tr'); //抓到tr
            const sid = tr.getAttribute('data-sid'); //拿到sid
            console.log(sid);

            if (confirm(`是否要刪除編號為 ${sid} 的資料？`)) {
                fetch('data-delete-api.php?sid=' + sid)
                    .then(r => r.json())
                    .then(obj => {
                        if (obj.success) {
                            tr.remove(); //成功的話就從DOM裡移除元素
                            //TODO: 1.刷頁面  2.取得該頁的資料再呈現
                        } else {
                            alert(obj.error); //沒有成功就跳這個
                        }
                    });
            }
        }
    });
    let willDeleteId = 0;
    $('.del1btn').on('click', function(event) {
        willDeleteId = event.target.closest('tr').dataset.sid;
        console.log(willDeleteId);
        modal.find('.modal-body').html(`確定要刪除編號為 ${willDeleteId} 的資料嗎？`);
    });
    //find＝搜尋

    // 按了確定刪除的按鈕
    modal.find('.modal-del-btn').on('click', function(event) {
        console.log(`data-delete.php?sid=${willDeleteId}`);
        location.href = `data-delete.php?sid=${willDeleteId}`;
    });

    //加上事件處理器 Modal一開始顯示時觸發
    modal.on('show.bs.modal', function(event) {
        // console.log(event.target);
    });
</script>
<?php include __DIR__ . '/partials/html-foot.php'; ?>