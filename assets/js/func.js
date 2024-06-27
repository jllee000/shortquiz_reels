function goBanner(_url) {
  if (typeof s3app === 'undefined') {
    const ret = window.open(_url);
    if (ret == null) {
      alert('팝업 차단을 해제해주세요.');
      ret.close();
      return false;
    }
  } else {
    s3app.openInAppBrowser(_url);
  }
  return true;
}

// 페이지 이동
function goPage(link) {
  let newPath = '';
  if (link == 'quiz') {
    newPath = '/sr/';
  } else if (link == 'form') {
    $('.app-main-page').hide();
    $('.app-sign-page').show();
    return -1;
  } else if (link == 'result') {
    $('.app-sign-page').hide();
    $('.app-result-page').show();
    return -1;
  } else if (link == 'mypage') {
    newPath = '/sr/mypage';
  } else if (link == 'list') {
    newPath = '/sr/list';
  } else if (link == 'home') {
    newPath = 'https://www.banggooso.com/';
  } else if (link == 'winner') {
    newPath = '/sr/winner';
  } else if (link == 'login') {
    newPath = ' https://www.banggooso.com/member/signup_login.html';
  } else if (link == 'signup') {
    newPath = 'https://www.banggooso.com/member/signup.html';
  } else if (link == 'kakao') {
    newPath =
      'https://accounts.kakao.com/login/?continue=https%3A%2F%2Fkauth.kakao.com%2Foauth%2Fauthorize%3Fproxy%3DeasyXDM_Kakao_kqh4usvd8zs_provider%26ka%3Dsdk%252F1.43.1%2520os%252Fjavascript%2520sdk_type%252Fjavascript%2520lang%252Fko-KR%2520device%252FWin32%2520origin%252Fhttps%25253A%25252F%25252Fwww.banggooso.com%26origin%3Dhttps%253A%252F%252Fwww.banggooso.com%26response_type%3Dcode%26redirect_uri%3Dkakaojs%26state%3Dfywjxeg9p5fotew26qy6%26through_account%3Dtrue%26client_id%3D7d0a9c77340897f7016e2e4f3cc1a017&talk_login=hidden#login';
  } else if (link == 'notion') {
    newPath = 'https://www.softsphere.co.kr/shortquiz';
  } else if (link == 'again') {
    if (isLogin) {
      const lastPlayedIdxSR = window.sessionStorage.getItem('lastPlayedIdxSR');
      if (lastPlayedIdxSR !== null) {
        newPath = `/sr/?idx=${lastPlayedIdxSR}`;
      } else {
        return -1;
      }
    } else {
      showModal('invite');
      return -1;
    }
  } else {
    const lastPlayedIdxSR = window.sessionStorage.getItem('lastPlayedIdxSR');
    if (lastPlayedIdxSR === null) {
      newPath = '/';
    } else {
      newPath = `/sr/?idx=${lastPlayedIdxSR}`;
    }
  }
  window.location.href = newPath;
}

function headerTxt(_txt) {
  $('.page-header .header-txt').text(_txt);
}
// gtag 연동
function getGtagSr(_pageType, _actions, _label) {
  let pageType = '';
  switch (_pageType) {
    case 'intro':
      pageType = '인트로';
      break;
    case 'quiz':
      pageType = '콘텐츠';
      break;
    case 'event':
      pageType = '이벤트정보';
      break;
    case 'result':
      pageType = '결과';
      break;
    case 'mypage':
      pageType = '나의등급';
      break;
  }
  gtag('event', `${JS_GA_TITLE}_${pageType}_${_actions}`, {
    event_category: `${JS_GA_TITLE}_${pageType}`,
    event_label: `${_label}`,
  });
}

// 모달창 닫는 기능
function closeModal(_modal) {
  switch (_modal) {
    case 'invite-modal':
      $('.invite-modal').hide();
      $('.result-main-page').css('position', 'relative');
      break;
    case 'nonmember-playmodal':
      $('.nonmembers-play-modal').hide();
      $('.nonmembers-play-modal > .modal-dim .modal').empty();
      break;
    case 'nonmember-startmodal':
      $('.nonmembers-start-modal').hide();
      $('.nonmembers-start-modal > .modal-dim .modal').empty();
      $('.result-main-page').css('position', 'relative');
      break;
    case 'nonmember-again':
      $('.nonmembers-again').hide();
      $('.nonmembers-again > .modal-dim .modal').empty();
      break;
    case 'nonmembers-end-modal':
      getGtagSr('intro', '가입_팝업_끔', '가입_팝업_끔');
      $('.nonmembers-again').hide();
      $('.nonmembers-end-modal').hide();
      $('.result-main-page').css('position', 'relative');
      $('.mypage-tutorial').css('position', 'relative');
      $('.nonmembers-again > .modal-dim .modal').empty();
      break;
    default:
      $('.' + _modal).hide();
      break;
  }
}
function openSignModal() {
  $('.info-modal').show();
  $('.user-form').css('position', 'fixed');
  $('.user-form').css('max-width', '500px');
}

function closeInfo() {
  $('.info-modal').hide();
  $('.user-form').css('position', 'relative');
}

// 비회원일 경우 0 처리
function eggUpdate() {
  const isLogin = checkLogin();
  if (!isLogin) {
    currentEggCount = 0;
    $('.header .header-egg .span').get(0).innerText = currentEggCount;
    $('.header .header-egg .span').get(1).innerText = currentEggCount;
    $('.header .header-egg .span').get(2).innerText = currentEggCount;
  }
}
function showModal(_pop) {
  const isLogin = checkLogin();
  if (isLogin) {
    if (_pop == 'invite') {
      $('.invite-modal').show();
      $('.result-main-page').css('position', 'fixed');
    }
  } else {
    $('.nonmembers-end-modal > .modal-dim .modal').empty().load('/sr/modal/nonmember/endModalContent.php');
    $('.nonmembers-end-modal').show();
    $('.result-main-page').css('position', 'fixed');
    $('.mypage-tutorial').css('bottom', '0');
    $('.mypage-tutorial').css('position', 'fixed');
  }
}

function copyToClipboard(_text) {
  // 새로운 textarea 엘리먼트를 생성합니다.
  var textarea = document.createElement('textarea');

  // 텍스트를 textarea에 할당합니다.
  textarea.value = _text;

  // textarea를 화면에 표시하지 않도록 스타일을 설정합니다.
  textarea.style.position = 'absolute';
  textarea.style.left = '-9999px';

  // textarea를 문서에 추가합니다.
  document.body.appendChild(textarea);

  // textarea의 내용을 선택하고 복사합니다.
  textarea.select();
  document.execCommand('copy');

  // textarea를 제거합니다.
  document.body.removeChild(textarea);
}

/**
 * 모바일 체크 메소드
 * @returns String - android, ios, other
 **/
function checkMobile() {
  var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
  if (varUA.indexOf('android') > -1) {
    //안드로이드
    return 'android';
  } else if (varUA.indexOf('iphone') > -1 || varUA.indexOf('ipad') > -1 || varUA.indexOf('ipod') > -1) {
    //IOS
    return 'ios';
  } else {
    //아이폰, 안드로이드 외
    return 'other';
  }
}

//쿠키값 Set
function setCookie(cookieName, value, exdays) {
  const exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  const cookieValue = escape(value) + (exdays == null ? '' : '; expires=' + exdate.toGMTString());

  document.cookie = cookieName + '=' + cookieValue + '; path=/;';
}

//쿠키값 Set (분 단위)
function setCookieMinutes(cookieName, value, miuntes) {
  const exdate = new Date();
  exdate.setMinutes(exdate.getMinutes() + miuntes);
  const cookieValue = escape(value) + (miuntes == null ? '' : '; expires=' + exdate.toUTCString());
  document.cookie = cookieName + '=' + cookieValue + '; path=/;';
}

//쿠키값 Delete
function deleteCookie(cookieName) {
  var expireDate = new Date();
  expireDate.setDate(expireDate.getDate() - 1);
  document.cookie = cookieName + '= ' + '; expires=' + expireDate.toGMTString();
}

//쿠키값 가져오기
function getCookie(cookie_name) {
  var x, y;
  var val = document.cookie.split(';');

  for (var i = 0; i < val.length; i++) {
    x = val[i].substr(0, val[i].indexOf('='));
    y = val[i].substr(val[i].indexOf('=') + 1);
    x = x.replace(/^\s+|\s+$/g, ''); // 앞과 뒤의 공백 제거하기

    if (x == cookie_name) {
      return unescape(y); // unescape로 디코딩 후 값 리턴
    }
  }
}

$(function () {
  $('.modal-dim').on('mousedown', function () {
    $('.play-container').removeClass('scale-large');
    $('.play-container').addClass('scale-small');
  });

  $('.modal-dim').on('mouseup', function () {
    $('.play-container').removeClass('scale-small');
    $('.play-container').addClass('scale-large');
  });
});

function playModalAnimation() {
  setTimeout(() => {
    startQuiz('nonmember-playmodal');
  }, 500);
}
