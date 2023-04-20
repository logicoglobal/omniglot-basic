(function( $ ) {
	'use strict';

	$( document ).ready(function() {
        $('.cn_select_li').on('click', function(event) {
        	jQuery('.cn_select_li').addClass('cn_active');
        	var cn_tag=$(this).attr('cn_tag');
        	var cn_value =$(this).attr('cn_value');
        	if (cn_tag=='selected') {

        	}else{
        		$('.cn_select_li').attr('cn_tag', '');
        		$(this).attr('cn_tag', 'selected');
        		$('.cn_select_li').removeClass('cn_active');
        		$(this).addClass('cn_active');
        		var cn_url=$('.cn_url').val();
        		window.location.href=cn_url+'?lang='+cn_value;
        	}
        });

        $('.cn_select_li_page').on('click', function(event) {
        	jQuery('.cn_select_li_page').addClass('cn_active');
        	var cn_tag=$(this).attr('cn_tag');
        	var cn_value =$(this).attr('cn_value');
        	if (cn_tag=='selected') {

        	}else{
        		$('.cn_select_li_page').attr('cn_tag', '');
        		$(this).attr('cn_tag', 'selected');
        		$('.cn_select_li_page').removeClass('cn_active');
        		$(this).addClass('cn_active');
        		window.location.href=cn_value;
        	}
        });


         $('.cnopen').on('click', function(event) {
        	jQuery('.cn_select_li_page').addClass('cn_active');
        });

    });









})( jQuery );


// function cn_select_ul_click(){
// 	jQuery('.cn_select_li').addClass('cn_active');
// }


function omniglot_language_for_page(url){
	var cn=jQuery('#omniglot_language_switcher').val();
	var cn_page_id=jQuery('#cn_page_id').val();
	// var clocation=window.location;
	//window.location.href=url+'?lang='+cn;

	jQuery.post(cn_plugin_vars.ajaxurl,{
		'action': 'cn_public_omniglot_ajax',
		'param': 'find_post_page',
		'cn_page_id':cn_page_id
	}, function(response){
		
		console.log(response);
		//var newResponse = JSON.parse(response);
		// if (newResponse.success=='success') {
		// 	window.location.href=newResponse.location_url;
		// }
		
	});



}

function omniglot_language_switcher(url){
	var cn=jQuery('#omniglot_language_switcher').val();
	// var clocation=window.location;
	alert(cn);
	window.location.href=url+'?lang='+cn;
}

function omniglot_language_page(){
	var cn=jQuery('#omniglot_language_switcher').val();
	// var clocation=window.location;
	if (cn!='') {
		window.location.href=cn;	
	}
	
}

jQuery(document).ready(function($){

    if ( cn_plugin_vars.page_open_tab !== '_blank') {
        cn_plugin_vars.page_open_tab = '_self';
    }
        //test for getting url value from attr
    // var img1 = $('.test').attr("data-thumbnail");
    // console.log(img1);

    //test for iterating over child elements
    // var current_img = $('#current-data').attr("data-current-url");
    // var current_url = $('#current-data').attr("data-url");
    // alert(current_img);
    var langArray = [];
    $("select.languagepicker option").each(function(){
      var img = ''; 
      var img = $(this).attr("data-thumbnail");
      var url = $(this).attr("data-url");
      var text = this.innerText;
      var value = $(this).val();
      if ( text != '' ) {
          item =
            '<li data-url="'+url+'"><img src="' +
            img +
            '" alt="" value="' +
            value +
            '"/><span>' +
            text +
            '</span></li>';
      }else{
        item =
            '<li data-url="'+url+'"><img src="' +
            img +
            '" alt="" value="' +
            value+'"/></li>';
      }

      langArray.push(item);
      
    });
    
    $(".lang-select-list").html(langArray);

    //Set the button value to the first el of the array
    
   // $(".btn-lang-select").html(langArray[0]);
   // $(".btn-lang-select").attr("value", "en");

    //change button stuff on click
    $(".lang-select-list li").click(function () {
      var img = $(this).find("img").attr("src");
      var value = $(this).find("img").attr("value");
      var text = this.innerText;
      var item =
        '<li><img src="' + img + '" alt="" /><span>' + text + "</span></li>";
      $(".btn-lang-select").html(item);
      $(".btn-lang-select").attr("value", value);
      $(".lang-select").toggle();
      var url = $(this).attr("data-url");
      window.open(url, cn_plugin_vars.page_open_tab);
    });

    $(".btn-lang-select").click(function () {
      $(".lang-select").toggle();
    });

    //check local storage for the lang
    var sessionLang = localStorage.getItem("lang");
    if (sessionLang) {
      //find an item with value of sessionLang

      var langIndex = langArray.indexOf(sessionLang);
      $(".btn-lang-select").html(langArray[langIndex]);
      $(".btn-lang-select").attr("value", sessionLang);
    } else {
      var langIndex = langArray.indexOf("ch");
      console.log(langIndex);

      
      //$('.btn-lang-select').attr('value', 'en');
    }

});