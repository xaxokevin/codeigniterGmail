$(document).ready(main);

function main () {
    
    cargarGmail();

}


function cargarGmail(){

//accion que se ejecuta al pulsar el boton de syn gmail
$(".sync").on("click",function () {

//llamamos al ajax
    $.ajax({
        type: "POST",
        url: "/Cgmail",
        success:function(data){

            console.log("ok cargar gmail")
            //refresh();
          
        },
  
      });

});

//accion que se ejecuta cuando enviamos el token
$(".token").on("click",function () {

    //creamos los datos a pasar

    var data = {

        "token" :  $("#token").val(),
       
    }
        
        if(data.token == ""){

            alert("Token vacio");
        }else{

             //llamamos al ajax
        $.ajax({
            data:  data,
            type: "POST",
            url: "ajax/Token",
            success:function(){
    
                console.log("ok token")
              
            },
      
          });
        }
    
   
    
    });
}

    





/**
 * Recarga la pagina
 */
function refresh(){

   
    location.reload(true);

}


