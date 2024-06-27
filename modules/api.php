<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/sr/modules/sr_dao.php';

define("SUCCESS_CODE", 0); // 성공 코드
define("INSUFFICIENT_BALANCE_CODE", 1); // EGG 부족
define("NO_MEMBER", 4); // 비가입자 코드 , 비 로그인 코드
define("EMPTY_DATA", 5); // 데이터 미존재 코드
define("NO_MEMBER_PARTICIPATION_HISTORY_EXISTS", 6); // 참여이력 있음 코드
define("LIMITED_TIME_EXPIRED", 11); // 한정 시간 초과 코드
define("MISSING_PARAMETER", 12); // 필수 파라미터 누락 코드
define("UNKNOWN_ERROR_CODE", -2); // 알수없는 오류 코드

$apiEndpoint = isset($_POST['proc']) ? $_POST['proc'] : ''; // endpoint param
$apiEndpoint = filter_var($apiEndpoint, FILTER_SANITIZE_STRING); // 문자열 필터링

// 필수 파라미터 validation
if (empty($apiEndpoint)) {

  $code = MISSING_PARAMETER;
  $errmsg = "missing parameter (필수 파라미터 누락)";
  $json_data = [
    "code" => $code,
    "errmsg" => $errmsg,
  ];

  echo json_encode($json_data);
  exit;
}

$dao = new SR_DAO(); // dao 생성

$userId = isset($_SESSION['u_aid']) ? $_SESSION['u_aid'] : '';

// CSRF 토큰 검사 
if (!validateCSRFToken($_POST['csrf_token'])) {

  $code = UNKNOWN_ERROR_CODE;
  $errmsg = "No permission";
  $json_data = [
    "code" => $code,
    "errmsg" => $errmsg,
  ];

  echo json_encode($json_data);
  exit;
}

/**
 * 유저 캐시 정보 저장
 * @param String $_userMoney
 */
function setUserMoney($_userMoney)
{
  $_SESSION['u_sr_egg'] = $_userMoney;
}

/**
 * Exception 에러 핸들러
 * @param String 
 */
function handleException($ex)
{
  $code = UNKNOWN_ERROR_CODE;
  $errmsg = $ex->getMessage();
  return [$code, $errmsg];
}

/**
 * user idx Check
 * @param Int $_loginAidx
 * @param Int $_aidx
 */
function userValidationChk($_loginAidx, $_aidx)
{
  if ($_loginAidx === $_aidx) {
    return true;
  } else {
    return false;
  }
}

$response = null;
$egg_cnt = $_SESSION['u_sr_egg'];
$code = null;
$errmsg = null;

/**
 * API 분기 START
 */
switch ($apiEndpoint) {
    /**
   * 로그인 check
   */
  case 'login-check':
    try {
      if (!empty($userId)) {

        $userInfo = $dao->getUserInfo($userId);
        setUserMoney($userInfo['sr_egg']);
        $code = SUCCESS_CODE;
        $errmsg = "success";
      } else {
        // 비로그인
        $code = NO_MEMBER;

        $errmsg = "no member (비 로그인)";
      }
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;
    /**
     * 로깅
     * @param String actions - view, send share result
     */
  case 'logging':
    try {
      $actions = isset($_POST['actions']) ? $_POST['actions'] : '';
      $quizIdx = isset($_POST['quiz_idx']) ? $_POST['quiz_idx'] : 0;

      if (empty($actions) || empty($quizIdx)) {
        $code = MISSING_PARAMETER;
        $errmsg = "missing parameter (필수 파라미터 누락)";
        break;
      }

      $userInfo = $dao->getUserInfo($userId);


      $dao->setLoggingAction($userInfo['idx'], $actions, $RealipAddr, $quizIdx);

      $code = SUCCESS_CODE;
      $errmsg = "success";
      $response = [TRUE];
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    /**
     * 퀴즈 idx 추천
     * @return int 퀴즈 idx
     */
  case 'quiz-init':
    try {

      $userInfo = $dao->getUserInfo($userId);
      $userIdx = $userInfo['idx'];

      // 참여 이력 조회 > 0 : 참여이력 있음, empty 없음
      $entryValidate = $dao->getQuizEntryValidate($userIdx);

      // 참여 이력 idx 재정렬
      $quizIdxs = array_map(function ($data) {
        return $data[0];
      }, $entryValidate);

      // 플레이할 gameIdx 추출
      $quizIdx = $dao->getQuizIdx($quizIdxs);

      if (empty($quizIdx)) {
        // $quizIdx = $dao->getQuizIdx();
        $code = EMPTY_DATA;
        $errmsg = "empty data (데이터 없음)";
        break;
      }

      $response = [
        "quizidx" => $quizIdx
      ];

      $code = SUCCESS_CODE;
      $errmsg = "success";
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;

    /**
     * 퀴즈 조회
     * @param int quiz_idx - 퀴즈 IDX
     * @return json 퀴즈 정보, 문제 정보 json
     */
  case 'get-quiz-data':

    try {

      $userInfo = $dao->getUserInfo($userId);
      $userIdx = null;
      $quizIdx = !empty($_POST['quiz_idx']) ? $_POST['quiz_idx'] : 0;
      $codeData = '';
      $entryUserData = [];
      $entryValidate = 0;

      if (empty($quizIdx)) {
        $code = MISSING_PARAMETER;
        $errmsg = "missing parameter (필수 파라미터 누락)";
        break;
      }

      // 가입자 로직
      if (!empty($userId)) {
        $userIdx = $userInfo['idx'];
        $entryUserData = $dao->getEntryData($userIdx);
        // 유저 당 숏퀴즈 하나당 1개의 공유 코드 생성
        // 코드가 이미 있는지 확인 후 없으면 생성
        $codeData = $dao->getShareCode($quizIdx, $userIdx);

        if (empty($codeData)) {
          $codeData = $dao->makeShareCode($quizIdx, $userIdx, $userId);
        }

        // 현재 퀴즈 참여 이력 조회 0 : 참여 이력 없음, 1 참여 이력 있음
        $entryValidate = $dao->getSingleQuizValidate($userIdx, $quizIdx);
      }

      // 퀴즈 정보 조회
      $quizData = $dao->loadQuizData($quizIdx);

      // 없는 퀴즈 번호 입니다.
      if (empty($quizData)) {
        $code = EMPTY_DATA;
        $errmsg = "empty data (데이터 없음)";
        break;
      }

      $quizData['entry_title'] = htmlspecialchars_decode($quizData['entry_title']);
      // 문제 정보 조회
      $questionData = $dao->loadQuestionData($quizIdx);
      $answerData = [];
      $returnQuestion = [];

      foreach ($questionData as $idx => $row) {

        $returnQuestion[$idx] = $row;

        unset($returnQuestion[$idx]['answers_json']);
        $returnQuestion[$idx]['video'] = SR_CDN_PATH . "/video/quiz{$quizIdx}/{$questionData[$idx]['video']}";
        $returnQuestion[$idx]['answers'] = json_decode(stripslashes($row['answers_json']), true);
      }

      $dao->setLoggingAction($userIdx, 'view', $RealipAddr, $quizIdx);
      $response = [
        "quizdata" => $quizData,
        "questiondata" => $returnQuestion,
        "entryvalidate" => $entryValidate,
        "entryuserdata" => $entryUserData,
        "sharecode" => empty($codeData) ? '' : $codeData['code'],
      ];

      $code = SUCCESS_CODE;
      $errmsg = "success";
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;

    /**
     * 퀴즈 스타트
     * @param int quiz_idx - 퀴즈 IDX
     * @return json {"code":0,"errmsg":"success","response":{"cnt":null,"result":null}}
     */
  case 'quiz-start':
    try {
      $quizIdx = $_POST['quiz_idx'];

      if (empty($quizIdx)) {
        $code = MISSING_PARAMETER;
        $errmsg = "missing parameter (필수 파라미터 누락)";
        break;
      }

      if (!empty($userId)) { // 가입자
        // egg 데이터 조회
        $userInfo = $dao->getUserInfo($userId);
        $userIdx = $userInfo['idx'];
        $userEgg = $dao->checkUserEgg($userIdx);


        // 현재 퀴즈 참여 이력 조회 0 : 참여 이력 없음, 1 참여 이력 있음
        $entryValidate = $dao->getSingleQuizValidate($userIdx, $quizIdx);

        if (!empty($entryValidate)) { // 참여이력 있음

          // egg 확인
          if ($userEgg < 1) {
            $code = INSUFFICIENT_BALANCE_CODE;
            $errmsg = "Your balance is insufficient.(egg 부족)";
            break;
          }
        }
      }

      $dao->setLoggingAction($userIdx, 'start', $RealipAddr, $quizIdx);

      $code = SUCCESS_CODE;
      $errmsg = "success";
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;

    /**
     * 결과 저장
     * @param int quiz_idx - 퀴즈 IDX
     * @param string selected_json - 유저 문제 선택 값 json
     * @param string entry_name - 응모자명
     * @param string entry_phone - 응모자 전화번호
     * @param string share_code - 공유 코드
     * @return json
     */
  case 'save-result':
    try {
      $quizIdx = !empty($_POST['quiz_idx']) ? $_POST['quiz_idx'] : 0;
      $selectedData = !empty($_POST['selected_json']) ? $_POST['selected_json'] : '';
      $entryName = !empty($_POST['entry_name']) ? $_POST['entry_name'] : '';
      $entryPhone = !empty($_POST['entry_phone']) ? $_POST['entry_phone'] : '';
      $shareCode = !empty($_POST['share_code']) ? $_POST['share_code'] : '';

      $userIdx = '';
      $firstPlayStatus = 0; // 첫 참여시 0 아닐시 1

      if (empty($selectedData) || empty($entryName) || empty($entryPhone)) {
        $code = MISSING_PARAMETER;
        $errmsg = "missing parameter (필수 파라미터 누락)";
        break;
      }

      if (!empty($userId)) {
        $userInfo = $dao->getUserInfo($userId);
        $userIdx = $userInfo['idx'];

        // 현재 퀴즈 참여 이력 조회 0 : 참여 이력 없음, 1 참여 이력 있음
        $entryValidate = $dao->getSingleQuizValidate($userIdx, $quizIdx);

        if (!empty($entryValidate)) { // 참여이력 있음
          // egg 데이터 조회
          $userEgg = $dao->checkUserEgg($userIdx);

          // egg 사용 가능 여부 확인
          if ($userEgg < 1) {
            $code = INSUFFICIENT_BALANCE_CODE;
            $errmsg = "Your balance is insufficient.(egg 부족)";
            break;
          }

          // egg 사용
          $dao->setEggLogging($userIdx, $userId, $quizIdx, -1);

          $userEgg = $dao->checkUserEgg($userIdx, "w");
          setUserMoney($userEgg);

          $firstPlayStatus = 1;
        }

        // egg 공유 코드
        if (!empty($shareCode)) {

          // 해당 코드로 결과가 만들어진 유저가 또 플레이 시에는 지급 X 
          $resultShareCodeValidate = $dao->getResultWithShareCode($quizIdx, $userIdx, $shareCode);

          if (empty($resultShareCodeValidate)) {
            // 2, 코드로 플레이시 초대자에게만 인당 에그 5개씩 지급
            $shareCodeInfo = $dao->getShareInfoWithCode($shareCode);

            // 3, 자기 자신의 코드로는 지급 X 
            if ($shareCodeInfo['aidx'] != $userIdx) {
              $dao->setEggLogging($shareCodeInfo['aidx'], $shareCodeInfo['aid'], $shareCodeInfo['quiz_idx'], 5);
            } else { // 자기 자신의 코드일 경우엔 코드 초기화
              $shareCode = '';
            }
          } else { //2회 이상 플레이시 코드 초기화
            $shareCode = '';
          }
        }
      } else {
        // 비가입자 응모 이력 조회
        $entryCheck = $dao->getEntryValidate($quizIdx, $entryPhone);

        if ($entryCheck > 0) {
          // 응모 이력 있음 응모 X 
          $code = NO_MEMBER_PARTICIPATION_HISTORY_EXISTS;
          $errmsg = "no member participation_history_exists(비가입자 참여이력 존재)";
          break;
        }

        $firstPlayStatus = 0;
      }

      //정답 조회
      $questionData = $dao->getCorrectAnswer($quizIdx);
      $score = 'X';

      foreach ($selectedData as $idx => $data) {
        foreach ($questionData as $question) {
          if ($question["question_no"] == $data['qno']) {
            if ($question["correct_answer"] == $data['answer']) {
              $score = 'O';
            } else {
              $score = 'X';
            }
            $selectedData[$idx]['score'] = $score;
          }
        }
      }

      // 결과 저장
      $resultData = $dao->saveResult($quizIdx, $userIdx, $userId, json_encode($selectedData), $shareCode, $firstPlayStatus);

      if (!empty($userIdx)) { // 로그인 되어 있을때만 알 누적
        $userInfo = $dao->getUserInfo($userId);
        if ($resultData['sr_result_cnt'] > 0 && $resultData['sr_result_cnt'] % 5 == 0) { // 결과 저장 후 EGG 3건 지급 조건인 결과 5건 일때 알 지급
          $dao->setEggLogging($userIdx, $userId, $quizIdx, 3);
          $userInfo = $dao->getUserInfo($userId);
          setUserMoney($userInfo['sr_egg']);
        }
      }

      // 결과 로깅
      $dao->setLoggingAction($userIdx, 'result', $RealipAddr, $quizIdx);

      // 응모
      $dao->setEntryData($quizIdx, $userIdx, $userId, $entryName, $entryPhone);

      $egg_cnt = $_SESSION['u_sr_egg']; // egg 누적 타이밍이라 재세팅

      $response = [
        "resultcode" => $resultData['result_seq']
      ];

      $code = SUCCESS_CODE;
      $errmsg = "success";
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;

    /**
     * 결과 조회
     * @param int code - 퀴즈 결과코드
     * @return json
     */
  case 'get-result':
    try {
      $resultCode = !empty($_POST['code']) ? $_POST['code'] : 0;

      if (empty($resultCode)) {
        $code = MISSING_PARAMETER;
        $errmsg = "missing parameter (필수 파라미터 누락)";
        break;
      }

      $resultData = $dao->getResult($resultCode);

      if (empty($resultData)) {
        $code = EMPTY_DATA;
        $errmsg = "empty data (데이터 없음)";
        break;
      }

      if (!empty($userId)) {
        $userInfo = $dao->getUserInfo($userId);
        $resultCnt = $dao->getResultLogCount($resultData['quiz_idx'], $userInfo['idx']);
      }

      $response = [
        "resultdata" => $resultData,
        "resultcnt" => empty($userInfo) ? '' : $resultCnt
      ];

      $code = SUCCESS_CODE;
      $errmsg = "success";
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;

    /**
     * 나의 숏퀴즈 조회
     * @param int code - 퀴즈 결과코드
     * @return json
     */
  case 'get-my-data':
    try {
      $userInfo = $dao->getUserInfo($userId);

      $resultValidate = $dao->getResultCheck($userInfo['idx']);

      if (empty($userInfo)) {
        $code = EMPTY_DATA;
        $errmsg = "empty data (데이터 없음)";
        break;
      }

      $response = [
        "username" => $userInfo['aname'],
        "eggcnt" => $userInfo['sr_egg'],
        "resultcnt" => $userInfo['sr_result_cnt'],
        "level" => !empty($resultValidate) ? $userInfo['sr_level'] : 0,
      ];

      $code = SUCCESS_CODE;
      $errmsg = "success";
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;

    /**
     * 퀴즈 리스트 조회
     * @param int code - 퀴즈 결과코드
     * @return json
     */
  case 'get-quiz-list':
    try {
      $userInfo = $dao->getUserInfo($userId);
      $userIdx = $userInfo['idx'];

      $openList = $dao->getQuizList('open', $userIdx, 8);
      $expectList = $dao->getQuizList('expected', 5);
      $endList = $dao->getQuizList('end', 5);
      $notice = $dao->getNotice();

      if (empty($openList) && empty($expectList) && empty($endList)) {
        $code = EMPTY_DATA;
        $errmsg = "empty data (데이터 없음)";
        $response = [
          "notice" => $notice,
          "openlist" => $openList,
          "expectlist" => $expectList,
          "endlist" => $endList,
        ];
        break;
      }

      $response = [
        "notice" => $notice,
        "openlist" => $openList,
        "expectlist" => $expectList,
        "endlist" => $endList,
      ];

      $code = SUCCESS_CODE;
      $errmsg = "success";
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;

    /**
     * 당첨자 조회
     * @param int quiz_idx - 퀴즈 번호
     * @return json
     */
  case 'get-winner-list':
    try {
      $quizIdx = !empty($_POST['quiz_idx']) ? $_POST['quiz_idx'] : 0;
      $winnerNotice = $dao->getWinner($quizIdx);

      if (empty($winnerNotice)) {
        $code = EMPTY_DATA;
        $errmsg = "empty data (데이터 없음)";
        break;
      }

      $response = [
        "data" => $winnerNotice,
      ];

      $code = SUCCESS_CODE;
      $errmsg = "success";
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;
    /**
     * 당첨자 조회
     * @param int quiz_idx - 퀴즈 번호
     * @return json
     */
  case 'get-winner-data':
    try {
      $quizIdx = !empty($_POST['quiz_idx']) ? $_POST['quiz_idx'] : 0;
      $winnerNotice = $dao->getWinner($quizIdx);

      if (empty($winnerNotice)) {
        $code = EMPTY_DATA;
        $errmsg = "empty data (데이터 없음)";
        break;
      }

      $response = [
        "data" => $winnerNotice,
      ];

      $code = SUCCESS_CODE;
      $errmsg = "success";
    } catch (Exception $ex) {
      list($code, $errmsg) = handleException($ex);
    }
    break;
}

$json_data = [
  "code" => $code,
  "errmsg" => $errmsg,
  "response" => [
    "eggcnt" => $egg_cnt,
    "result" => $response
  ]
];

echo json_encode($json_data);
