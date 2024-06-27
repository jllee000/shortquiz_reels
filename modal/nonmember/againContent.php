<img onclick="closeModal('nonmember-again')" class="closesvg" src="https://cdn.banggooso.com/sr/assets/images/modal/close.png" />
<div class="modal-title">
  이미 참여한 숏폼 퀴즈입니다.<br />
  로그인 후 여러 번 참여하고<br />
  당첨확률을 높여보세요!</div>
<div>
  <button onclick="getGtagSr('event', '가입_카카오', '가입_카카오'); goBanggooso('signup_kakao')" class="modal-btn kakao" onclick="closeModal('nonmember-again')">
    <img class="modal-kakao-icon" src="https://cdn.banggooso.com/sr/assets/images/modal/kakaoicon.png" />
    카카오 1초 로그인/가입
  </button>
</div>
<div class="modal-a flex-row nonmember-modal-a">
  <div class="a-admin" onclick="getGtagSr('event', '가입_다른계정', '가입_다른계정'); goBanggooso('signup_login')">다른 계정으로 로그인</div>
  <div class="a-sign" onclick="getGtagSr('event', '가입_회원가입', '가입_회원가입'); goBanggooso('signup')">회원가입</div>
</div>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/sr/modal/nonmember/kakaoLoginForm.php'; ?>