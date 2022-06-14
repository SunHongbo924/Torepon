//ハンバーガーメニュー
$(".burger-btn").on("click", function () {
  $(".humburger-nav").toggleClass("none");
  $(this).toggleClass("cross");
});
