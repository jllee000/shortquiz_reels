const shareURL = new URL('https://www.banggooso.com/sr');
const shareTitle = '숏퀴즈';
const kakaoAppKey = '7d0a9c77340897f7016e2e4f3cc1a017';
const chkM = checkMobile();
const isMobile = chkM == 'android' || chkM == 'ios';
const isInApp = isMobile && navigator.userAgent.indexOf('Banggooso') > -1;
// 사용할 카카오 공유하기의 JavaScript 키를 설정
if (!Kakao.isInitialized()) {
  Kakao.init(kakaoAppKey);
}

function shareKakaotalk(
  _shareTitle = shareTitle,
  _shareText = '',
  _shareDesc = '',
  _btnTitle = '바로가기',
  _imgWidth = 1200,
  _imgHeight = 630,
  _shareUrl = '',
  _pageType = '',
  _quizIdx = null,
  _actionType = ''
) {
  // 공유하기 카운팅
  if (_actionType !== 'invite' && _actionType !== 'share') {
    console.log('action type: ', _actionType);
    window.alert('공유하기 파라미터 에러! 관리자에게 문의해주세요.');
    return;
  }
  loggingShareActionByType(_quizIdx, _actionType);
  if (isInApp && window.s3app) {
    // 방구소 앱?
    window.s3app.shareKakao(_shareText, _shareDesc, shareURL.toString() + _shareUrl, JS_PAGE_OG_IMAGE);
  } else {
    // 그 외
    Kakao.Link.sendDefault({
      objectType: 'feed',
      content: {
        title: _shareText, // 콘텐츠의 타이틀
        description: _shareDesc, // 콘텐츠 상세설명
        imageUrl: JS_PAGE_OG_IMAGE, // 썸네일 이미지설정
        imageWidth: _imgWidth,
        imageHeight: _imgHeight,
        link: {
          mobileWebUrl: shareURL.toString() + _shareUrl, // 모바일 카카오톡에서 사용하는 웹 링크 URL
          webUrl: shareURL.toString() + _shareUrl, // PC버전 카카오톡에서 사용하는 웹 링크 URL
        },
      },
      buttons: [
        {
          title: _btnTitle, // 버튼 제목
          link: {
            mobileWebUrl: shareURL.toString() + _shareUrl, // 모바일 카카오톡에서 사용하는 웹 링크 URL
            webUrl: shareURL.toString() + _shareUrl, // PC버전 카카오톡에서 사용하는 웹 링크 URL
          },
        },
      ],
    });
  }
}
function shareFacebook(
  _shareUrl,
  _shareTitle = shareTitle,
  _shareText,
  _pageType = '',
  _quizIdx = null,
  _actionType = ''
) {
  // 공유하기 카운팅
  if (_actionType !== 'invite' && _actionType !== 'share') {
    console.log('action type: ', _actionType);
    window.alert('공유하기 파라미터 에러! 관리자에게 문의해주세요.');
    return;
  }
  loggingShareActionByType(_quizIdx, _actionType);
  const shareFacebookURL = new URL('http://www.facebook.com/sharer/sharer.php');
  shareFacebookURL.searchParams.append('u', shareURL.toString() + _shareUrl);
  if (isInApp && window.s3app) {
    if (chkM == 'ios') {
      window.s3app.shareFacebook(_shareTitle, JS_PAGE_OG_IMAGE);
    } else {
      window.s3app.openBrowser(shareFacebookURL.toString());
    }
  } else {
    openSharePopup(shareFacebookURL.toString());
  }
}

function shareTwitter(
  _shareUrl,
  _shareTitle = shareTitle,
  _shareText,
  _pageType = '',
  _quizIdx = null,
  _actionType = ''
) {
  // 공유하기 카운팅
  if (_actionType !== 'invite' && _actionType !== 'share') {
    console.log('action type: ', _actionType);
    window.alert('공유하기 파라미터 에러! 관리자에게 문의해주세요.');
    return;
  }

  loggingShareActionByType(_quizIdx, _actionType);

  // const replacedURL = currentURL.toString().replace('https://www.', 'https://')

  const shareTwitterURL = new URL('https://twitter.com/intent/tweet');
  shareTwitterURL.searchParams.append('text', _shareText);
  shareTwitterURL.searchParams.append('url', '링크 : ' + shareURL.toString() + _shareUrl);

  if (isInApp && window.s3app) {
    window.s3app.openBrowser(shareTwitterURL.toString());
  } else {
    openSharePopup(shareTwitterURL.toString());
  }
}

function shareLink(_shareUrl, _shareTitle = shareTitle, _shareText, _pageType = '', _quizIdx = null, _actionType = '') {
  copyToClipboard(_shareText + '\n\n링크 : ' + shareURL.toString() + _shareUrl);

  // 공유하기 카운팅
  if (_actionType !== 'invite' && _actionType !== 'share') {
    console.log('action type: ', _actionType);
    window.alert('공유하기 파라미터 에러! 관리자에게 문의해주세요.');
    return;
  }

  loggingShareActionByType(_quizIdx, _actionType);

  alert('링크가 복사 되었습니다.');
}

function openSharePopup(_url) {
  const options = {
    title: '_blank',
    width: 800,
    height: 600,
    url: _url,
  };

  const ml = (screen.availWidth - options.width) / 2;
  const mt = (screen.availHeight - options.height) / 2;
  const params =
    'width=' +
    options.width +
    ',height=' +
    options.height +
    ',top=' +
    mt +
    ',left=' +
    ml +
    ',scrollbars=yes,resizable=no';

  const win = window.open(options.url, options.title, params);
}

function loggingShareActionByType(_quizIdx = null, _actionType = 'share') {
  if (_quizIdx === null) {
    console.log('quiz index: ', _quizIdx);
    window.alert('로깅 파라미터 에러.');
    return;
  }

  if (_actionType !== 'invite' && _actionType !== 'share') {
    console.log('action type: ', _actionType);
    window.alert('로깅 파라미터 에러.');
    return;
  }

  $.ajax({
    type: 'post',
    data: {
      proc: 'logging',
      actions: _actionType,
      quiz_idx: _quizIdx,
      csrf_token: JS_CSRF,
    },
    url: '/sr/modules/api.php',
    success: function () {
      console.log(`${_actionType} success`);
    },
    error: function () {
      console.log('failed');
    },
  });
}
