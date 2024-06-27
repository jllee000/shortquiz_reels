<div class="again-modal">
  <div class="modal-dim">
    <?php include  $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/pop.php'; ?>
    <div class="modal">
      <div class="modal-title">한번 더 참여하고<br />당첨률을 높여보세요!</div>
      <div class="modal-txt">* 참여 1회 당 알 1개가 차감됩니다.</div>
      <div><button class="modal-btn" onclick="getGtagSr('intro', '재참여하기', '재참여하기'); startQuiz('again-modal')">지금 참여하기</button></div>
      <div class="modal-a ">
        <a onclick="getGtagSr('intro', '재참여_다른퀴즈', '재참여_다른퀴즈'); goPage('list')">다른 퀴즈 보기 </a>

        <div class="modal-other-quiz"><img src="https://cdn.banggooso.com/sr/assets/images/main/right.png" class="modal-right-img-width" /></div>
      </div>
    </div>

  </div>
</div>