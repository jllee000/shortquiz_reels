headerTxt('당첨자 발표');
const urlParams = new URLSearchParams(location.search);
if (urlParams.has('quiz_idx')) {
  quizIdx = urlParams.get('quiz_idx');

  $.ajax({
    type: 'post',
    url: '/sr/modules/api.php',
    dataType: 'json',
    data: {
      proc: 'get-winner-data',
      csrf_token: JS_CSRF,
      quiz_idx: quizIdx,
    },
    success: (_json) => {
      if (_json.code === 0) {
        $('.winner-txt').append(_json.response.result.data.content);
      }
    },
    error: function (request, status, error) {
      console.log(error);
    },
  });
} else {
}
