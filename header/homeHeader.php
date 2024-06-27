<?php

// 세션 변수 'u_sr_egg'의 값을 가져옴
$egg_count = $_SESSION['u_sr_egg'];

// 'u_sr_egg' 값이 99 이상인 경우 '99+'로 대체, 그렇지 않으면 그대로 출력
$egg_display = ($egg_count >= 99) ? '99+' : $egg_count;
?>
<div class="header homeheader">
  <div class="header-logo-div">
    <div onclick="goPage('quiz')"><img class="img-width" src="https://cdn.banggooso.com/sr/assets/images/header/logo.png" /></div>
    <div onclick="goBanner('https: //www.softsphere.co.kr/shortquiz')"> <img class="header-icon-notion-img" src="https://cdn.banggooso.com/sr/assets/images/header/notion.png" />
    </div>
  </div>
  <div class="header-menu">
    <div class="header-egg" onclick="goPage('mypage')">
      <div class="header-egg-div"><img src="/sr/header/egg.png" class="header-icon-egg-img" /></div>
      <span class="span white-span"><?= $egg_display ?></span>
    </div>
    <div class="header-cal" onclick="goPage('list')">
      <img class="header-icon-img" src="https://cdn.banggooso.com/sr/assets/images/header/list.png" />
    </div>
    <div class="header-home" onclick="goPage('home')">
      <img class="header-icon-img" src="https://cdn.banggooso.com/sr/assets/images/header/home.png" />
    </div>
  </div>

</div>
<script>
  $(document).ready(function() {
    eggUpdate();
  })
</script>