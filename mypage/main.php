<style>
  @keyframes fillAnimation {
    from {
      width: 0;
    }

    to {
      width: var(--fillPercentage);
    }
  }
</style>

<div class="mypage">
  <div class="mypage-top">
    <div><span class="mypage-username"></span>님은 <br />
      <span class="mypage-userlv">
        <!-- 레벨 -->
        <span class="mypage-nogame-span"></span>
        <span class="mypage-userlv-span"> </span><span class="mypage-userlv-span-black">입니다</span>
        <!-- 레벨에 따른 레벨 네임 맵핑 -->

      </span>
    </div>
    <div class="mypage-eggcnt">보유 알개수<span class="mypage-eggcnt-span"></span></div>
  </div>
  <div class="mypage-wrap">
    <!-- 레벨이미지 추가 -->
    <div class="mypage-imgdiv">
    </div>

    <!-- 하단 도표부분들 -->
    <div class="mypage-bottom">
      <!-- 레벨 부분들 -->
      <div class="mypage-lv">
        <div class="tag">Lv.</div>
        <div class="mypage-chart">
          <!-- 그래프 outer+inner -->
          <div class="outer">
            <div class="inner lv-in"></div>
          </div>
          <!-- 그래프 하단 수치마크 -->
          <div class="chart-mark">
            <div>0</div>
            <div>50</div>
            <div>100</div>
          </div>
        </div>
      </div>


      <!-- 알 부분 -->
      <div class="mypage-egg">
        <div class="tag">
          <div>
            <div>
              알 충전
            </div>
            <div>
              알을 5번 사용하면, 3알을 지급해요.
            </div>
          </div>
          <div class="egg3-div"><img src="https://cdn.banggooso.com/sr/assets/images/mypage/egg3.png" /></div>
        </div>
        <div class="mypage-chart">
          <!-- 그래프 outer+inner -->
          <div class="outer">
            <div class="inner egg-in"></div>
          </div>

          <!-- 그래프 하단 수치마크 -->
          <div class="chart-mark">
            <div>0회</div>
            <div>5회</div>
          </div>
        </div>

      </div>
    </div>

  </div>
  <div>
    <img class="aboutegg-img" src="https://cdn.banggooso.com/sr/assets/images/mypage/aboutegg.png" />
    <button class="mypage-goquiz img-width" onclick="getGtagSr('mypage', '퀴즈리스트', '퀴즈리스트'); goPage('list')">숏퀴즈 참여하기</button>
  </div>

</div>