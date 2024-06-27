<div class="user-form">
  <div class="form-top">
    <div class="text-mid form-brand">
      <span class="form-brandname"></span>
    </div>
    <div class="form-announce">
      <div>기간 <span class="date end-date">미정</span></div>
      <div>발표 <span class="date winner-date">미정</span></div>
    </div>
    <div class="form-img"></div>
    <div class="form-inputdiv">
      <div><input id="nameInput" autofocus class="form-inputbox" placeholder=" 이름을 입력하세요" onfocus="this.placeholder = ''" /></div>
      <div><input id="phoneInput" oninput="oninputPhone(this)" class=" form-inputbox" placeholder="9자 이상의 전화번호를 입력하세요" onfocus="this.placeholder = ''" /></div>
    </div>
    <div class="form-acceptbox">
      <div>
        <label for="agreeCheckbox" onchange="toggleButton()" class="sign-label">
          <input type="checkbox" id="agreeCheckbox" />
          <i class=" form-checkbox circle"></i>
          <div class="accept-area">
            <div class="checktxt">개인정보 수집 및 활용동의</div>
            <div class="accept-text">개인정보는 이벤트 상품 발송 후 안전하게 폐기됩니다.</div>
          </div>
        </label>
      </div>
      <div onclick="openSignModal()" class="accept-toggle-box">약관</div>
    </div>
    <div>
      <button id="submitButton" class="form-btn" disabled onclick="getGtagSr('event', '결과보기', '결과보기'); goResult()">입력 완료하고 결과 보기</button>
    </div>
  </div>
  <div class="form-notice">
    <div>부정한 방법으로 이벤트에 응모했다고 판단되는 경우<br /> 이벤트 당첨에서 제외됩니다.</div>
    <div class="notice-close">
      <div class="form-notice-toggle" onclick="noticeToggle()">유의사항 확인하기
        <img class="toggleicon" src="https://cdn.banggooso.com/sr/assets/images/sign/notice-down.png" />
      </div>
    </div>
    <div class="notice-open">
      <div class="form-notice-toggle" onclick="noticeToggle()">유의사항 확인하기
        <img class="toggleicon" src="https://cdn.banggooso.com/sr/assets/images/sign/notice-up.png" />
      </div>
      <div class="form-notice-ul-div">
        <ul class="form-notice-ul">
          <li>경품은 이벤트 참여자 대상으로 별도 추첨이 진행됩니다. 5만 원 이상의 경품 및 발송을 위한 주소 정보 확보가 필요한 경우엔 7일 내 개별 연락을 통한 안내 예정이며, 이외엔 별도 안내 없이 응모한 번호로 경품이 발송됩니다.</li>
          <li>당첨자는 경품 발송을 위한 안내에 응답하지 않을 경우 당첨이 자동 취소됩니다.</li>
          <li>경품의 제세공과금(22%)은 당사 및 제휴사에서 부담합니다.</li>
          <li>경품 안내는 이벤트 참여 시 직접 입력한 연락처를 통해 안내되오니 정확한 정보를 입력해 주세요.
          <li>이벤트 경품은 재고 상황에 따라 다른 상품 또는 옵션으로 변경될 수 있습니다.</li>
          <li>이벤트 경품 수령 후 교환 및 환불은 불가능합니다.</li>
          <li>이벤트 경품은 타인에게 양도 및 판매할 수 없으며, 비정상적인 방법으로 참여하실 경우 방구석연구소 운영 정책에 따라 제재 받을 수 있습니다.</li>
          <li>해당 이벤트는 경품으로 제공되는 상품 브랜드와 무관하게 진행되는 방구석연구소 자체 이벤트이며, 경품 A/S 및 사용 문의는 경품 제조사 고객센터로 문의 부탁드립니다</li>
        </ul>
      </div>
    </div>
  </div>
</div>