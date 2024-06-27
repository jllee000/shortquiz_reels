<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/layout/header.php'; ?>
<div class="app-wrapper heightset">
  <header class="app-header">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/header/darkHeader.php'; ?>
  </header>
  <main class="app-main quiz-page bg-black">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/member/startModal.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/member/again.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/member/noEgg.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/nonmember/startModal.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/nonmember/playModal.php'; ?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/main/main.php'; ?>
  </main>
</div>