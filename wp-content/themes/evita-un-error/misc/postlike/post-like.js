jQuery(document).ready(function() {

	if (jQuery(".count").hasClass("hasVoted")) {
		jQuery(".authory").show();
	} 

	jQuery(".img-thumbup").click(function(){
	
		heart = jQuery(this);
		poll = jQuery(".poll");
		post_id = heart.data("post_id");
		author = jQuery(".authory");
		texto = jQuery(".poll-text");
		loged = jQuery(".user-loged");

		/*if (loged.length > 0) {

		jQuery.ajax({
			type: "post",
			url: ajax_var.url,
			data: "action=post-like&nonce="+ajax_var.nonce+"&post_like=&post_id="+post_id,
			success: function(count){
				if(count != "already")
				{
					heart.addClass("voted");
					// heart.siblings(".count").text(count);
					texto.text("¡Gracias!");
					heart.addClass("voted");
					poll.delay(800).slideUp(400, function() {
						author.slideDown();
					});
				}
			}
		});
		} else {
			texto.text("Debes registrarte para poder votar");
		}*/
		jQuery.ajax({
			type: "post",
			url: ajax_var.url,
			data: "action=post-like&nonce="+ajax_var.nonce+"&post_like=&post_id="+post_id,
			success: function(count){
				if(count != "already")
				{
					heart.addClass("voted");
					// heart.siblings(".count").text(count);
					texto.text("¡Gracias!");
					heart.addClass("voted");
					poll.delay(800).slideUp(400, function() {
						author.slideDown();
					});
				}
			}
		});
		
		return false;
	})
jQuery(".img-thumbdown").click(function(){

	heart = jQuery(this);
	poll = jQuery(".poll");
	post_id = heart.data("post_id");
	author = jQuery(".authory");
	texto = jQuery(".poll-text");
	loged = jQuery(".user-loged");

	/*if (loged.length > 0) {

	jQuery.ajax({
		type: "post",
		url: ajax_var.url,
		data: "action=post-like&nonce="+ajax_var.nonce+"&post_like=&post_id="+post_id,
		success: function(count){
			if(count != "already")
			{
				heart.addClass("voted");
				// heart.siblings(".count").text(count);
				texto.text("¡Gracias!");
				heart.addClass("voted");
				poll.delay(800).slideUp(400, function() {
					author.slideDown();
				});
			}
		}
	});
	} else {
		texto.text("Debes registrarte para poder votar");
	}*/

			
	heart.addClass("voted");
	// heart.siblings(".count").text(count);
	texto.text("¡Gracias!");
	heart.addClass("voted");
	poll.delay(800).slideUp(400, function() {
		author.slideDown();
	});
			
		

	
	return false;
})
})