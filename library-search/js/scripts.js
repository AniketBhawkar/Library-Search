jQuery(document).ready(function(event){
	var str = "", author, title, publisher, rating, minprice, maxprice;	

	// Ajax Call	
	jQuery(".library-form").submit(function(e){
		e.preventDefault();
		author = jQuery('select[name=book-author]').val();
		title = jQuery('input[name=book-title]').val();
		publisher = jQuery('select[name=book-publisher]').val();
		rating = jQuery('select[name=book-rating]').val();
		minprice = jQuery('input[name=book-min-value]').val();
		if(minprice == ""){
			minprice = "0";
		}
		maxprice = jQuery('input[name=book-max-value]').val();
		if(maxprice == ""){
			maxprice = "1000";
		}
		
		str = 'author='+author+'&publisher='+publisher+'&title='+title+'&rating='+rating+'&minprice='+minprice+'&maxprice='+maxprice;
		jQuery.ajax({
			dataType : 'text',
			type: 'POST',
			url: myScript.pluginsUrl + 'ajax/filter-books.php',
			data: str,
			global: false,
			async: true,
			success: function(msg){
				jQuery(".library-result-outer").empty().append(msg); // Append the output
			},
			error: function(msg) {

			}
		});
	});
});
