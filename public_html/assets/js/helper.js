$(document).ready(function(){

  // alert("entrei aqui");

  function validarSenha(){
    senha1 = $('#senha').val();
    senha2 = $('#senha2').val();

    if (senha1 == senha2) {
      $("#txt-senha2").css("color","green");
      $("#txt-senha2").html("Ok! Senhas iguais");
      $('#btn-submit').attr('disabled',false);
    } else {
      $("#txt-senha2").css("color","red");
      $("#txt-senha2").html("Opss... Senhas diferentes");
      $('#btn-submit').attr('disabled',true);
    }
  }

  $("#senha2").keyup(validarSenha);


  // Listen for click on toggle checkbox (Selecionar tudo)
  $('[id|=selectAll]').click(function(event) {
      var id = $(this).attr('id').split('selectAll-')[1];
      // var ckbClass = '.ckb-'+id;
      // console.log('class: '+ckbClass);
      if(this.checked) {
          // Iterate each checkbox
          $('.ckb-'+id).each(function() {
              this.checked = true;
          });
      } else {
          $('.ckb-'+id).each(function() {
              this.checked = false;
          });
      }
  });


  $('.navegacao a.naveg-link').on('click', function (event) {

      // prevenir comportamento normal do link
      event.preventDefault();

      var hrefValue = $(this).attr("href");
      // alert(hrefValue);

      window.location.href = hrefValue;
  });

/*************** start - Tela Bairros por Cidade ***************/
  /*** start - Preencher selected do campo cidade ***/
  function preencheCidades(){
    console.log('init');

    var cidades = {0 : "Escolha a Cidade"};

    var uri = window.location.pathname;
    var pos = uri.lastIndexOf("/adm/bairrocid");

    if ( pos >= 0 ) {
      var url = window.location.origin + "/adm/bairrocid/api/cidades";
      // console.log(url);
      // console.log( 'pais: ' + $('#pais_bairro').val() );
      // console.log( 'uf: ' + $('#uf_bairro').val() );

      uf = $('#uf_bairro').val();
      pais = $('#pais_bairro').val();

      url = url+'/'+uf+'/'+pais;

      $.ajax({
        type: "GET",
        url: url,
        // data: {},
        cache: false,
        success: function(data){
          var json = $.parseJSON(data); // create an object with the key of the array
          // console.log(json); // where html is the key of array that you want, $response['html'] = "<a>something..</a>";

          for (var i = 0; i < json.length; i++) {
           cidades[json[i].cid_id] = json[i].cidade;
          }

          var newOptions = {};
          newOptions = cidades;
          console.log('newOptions');
          console.log(newOptions);

          var selectedOption = '0';

          var select = $('#cidade_bairro');

          if(select.prop) {
            var options = select.prop('options');
          }
          else {
            var options = select.attr('options');
          }

          $('option', select).remove();

          if ( options ) {
            $.each(newOptions, function(val, text) {
              options[options.length] = new Option(text, val);
            });
          }

          select.val(selectedOption);

        },
        error: function(data){
           var json = $.parseJSON(data);
           alert(json.error);
        }
      });
    }

  }

  $('#uf_bairro').on('change', function() {
    preencheCidades();
  })

  $('#pais_bairro').on('change', function() {
    preencheCidades();
  })
  /*** finish - Preencher selected do campo cidade ***/

  /*** start - Mostrar bairros por cidade   ***/
  function mostrarBairros( cidade, uf, pais ){
    var uri = window.location.pathname;
    var pos = uri.lastIndexOf("/adm/bairrocid");

    if ( pos >= 0 ) {
      var url = window.location.origin + "/adm/bairrocid/pagina/1/" + cidade + "/"+ uf + "/" + pais;
      console.log(url);
      window.location.href = url;
    }
  }

  $('#cidade_bairro').on('change', function() {
    var pais = $('#pais_bairro').val();
    console.log(pais);
    var uf = $('#uf_bairro').val();
    console.log(uf);
    var cidade = $('#cidade_bairro').val();
    console.log(cidade);
    mostrarBairros( cidade, uf, pais );
  })
  /*** finish - Mostrar bairros por cidade  ***/

/*************** finish - Tela Bairros por Cidade ***************/

/*************** start - Tela Munícipe ***************/
  /* Máscaras ER para Telefones, CPF, CEP e Salário */
  function mascara(o,f){
      // console.log(o);
      v_obj=o
      v_fun=f
      setTimeout(execmascara,1)
  }

  function execmascara(){
      v_obj.value=v_fun(v_obj.value)
  }

  function mtel(v){
      v=v.replace(/\D/g,"");                  //Remove tudo o que não é dígito
      v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
      v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
      return v;
  }

  function mcpf(v){
      v=v.replace(/\D/g,"");                //Remove tudo o que não é dígito
      v=v.replace(/^(\d{3})(\d)/g,"$1.$2"); //Coloca ponto entre o terceiro e o quarto dígito
      v=v.replace(/(\d)(\d{5})$/,"$1.$2");  //Coloca ponto entre o sexto e o sétimo dígito
      v=v.replace(/(\d)(\d{2})$/,"$1-$2");  //Coloca hífen entre o nono e o décimo dígito
      return v;
      // return valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
  }

  function mcnpj(v){
      return v.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
  }

  function mcep(v){
      v=v.replace(/\D/g,"");                //Remove tudo o que não é dígito
      v=v.replace(/^(\d{2})(\d)/g,"$1.$2"); //Coloca ponto entre o segundo e o terceiro dígito
      v=v.replace(/(\d)(\d{3})$/,"$1-$2");  //Coloca hífen entre o quinto e o sexto dígito
      return v;
  }

  function msal(v){
      v=v.replace(/\D/g,"");                //Remove tudo o que não é dígito
      v=v.replace(/(\d)(\d{5})$/,"$1.$2");  //Coloca hífen entre o quinto e o sexto dígito
      v=v.replace(/(\d)(\d{2})$/,"$1,$2");  //Coloca hífen entre o quinto e o sexto dígito
      return v;
  }

  function id( el ){
    return document.getElementById( el );
  }

  function allId( el ){
    return document.querySelectorAll('[id^='+el+']');
  }

  if ( id('tel') ) {
    id('tel').onkeyup = function(){
      mascara( this, mtel );
    }
  }

  if ( id('conTel') ) {
    id('conTel').onkeyup = function(){
      mascara( this, mtel );
    }
  }

  if ( id('conCel') ) {
    id('conCel').onkeyup = function(){
      mascara( this, mtel );
    }
  }

  if ( id('conFax') ) {
    id('conFax').onkeyup = function(){
      mascara( this, mtel );
    }
  }

  if ( id('cel') ) {
    id('cel').onkeyup = function(){
      mascara( this, mtel );
    }
  }

  if ( id('cpf') ) {
    id('cpf').onkeyup = function(){
      mascara( this, mcpf );
    }
  }

  if ( id('cpf_pesq') ) {
    id('cpf_pesq').onkeyup = function(){
      mascara( this, mcpf );
    }
  }

  if ( id('cpf_atend') ) {
    id('cpf_atend').onkeyup = function(){
      mascara( this, mcpf );
    }
  }

  if ( id('cnpj') ) {
    id('cnpj').onkeyup = function(){
      mascara( this, mcnpj );
    }
  }

  if ( id('cnpj_pesq') ) {
    id('cnpj_pesq').onkeyup = function(){
      mascara( this, mcnpj );
    }
  }

  if ( id('cep') ) {
    id('cep').onkeyup = function(){
      mascara( this, mcep );
    }
  }

  if ( id('munSal') ) {
    id('munSal').onkeyup = function(){
      mascara( this, msal );
    }
  }

  if ( id('salario') ) {
    id('salario').onkeyup = function(){
      mascara( this, msal );
    }
  }

  $('[id^=munSal-]').on('keyup', function(e){
    mascara( this, msal );
  });

  $('[id^=conTel-]').on('keyup', function(e){
    mascara( this, mtel );
  });

  $('[id^=conCel-]').on('keyup', function(e){
    mascara( this, mtel );
  });

  $('[id^=conFax-]').on('keyup', function(e){
    mascara( this, mtel );
  });


  /*** start - Preencher selected do campo bairro_mun ***/
  function preencheBairros(){

    var uri = window.location.pathname;
    var pos = -1;
    if ( (uri.lastIndexOf("/adm/municipe") >= 0) ) {
      pos = 1;
    }

    if ( pos >= 0 ) {
      var strCidade = $("#myInput2").val();
      var strRes = strCidade.split(",");

      var cidade = strRes[0].trim();
      var uf = strRes[1].trim();
      var pais = strRes[2].trim();


      var url = window.location.origin + "/adm/municipe/api/bairros/" + cidade + "/" + uf + "/" + pais;
      // console.log(url);

      $.ajax({
        type: "GET",
        url: url,
        // data: myusername,
        cache: false,
        success: function(data){
          // console.log(data);
          var json = $.parseJSON(data); // create an object with the key of the array
          // console.log(json); // where html is the key of array that you want, $response['html'] = "<a>something..</a>";

          json.unshift({bac_id: '0', bai_desc:'Escolha o Bairro'});
          var temp = json;

          var menu = $("#bairro_mun");

          menu.empty();
          $.each(temp, function(){
              $("<option />")
              .attr("value", this.bac_id)
              .html(this.bai_desc)
              .appendTo(menu);
          });

        },
        error: function(data){
           var json = $.parseJSON(data);
           alert(json.error);
        }
      });
    }

  }

  $('#myInput2').on('change', function() {
    setTimeout(preencheBairros, 200)
    // preencheBairros();
  })

  /*** finish - Preencher selected do campo bairro_mun ***/

  /*** start - Preencher selected do campo formação ***/
  /* When the user clicks on the button,
  toggle between hiding and showing the dropdown content */
  function myFunction(id) {
      // console.log("myFunction id:"+id);
      // document.getElementById("fmcDropdown-"+id).classList.toggle("show");
      var element = document.getElementById("fmcDropdown-"+id);
      var classe = element.className;
      // console.log("antes: "+classe);

      if ( classe === "dropdown-content show" ) {
        element.classList.remove("show");
      } else {
        element.classList.add("show")
      }

      // var classe = element.className;
      // console.log("depois: "+classe);
  }

  function filterFunction(el) {
      var input, filter, ul, li, a, i;
      var dropId = el.id.split("-");
      dropId = dropId[2];

      //input = document.getElementById("myInput");
      input = document.getElementById(el.id);
      filter = input.value.toUpperCase();
      div = document.getElementById("fmcDropdown-" + dropId);
      a = div.getElementsByTagName("a");
      for (i = 0; i < a.length; i++) {
          if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
              a[i].style.display = "";
          } else {
              a[i].style.display = "none";
          }
      }
  }

  function refreshFormacaoHiddenInput() {
    // Remove todos os inputs tipo hidden da classe "hiddenInput"
    $('input.hiddenInput').each(function (index, value) {
      $(this).remove();
    });

    inpsFormacao = $('input.formacao');
    // console.log(inpsFormacao.length);

    // Acrescenta os hidden inputs novamente
    inpsFormacao.each(function (index, value) {
      // console.log('index:' + index + ' - Id: ' + $(this).attr('id') + ' - Value: ' + $(this).val() + ' - DataValue: ' + $(this).attr('data-value'));
      var hiddenInput = "<input name=\"id_fmc[]\" type=\"hidden\" class=\"hiddenInput\" value=\"" + $(this).attr('data-value') + "\">";
      $("#formacao-molde").append(hiddenInput);
    });
  }

  function pickUp(el) {
    //console.log(el.parentNode.id);
    var dropId = el.parentNode.id.split("-");
    dropId = dropId[1];

    var formacao = $('#fmc-'+dropId);
    formacao.val(el.text);
    formacao.attr({"data-value" : el.id});

    refreshFormacaoHiddenInput();

    myFunction(dropId);
  }

  $('[id^=fmc-]').on('click', function(e){
    e.preventDefault();
    // console.log("teste");

    var id = this.id.split("-");
    id = id[1];
    // console.log("id:"+id);

    myFunction(id);
  });

  $('[id^=pesq-fmc-]').on('keyup', function(e){
    filterFunction(this);
  });

  //$('.dropLink-').on('click', function(e){
  $('[class^=dropLink-]').on('click', function(e){
    e.preventDefault();
    pickUp(this);
  });

  $('.btn-plus').on('click', function(e){
    e.preventDefault();

    var lastId = $(".dropdown-content:last").attr("id").split("-")[1];
    var curId = Number(lastId) + 1;
    // console.log(curId);

    var contentFormacao = $("#fmcDropdown-1").html();
    // console.log(contentFormacao);
    var erase = '<input type="text" placeholder="Pesquisar..." class="pesquisa dropInput" id="pesq-fmc-1">';
    contentFormacao = contentFormacao.replace(erase,"");
    contentFormacao = contentFormacao.replace(/dropLink-1/g,"dropLink-"+curId);
    // console.log(contentFormacao);

    var formacao;
    formacao = "<div class=\"dropdown form-group col-md-5\" id=\"formacao-molde-"+curId+"\"> ";
    formacao +=     "<label for=\"fmc-"+curId+"\">&nbsp</label>";
    formacao +=     "<input type=\"text\" class=\"formacao\" data-value=\"\" id=\"fmc-"+curId+"\" name=\"text_fmc[]\" placeholder=\"Escolha a Formação\"/>";
    formacao +=     "<div id=\"fmcDropdown-"+curId+"\" class=\"dropdown-content\">";
    formacao +=      "<input type=\"text\" placeholder=\"Pesquisar...\" class=\"pesquisa dropInput\" id=\"pesq-fmc-"+curId+"\"/>";

    formacao +=     "</div>";
    formacao += "</div>";

    var contentTipo = $("#tipo-molde").html();
    contentTipo = contentTipo.replace(/tpoForm/g,"tpoForm-"+curId);
    // console.log(contentTipo);

    var tipo;
    tipo =  "<div class=\"form-group col-md-4\" id=\"tipo-molde-"+curId+"\">";
    tipo += "</div>";

    var contentStatus = $("#status-molde").html();
    contentStatus = contentStatus.replace(/staForm/g,"staForm-"+curId);
    // console.log(contentStatus);

    var status;
    status =  "<div class=\"form-group col-md-3\" id=\"status-molde-"+curId+"\">";
    status += "</div>";

    formacaoTipoStatus = "<div class=\"row row-fts\">" + formacao + tipo + status + "</div>";

    $("#menu1").append(formacaoTipoStatus);
    $("#fmcDropdown-"+curId).append(contentFormacao);
    $("#tipo-molde-"+curId).append(contentTipo);
    $("#status-molde-"+curId).append(contentStatus);

    $('#fmc-'+curId).on('click', function(e){
      e.preventDefault();
      // console.log("teste");

      var id = this.id.split("-");
      id = id[1];
      // console.log("id:"+id);

      myFunction(id);
    });

    $('#pesq-fmc-'+curId).on('keyup', function(e){
      filterFunction(this);
    });

    $('.dropLink-'+curId).on('click', function(e){
      e.preventDefault();
      pickUp(this);
    });

  });

  $('.btn-minus').on('click', function(e){
    e.preventDefault();
    $(".row-fts:last").remove();
    refreshFormacaoHiddenInput();
  });

  /*** finish - Preencher selected do campo formação ***/


/*** start - Preencher selected do campo ocupaçao ***/
  /* When the user clicks on the button,
  toggle between hiding and showing the dropdown content */
  function myFunction2(id) {
      // console.log("myFunction id:"+id);
      // document.getElementById("fmcDropdown-"+id).classList.toggle("show");
      var element = document.getElementById("ocpDropdown-"+id);
      var classe = element.className;
      // console.log("antes: "+classe);

      if ( classe === "dropdown2-content show" ) {
        element.classList.remove("show");
      } else {
        element.classList.add("show")
      }

      // var classe = element.className;
      // console.log("depois: "+classe);
  }

  function filterFunction2(el) {
      var input, filter, ul, li, a, i;
      var dropId = el.id.split("-");
      dropId = dropId[2];

      //input = document.getElementById("myInput");
      input = document.getElementById(el.id);
      filter = input.value.toUpperCase();
      div = document.getElementById("ocpDropdown-" + dropId);
      a = div.getElementsByTagName("a");
      for (i = 0; i < a.length; i++) {
          if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
              a[i].style.display = "";
          } else {
              a[i].style.display = "none";
          }
      }
  }

  function refreshOcupacaoHiddenInput() {
    // Remove todos os inputs tipo hidden da classe "hiddenInput"
    $('input.hiddenInput2').each(function (index, value) {
      $(this).remove();
    });

    inpsOcupacao = $('input.ocupacao');
    // console.log(inpsOcupacao.length);

    // Acrescenta os hidden inputs novamente
    inpsOcupacao.each(function (index, value) {
      // console.log('index:' + index + ' - Id: ' + $(this).attr('id') + ' - Value: ' + $(this).val() + ' - DataValue: ' + $(this).attr('data-value'));
      if ( $(this).attr('data-value') ) {
        var hiddenInput = "<input name=\"id_ocp[]\" type=\"hidden\" class=\"hiddenInput2\" value=\"" + $(this).attr('data-value') + "\">";
      }

      $("#ocupacao-molde").append(hiddenInput);
    });
  }

  function pickUp2(el) {
    //console.log(el.parentNode.id);
    var dropId = el.parentNode.id.split("-");
    dropId = dropId[1];

    var ocupacao = $('#ocp-'+dropId);
    ocupacao.val(el.text);
    ocupacao.attr({"data-value" : el.id});

    refreshOcupacaoHiddenInput();

    myFunction2(dropId);
  }

  $('[id^=ocp-]').on('click', function(e){
    e.preventDefault();
    // console.log("teste");

    var id = this.id.split("-");
    id = id[1];
    // console.log("id:"+id);

    myFunction2(id);
  });

  $('[id^=pesq-ocp-]').on('keyup', function(e){
    filterFunction2(this);
  });

  // $('.dropLink2-1').on('click', function(e){
  $('[class^=dropLink2-]').on('click', function(e){
    e.preventDefault();
    pickUp2(this);
  });

  $('.btn-plus-exp').on('click', function(e){
    e.preventDefault();
    // alert("Adicionar Experiência");

    var lastId = $(".dropdown2-content:last").attr("id").split("-")[1];
    var curId = Number(lastId) + 1;
    // console.log(curId);

    var dataValue = $("#ocp-1").attr('data-value');

    var contentExp = $("#exp-molde").html();
    contentExp = contentExp.replace(/id=\"ocupacao-molde\"/g,"id=\"ocupacao-molde-"+curId+"\"");
    contentExp = contentExp.replace(/ocp-1/g,"ocp-"+curId);
    contentExp = contentExp.replace(/ocpDropdown-1/g,"ocpDropdown-"+curId);
    contentExp = contentExp.replace(/dropLink2-1/g,"dropLink2-"+curId);
    contentExp = contentExp.replace(/id=\"munSal\"/g,"id=\"munSal-"+curId+"\"");


    if ( dataValue ) {
      contentExp = contentExp.replace(/data-value=\"(.+?)\"/g,"data-value=\"\"");
    }


    // console.log(contentExp);

    var newExp = "<div class=\"well new-exp\" style=\"background-color:#ddd\">" + contentExp + "</div>";

    $("#menu3").append(newExp);

    $('#ocp-'+curId).on('click', function(e){
      e.preventDefault();
      // console.log("teste");

      var id = this.id.split("-");
      id = id[1];
      // console.log("id:"+id);

      myFunction2(id);
    });

    $('#pesq-ocp-'+curId).on('keyup', function(e){
      filterFunction2(this);
    });

    $('.dropLink2-'+curId).on('click', function(e){
      e.preventDefault();
      pickUp2(this);
    });

    $('#munSal-'+curId).on('keyup', function(e){
      mascara( this, msal );
    });

    refreshOcupacaoHiddenInput();

  });

  $('.btn-minus-exp').on('click', function(e){
    e.preventDefault();

    $(".new-exp:last").remove();
    refreshOcupacaoHiddenInput();
  });

  /*** finish - Preencher selected do campo ocupaçao ***/


  /*** start - Preencher form do campo idioma ***/
  $('.btn-plus-idi').on('click', function(e){
    e.preventDefault();
    // alert('Adicionar idioma');

    var lastId = $(".munIdi:last").attr("id").split("-")[1];
    var curId = Number(lastId) + 1;
    // console.log(curId);

    // var dataValue = $("#ocp-1").attr('data-value');

    var contentIdi = $("#idioma-molde").html();
    contentIdi = contentIdi.replace(/munIdi-1/g,"munIdi-"+curId);
    contentIdi = contentIdi.replace(/munNivIdi-1/g,"munNivIdi-"+curId);

    // console.log(contentIdi);

    var newIdi = "<div class=\"row new-idi\">" + contentIdi + "</div>";

    $("#menu4").append(newIdi);
  });

  $('.btn-minus-idi').on('click', function(e){
    e.preventDefault();
    // alert('Remover idioma');

    $(".new-idi:last").remove();
  });
  /*** finish - Preencher form do campo idioma ***/

  /*** start - Preencher form do campo contato ***/
  $('.btn-plus-cont').on('click', function(e){
    e.preventDefault();
    // alert('Adicionar contato');

    var lastId = $(".hiddenInput3:last").attr("id").split("-")[1];
    var curId = Number(lastId) + 1;
    // console.log(curId);

    var contentCon = $("#con-molde").html();
    // contentCon = contentCon.replace(/id=\"conId-1\"/g,"id=\"conId-"+curId+"\"");
    contentCon = contentCon.replace(/id=\"conCel\"/g,"id=\"conCel-"+curId+"\"");
    contentCon = contentCon.replace(/id=\"conTel\"/g,"id=\"conTel-"+curId+"\"");
    contentCon = contentCon.replace(/id=\"conFax\"/g,"id=\"conFax-"+curId+"\"");

    var newInput = "<input type=\"hidden\" class=\"hiddenInput3\" id=\"conId-"+curId+"\" name=\"conId[]\" value=\"\">";
    contentCon = contentCon.replace(/<input type=\"hidden\" class=\"hiddenInput3\"(.+?)>/g, newInput);


    // console.log(contentExp);

    var newCon = "<div class=\"well new-con\" style=\"background-color:#ddd\">" + contentCon + "</div>";

    $("#menu1Emp").append(newCon);

    $('#conCel-'+curId).on('keyup', function(e){
      mascara( this, mtel );
    });

    $('#conTel-'+curId).on('keyup', function(e){
      mascara( this, mtel );
    });

    $('#conFax-'+curId).on('keyup', function(e){
      mascara( this, mtel );
    });

  });

  $('.btn-minus-cont').on('click', function(e){
    e.preventDefault();
    // alert('Remover contato');

    $(".new-con:last").remove();
  });
  /*** finish - Preencher form do campo contato ***/




/*************** finish - Tela Munícipe ***************/

  if (typeof(Storage) !== "undefined") {
      // Code for localStorage/sessionStorage.
      // https://stackoverflow.com/questions/17642872/refresh-page-and-keep-scroll-position
      // on certain links save the scroll postion.
      $('.naveg-link').on("click", function (e) {
          e.preventDefault();

          var currentYOffset = window.pageYOffset;  // save current page postion.
          sessionStorage.jumpToScrollPostion = currentYOffset;

          var url = this.href;
          window.location = url;
      });

      // para o botão Filtrar do form
      $('.btn-filter').on("click", function (e) {
          var currentYOffset = window.pageYOffset;  // save current page postion.
          sessionStorage.jumpToScrollPostion = currentYOffset;
      });

      // check if we should jump to postion.
      if(sessionStorage.jumpToScrollPostion !== "undefined") {
          // alert($(window).width());
          if ( $(window).width() <= 768 ) {
              var jumpTo = Number(sessionStorage.jumpToScrollPostion)+250;
              window.scrollTo(0, jumpTo);
          }

          sessionStorage.jumpToScrollPostion = "undefined";  // and delete storage so we don't jump again.
      }
  } else {
      alert('Sorry! Your browser does not support web storage...');
  }

});
