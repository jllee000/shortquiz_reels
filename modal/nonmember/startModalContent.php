<img onclick="getGtagSr('intro', '가입_팝업_끔', '가입_팝업_끔'); goPage('list')" class="closesvg" src="https://cdn.banggooso.com/sr/assets/images/modal/close.png" />
<div class="modal-title">로그인 후 이벤트 참여하면</div>
<div class="modal-txt">
  <div class="check-txt">
    <img class="check-icon" src="https://cdn.banggooso.com/sr/assets/images/modal/check.png">
    손쉽게 알을 모을 수 있어요!
  </div>
  <div class="check-txt">
    <img class="check-icon" src="https://cdn.banggooso.com/sr/assets/images/modal/check.png">중복 참여하고 당첨확률을 높일 수 있어요!
  </div>
</div>
<div>
  <button onclick="getGtagSr('intro', '가입_카카오', '가입_카카오'); goBanggooso('signup_kakao')" class="modal-btn kakao">
    <img class="modal-kakao-icon" src="https://cdn.banggooso.com/sr/assets/images/modal/kakaoicon.png" />
    카카오 1초 로그인/가입
  </button>
</div>
<div><button class="modal-btn nologin" onclick="getGtagSr('intro', '가입_비회원', '가입_비회원'); startQuiz('nonmember-startmodal')">로그인 없이 참여하기</button></div>
<div class="modal-a flex-row nonmember-modal-a">
  <div class="a-admin" onclick="getGtagSr('intro', '가입_다른계정', '가입_다른계정'); goBanggooso('signup_login')">다른 계정으로 로그인</div>
  <div class="a-sign" onclick="getGtagSr('intro', '가입_회원가입', '가입_회원가입'); goBanggooso('signup')">회원가입</div>
</div>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/nonmember/kakaoLoginForm.php'; ?>