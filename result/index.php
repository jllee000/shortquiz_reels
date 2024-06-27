<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/layout/header.php'; ?>

<div class="app-wrapper">
  <header class="app-header">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/header/homeHeader.php'; ?>
  </header>
  <main class="app-main quiz-page">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/member/result.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/nonmember/endModal.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/result/main.php'; ?>
  </main>
</div>