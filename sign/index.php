<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/layout/header.php'; ?>

<div class="app-wrapper ">

  <header class="app-header">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/header/homeHeader.php'; ?>
  </header>
  <main class="app-main">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/info.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/nonmember/again.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/sign/main.php'; ?>
  </main>
</div>