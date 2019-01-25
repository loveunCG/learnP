	 $('.sidebar-toggle-box').click(function (e) {

            $(".dg-leftside-navigation").niceScroll({
                cursorcolor: "#2196f3",
                cursorborder: "0px solid #fff",
                cursorborderradius: "0px",
                cursorwidth: "3px"
				
            });

            $('#dg-sidebar').toggleClass('hide-left-bar');
            if ($('#dg-sidebar').hasClass('hide-left-bar')) {
                $(".leftside-navigation").getNiceScroll().hide();
            }
            $(".dg-leftside-navigation").getNiceScroll().show();
            $('#dg-main-content').toggleClass('merge-left');
			});
	var $window = $(window),
			$ul = $('#dg-main-content');
				$(window).on('load', function () {
				if ($window.width() < 979) {
				   $ul.addClass('merge-left');
			   }else{
				$ul.removeClass('merge-left')};
		});
	

	var $window = $(window),
			$ulside = $('#dg-sidebar');
			 $(window).on('load', function () {
				if ($window.width() < 979) {
				   $ulside.addClass('hide-left-bar');
			   }else{
				$ulside.removeClass('hide-left-bar')};
		});