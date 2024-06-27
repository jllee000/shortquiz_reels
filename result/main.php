<div class="result-main-page">
  <div class="result-page">
    <div class="result-top">
      <div class="result-participation">
        <?php
        $username = $_SESSION['u_aname'];
        if (strlen($username) >= 5) {
          echo $username . "님이 <br/>해당 퀴즈에 참여한 횟수 <span class='result-cnt'></span>회";
        } else {
          echo $username . "님이 해당 퀴즈에 참여한 횟수 <span class='result-cnt'></span>회";
        }
        ?>
      </div>

      <div class="result-top-title">총 <span class="correct-cnt"></span>문제를 맞췄어요!</div>
      <div class="result-egg">
      </div>
      <? /* <!-- <img src="https://cdn.banggooso.com/sr/assets/images/result/friends.png" class="img-width" /> */ ?>
      <div>

        <div class="grey-txt">친구 1명 초대하면 알 5개!</div>
        <button class="result-event-btn friend" onclick="getGtagSr('result', '친구초대하기', '친구초대하기'); showModal('invite')">친구 초대하고 당첨확률 높이기 <img class="right-icon" src="https://cdn.banggooso.com/sr/assets/images/result/right.png" /> </button>
      </div>
    </div>
    <div class="result-video-div">
      <div>숏퀴즈 힌트 보고 재도전하기</div>
      <div class="result-video">
        <iframe class="result-iframe" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>

    </div>
    <div class="result-event">
      <div class="result-event-title">여러번 참여할 수록 당첨 확률 UP!</div>
      <div class="grey-txt">숏퀴즈는 매일 무제한 참여가 가능해요!</div>
      <div id="timer" class="result-event-time"></div>

      <div class="result-gift"></div>
      <div class="result-event-btn-div">
        <button class="result-event-btn goagain" onclick="getGtagSr('result', '숏퀴즈 다시하기', '숏퀴즈 다시하기'); goPage('again')">숏퀴즈 다시 풀기 <img class="replay-icon" src="https://cdn.banggooso.com/sr/assets/images/result/replay.png" /></button>

      </div>
    </div>

  </div>

  <div class="result-share-area">
    <div class="result-sharetxt">내 결과 공유하기 <img class="result-share-icon" src="https://cdn.banggooso.com/sr/assets/images/result/share.png" />
      <span class="share-num"></span>
    </div>
    <div class="display-row iconbox share-wrapper">
    </div>
    <div class="myrank-div">
      <button class="result-event-btn myrank" onclick="getGtagSr('result', '등급보기', '등급보기'); goPage('mypage')">나의 등급보기</button>
    </div>
  </div>
</div>