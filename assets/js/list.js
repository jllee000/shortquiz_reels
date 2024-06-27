getQuizList();
headerTxt('퀴즈 리스트');
// 오픈리스트 드래그
let isDragging = false;
let startX, scrollLeft;

$('.open-group').on('mousedown', function (e) {
  isDragging = true;
  startX = e.pageX - $('.open-group').offset().left;
  scrollLeft = $('.open-group').scrollLeft();
});

$('.open-group').on('mouseleave', function () {
  isDragging = false;
});

$('.open-group').on('mouseup', function () {
  isDragging = false;
});

$('.open-group').on('mousemove', function (e) {
  if (!isDragging) return;
  e.preventDefault();
  const x = e.pageX - $('.open-group').offset().left;
  const walk = (x - startX) * 2; // 드래그한 거리에 따라 스크롤 속도를 조절할 수 있습니다.
  $('.open-group').scrollLeft(scrollLeft - walk);
});

function fnMove(seq) {
  var offset = $('#div' + seq).offset();
  $('html, body').animate(
    {
      scrollTop: offset.top,
    },
    400
  );
}

// list페이지 api연동 부분
function getQuizList() {
  const data = {
    proc: 'get-quiz-list',
    csrf_token: JS_CSRF,
  };

  $.ajax({
    type: 'post',
    url: '/sr/modules/api.php',
    dataType: 'json',
    data: data,

    success: (_json) => {
      if (_json.code === 0 || _json.code === 5) {
        const openList = _json.response.result.openlist;
        const endList = _json.response.result.endlist;
        const expectList = _json.response.result.expectlist;
        const notice = _json.response.result.notice;

        createOpenElements(openList);
        createEndElements(endList);
        createExpectElements(expectList);
        updateNotice(notice);
      }
    },
    error: function (request, status, error) {
      console.log(error);
    },
  });
}

// 상단 띠배너 notice 글자수 22기준 애니메이션 주기
function updateNotice(_notice) {
  $('.content').text(_notice[0]['content']);
  let noticeLength = $('.content').text().trim().length;
  if (noticeLength >= 22) {
    $('.track').addClass('start-ani');
  } else {
    $('.track').removeClass('start-ani');
  }
}

function goContent(_idx) {
  if (_idx >= 0) {
    location.href = `/sr/?idx=${_idx}`;
  }
}
// 데이터 맵핑 시키기 createOpen~ createExpect~ createEnd~
function createOpenElements(_openList) {
  let isLogin = checkLogin();
  if (_openList.length == 0) {
    const $open = $('.open');
    $('.open .open-group').remove();
    $open.css('height', '7.5rem');
    $open.append(`<div class="no-open">
    <img class="no-open-img" src="https://cdn.banggooso.com/sr/assets/images/list/no-opening.png" />
  </div>`);
  } else {
    for (let i = 0; i < _openList.length; i++) {
      let entry = _openList[i];
      let dueDate = new Date(entry.enddate);
      let currentDate = new Date();

      // 시간을 0으로 설정
      dueDate.setHours(0, 0, 0, 0);
      currentDate.setHours(0, 0, 0, 0);

      let daysRemaining = Math.floor((dueDate - currentDate) / (1000 * 60 * 60 * 24));

      // open-element 생성 및 속성 설정
      let $openElement = $(
        `<div class="open-element" onclick="goContent(${_openList[i].idx})" id="openList[i].idx"></div>`
      );

      // open-overlay 생성
      let $openOverlay = $("<div class='open-overlay'></div>");
      let $hotSticker = `<div class="hot-sticker">추천</div>`;
      let $newSticker = `<div class="new-sticker">NEW</div>`;
      let $dueSticker = `<div class="due-sticker">D-${daysRemaining}</div>`;
      let $overlayTop = $("<div class='open-overlay-top'></div>");

      // openlist 뱃지 달기
      // 1이면 new, 2이면 hot
      if (entry.badge == '2') {
        $overlayTop.append($hotSticker, $dueSticker);
      } else if (entry.badge == '1') {
        $overlayTop.append($newSticker, $dueSticker);
      } else {
        $overlayTop.append($dueSticker);
      }

      let $overlayBottom = $("<div class='open-overlay-bottom'></div>");

      let winnerDate = new Date(entry.winner_date);
      let startDate = new Date(entry.startdate);
      let endDate = new Date(entry.enddate);

      $overlayBottom.html(
        '<span class="open-overlay-bold">발표</span> ' +
          (parseInt(winnerDate.getMonth()) + 1) +
          '월' +
          winnerDate.getDate() +
          '일' +
          '<br />' +
          '<span class="open-overlay-bold">기간</span> ' +
          (parseInt(startDate.getMonth()) + 1) +
          '월' +
          startDate.getDate() +
          '일' +
          '~' +
          (parseInt(endDate.getMonth()) + 1) +
          '월' +
          endDate.getDate() +
          '일'
      );

      $openOverlay.append($overlayTop, $overlayBottom);

      // open-element-video 생성
      let $openElementVideo = $('<div class="open-element-video"></div>');
      let $videoImg = `<img class="open-img-height" src="https://cdn.banggooso.com/sr/quiz/thumbnails/${entry.thumbnail}" />`;
      $openElementVideo.append($videoImg);

      // open-element-contents 생성
      let $openElementContents = $('<div class="open-element-contents"></div>');
      $openElementContents.append($openOverlay, $openElementVideo);
      // open-element-detail 생성
      let $openElementDetail = $('<div class="open-element-detail"> </div');
      if (isLogin) {
        $openElementDetail.html(
          '<div class="open-detail-txt">' +
            entry.title +
            '</div>' +
            '<div class="open-mycheck">내 참여 횟수 ' +
            entry.my_result_cnt +
            '</div>' +
            '<div><div class="open-check">참여수 ' +
            entry.start_cnt +
            '</div></div>'
        );
      } else {
        $openElementDetail.html(
          '<div class="open-detail-txt">' +
            entry.title +
            '</div>' +
            '<div><div class="open-check">참여수 ' +
            entry.start_cnt +
            '</div></div>'
        );
      }

      // open-element에 open-element-contents, open-element-detail 추가
      $openElement.append($openElementContents, $openElementDetail);

      // 생성한 open-element를 .open-group에 추가
      $('.open-group').append($openElement);
    }
  }
}

function confirmWinnerDate(winnerDate) {
  const currentDate = new Date();
  return currentDate > winnerDate;
}

function createExpectElements(_expectList) {
  if (_expectList.length == 0) {
    $('.coming-group').append(`<div class="no-coming">
    <img class="no-coming-img" src="https://cdn.banggooso.com/sr/assets/images/list/no-open3.png" />
  </div>`);
  } else {
    for (let i = 0; i < _expectList.length; i++) {
      let expectDate = new Date(_expectList[i].startdate);
      let day = expectDate.getDate();
      let formattedDay = day < 10 ? '0' + day : day;
      let month = parseInt(expectDate.getMonth()) + 1;
      let formattedMonth = month < 10 ? '0' + month : month;
      $('.coming-group').append(
        `<div class="coming-element">
          <div class="coming-overlay">
            <div>Coming soon!</div>
            <div class="coming-date">
              ${expectDate.getFullYear()}.${formattedMonth}.${formattedDay} 
            </div>

          </div>
          <img class="coming-video heightset" src='https://cdn.banggooso.com/sr/quiz/expected/${
            _expectList[i].expected_thumbnail
          }'/>
        </div>`
      );
    }
  }
}
// <source src="https://cdn.banggooso.com/assets/images/game191/video/11.mp4" />
function createEndElements(_endList) {
  let endBrand = 0;
  if (_endList.length != 0) {
    for (let i = 0; i < _endList.length; i++) {
      let winnerDate = new Date(_endList[i]['winner_date']);
      let isWinnerDate = confirmWinnerDate(winnerDate);
      let $endVideoImg = $(
        `<img class="img-width heightset" src="https://cdn.banggooso.com/sr/quiz/expected/${_endList[i].expected_thumbnail}">`
      );

      $('.end-group').append(`
      <div class="end-element">
        <div class="end-overlay">
          <div>숏퀴즈 with ${_endList[i].company_name}</div>
          ${
            isWinnerDate
              ? `
            <div class="end-winner" onclick="location.href='/sr/winner/?quiz_idx=${_endList[i].idx}'";>
              당첨자 보기 <img src="https://cdn.banggooso.com/sr/assets/images/modal/button-right.png" class="modal-right-img-width" />
            </div>
          `
              : `
            <div class="end-winner">
              당첨자 발표 ${winnerDate.getMonth() + 1}월 ${winnerDate.getDate()}일
            </div>
          `
          }
          
        </div>
        <div class="end-video idx-${_endList[i].idx}"></div>
      </div>`);
      $(`.end-video.idx-${_endList[i].idx}`).append($endVideoImg);
    }
  }
}
