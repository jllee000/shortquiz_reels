// 비디오 부분
// const video = $('#shortQuizVideo')[0];

// 얜 전역필요
let questionData = [];
let currentTime = 0; // 현재 문제에서 경과한 시간 (초 단위)
let timerInterval; // setInterval을 저장할 변수
let quizIdx = 0; // 퀴즈 idx
let currentStep = 1; // 현재 페이지 확인
let selectedAnswerData = [];
let isSoundFlag = true;
// let videoPlayFlag = 0;

const urlParams = new URLSearchParams(location.search);
const isLogin = checkLogin();

if (urlParams.has('idx')) {
  quizIdx = urlParams.get('idx');
  getQuiz(quizIdx);
} else {
  getQuizIdx();
}

// 랜더링 메서드
function renderShareButtons(_idx, _shareCode, _quizData) {
  // 인트로 모달
  $('.modal-share.no-egg-share-wrapper').append(`
   <div style="width: 18%;" onclick="getGtagSr('intro', '친구초대_카카오', '친구초대_카카오'); shareKakaotalk('숏퀴즈', '${JS_USER_NAME}님이 숏퀴즈 이벤트에 초대했어요! 친구와 같이 퀴즈 풀고 선물 받아가세요.', '숏퀴즈 with ${_quizData['company_name']} ', '바로가기', 1200, 630, '?idx=${_idx}&sharecode=${_shareCode}', 'main', ${_idx} ,'invite');">
     <img src="https://cdn.banggooso.com/sr/assets/images/modal/kakao.png" class="img-width" />
   </div>
   <div style="width: 18%;"   onclick="getGtagSr('intro', '친구초대_카카오', '친구초대_카카오'); shareFacebook('?idx=${_idx}&sharecode=${_shareCode}', '숏퀴즈', '${JS_USER_NAME}님이 숏퀴즈 이벤트에 초대했어요!\\n친구와 같이 퀴즈 풀고 선물 받아가세요.', 'main', ${_idx},'invite');">
     <img src="https://cdn.banggooso.com/sr/assets/images/modal/fb.png" class="img-width" />
   </div>
   <div style="width: 18%;"  onclick="getGtagSr('intro', '친구초대_카카오', '친구초대_카카오'); shareTwitter('?idx=${_idx}&sharecode=${_shareCode}', '숏퀴즈', '${JS_USER_NAME}님이 숏퀴즈 이벤트에 초대했어요!\\n친구와 같이 퀴즈 풀고 선물 받아가세요.', 'main', ${_idx}, 'invite');">
     <img src="https://cdn.banggooso.com/sr/assets/images/modal/twt.png" class="img-width" />
   </div>
   <div style="width: 18%;"  onclick="getGtagSr('intro', '친구초대_카카오', '친구초대_카카오'); shareLink('?idx=${_idx}&sharecode=${_shareCode}', '숏퀴즈', '${JS_USER_NAME}님이 숏퀴즈 이벤트에 초대했어요!\\n친구와 같이 퀴즈 풀고 선물 받아가세요.', 'main', ${_idx}, 'invite');">
     <img src="https://cdn.banggooso.com/sr/assets/images/modal/link.png" class="img-width" />
   </div>
   `);

  // 결과 모달
  $('.invite-share-box.no-egg-share-wrapper').append(`
   <div style="width: 18%;" onclick="getGtagSr('result', '친구초대_카카오', '친구초대_카카오'); shareKakaotalk('숏퀴즈', '${JS_USER_NAME}님이 숏퀴즈 이벤트에 초대했어요! 친구와 같이 퀴즈 풀고 선물 받아가세요.', '숏퀴즈 with ${_quizData['company_name']} ', '바로가기', 1200, 630, '?idx=${_idx}&sharecode=${_shareCode}', 'result', ${_idx},'invite');">
     <img src="https://cdn.banggooso.com/sr/assets/images/modal/kakao.png" class="img-width" />
   </div>
   <div style="width: 18%;"   onclick="getGtagSr('result', '친구초대_페북', '친구초대_페북'); shareFacebook('?idx=${_idx}&sharecode=${_shareCode}', '숏퀴즈', '${JS_USER_NAME}님이 숏퀴즈 이벤트에 초대했어요!\\n친구와 같이 퀴즈 풀고 선물 받아가세요.', 'result', ${_idx},'invite');">
     <img src="https://cdn.banggooso.com/sr/assets/images/modal/fb.png" class="img-width" />
   </div>
   <div style="width: 18%;"  onclick="getGtagSr('result', '친구초대_트위터', '친구초대_트위터'); shareTwitter('?idx=${_idx}&sharecode=${_shareCode}', '숏퀴즈', '${JS_USER_NAME}님이 숏퀴즈 이벤트에 초대했어요!\\n친구와 같이 퀴즈 풀고 선물 받아가세요.', 'result', ${_idx},'invite');">
     <img src="https://cdn.banggooso.com/sr/assets/images/modal/twt.png" class="img-width" />
   </div>
   <div style="width: 18%;"  onclick="getGtagSr('result', '친구초대_링크', '친구초대_링크'); shareLink('?idx=${_idx}&sharecode=${_shareCode}', '숏퀴즈', '${JS_USER_NAME}님이 숏퀴즈 이벤트에 초대했어요!\\n친구와 같이 퀴즈 풀고 선물 받아가세요.', 'result', ${_idx},'invite');">
     <img src="https://cdn.banggooso.com/sr/assets/images/modal/link.png" class="img-width" />
   </div>
   `);
}

function getQuizIdx() {
  const data = {
    proc: 'quiz-init',
    csrf_token: JS_CSRF,
  };

  $.ajax({
    type: 'post',
    url: '/sr/modules/api.php',
    dataType: 'json',
    data: data,
    success: (_json) => {
      if (_json.code === 0) {
        const contents = _json.response.result;
        const quizIdx = contents.quizidx;
        location.href = '/sr/?idx=' + quizIdx;
      } else if (_json.code === 5) {
        location.replace('/sr/list');
      }
    },
    error: function (request, status, error) {
      console.log(error);
    },
  });
}

function getQuiz(_quizIdx) {
  const data = {
    proc: 'get-quiz-data',
    quiz_idx: _quizIdx,
    csrf_token: JS_CSRF,
  };

  $.ajax({
    type: 'post',
    url: '/sr/modules/api.php',
    dataType: 'json',
    data: data,
    success: (_json) => {
      if (_json.code === 0) {
        questionData = _json.response.result.questiondata;
        const quizData = _json.response.result.quizdata;
        const entryValidate = _json.response.result.entryvalidate;
        const shareCode = _json.response.result.sharecode;
        const entryUserData = _json.response.result.entryuserdata;
        const shareCnt = _json.response.result.quizdata.share_cnt;
        if (shareCode) {
          // 공유하기 코드 생성시 공유하기 버튼 랜더링
          renderShareButtons(_quizIdx, shareCode, quizData);
        }

        renderShare(shareCnt);
        initModal(entryValidate);
        modalPopTextSetting(quizData['notice']);
        renderVideo();
        renderQuestion(); // 질문지, 선택지 업데이트
        signSetting(entryUserData);
        giftInfo(quizData);
        showResultVideo(quizData['result_video_url']);

        window.sessionStorage.setItem('lastPlayedIdxSR', _quizIdx);
      }
      // 데이터없으면 sr/로 재로드
      else if (_json.code == 5) {
        window.sessionStorage.removeItem('lastPlayedIdxSR');
        location.href = '/sr/';
      } else {
        window.alert('올바르지 않은 접근입니다.');
        location.href = '/sr/list/';
      }
    },
    error: function (request, status, error) {
      console.log(error);
    },
  });
}
function showResultVideo(_resultVideo) {
  $('.result-iframe').attr('src', _resultVideo);
}
function saveResult(_quizIdx, _selectedAnswer, _nameInput, _phoneInput, _shareCode = '') {
  const data = {
    proc: 'save-result',
    quiz_idx: _quizIdx,
    selected_json: _selectedAnswer,
    entry_name: _nameInput,
    entry_phone: _phoneInput,
    share_code: _shareCode,
    csrf_token: JS_CSRF,
  };
  $.ajax({
    type: 'post',
    url: '/sr/modules/api.php',
    dataType: 'json',
    async: false,
    data: data,
    success: (_json) => {
      if (_json.code === 0) {
        const resultCode = _json.response.result.resultcode;

        // 페이지 넘어감
        $('.app-sign-page').remove(); // 이전 페이지 제거
        $('.app-result-page').show(); // 결과 페이지 show
        $(window).scrollTop(0);
        // 결과 조회
        getResult(resultCode);
      } else if (_json.code === 6) {
        getGtagSr('event', '중복_팝업_뜸', '중복_팝업_뜸');
        $('.nonmembers-again > .modal-dim .modal').empty().load('/sr/modal/nonmember/againContent.php');
        $('.nonmembers-again').show();
      }
    },
    error: function (request, status, error) {
      console.log(error);
    },
  });
  $('#agreeCheckbox').prop('checked', false);
}

function signSetting(_entryUserData) {
  phoneInput.addEventListener('input', function (event) {
    // 입력 값의 길이가 11자를 초과하면 초과된 부분을 제거함으로써 11자로 제한
    if (phoneInput.value.length > 13) {
      phoneInput.value = phoneInput.value.slice(0, 13);
    }
  });
  if (isLogin) {
    $('#nameInput').val(_entryUserData.entry_name);
    if (_entryUserData.entry_phone) {
      {
        $('#phoneInput').val(_entryUserData.entry_phone.replace(/^(\d{2,3})(\d{3,4})(\d{4})$/, `$1-$2-$3`));
      }
    }

    $('#agreeCheckbox').prop('checked', false);
  } else {
    $('#nameInput').empty();
    $('#phoneInput').empty();
    $('#agreeCheckbox').prop('checked', false);
  }
}
function getResult(_resultCode) {
  const data = {
    proc: 'get-result',
    code: _resultCode,
    csrf_token: JS_CSRF,
  };

  $.ajax({
    type: 'post',
    url: '/sr/modules/api.php',
    dataType: 'json',
    async: false,
    data: data,
    success: (_json) => {
      if (_json.code === 0) {
        const resultData = _json.response.result.resultdata;
        const resultCnt = _json.response.result.resultcnt;
        renderResultCnt(resultCnt);
        renderResult(resultData, _json.response.eggcnt);
      }
    },
    error: function (request, status, error) {
      console.log(error);
    },
  });
}

/**
 * 퀴즈 시작
 * @param {Int} _quizIdx
 * @returns
 */
function quizStart(_quizIdx) {
  return new Promise((resolve, reject) => {
    const data = {
      proc: 'quiz-start',
      quiz_idx: _quizIdx,
      csrf_token: JS_CSRF,
    };
    $.ajax({
      type: 'post',
      url: '/sr/modules/api.php',
      dataType: 'json',
      data: data,
      success: (_json) => {
        resolve(_json.code);
      },
      error: function (request, status, error) {
        console.log(error);
        reject(error);
      },
    });
  });
}

// 메인 페이지 : 퀴즈부분

function soundToggle() {
  if (isSoundFlag == true) {
    $.each(questionData, function (_index, _question) {
      $(`.question-video`)[_index].muted = true;
      $('.sound-off-img').show();
      $('.sound-on-img').hide();
    });
    isSoundFlag = false;
  } else {
    $.each(questionData, function (_index, _question) {
      $(`.question-video`)[_index].muted = false;
      $('.sound-on-img').show();
      $('.sound-off-img').hide();
    });
    isSoundFlag = true;
  }
}

function selectedAnswer(_qNo, _aNo, _callback) {
  selectedAnswerData.push({ qno: _qNo, answer: _aNo });
  if (_callback && typeof _callback === 'function') {
    _callback();
    return false;
  } else {
    nextStep();
  }
}

// 문제 로딩 시간 측정 시작
function startTimer() {
  let timeLimit = questionData[currentStep - 1]['limited_time']; // 문제 하나당 제한시간

  timerInterval = setInterval(function () {
    currentTime++; // 경과시간 1초마다 체크
    const countdown = timeLimit - currentTime; // 남은 시간

    if (currentTime >= timeLimit) {
      // 경과시간 넘었을때
      endVideo(); // 영상 멈추고
      if (currentStep >= questionData.length) {
        // 세번째 영상이였다면
        selectedAnswer(Number(questionData[currentStep - 1]['question_no']), 0, function () {
          return false;
        });
        clearCountdown(); // 타임어택 꺼
        $('.select-wrap').hide();
        $('.insert-finish').show();

        resetTimer();
        currentTime = -1;
        clearInterval(timerInterval); // 1초마다 체크되는 작동 중지
      } else {
        clearCountdown();
        $('.select-wrap').hide();
        $('.insert-timeout').show();
      }
    } else {
      // 아직 경과시간 넘기전에
      if (countdown <= 5) {
        // 5초 남으면
        updateCountdown(countdown); // 타임어택 고
        if (countdown === 0) {
          clearCountdown(); // 타임어택 꺼
        }
      } else {
        clearCountdown(); // 타임어택 꺼
      }
    }
  }, 1000);
}
// 5초 카운트다운 생성 & 삭제
// 타임어택 업데이트
function updateCountdown(_countdown) {
  // 타임어택 키기
  if (currentStep >= 0) {
    // 타임어택 키기
    $('.loading-wrapper').show();
    $('.loading-wrapper-alert').show();
    $('.loading-wrapper-time').text(_countdown);
  } else {
    console.error('Invalid currentStep:', currentStep);
  }
}
// 타임어택 끄기
function clearCountdown() {
  $('.loading-wrapper').hide();
  $('.loading-wrapper-time').text('');
}

// function showStartModal() {
//   if (!isLogin && currentStep === 2) {
//     $('.nonmembers-start-modal > .modal-dim .modal').empty().load('/sr/modal/nonmember/startModalContent.php');
//     $('.nonmembers-start-modal').show();
//     getGtagSr('intro', '가입_팝업_뜸', '비회원_가입_팝업_뜸');

//     const $video = $(`.question-video.no-2`)[0];
//     $video.pause();
//   }
// }

// 다음 버튼
function nextStep() {
  // 타이머 초기화
  svgClear(questionData[currentStep - 1]['limited_time']);
  // 선택지 내용 초기화
  answersInit();

  if (currentStep < questionData.length) {
    $('.select-wrap').show();
    $('.insert-timeout').hide();
    endVideo();
    currentStep++;
    clearInterval(timerInterval); // 이 부분을 추가하여 이전에 실행 중이던 interval을 중지
    renderQuestion();
    resetTimer();
    // showStartModal();
    // 로그인된 상태에선 무조건 실행
    // if (isLogin) {
    startVideo();
    // }
    // 비회원 기준 두번째 문제이후부턴 무조건 실행
    // else if (!isLogin && currentStep > 2) {
    //   startVideo();
    // }
  } else if (currentStep === questionData.length) {
    endVideo();
    $('.select-wrap').hide();
    $('.insert-finish').show();

    currentTime = 0;
    clearCountdown();
    resetTimer();
    clearInterval(timerInterval); // 이 부분을 추가하여 interval을 중지
    return;
  }
}

function resetTimer() {
  clearInterval(timerInterval); // setInterval 중지
  currentTime = 0; // 현재 시간 초기화
}

// 답안지 초기화
function answersInit() {
  const answers = document.querySelectorAll('.app-main-page .answer-wrap .answer');
  answers.forEach((answer) => {
    answer.style.backgroundColor = 'none';
  });
  clearCountdown();
}

// 비디오 전부 로드
function renderVideo() {
  const $videoWrap = $('.video-wrap');

  $.each(questionData, function (_index, _question) {
    autoplayStr = '';
    if (_question['question_no'] == 1) {
      autoplayStr = 'autoPlay';
    }
    $videoWrap.append(
      `<video playsinline ${autoplayStr} class="question-video no-${_question['question_no']}">
        <source src="${_question['video']}" />
      </video>`
    );
  });

  const $video = $(`.question-video.no-1`)[0];
  $video.pause();
}

// 문제와 질문지 로드
function renderQuestion() {
  $('.paging').text(`${currentStep}/${questionData.length}`);
  $('.question-video').removeClass('active');

  const $videoEl = $(`.question-video.no-${currentStep}`);
  $videoEl.addClass('active');

  $('.select-area-question .question').text(questionData[currentStep - 1]['question']);
  const $aWrap = $('.answer-wrap'); // 모든 .a-wrap 요소를 캐시
  $aWrap.empty(); // .answer-wrap 내부의 내용을 비움
  const aData = questionData[currentStep - 1]['answers'];

  $.each(aData, function (_index, _el) {
    $aWrap.append(
      `<div class="answer" onclick="getGtagSr('quiz', '${currentStep}번_보기${
        _index + 1
      }', '콘텐츠 문제풀이'); selectedAnswer(${questionData[currentStep - 1]['question_no']},${_el['ano']})">${
        _el['answer']
      }</div>`
    );
  });

  $('.insert-btn.insert-next-btn').attr(`onclick`, `selectedAnswer(${questionData[currentStep - 1]['question_no']},0)`);
}

function svgClear(_questionTime) {
  const $targetCircle = $('.target-circle-c');

  $targetCircle.css('animation', 'none');
  // 간격을 둬서 초기화가 완료된 후에 다시 애니메이션 설정
  setTimeout(() => {
    // 새로운 애니메이션 지속시간
    let animationDuration = parseInt(_questionTime) + 4; // 문제 하나당 제한시간
    $targetCircle.css('animation', `stroke-ani ${animationDuration}s linear`);
  }, 10);
}

// 모달 관련
// pop notice 업데이트
function modalPopTextSetting(_str) {
  $('.modal-pop-txt').text(_str);
}

function togglePop() {
  $('.modal-dim .modal-pop-hide').on('click', function () {
    $('.select-wrap .modal-pop').hide();
    $('.modal-dim .modal-pop-show').show();
    $('.modal-dim .modal-pop-hide').hide();
  });
  $('.modal-dim .modal-pop-show').on('click', function () {
    $('.select-wrap .modal-pop').hide();
    $('.modal-dim .modal-pop-show').hide();
    $('.modal-dim .modal-pop-hide').show();
  });
  $('.select-wrap .modal-pop-hide').on('click', function () {
    $('.select-wrap .modal-pop-show').show();
    $('.select-wrap .modal-pop-hide').hide();
  });
  $('.select-wrap .modal-pop-show').on('click', function () {
    $('.select-wrap .modal-pop-show').hide();
    $('.select-wrap .modal-pop-hide').show();
  });
}

function initModal(_entryValidate) {
  // 비회원
  if (!isLogin) {
    $('.nonmembers-play-modal > .modal-dim .modal').empty().load('/sr/modal/nonmember/playModalContent.php');
    $('.nonmembers-play-modal').show();
    getGtagSr('intro', '재생버튼', '비회원_재생버튼');
  }
  // 회원
  else {
    // 1번 이상 해봄
    if (_entryValidate == 1) {
      getGtagSr('intro', '재참여_팝업_뜸', '회원_재참여_팝업_뜸');
      $('.modal-pop').show();
      $('.again-modal').show();
    }
    // 첫 참여
    else {
      getGtagSr('intro', '첫참여_팝업_뜸', '회원_첫참여_팝업_뜸');
      $('.modal-pop').show();
      $('.start-modal').show();
    }
  }
}

// startQuiz : 모달끄면서 문제영상 작동
function startQuiz(_modal) {
  $('.select-wrap .modal-pop').show();
  $('.select-wrap .modal-pop-hide').show();
  $('.select-wrap .modal-pop-show').hide();

  quizStart(quizIdx)
    .then((code) => {
      if (code === 1) {
        getGtagSr('intro', '친구초대_팝업_뜸', '알없음_친구초대_팝업_뜸');
        closeModal(_modal);
        $('.noEgg-modal').show();
      } else {
        closeModal(_modal);
        startVideo();
      }
    })
    .catch((error) => {
      console.error(error);
    });
}

function showModal(_pop) {
  if (isLogin) {
    if (_pop == 'invite') {
      getGtagSr('result', '친구초대하기', '친구초대하기');
      closeModal(_pop);
      $('.invite-modal').show();
      $('.result-main-page').css('position', 'fixed');
    }
  } else {
    $('.nonmembers-end-modal > .modal-dim .modal').empty().load('/sr/modal/nonmember/endModalContent.php');
    $('.nonmembers-end-modal').show();
    $('.result-main-page').css('position', 'fixed');
  }
}

//  퀴즈 메인페이지
// 모달닫기와 함께 영상재생
function startVideo() {
  const $video = $(`.question-video.no-${currentStep}`)[0];
  // videoPlayFlag = 1;
  startTimer();

  // if (videoPlayFlag == 1) {
  $video.play();
  svgClear(questionData[currentStep - 1]['limited_time']);
  // }
}

function endVideo() {
  const $video = $(`.question-video.no-${currentStep}`)[0];
  $video.pause();
}

let now = true;
// 유의사항 확인하기
function noticeToggle() {
  if (now == true) {
    /* 지금 열려있지않으면 */
    $('.form-notice .notice-open').show();
    $('.form-notice .notice-close').hide();
    now = false;
  } else {
    $('.form-notice .notice-open').hide();
    $('.form-notice .notice-close').show();
    now = true;
  }
}
// 번호입력칸 자동 번호포맷화
function oninputPhone(target) {
  target.value = target.value.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
}
// 버튼활성화
function toggleButton() {
  $('#nameInput, #phoneInput').on('input', updateButtonState);
  $('#agreeCheckbox').on('change', updateButtonState);
  // 초기화
  updateButtonState();
}

// 버튼 토글 상태 변경 메서드
function updateButtonState() {
  let $submitButton = $('#submitButton');

  let nameValue = $('#nameInput').val().trim();
  let phoneValue = $('#phoneInput').val().trim();
  let isAgreed = $('#agreeCheckbox').is(':checked');

  // 입력된 내용이 있고 체크박스가 선택되어 있을 때 버튼을 활성화

  if (nameValue !== '' && phoneValue !== '' && isAgreed) {
    if (phoneValue.length < 9) {
      $submitButton.prop('disabled', true).css('backgroundColor', '#CFCFCF');
    } else {
      $submitButton.prop('disabled', false).css('backgroundColor', '#FA9715');
    }
  } else {
    $submitButton.prop('disabled', true).css('backgroundColor', '#CFCFCF');
  }
}

// 결과페이지
function goResult() {
  // 입력받은 데이터 전달
  let nameInput = $('#nameInput').val();
  let phoneNumInput = $('#phoneInput').val();
  let phoneInput = phoneNumInput.replace(/-/g, '');
  let shareCode = urlParams.get('sharecode');

  saveResult(quizIdx, selectedAnswerData, nameInput, phoneInput, shareCode); // 결과 코드
}

function giftInfo(_quizData) {
  // 제목
  $('.form-brandname').html(_quizData['entry_title']);

  // 해당 상품의 마감기한 & 당첨자 발표기한
  const endDate = new Date(_quizData['enddate']);
  const winnerDate = new Date(_quizData['winner_date']);
  const targetDate = new Date(_quizData['enddate']);

  $('.form-announce .end-date').text('~' + (parseInt(endDate.getMonth()) + 1) + '월' + endDate.getDate() + '일');
  $('.form-announce .winner-date').text(parseInt(winnerDate.getMonth()) + 1 + '월' + winnerDate.getDate() + '일');

  // 결과페이지 카운트다운
  // 타이머에 사용될 변수 선언
  let timer;
  // 1초마다 업데이트
  timer = setInterval(() => {
    displayRemainingTime(targetDate);
  }, 1000);

  $('.form-img')
    .empty()
    .append(`<img class="img-width" src="https://cdn.banggooso.com/sr/quiz/prize/${_quizData['result_prize_img']}" />`);
  $('.result-event .result-gift')
    .empty()
    .append(`<img class="img-width" src="https://cdn.banggooso.com/sr/quiz/prize/${_quizData['result_prize_img']}" />`);
}
function displayRemainingTime(_toDate) {
  let dateEntered = _toDate; // 주어진 날짜
  let now = new Date(); // 현재 시간
  let difference = dateEntered.getTime() - now.getTime(); // 두 날짜 간의 밀리초 단위 차이 계산

  if (difference <= 0) {
    // 주어진 날짜 이후면 타이머 종료
    clearInterval(timer);
    return;
  } else {
    // 남은 시간 계산
    const days = Math.floor(difference / (1000 * 60 * 60 * 24));
    difference -= days * (1000 * 60 * 60 * 24);
    const hours = Math.floor(difference / (1000 * 60 * 60));
    difference -= hours * (1000 * 60 * 60);
    const minutes = Math.floor(difference / (1000 * 60));
    difference -= minutes * (1000 * 60);
    const seconds = Math.floor(difference / 1000);

    // 시간을 두 자리로 표시하도록 수정
    const hoursStr = hours.toString().padStart(2, '0');
    const minutesStr = minutes.toString().padStart(2, '0');
    const secondsStr = seconds.toString().padStart(2, '0');
    if (days <= 1) {
      $('#timer').text(`0일 ${hoursStr}:${minutesStr}:${secondsStr} 남음`);
    } else {
      $('#timer').text(`${days - 1}일 ${hoursStr}:${minutesStr}:${secondsStr} 남음`);
    }
    // 남은 시간을 화면에 표시
  }
}

function renderShare(_shareCnt) {
  $('.result-main-page .share-num').text(_shareCnt);
}
// 채점 후 반영
function renderResult(_resultData, _eggCnt) {
  if (_eggCnt == null) {
    _eggCnt = 0;
  }
  $('.header-egg .white-span').text(_eggCnt);
  let eggImage = '';
  let correctCnt = 0;
  let eggEmoji = '';
  let eggText = '';

  const resultJsonData = JSON.parse(_resultData['json_data']);
  const companyName = _resultData['company_name'];
  const quizRound = _resultData['quiz_round'];
  $.each(resultJsonData, function (index, el) {
    if (el.score == 'O') {
      eggImage = 'https://cdn.banggooso.com/sr/assets/images/result/correct.png';
      eggEmoji += '🐣';
      correctCnt++;
    } else {
      eggImage = 'https://cdn.banggooso.com/sr/assets/images/result/wrong.png';
      eggEmoji += '🥚';
    }

    $('.result-egg').append(
      `<div class="result-eggdiv"><div>${el.qno}/${resultJsonData.length}</div><div class="result-eggdiv-img"><img class="img-width" src="${eggImage}"></div></div>`
    );
    $('.correct-cnt').text(correctCnt);
  });

  let wrongCnt = resultJsonData.length - correctCnt;
  if (wrongCnt == resultJsonData.length) {
    // 다 틀림
    eggText = '저 좀 꺼내주세요..';
  } else if (wrongCnt == 2) {
    eggText = '알 두개 더 깨기 도전? ';
  } else if (wrongCnt == 1) {
    eggText = '라스트 알 깨기 도전? ';
  } else if (wrongCnt > 2) {
    eggText = '아직 깨야 할게 많아 다시 도전? ';
  } else {
    eggText = '모든 알 깨기 성공!🎊 ';
  }

  $('.share-wrapper').append(`
  <div class="result-iconbox" onclick="getGtagSr('result', '공유하기_팝업_카카오', '공유하기_팝업_카카오'); shareKakaotalk('숏퀴즈', '숏퀴즈 ${quizRound} ${correctCnt}/${resultJsonData.length} ${eggEmoji} : ${eggText}알 모아서 재도전 당첨확률 UP!', '숏퀴즈 with ${companyName}', '바로가기', 1200, 630, '?idx=${quizIdx}', 'main', ${quizIdx}, 'share');">
    <img src="https://cdn.banggooso.com/sr/assets/images/result/kakao.png" class="img-width" />
  </div>
  <div class="result-iconbox" onclick="getGtagSr('result', '공유하기_팝업_페북', '공유하기_팝업_페북'); shareFacebook('?idx=${quizIdx}', '숏퀴즈', '숏퀴즈 ${quizRound} ${correctCnt}/${resultJsonData.length} ${eggEmoji} : ${eggText}\\n알 모아서 재도전 당첨확률 UP!\\n', 'main', ${quizIdx}, 'share');">
    <img src="https://cdn.banggooso.com/sr/assets/images/result/fb.png" class="img-width" />
  </div>
  <div class="result-iconbox" onclick="getGtagSr('result', '공유하기_팝업_트위터', '공유하기_팝업_트위터'); shareTwitter('?idx=${quizIdx}', '숏퀴즈', '숏퀴즈 ${quizRound} ${correctCnt}/${resultJsonData.length} ${eggEmoji} : ${eggText}\\n알 모아서 재도전 당첨확률 UP!\\n', 'main', ${quizIdx}, 'share');">
    <img src="https://cdn.banggooso.com/sr/assets/images/result/twt.png" class="img-width" />
  </div>
  <div class="result-iconbox" onclick="getGtagSr('result', '공유하기_팝업_링크', '공유하기_팝업_링크'); shareLink('?idx=${quizIdx}', '숏퀴즈', '숏퀴즈 ${quizRound} ${correctCnt}/${resultJsonData.length} ${eggEmoji} : ${eggText}\\n알 모아서 재도전 당첨확률 UP!\\n', 'main', ${quizIdx}, 'share');">
    <img src="https://cdn.banggooso.com/sr/assets/images/result/link.png" class="img-width" />
  </div>
  `);
}

function renderResultCnt(_resultCnt) {
  if (!isLogin) {
    $('.result-main-page .result-participation').hide();
  } else {
    $('.result-main-page .result-cnt').text(_resultCnt);
    $('.result-main-page .result-participation').show();
  }
}
