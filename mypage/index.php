<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/_admin/assets/include/common.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/layout/header.php'; ?>

<div class="app-body">
  <div class="app-wrapper">
    <header class="app-header">
      <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/header/pageHeader.php'; ?>
    </header>
    <main class="app-main">
      <div id="member">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/mypage/main.php'; ?>
      </div>
      <div id="nonmember">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/nonmember/endModal.php'; ?>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/mypage/tutorial.php'; ?>
      </div>
    </main>
  </div>
</div>

<script type="text/JavaScript" src="/sr/assets/js/mypage.js<?= $cacheVer ?>"></script>