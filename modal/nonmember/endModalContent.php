<img onclick="closeModal('nonmembers-end-modal')" class="closesvg" src="https://cdn.banggooso.com/sr/assets/images/modal/close.png" />
<div class="modal-title">로그인 후 이용하면</div>
<div class="modal-txt">
  <div class="check-txt">
    <img class="check-icon" src="https://cdn.banggooso.com/sr/assets/images/modal/check.png">
    손쉽게 알을 모을 수 있어요!
  </div>
  <div class="check-txt">
    <img class="check-icon" src="https://cdn.banggooso.com/sr/assets/images/modal/check.png">
    중복 참여하고 당첨확률을 높일 수 있어요!
  </div>
</div>
<button onclick="goBanggooso('signup_kakao')" class="modal-btn kakao" onclick="closeModal('nonmember-again')">
  <img class="modal-kakao-icon" src="https://cdn.banggooso.com/sr/assets/images/modal/kakaoicon.png" />
  카카오 1초 로그인/가입
</button>
</div>
<div class="modal-a flex-row nonmember-modal-a">
  <div class="a-admin" onclick="goBanggooso('signup_login')">다른 계정으로 로그인</div>
  <div class="a-sign" onclick="goBanggooso('signup')">회원가입</div>
</div>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/nonmember/kakaoLoginForm.php'; ?>