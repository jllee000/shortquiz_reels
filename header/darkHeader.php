<?php


// 세션 변수 'u_sr_egg'의 값을 가져옴
$egg_count = $_SESSION['u_sr_egg'];

// 'u_sr_egg' 값이 99 이상인 경우 '99+'로 대체, 그렇지 않으면 그대로 출력
$egg_display = ($egg_count >= 99) ? '99+' : $egg_count;
?>

<div class="header homeheader  header-dark">
  <div class="header-logo-div">
    <div onclick="goPage('quiz')"><img class="img-width" src="https://cdn.banggooso.com/sr/assets/images/header/logo-dark.png" /></div>
    <div onclick="goBanner('https://www.softsphere.co.kr/shortquiz')"> <img class="header-icon-notion-img" src="https://cdn.banggooso.com/sr/assets/images/header/notion-dark.png" /> </div>
  </div>

  <div class="header-menu">

    <div class="header-egg header-egg-dark" onclick="getGtagSr('intro','등급보기', '등급보기'); goPage('mypage')">
      <div class="header-egg-div"><img src="/sr/header/egg.png" class="header-icon-egg-img" /></div>
      <span class="span dark-span"><?= $egg_display ?></span>
    </div>
    <div class="header-cal" onclick="getGtagSr('intro','퀴즈리스트', '퀴즈리스트'); goPage('list')">
      <img class="header-icon-img" src="https://cdn.banggooso.com/sr/assets/images/header/list-dark.png" />
    </div>
    <div class="header-home" onclick="getGtagSr('intro','방구소메인홈', '방구소메인홈'); goPage('home')">
      <img class="header-icon-img" src="https://cdn.banggooso.com/sr/assets/images/header/home-dark.png" />
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    eggUpdate();
  })
</script>