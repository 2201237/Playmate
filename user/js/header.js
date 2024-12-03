// $(function () {
// 	$('.btn-gNav').on("click", function () {
// 	  $('.gNav').toggleClass('open');  // メニューにopenクラスをつけ外しする
// 	});
//   });
	$('#hamburger').on('click', function(){
	$('.icon').toggleClass('close');
	$('.sm').slideToggle();
	});