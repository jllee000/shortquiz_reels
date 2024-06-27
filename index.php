<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/_admin/assets/include/common.php'; ?>
<?php
$jsVar['JS_USER_NAME'] = "'" . $_SESSION['u_aname'] . "'";
?>
<?php
function imageExists($quizIdx)
{
  $image_url = "https://cdn.banggooso.com/sr/assets/share/{$quizIdx}.png";
  $default_image = "https://cdn.banggooso.com/sr/assets/images/share/shortquiz_share.png";

  $headers = @get_headers($image_url);
  if ($headers && strpos($headers[0], '200') !== false) {
    return $image_url;
  } else {
    return $default_image;
  }
};

$imageUrl = imageExists($_GET['idx']);
define('PAGE_OG_IMAGE', $imageUrl);
?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/layout/header.php'; ?>
<div class="app-body">
  <div class="app-main-page">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/main/index.php'; ?>
  </div>
  <div class="app-sign-page">
    <?php include  $_SERVER['DOCUMENT_ROOT'] . '/sr/sign/index.php'; ?>
  </div>
  <div class="app-result-page">
    <?php include  $_SERVER['DOCUMENT_ROOT'] . '/sr/result/index.php'; ?>
  </div>
</div>

<script type="text/JavaScript" src="/sr/assets/js/quiz.js<?= $cacheVer ?>"></script>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/layout/footer.php'; ?>