<script type="text/javascript"> 
jQuery(document).ready(function(){     
    $("#admin_submit").click(function(){
         //alert('ok');
         var entered_email=$("#email").val(); 
         var entered_password=$("#password").val();

         var APP_URL = {!! json_encode(url('/')) !!}              

          if(entered_email!='' && entered_password!=''){                  
               $.ajax({
                  type:'POST',
                     url:APP_URL +'/admin/login',
                     data: {"_token": "{{ csrf_token() }}","email":entered_email,"password":entered_password},
                     success:function(data){
                         //alert(data);
                         if(data==0){
                         alert('Invalid password!');
                         }else if(data==1){
                          window.location.href = APP_URL+ '/admin/login-success';
                         }else{

                         }                        
                  }
               });
          }else{
              alert('Please enter password');
          }
    });
});


</script>