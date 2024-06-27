<? $jsVar['JS_CSRF'] = "'" . $_SESSION['csrf_token'] . "'"; ?>
<?php
if (!defined('PLATFORM_TITLE')) {
  define('PLATFORM_TITLE', '숏퀴즈｜재밌는 영상 퀴즈형 래플 이벤트');
}
if (!defined('PAGE_DESC')) {
  define('PAGE_DESC', '퀴즈 풀고 선물 받고! 퀴즈 속 제품이 내꺼? 당첨 기회를 잡아보세요.');
  $jsVar['JS_PAGE_DESC'] = "'" . PAGE_DESC . "'";
}

if (!defined('PAGE_OG_TITLE')) {
  define('PAGE_OG_TITLE', "숏퀴즈｜재밌는 영상 퀴즈형 래플 이벤트");
}
$jsVar['JS_PAGE_OG_TITLE'] = "'" . PAGE_OG_TITLE . "'";

if (!defined('PAGE_OG_IMAGE')) {
  define('PAGE_OG_IMAGE', SR_CDN_PATH . "/assets/images/share/shortquiz_share.png");
}
$jsVar['JS_PAGE_OG_IMAGE'] = "'" . PAGE_OG_IMAGE . "'";
if (!defined('PAGE_OG_IMAGE_M')) {
  define('PAGE_OG_IMAGE_M', SR_CDN_PATH . "/assets/images/share/shortquiz_share_square.png");
}
$jsVar['JS_PAGE_OG_IMAGE_M'] = "'" . PAGE_OG_IMAGE_M . "'";

if (!defined('PAGE_OG_DESC')) {
  define('PAGE_OG_DESC', "숏퀴즈");
}
$jsVar['JS_PAGE_OG_DESC'] = "'" . PAGE_OG_DESC . "'";

$jsVar['JS_GA_TITLE'] = "'숏퀴즈'";
?>

<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
  <title><?= PLATFORM_TITLE ?></title>
  <link rel="stylesheet" href="/sr/style.css?ver=20240408" />
  <meta name="description" content="<?= PAGE_DESC ?>">
  <link rel="shortcut icon" href="https://cdn.banggooso.com/sr/assets/images/favicon/favicon.ico">
  <link rel="icon" type="image/png" sizes="16x16" href="https://cdn.banggooso.com/sr/assets/images/favicon/favicon-16x16.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://cdn.banggooso.com/sr/assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="192x192" href="https://cdn.banggooso.com/sr/assets/images/favicon/android-icon-192x192.png">
  <link rel="image_src" href="<?= PAGE_OG_IMAGE ?>">
  <meta property="twitter:title" content="<?= PAGE_OG_TITLE ?>">
  <meta property="twitter:description" content="<?= PAGE_OG_DESC ?>">
  <meta property="twitter:image" content="<?= PAGE_OG_IMAGE ?>">
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:image:height" content="250">
  <meta property="twitter:image:width" content="500">
  <meta property="og:title" content="<?= PAGE_OG_TITLE ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="https://www.banggooso.com/sr/" />
  <meta property="og:image" content="<?= PAGE_OG_IMAGE ?>" />
  <meta property="og:description" content="<?= PAGE_OG_DESC ?>" />
  <meta property="og:site_name" content="숏퀴즈" />
  <meta property="fb:app_id" content="868684923711460" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <!-- Swiper JS -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="/assets/js/html2canvas_custom.js<?= $chashver ?>"></script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-NE7TM662DE"></script>
  <!-- 카카오 픽셀 그 외? -->
  <!-- <script type="text/javascript" src="//t1.daumcdn.net/kas/static/ba.min.js" async></script> -->
  <script type="text/JavaScript" src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
  <script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/kas/static/kp.js"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    let resultData = 0;

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'G-NE7TM662DE');

    kakaoPixel('6894544435604599766').pageView();

    // JS용 상수 선언
    <?php
    // footer에서 호출할 스크립트
    $footerScript = array();

    foreach ($jsVar as $_var => $_val) {
      echo "const {$_var} = {$_val}; \n";
    }
    ?>

    function checkLogin() {
      let isLogin = false;
      const data = {
        proc: 'login-check',
        csrf_token: JS_CSRF,
      };
      $.ajax({
        type: 'post',
        url: '/sr/modules/api.php',
        dataType: 'json',
        data: data,
        async: false,
        success: (_json) => {
          if (_json.code == 0) {
            isLogin = true;
          } else if (_json.code == 4) {
            isLogin = false;
          }
        },
        error: function(request, status, error) {
          console.log(error)
        }
      })
      return isLogin;
    }
  </script>

  <script src="/sr/assets/js/func.js<?= $chashver ?>"></script>
  <script src="/sr/assets/js/share.js<?= $chashver ?>"></script>
</head>

<body>