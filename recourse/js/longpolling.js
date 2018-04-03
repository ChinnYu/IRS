function waitfortestbegin(){
		//var Today=new Date();
		//var queryString = {'timestamp' : Today.getFullYear()+ (Today.getMonth()+1) + Today.getDate()};

		$.ajax(
			{
				type: 'GET',
				url: 'longpolling/polling',
				dataType : 'text',
				success: function(data){
					//alert('su');
					window.location = '../index.php/QuizPage';
				},
				error : function(){
					alert("fa");
				}
			}
		);
	
	}