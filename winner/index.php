<link rel="stylesheet" href="/sr/style.css" />
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/_admin/assets/include/common.php'; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/layout/header.php'; ?>
<div class="app-body">
  <div class="app-wrapper ">
    <header class="app-header">
      <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/header/pageHeader.php'; ?>
    </header>
    <main class="app-main">
      <div class="winner-txt"></div>
    </main>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/JavaScript" src="/sr/assets/js/winner.js<?= $cacheVer ?>"></script>