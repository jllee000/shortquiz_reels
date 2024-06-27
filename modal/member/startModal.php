<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/layout/header.php'; ?>
<div class="modal-dim start-modal">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/pop.php'; ?>
  <div class="modal">
    <div class="modal-title">숏퀴즈에 참여하고<br /> 쏟아지는 혜택을 받으세요!</div>
    <div class="modal-txt">* 첫 참여는 알 없이 바로 참여 가능해요.</div>
    <div><button class="modal-btn" onclick="getGtagSr('intro', '첫참여하기', '첫참여하기'); startQuiz('start-modal')">지금 참여하기</button></div>
    <div class="modal-a members-modal-a"><a onclick=" getGtagSr('intro','첫참여_다른퀴즈', '첫참여_다른퀴즈'); goPage('list')">다른 퀴즈 보기</a>
      <div class="modal-other-quiz"><img src="https://cdn.banggooso.com/sr/assets/images/main/right.png" class="modal-right-img-width" /></div>
    </div>
  </div>
</div>