headerTxt('나의 등급');

getMyData();
// 비회원 튜토리얼
$(document).ready(function () {
  const isLogin = checkLogin();

  if (!isLogin) {
    $('.app-main #member').hide();
    $('.app-main #nonmember').show();
  } else {
    $('.app-main #member').show();
    $('.app-main #nonmember').hide();
  }
});

function getMyData() {
  const data = {
    proc: 'get-my-data',
    csrf_token: JS_CSRF,
  };

  $.ajax({
    type: 'post',
    url: '/sr/modules/api.php',
    dataType: 'json',
    data: data,

    success: (_json) => {
      if (_json.code === 0) {
        const mypageUName = _json.response.result.username;
        const mypageEggcnt = _json.response.result.eggcnt;
        const mypageResultcnt = _json.response.result.resultcnt;
        const mypageLv = _json.response.result.level;
        updateMyPage(mypageUName, mypageEggcnt, mypageLv, mypageResultcnt);
        
      }
    },
    error: function (request, status, error) {
      console.log(error);
    },
  });
}

function updateMyPage(_mypageUserName, _mypageEggCnt, _myPageLv, _mypageResultCnt) {
  let nData = {
    0: ['아직 참여한 숏퀴즈가 없어요.', 'https://cdn.banggooso.com/sr/assets/images/mypage/level/lv0.png'],
    1: ['아기꾸꾸', 'https://cdn.banggooso.com/sr/assets/images/mypage/level/lv1.png'],
    2: ['어른 꾸꾸', 'https://cdn.banggooso.com/sr/assets/images/mypage/level/lv2.png'],
    3: ['브론즈 꾸꾸', 'https://cdn.banggooso.com/sr/assets/images/mypage/level/lv3.png'],
    4: ['실버 꾸꾸', 'https://cdn.banggooso.com/sr/assets/images/mypage/level/lv4.png'],
    5: ['골드 꾸꾸', 'https://cdn.banggooso.com/sr/assets/images/mypage/level/lv5.png'],
    6: ['무지개 꾸꾸', 'https://cdn.banggooso.com/sr/assets/images/mypage/level/lv6.png'],
  };
  $('.mypage-username').text(_mypageUserName);
  $('.mypage-eggcnt-span').text('  ' + _mypageEggCnt);

  // 미참여자
  if (parseInt(_myPageLv) == 0){
    updateGraph(0);
    $('.mypage-userlv .mypage-nogame-span').show();
    $('.mypage .mypage-userlv-span-black').hide();
    $('.mypage-userlv .mypage-nogame-span').text(nData['0'][0]);
    $('.mypage-imgdiv').append(`<img class="mypage-img" src=${nData['0'][1]} alt="꾸꾸레벨" />`);
  }
  // 한번이상 참여자
  else {
    // 한번이상 참여했을때는 무조건 lv.1인데, 그래프에선 반영 안되게
    if (_mypageResultCnt == 0) {
      updateGraph(0);
      $('.mypage-imgdiv').append(`<img class="mypage-img" src=${nData[_myPageLv][1]} alt="꾸꾸레벨" />`);
      $('.mypage-userlv-span').text('Lv.' + _myPageLv + '  ' + nData[_myPageLv][0]);
      $('.mypage-userlv-span-black').show();
    }
    else{
      updateGraph(_mypageResultCnt);
      // 레벨 6 넘어가면 최대 6으로 처리 (예외처리)
      if (parseInt(_myPageLv) >= 6) {
        _myPageLv = '6';
        $('.mypage-imgdiv').append(`<img class="mypage-img" src=${nData[_myPageLv][1]} alt="꾸꾸레벨" />`);
        $('.mypage-userlv-span').text('Lv.' + _myPageLv + '  ' + nData[_myPageLv][0]);
        $('.mypage-userlv-span-black').show();
      }
      else {
        $('.mypage-imgdiv').append(`<img class="mypage-img" src=${nData[_myPageLv][1]} alt="꾸꾸레벨" />`);
        $('.mypage-userlv-span').text('Lv.' + _myPageLv + '  ' + nData[_myPageLv][0]);
        $('.mypage-userlv-span-black').show();
      }
      
    }
  }



  
}
// 참여현황 그래프
function updateGraph(_currentCount) {
  const $innerLvElement = document.querySelector(`.mypage-chart .lv-in`);
  const $innerEggElement = document.querySelector(`.mypage-chart .egg-in`);
  // 레벨 그래프 (10단위)
  if (_currentCount == 0) {
    $innerLvElement.style.width = `0%`;
    $innerLvElement.style.setProperty('--fillPercentage', `0%`);
    $innerLvElement.style.animation = 'fillAnimation 3.8s both';
    $innerEggElement.style.width = `0%`;
    $innerEggElement.style.setProperty('--fillPercentage', `0%`);
    $innerEggElement.style.animation = 'fillAnimation 3.8s both';
  }
  else {
    const lvPercentage = (_currentCount % 10) * 10;
    if (_currentCount % 10 == 0) {
      $innerLvElement.style.width = `100%`;
      $innerLvElement.style.setProperty('--fillPercentage', `100%`);
      $innerLvElement.style.animation = 'fillAnimation 3.8s both';
    } else {
      $innerLvElement.style.width = `${lvPercentage}%`;
      $innerLvElement.style.setProperty('--fillPercentage', `${lvPercentage}%`);
      $innerLvElement.style.animation = 'fillAnimation 3.8s both';
    }
    // 알 그래프 (5단위)
    const eggPercentage = (_currentCount % 5) * 20;
    if (_currentCount % 5 == 0) {
      $innerEggElement.style.width = `100%`;
      $innerEggElement.style.setProperty('--fillPercentage', `100%`);
      $innerEggElement.style.animation = 'fillAnimation 3.8s both';
    } else {
      $innerEggElement.style.width = `${eggPercentage}%`;
      $innerEggElement.style.setProperty('--fillPercentage', `${eggPercentage}%`);
      $innerEggElement.style.animation = 'fillAnimation 3.8s both';
    }
  }
}
