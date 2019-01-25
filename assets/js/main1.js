( function( $ ) {
	

    //});   
    $('#clear').click(function(e){  e.preventDefault();     
    	$('#addpro')[0].reset(); $("#download_modal").modal("hide");
    });

    $('#addpro1').submit(function(e){  e.preventDefault();
		   var newval=$('#formstep1').val();
		   var theDate=$('#theDate').val();
		   var mainemail=$('#mainemail').val();
		   var requestername=$('#requestername').val();
		   var mainphone=$('#mainphone').val();
          	var BookingForm = $('#addpro1').serialize();
        			$.ajax({
			            type: "post",
			            url: "/wp-admin/admin-ajax.php",   // variable defined above with an array for url and nonce 
			            data: BookingForm,  // Action variable defines the name of the php function which proceess ajax request based on the variable we have passed   
			            success: function(data){
			            	var nstep=parseFloat(newval)+1;
			            	
    						$('#addpro1').attr('name', 'updatepro1');
    						$('#addpro1').attr('id', 'updatepro1');
			                $('#panel2').css('display','block');
			                $( "#collapseTwo" ).collapse( "show" );
			                $( "#collapseOne" ).collapse( "hide" );
			            }
			        });
          
	 });
    $('#addpro2').submit(function(e){  e.preventDefault();
		   var newval=$('#formstep2').val();
		   var theDate=$('#theDate').val();
		   var mainemail=$('#mainemail').val();
		   var requestername=$('#requestername').val();
		   var mainphone=$('#mainphone').val();
          	var BookingForm = $('#addpro2').serialize();
        			$.ajax({
			            type: "post",
			            url: "/wp-admin/admin-ajax.php",   // variable defined above with an array for url and nonce 
			            data: BookingForm,  // Action variable defines the name of the php function which proceess ajax request based on the variable we have passed   
			            success: function(data){
			            	var nstep=parseFloat(newval)+1;
			                $('#panel3').css('display','block');
			                $( "#collapseThree" ).collapse( "show" );
			                $( "#collapseTwo" ).collapse( "hide" );
			            }
			        });
          
	 });
	$('#requser .option a').click(function(e){  e.preventDefault(); var id=$(this).attr("data-value");
		$.ajax({
			            type: "post",
			            url: "/wp-admin/admin-ajax.php",   // variable defined above with an array for url and nonce 
			            data: {action : 'userinfo_data',
                             user_id : id},  // Action variable defines the name of the php function which proceess ajax request based on the variable we have passed   
			            success: function(data){ 
                           console.log(data);
				        	$("#mainemail").val(data[0]);
				      	 	$("#mainphone").val(data[1]);
			            }
			        });
	   });  
   
	$('#approverquser .option a').click(function(e){  
		e.preventDefault(); var id=$(this).attr("data-value");
		$.ajax({
			            type: "post",
			            url: "/wp-admin/admin-ajax.php",   // variable defined above with an array for url and nonce 
			            data: {action : 'appuserinfo_data',
                             user_id : id},  // Action variable defines the name of the php function which proceess ajax request based on the variable we have passed   
			            success: function(data){ 
                           console.log(data);
				        	$("#approveremail").val(data[0]);
				      	 	$("#approverphone").val(data[1]);
			            }
			        });
	   }); 
    $('#approverquser2 .option a').click(function(e){  
		e.preventDefault(); var id=$(this).attr("data-value");
		$.ajax({
			            type: "post",
			            url: "/wp-admin/admin-ajax.php",   // variable defined above with an array for url and nonce 
			            data: {action : 'appuserinfo_data',
                             user_id : id},  // Action variable defines the name of the php function which proceess ajax request based on the variable we have passed   
			            success: function(data){ 
                           console.log(data);
				        	$("#approveremail2").val(data[0]);
				      	 	$("#approverphone2").val(data[1]);
			            }
			        });
	   }); 
// Custom Dropdowncollapse
} )( jQuery );


    function removefield(ids){ $('#val_'+ids).remove();
		
    }
    function userinfo(id){ alert(id); 
        			$.ajax({
			            type: "post",
			            url: "/wp-admin/admin-ajax.php",   // variable defined above with an array for url and nonce 
			            data: {action : 'userinfo_data',
                             user_id : id},  // Action variable defines the name of the php function which proceess ajax request based on the variable we have passed   
			            success: function(data){
			                 $("#requesterphone").value(data.phone);
			                 $("#requesteremail").value(data.email);
			            }
			        });
          
	}
function addfield(id){
		//$('#addpro').submit(ajaxSubmit);
	
	var ids=id+1;   
    //$('.more-fields .fa-plus').on('click',function(){  

         // $('.additional-fields row:last').clone(true).insertAfter('.additional-fields row:last');
    //e.preventDefault();  
    	$( ".additional-fields1" ).append( '<div class="additional-fields" id="val_'+ids+'"><div class="more-fields1"><i  id="'+ids+'" class="fa fa-minus" onclick="return removefield('+ids+');"></i></div><div class="row" id="'+ids+'"><div class="more-fields"><i class="fa fa-plus" onclick="return addfield('+ids+');"></i></div><div class="col-sm-2"><input type="text" placeholder="Cost center" name="costcenter[]"></div><div class="col-sm-2"><input type="text" placeholder="Network" name="network[]"></div><div class="col-sm-2"><input type="text" placeholder="Activity code" name="activitycode[]"></div><div class="col-sm-2"><input type="text" placeholder="GL code" name="gl[]"></div><div class="col-sm-2"><input type="text" placeholder="% of total" name="totalpercentage[]"></div></div></div>' );
    }
	
$('[type="submit"]').on('click', function () {
    // this adds 'required' class to all the required inputs under the same <form> as the submit button
    $(this)
        .closest('form')
        .find('[required]')
        .addClass('required');
});