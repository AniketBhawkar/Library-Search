jQuery(document).ready(function(event){
	var str = "", _this;

	// Function to identify user has finished typing
	
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();
	
	// Ajax Call
	
	jQuery(".library-form-outer .field").keyup(function(){
		_this = jQuery(this);
		delay(function(){
			if(_this.val().length >= 3){ // Minimum 3 characters
				str = 'val='+_this.val();
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
			}else{
				
			}
		}, 1000 ); // Delay of 1 second to identify user has done typing
	});
});
