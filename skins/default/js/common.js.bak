$(function(){
	notice_width();

// 상단메뉴 클릭
	$(".fstLi").mouseover(function(){
		$(this).attr('class', 'fstLiOv');
	}).mouseout(function(){
		$(this).attr('class', 'fstLi');
	}).click(function(){
		var eq = $("li[class^='fstLi']").index(this);
		$(".stmenu").each(function(){
			$(this).css("display", "none");
		});
		$(".stmenu").eq(eq).css("display", "block");
	});

//왼쪽 메뉴 클릭	
	$(".rdLeftMenu").click(function(){
		//div index
		var eq1 = $("div[class='stmenu']").index($(this).parent().parent().parent().parent());
		//li index
		var eq2 = $("div[class='stmenu']").eq(eq1).children().children().children().children().index(this);

		$.ajax({
			type: "POST",
			url: "./get_html.php",
			data: {e1:eq1, e2:eq2},
			success: function(msg){
				$(".notice_div").html(msg);
			},
			error : function(msg) {
		     alert('Error'+msg);
			}
		});
		   notice_width();
	});

//상단메뉴 애니메이션
	$(".sndLi").each(function(){
		$(this).parent(".snd").css("width", $(this).parent(".snd").parent(".fstLi").width()+60);
		$(this).css("width", $(this).parent(".snd").parent(".fstLi").width()+60);
	});
	$(".fstLi").mouseenter(function(){
		$(this).children(".snd").stop().slideDown("fast");
	}).mouseleave(function(){
		$(this).children(".snd").stop().slideUp("fast");
	});;


//창크기가 변항때
	$(window).resize(function(){
		notice_width();
//		iframe_resize();
	});
});

//notice 가로 크기 고정
function notice_width(){
	var x = $(".body").width()-$(".left_menu").width();
	$(".lastwrap").css('width', x+"px");

	var y = $(window).height() - $(".top").height() - $(".menubar").height() - $(".tail").height() - 10;
	$(".body").css('height', y+"px");

	$(".left_menu").css('height', y+"px");
//	$(".body").css('border', "1px solid red");
//	$(".body").html($(window).height()+"-"+$(".top").height()+"-"+$(".menubar").height()+"-"+$(".tail").height()+"-"+10+"-"+y);
}

function notice_widths(){
	var x = $(".body").width()-$(".left_menu").width();
	return x;
}

//iframe resize
function iframe_resize(){
/*

	if($("#mainContent").height() != 0 && $(parent.document).find("#ifrm_bbs") != null){
		pageheight = $("#mainContent").height()+30;
		$(parent.document).find("#ifrm_bbs").css('height',pageheight+"px");
		$(parent.document).find("#ifrm_bbs").width = "800px";
	}


	var y = $(window).height() - $(".top").height() - $(".menubar").height() - $(".tail").height() - 10;
	$(".body").css('height', y+"px");
	$(top.document).find("#ifrm_bbs").css('height', y+"px");
	*/
}


//헤드 메뉴 스크립트
function headGlobal(act){

	if(act == 'home'){
		location.href="./";
	}
	if(act == 'out'){
		location.href="./logout.php?a=out";
	}
}


//메인메뉴 이동
function indexhref(idx){
	var eq = 2;
	$(".stmenu").each(function(){
		$(this).css("display", "none");
	});
	$(".stmenu").eq(eq).css("display", "block");


	//div index
	var eq1 = 2;
	//li index
	var eq2 = 0;

	$.ajax({
		type: "POST",
		url: "./get_html.php",
		data: {e1:eq1, e2:eq2},
		success: function(msg){
			$(".notice_div").html(msg);
			$("#ifrm_bbs").attr('src', './board_view.php?idx='+idx);
		},
		error : function(msg) {
		 alert('Error'+msg);
		}
	});
	   notice_width();

}

function init(){
	var doc = document.getElementById("mainContent");
	var obj = parent.document.getElementById("ifrm_bbs");

	if(doc.offsetHeight != 0 && obj != null){
		pageheight = doc.offsetHeight+30;
		parent.document.getElementById("ifrm_bbs").height = pageheight+"px";
		obj.width = "800px";
	}
}