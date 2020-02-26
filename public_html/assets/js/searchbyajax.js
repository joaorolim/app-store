$(document).ready(function(){

function buscaCpf( cpf ) {

  var uri = window.location.pathname;
  var pos = uri.lastIndexOf("/adm/atendimento");

  if ( pos >= 0 ) {
    var url = window.location.origin + "/adm/atendimento/api/municipe/" + cpf;
    console.log(url);

    $.ajax({
      type: "GET",
      url: url,
      // data: myusername,
      cache: false,
      success: function(data){
        var json = $.parseJSON(data); // create an object with the key of the array
        var result = "Munícipe: Não encontrado!";

        if ( json[0].mun_nome ) {
          result = json[0].mun_nome;
        }

        // console.log(result); // where html is the key of array that you want, $response['html'] = "<a>something..</a>";
        resultadoCpf(result);

       // for (var i = 0; i < json.length; i++) {
       //   cidades.push(json[i].cidade+', '+json[i].uf+', '+json[i].pais);
       // }
       // console.log(cidades);
      },
      error: function(data){
         var json = $.parseJSON(data);
         alert(json.error);
      }
    });
  }

}

function resultadoCpf( result ) {
  if ( result == "Munícipe: Não encontrado!" ) {
    $("#txt-cpf").css("color","red");
    $("#txt-cpf").html(result);
  } else {
    $("#txt-cpf").css("color","green");
    $("#txt-cpf").html("Munícipe: " + result);
  }
}


// Initiate the buscaCpf( cpf ) Effect on "#cpf_atend"
$("#cpf_atend").keyup(function(){
    var cpf = document.getElementById('cpf_atend').value;
    // console.log(cpf.length);

    if ( cpf.length == 14 ) {
      cpf = cpf.replace(/\./g,"");
      cpf = cpf.replace(/\-/g,"");
      console.log(cpf);

      if ( cpf.length == 11 ) {
        buscaCpf(cpf);
      }

    } else {
      $("#txt-cpf").css("color","green");
      $("#txt-cpf").html("");
    }

});


});
