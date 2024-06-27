<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/_admin/assets/include/common.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/layout/header.php'; ?>
<div class="app-body">
  <div class="app-wrapper flex-col">
    <header class="app-header">
      <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/header/pageHeader.php'; ?>
    </header>
    <main class="app-main">
      <div class="quiz-list">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/list/notice.php'; ?>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/list/open.php'; ?>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/list/coming.php'; ?>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/list/end.php'; ?>
      </div>
    </main>
  </div>
</div>


<script type="text/JavaScript" src="/sr/assets/js/list.js<?= $cacheVer ?>"></script>