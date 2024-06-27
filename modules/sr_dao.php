<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/_admin/assets/include/common.php';

class SR_DAO
{
  private $db;

  public function __construct()
  {
    $this->db = $GLOBALS['dbh']; // common.php
  }

  /**
   * 유저 정보 조회 유저id, 유저 티켓
   * @param String $_aId
   */
  function getUserInfo($_aId)
  {
    $sql = "SELECT `idx`,
                `aid`,
                `aname`,
                `aemail`,
                `sr_egg`,
                `sr_result_cnt`,
                `sr_level`, 
                `astatus`,
                `aregdate`
            FROM `C_TB_USER`
            WHERE `aid` = :aid;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':aid', $_aId, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * 참여 이력 조회 (컨텐츠 조회 전)
   * @param Int $_aIdx
   */
  function getQuizEntryValidate($_aIdx)
  {
    $sql = "SELECT `quiz_idx`
    FROM `SR_TB_RESULT`
    WHERE aidx = :aidx
    AND first_play_status = 0 
    GROUP BY quiz_idx;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_NUM);
  }

  /**
   * 선택된 퀴즈 참여 이력 조회 (컨텐츠 조회 전)
   * @param Int $_aIdx
   * @param Int $_quizIdx
   */
  function getSingleQuizValidate($_aIdx, $_quizIdx)
  {
    $sql = "SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END as `validate`
              FROM `SR_TB_RESULT`
              WHERE `aidx` = :aidx
              AND `quiz_idx` = :quiz_idx";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
    $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchColumn();
  }

  /**
   * 퀴즈 idx 추출
   * @param Int[] $_quizIdxs
   */
  function getQuizIdx($_quizIdxs = array())
  {
    $quizIdxsQuery = "";

    if (!empty($_quizIdxs)) {
      $placeholders = implode(',', array_fill(0, count($_quizIdxs), '?'));

      $quizIdxsQuery = "AND `idx` NOT IN ({$placeholders})";
    }

    $sql = "SELECT `idx`
            FROM `SR_TB_SHORTQUIZ` 
              WHERE `status` = 1
              AND `startdate` < now()
              AND `enddate` > now()
              {$quizIdxsQuery}
            ORDER BY `startdate` DESC LIMIT 1;";

    $stmt = $this->db['r']->prepare($sql);

    // $_quizIdxs가 비어있지 않다면 각 값을 바인딩
    if (!empty($_quizIdxs)) {
      foreach ($_quizIdxs as $key => $value) {
        $stmt->bindValue(($key + 1), $value, PDO::PARAM_INT);
      }
    }

    $stmt->execute();
    return $stmt->fetchColumn();
  }

  /**
   * 컨텐츠 조회
   * @param Int $_idx
   */
  function loadQuizData($_idx)
  {
    $sql = "SELECT`Q`.`idx`,
                `Q`.`title`,
                `Q`.`quiz_round`,
                `Q`.`company_name`, 
                `Q`.`desc`,
                `Q`.`notice`,
                `Q`.`thumbnail`,
                `Q`.`expected_thumbnail`,
                `Q`.`reserve`,
                `Q`.`startdate`,
                `Q`.`enddate`,
                `Q`.`status`,
                `Q`.`entry_title`,
                `Q`.`winner_date`,
                `Q`.`result_prize_img`,
                `Q`.`result_video_url`,
                `C`.`view_cnt` + `C`.`view_cnt_today` as `view_cnt`,
                `C`.`share_cnt` + `C`.`share_cnt_today` as `share_cnt`,
                `C`.`invite_cnt` + `C`.`invite_cnt_today` as `invite_cnt`,
                `C`.`result_cnt` + `C`.`result_cnt_today` as `result_cnt`, 
                `C`.`start_cnt` + `C`.`start_cnt_today` as `start_cnt`,
                `Q`.`regdate`
            FROM SR_TB_SHORTQUIZ as Q
            LEFT JOIN SR_TB_ACTION_COUNT as C
            ON Q.idx = C.quiz_idx
            WHERE Q.idx = :idx
            AND Q.status = 1
            AND Q.startdate < now()
            AND Q.enddate > now();";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':idx', $_idx, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * 문제 조회
   * @param Int $_idx
   */
  function loadQuestionData($_idx)
  {
    $sql = "SELECT `quiz_idx`,
                `question_no`,
                `question`,
                `video`,
                `answers_json`,
                `limited_time`
            FROM `SR_TB_QUESTIONS`
            WHERE `quiz_idx` = :quiz_idx;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':quiz_idx', $_idx, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 정답 조회
   * @param Int $_idx
   */
  function getCorrectAnswer($_idx)
  {
    $sql = "SELECT `question_no`,
                `correct_answer`
            FROM `SR_TB_QUESTIONS`
            WHERE `quiz_idx` = :quiz_idx;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':quiz_idx', $_idx, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  /**
   * 응모자 확인
   * @param Int $_idx
   * @param String $_entryPhone
   */
  function getEntryValidate($_idx, $_entryPhone)
  {
    $sql = "SELECT COUNT(1)
            FROM `SR_TB_ENTRY`
            WHERE `quiz_idx` = :quiz_idx
            AND `entry_phone` = :entry_phone;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':quiz_idx', $_idx, PDO::PARAM_INT);
    $stmt->bindParam(':entry_phone', $_entryPhone, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetchColumn();
  }

  /**
   * 응모
   * @param Int $_quizIdx
   * @param Int $_aIdx
   * @param String $_aId
   * @param String $_entryName
   * @param String $_entryPhone
   */
  function setEntryData($_quizIdx, $_aIdx, $_aId, $_entryName, $_entryPhone)
  {
    $sql = "INSERT INTO `SR_TB_ENTRY`
            (`quiz_idx`,    `aidx`,    `aid`,    `entry_name`,    `entry_phone`)
            VALUES
            (:quiz_idx,    :aidx,    :aid,    :entry_name,    :entry_phone);";

    $stmt = $this->db['w']->prepare($sql);
    $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
    $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
    $stmt->bindParam(':aid', $_aId, PDO::PARAM_STR);
    $stmt->bindParam(':entry_name', $_entryName, PDO::PARAM_STR);
    $stmt->bindParam(':entry_phone', $_entryPhone, PDO::PARAM_STR);
    $stmt->execute();
  }

  /**
   * 응모자 데이터 조회(가입자 기준)
   *  @param Int $_aIdx
   */
  function getEntryData($_aIdx)
  {
    $sql = "SELECT `aid`,
              `entry_name`,
              `entry_phone`
            FROM `SR_TB_ENTRY`
            WHERE `aidx` = :aidx
            ORDER BY `regdate` DESC LIMIT 1;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * 결과 등록
   *  @param Int $_qIdx
   *  @param Int $_aIdx
   *  @param String $_aId
   *  @param String $_jsonData
   *  @param String $_shareCode
   *  @param Int $_firstPlayStatus
   */
  function saveResult($_qIdx, $_aIdx, $_aId, $_jsonData, $_shareCode, $_firstPlayStatus)
  {
    try {
      // -------- 트랜잭션 시작 -------- 
      $this->db['w']->beginTransaction();

      $sql = "INSERT INTO `SR_TB_RESULT`
            (`quiz_idx`,    `aidx`,    `aid`,    `json_data`,     `share_code`, `first_play_status`)
            VALUES
            (:quiz_idx,    :aidx,    :aid,    :json_data,     :share_code,   :first_play_status);";

      $stmt = $this->db['w']->prepare($sql);
      $stmt->bindParam(':quiz_idx', $_qIdx, PDO::PARAM_INT);
      $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
      $stmt->bindParam(':aid', $_aId, PDO::PARAM_STR);
      $stmt->bindParam(':json_data', $_jsonData, PDO::PARAM_STR);
      $stmt->bindParam(':share_code', $_shareCode, PDO::PARAM_STR);
      $stmt->bindParam(':first_play_status', $_firstPlayStatus, PDO::PARAM_INT);
      $stmt->execute();
      $resultData = [];
      $resultData['result_seq'] = $this->db['w']->lastInsertId();

      if ($_firstPlayStatus == 1) {

        $sql = "UPDATE `C_TB_USER` 
        SET 
            `sr_result_cnt` = `sr_result_cnt` + 1,
            `sr_level` = CASE
                WHEN `sr_level` < 6 AND `sr_result_cnt` BETWEEN 1 AND 10 THEN 1
                WHEN `sr_level` < 6 AND `sr_result_cnt` BETWEEN 11 AND 30 THEN 2
                WHEN `sr_level` < 6 AND `sr_result_cnt` BETWEEN 31 AND 100 THEN 3
                WHEN `sr_level` < 6 AND `sr_result_cnt` BETWEEN 101 AND 200 THEN 4
                WHEN `sr_level` < 6 AND `sr_result_cnt` BETWEEN 201 AND 500 THEN 5
                WHEN `sr_result_cnt` BETWEEN 501 AND 1000 THEN 6
                ELSE `sr_level`
              END
        WHERE `idx` = :aidx;";

        $stmt = $this->db['w']->prepare($sql);
        $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
        $stmt->execute();
      }

      $sql = "SELECT `sr_result_cnt` FROM `C_TB_USER` WHERE `aid` = :aid;";
      $stmt = $this->db['w']->prepare($sql);
      $stmt->bindParam(':aid', $_aId, PDO::PARAM_STR);
      $stmt->execute();

      $resultData['sr_result_cnt'] = $stmt->fetchColumn();

      // -------- 트랜잭션 커밋 -------- 
      $this->db['w']->commit();

      return $resultData;
    } catch (Exception $e) {
      throw $e;
    }
  }

  /**
   * 결과 조회
   * @param Int $_idx
   */
  function getResult($_idx)
  {
    $sql = "SELECT `R`.`idx`,
                `R`.`quiz_idx`,
                `Q`.`quiz_round`,
                `Q`.`company_name`,
                `R`.`aidx`,
                `R`.`aid`,
                `R`.`json_data`,
                `R`.`first_play_status`,
                `R`.`regdate`
            FROM `SR_TB_RESULT` as `R`
            INNER JOIN 	`SR_TB_SHORTQUIZ` as `Q`
            ON `Q`.`idx` = `R`.`quiz_idx`
            WHERE `R`.`idx` = :idx;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':idx', $_idx, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * 현재 티켓 조회 (사용 하려는 값의 차액이 -값이 아닐때만 사용되도록 하는 용도)
   * @param Int $_aIdx
   */
  function checkUserEgg($_aIdx, $_dbType = "r")
  {
    $sql = "SELECT `sr_egg` FROM C_TB_USER WHERE `idx` = :aidx;";

    $stmt = $this->db[$_dbType]->prepare($sql);
    $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn();
  }


  /**
   * 유저 테이블 티켓 건수 업데이트
   * @param Int $_aIdx
   * @param String $_aId
   * @param Int $_quizIdx
   * @param Int $_egg
   */
  function setEggLogging($_aIdx, $_aId, $_quizIdx, $_egg)
  {
    try {
      // 트랜잭션 START
      $this->db['w']->beginTransaction();

      $sql = "INSERT INTO `SR_TB_EGG_LOG`
      (`aidx`,      `aid`,      `quiz_idx`,      `usedprice`)
      VALUES
      (:aidx,       :aid,        :quiz_idx,       :usedprice);";

      $stmt = $this->db['w']->prepare($sql);
      $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
      $stmt->bindParam(':aid', $_aId, PDO::PARAM_STR);
      $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
      $stmt->bindParam(':usedprice', $_egg, PDO::PARAM_INT);
      $stmt->execute();

      $sql = "UPDATE C_TB_USER 
              SET `sr_egg` = `sr_egg` + :egg
              WHERE `idx` = :aidx;";

      $stmt = $this->db['w']->prepare($sql);
      $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
      $stmt->bindParam(':egg', $_egg, PDO::PARAM_INT);
      $stmt->execute();

      $this->db['w']->commit();
      // 트랜잭션 END

    } catch (Exception $e) {
      // 롤백 수행
      $this->db['w']->rollBack();
      // 오류 처리 (로그 기록)
    }
  }

  /** 
   * 공지사항 리스트 조회
   * @return array 
   */
  function getNoticeList()
  {
    $sql = "SELECT `idx`, 
              `text`,    
              `order`,    
              `regby`,    
              `regdate`
            FROM `SR_TB_NOTICE` 
            WHERE `status` = 1;";


    $stmt = $this->db['r']->prepare($sql);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * 카운트 조회
   * @return array
   */
  function getActionCount()
  {
    $sql = "SELECT `view_cnt` + `view_cnt_today` as `view_cnt`, 
                `share_cnt` + `share_cnt_today` as `share_cnt`, 
                `result_cnt`, `result_cnt_today` as `result_cnt`,
                `invite_cnt`, `invite_cnt_today` as `invite_cnt`
            FROM `SR_TB_ACTION_COUNT` WHERE quiz_idx = 0;";

    $stmt = $this->db['r']->prepare($sql);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * egg 공유하기 코드 생성
   *
   * @param Int $_quizIdx - 퀴즈 IDX
   * @param Int $_aIdx - 보내는 사람의 IDX
   * @param String $_aId - 보내는 사람의 ID
   */
  function makeShareCode($_quizIdx, $_aIdx, $_aId)
  {

    try {
      $this->db['w']->beginTransaction();
      $sql = "INSERT INTO `SR_TB_SHARE`
              (`code`,    `quiz_idx`,   `aidx`,    `aid`)
              VALUES
              (UUID(),    :quiz_idx,    :aidx,    :aid);";

      $stmt = $this->db['w']->prepare($sql);
      $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
      $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
      $stmt->bindParam(':aid', $_aId, PDO::PARAM_STR);
      $stmt->execute();

      $idx = $this->db['w']->lastInsertId();

      $sql = "SELECT `idx`, `code` FROM `SR_TB_SHARE` WHERE `idx` = :idx;";

      $stmt = $this->db['w']->prepare($sql);
      $stmt->bindParam(':idx', $idx, PDO::PARAM_INT);
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      $this->db['w']->commit();
    } catch (Exception $e) {
      $row = false;
    }

    return $row;
  }

  /**
   * 공유하기 데이터 인서트
   *
   * @param Int $_resultIdx - 결과 인덱스
   * @param Int $_aidx - 보내는 사람의 IDX
   * @param String $_aid - 보내는 사람의 ID
   */
  function setPlayStatusUp($_shareCode)
  {
    try {
      $sql = "UPDATE `SR_TB_SHARE`
                SET `play_status` = 1
                WHERE `code` = :code;";

      $stmt = $this->db['w']->prepare($sql);
      $stmt->bindParam(':code', $_shareCode, PDO::PARAM_STR);
      $stmt->execute();
    } catch (Exception $e) {
    }
  }

  /**
   * 공유하기 코드 조회
   * @param Int $_quizIdx - 결과 인덱스
   * @param Int $_aidx - 보내는 사람의 IDX
   */
  function getShareCode($_quizIdx, $_aidx)
  {
    $sql = "SELECT `idx`, `code`
            FROM `SR_TB_SHARE`
            WHERE `quiz_idx` = :quiz_idx
            AND `aidx` = :aidx;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
    $stmt->bindParam(':aidx', $_aidx, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * 공유하기 코드로 유저 조회
   * @param Int $_shareCode - 공유코드
   */
  function getShareInfoWithCode($_shareCode)
  {
    $sql = "SELECT `idx`,
                `quiz_idx`,
                `aidx`,
                `code`,
                `aid`
            FROM `SR_TB_SHARE`
            WHERE `code` = :code;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':code', $_shareCode, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * 공유하기 코드로 결과 조회
   * @param Int $_quizIdx - 퀴즈 번호
   * @param Int $_aIdx - 유저 idx
   * @param Int $_shareCode - 공유코드
   */
  function getResultWithShareCode($_quizIdx, $_aIdx, $_shareCode)
  {
    $sql = "SELECT  `idx`,
              `quiz_idx`,
              `aidx`,
              `share_code`,
              `aid`
            FROM `SR_TB_RESULT`
            WHERE `share_code` = :code
            AND `quiz_idx` = :quiz_idx
            AND `aidx` = :aidx;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
    $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
    $stmt->bindParam(':code', $_shareCode, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * 유저 액션 로깅
   * @param int $_aIdx 유저 인덱스
   * @param string $_actions 유저 액션 (view, share, result, start 중 하나)
   * @param string $_aIp 유저 IP 주소
   * @param int $_quizIdx Optional 퀴즈 인덱스 (기본값: 0)
   */
  function setLoggingAction($_aIdx, $_actions, $_aIp, $_quizIdx = 0)
  {

    $this->db['w']->beginTransaction();

    $sql = "INSERT INTO `SR_TB_ACTION_LOG_BATCH`
    (`aidx`,    `actions`,    `quiz_idx`,    `ip`)
    VALUES
    (:aidx,     :actions,     :quiz_idx,     :ip);";

    $stmt = $this->db['w']->prepare($sql);
    $stmt->bindParam(':aidx', $_aIdx, PDO::PARAM_INT);
    $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
    $stmt->bindParam(':actions', $_actions, PDO::PARAM_STR);
    $stmt->bindParam(':ip', $_aIp, PDO::PARAM_STR);

    $stmt->execute();

    // quiz_idx 유효성 검사
    $sql = "SELECT count(*) FROM `SR_TB_ACTION_COUNT` WHERE quiz_idx = :quiz_idx;";
    $stmt = $this->db['w']->prepare($sql);
    $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    if (1 > $count) {
      $sql = "INSERT INTO `SR_TB_ACTION_COUNT`
        (`quiz_idx`)
        VALUES
        (:quiz_idx);";

      $stmt = $this->db['w']->prepare($sql);
      $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
      $stmt->execute();
    }


    $sql = "UPDATE SR_TB_ACTION_COUNT 
            SET view_cnt_today = CASE WHEN :actions = 'view' THEN view_cnt_today + 1 ELSE view_cnt_today END, 
                share_cnt_today = CASE WHEN :actions = 'share' THEN share_cnt_today + 1 ELSE share_cnt_today END, 
                result_cnt_today = CASE WHEN :actions = 'result' THEN result_cnt_today + 1 ELSE result_cnt_today END, 
                start_cnt_today = CASE WHEN :actions = 'start' THEN start_cnt_today + 1 ELSE start_cnt_today END,
                invite_cnt_today = CASE WHEN :actions = 'invite' THEN invite_cnt_today + 1 ELSE invite_cnt_today END
            WHERE quiz_idx = 
            (
              CASE 
              WHEN :quiz_idx IS NOT NULL THEN :quiz_idx 
              ELSE 0 END
            );";

    $stmt = $this->db['w']->prepare($sql);
    $stmt->bindParam(':actions', $_actions, PDO::PARAM_STR);
    $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
    $stmt->execute();

    $this->db['w']->commit();
  }

  /**
   * 공지사항 띠 배너
   */
  function getNotice()
  {

    $sql = "SELECT `idx`,
                `title`,
                `content`,
                `member_id`,
                `regdate`
            FROM `SR_TB_NOTICE` 
            ORDER BY regdate DESC LIMIT 1;";

    $stmt = $this->db['r']->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 퀴즈 리스트 조회
   * @param String $_type
   * @param Int $_aidx
   * @param Int $_limit
   */
  function getQuizList($_type, $_aidx, $_limit = 8)
  {
    $whereSql = "";

    $orderbySql = "";

    switch ($_type) {

      case "open":
        $whereSql = "WHERE `Q`.`status` = 1 
        AND `Q`.`startdate` < NOW() 
        AND `Q`.`enddate` > NOW() ";

        $orderbySql = " ORDER BY quiz_round DESC";
        break;
      case "expected":
        $whereSql = "WHERE `Q`.`status` = 1
        AND (TIMESTAMPDIFF(DAY, NOW(), `Q`.`startdate`) <= 7 && NOW() < `Q`.`startdate`) ";
        $orderbySql = " ORDER BY `Q`.`startdate` DESC ";
        break;
      case "end":
        $whereSql = "WHERE `Q`.`status` = 1         
        AND `Q`.`enddate` < now() ";
        $orderbySql = " ORDER BY `Q`.`startdate` DESC ";
        break;
    }

    $sql = "SELECT 
              `Q`.`idx`,
              `Q`.`title`,
              `Q`.`desc`,
              `Q`.`notice`,
              `Q`.`thumbnail`,
              `Q`.`company_name`, 
              `Q`.`badge`, 
              `Q`.`expected_thumbnail`,
              `Q`.`reserve`,
              `Q`.`startdate`,
              `Q`.`enddate`,
              `Q`.`status`,
              `Q`.`entry_title`,
              `Q`.`winner_date`,
              `C`.`view_cnt` + `C`.`view_cnt_today` as `view_cnt`,
              `C`.`share_cnt` + `C`.`share_cnt_today` as `share_cnt`,
              `C`.`invite_cnt` + `C`.`invite_cnt_today` as `invite_cnt`,
              `C`.`result_cnt` + `C`.`result_cnt_today` as `result_cnt`, 
              `C`.`start_cnt` + `C`.`start_cnt_today` as `start_cnt`,
              IFNULL(`R`.`my_result_cnt`, 0) AS `my_result_cnt`, 
              `Q`.`regdate`
          FROM `SR_TB_SHORTQUIZ` AS `Q`
          LEFT JOIN `SR_TB_ACTION_COUNT` AS `C` 
          ON `Q`.`idx` = `C`.`quiz_idx`
          LEFT JOIN (
              SELECT `quiz_idx`, COUNT(*) AS `my_result_cnt` 
              FROM `SR_TB_RESULT` 
              WHERE `aidx` = :aidx 
              GROUP BY `quiz_idx`
          ) AS `R` 
          ON `Q`.`idx` = `R`.`quiz_idx`
          {$whereSql} 
          {$orderbySql}
          
          LIMIT :limit;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':limit', $_limit, PDO::PARAM_INT);
    $stmt->bindParam(':aidx', $_aidx, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 담첨자 공지 조회
   * @param Int $_quizIdx
   */
  function getWinner($_quizIdx)
  {
    $sql = "SELECT `quiz_idx`,
                `title`,
                `content`
            FROM `SR_TB_WINNER`
            WHERE `quiz_idx` = :quiz_idx;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':quiz_idx', $_quizIdx, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * 참여자 체크
   * @param Int $_aidx
   */
  function getResultCheck($_aidx)
  {
    $sql = "SELECT idx
            FROM `SR_TB_RESULT`
            WHERE `aidx` = :aidx;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':aidx', $_aidx, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  function getResultLogCount($_quiz_idx, $_aidx)
  {

    $sql = "SELECT COUNT(*) AS `my_result_cnt` 
            FROM `SR_TB_RESULT` 
              WHERE quiz_idx = :quiz_idx 
              AND `aidx` = :aidx;";

    $stmt = $this->db['r']->prepare($sql);
    $stmt->bindParam(':quiz_idx', $_quiz_idx, PDO::PARAM_INT);
    $stmt->bindParam(':aidx', $_aidx, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn();
  }
}
