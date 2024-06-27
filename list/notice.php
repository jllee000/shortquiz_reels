<style>
  .swiper-wrapper {
    padding: 0 !important;
    margin: 0 !important;
  }

  .banner-area {
    width: 100%;
    overflow: hidden;
    position: relative;
    margin: 0 auto;
  }


  .swiper-wrapper {
    display: flex;
  }
</style>

<div class="animated-title">
  <div class="notice-icon">🔔</div>
  <div class="track start-ani">
    <div class="content"> </div>
  </div>
</div>

<div class="banner-area">
  <div class="swiper loading-slider">
    <div class="swiper-wrapper">
      <div class="swiper-slide list-banner list-banner-1 "><img class="img-width list-banner-img" src="https://cdn.banggooso.com/sr/assets/images/list/%EB%B3%B4%EB%93%9C%EB%B0%B0%EB%84%882.png" onclick="goBanner('https://ss.notion.site/b14bdf2e1a034945b8af3dfb99aaa508')" /></div>
      <div class="swiper-slide list-banner list-banner-2 "><img class="img-width list-banner-img" src="https://cdn.banggooso.com/sr/assets/images/list/%EB%B3%B4%EB%93%9C%EB%B0%B0%EB%84%88.png" onclick="goBanner('https://www.softsphere.co.kr/shortquiz')" /></div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
  $(document).ready(function() {
    // Swiper 초기화
    var swiper = new Swiper('.swiper', {
      slidesPerView: 'auto', // 자동으로 슬라이드 개수를 설정
      spaceBetween: 16, // 슬라이드 간격 설정
    });
  });
</script>