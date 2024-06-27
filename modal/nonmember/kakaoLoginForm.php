<form name="hiddenfrm" method="post" action="/member/signup_login.html?login_type=kakaopop">
  <input type="hidden" id="sns" name="sns" value="">
  <input type="hidden" id="snsid" name="snsid" value="">
  <input type="hidden" id="email" name="email" value="">
  <input type="hidden" id="nickname" name="nickname" value="">
</form>
<script type="text/javascript">
  if (!Kakao.isInitialized()) {
    Kakao.init('7d0a9c77340897f7016e2e4f3cc1a017');
  }

  function goBanggooso(_type) {

    setCookie('refer', window.location.href, 1);

    if (_type == "signup") {
      location.href = "/member/signup.html";
    } else if (_type == "signup_login") {
      location.href = "/member/signup_login.html";
    } else if (_type == "signup_kakao") {
      loginWithKakao();
    }

  }

  //카카오로그인
  function loginWithKakao() {
    var broswerInfo = navigator.userAgent;
    var chkM = checkMobile();
    if ((chkM == "android" || chkM == "ios") && broswerInfo.indexOf("Banggooso") > -1 && window.s3app) { // 방구소 앱일때
      window.s3app.kakaoAuth(); // 카카오 로그인
    } else {
      Kakao.Auth.login({
        success: function(ress) {
          // 로그인 성공시, API를 호출합니다.
          Kakao.API.request({
            url: '/v2/user/me',
            success: function(res) {
              if (res != undefined) {
                const userId = res.id;
                const userEmail = res.kakao_account.email;
                const userNickName = res.kakao_account.profile.nickname;

                $('#sns').val("KA");
                $('#snsid').val(userId);
                $('#email').val(userEmail);
                $('#nickname').val(userNickName);

                document.hiddenfrm.submit();
              }

            },
            fail: function(error) {
              alert(JSON.stringify(error));
            }
          });
        },
        fail: function(error) {},
      });
    }
  }

  function handleKakaoAuth(token) {
    $.ajax({
      type: 'post',
      headers: {
        Authorization: 'Bearer ' + token
      },
      dataType: "json",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      url: '/modules/kakao_api.php',
      data: {
        proc: 'kakao-login'
      },
      success: function(res) {
        $('#sns').val("KA");
        $('#snsid').val(res.id);
        $('#email').val(res.kakao_account.email);
        $('#nickname').val(res.kakao_account.profile.nickname);

        document.hiddenfrm.submit();
      },
      error: function() {
        alert("카카오 로그인 실패, 관리자에 문의해주세요.");
      },
    });
  }
</script>