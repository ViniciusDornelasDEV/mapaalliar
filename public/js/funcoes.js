function carregarSetor(area, tipo, todos = "N", funcionarios = false){
    unidade = funcionarios;
    if(funcionarios && $.isNumeric(funcionarios) == false){
        unidade = $('#unidade').val();
    }

    $('#area').attr('disabled', 'disabled');
    var data = {area: area, tipo: tipo, todos: todos, unidade: unidade};
    $.ajax({
        type: "POST",
        url: "/setor/carregar",
        data: data,
        success: function(html) {
            $('#setor').html(html);
            $('#area').removeAttr('disabled');       
        }
    });
}

function carregarFuncao(setor, tipo, funcionarios = false){
    unidade = funcionarios;
    if(funcionarios && $.isNumeric(funcionarios) == false){
        unidade = $('#unidade').val();
    }
    
    $('#setor').attr('disabled', 'disabled');
    var data = {setor: setor, tipo: tipo, unidade: unidade};
    $.ajax({
        type: "POST",
        url: "/funcao/carregar",
        data: data,
        success: function(html) {
            $('#funcao').html(html);
            $('#setor').removeAttr('disabled');       
        }
    });
}

function carregarArea(idUnidade, tipo){
    $('#unidade').attr('disabled', 'disabled');
    var data = {idUnidade: idUnidade, tipo: tipo};
    $.ajax({
        type: "POST",
        url: "/area/carregar",
        data: data,
        success: function(html) {
            $('#area').html(html);
            $('#unidade').removeAttr('disabled');       
        }
    });
}

function carregarUnidade(empresa, tipo, todos = "F"){
    $('#empresa').attr('disabled', 'disabled');
    var data = {empresa: empresa, tipo: tipo, todos: todos};
    $.ajax({
        type: "POST",
        url: "/unidade/carregar",
        data: data,
        success: function(html) {
            $('#unidade').html(html);
            $('#empresa').removeAttr('disabled');       
        }
    });
}

function carregarUnidadeDestino(empresa, tipo){
    $('#empresa_apoio').attr('disabled', 'disabled');
    var data = {empresa: empresa, tipo: tipo};
    $.ajax({
        type: "POST",
        url: "/unidade/carregar",
        data: data,
        success: function(html) {
            $('#unidade_destino').html(html);
            $('#empresa_apoio').removeAttr('disabled');       
        }
    });
}

function carregarLider(unidade){
    $('#unidade').attr('disabled', 'disabled');
    var data = {unidade: unidade};
    $.ajax({
        type: "POST",
        url: "/lider/carregar",
        data: data,
        success: function(html) {
            $('#lider_imediato').html(html);
            $('#unidade').removeAttr('disabled');       
        }
    });
}

function trocarLider(gestor){
    $('#lider_imediato').attr('disabled', 'disabled');
    var data = {gestor: gestor};
    $.ajax({
        type: "POST",
        url: "/lider/troca/carregar",
        data: data,
        success: function(html) {
            $('#novo_lider').html(html);
            $('#lider_imediato').removeAttr('disabled');       
        }
    });
}


function carregarFuncionario(funcao){
    $('#funcao').attr('disabled', 'disabled');
    var data = {funcao: funcao};
    $.ajax({
        type: "POST",
        url: "/carregar/funcionario",
        data: data,
        success: function(html) {
            $('#funcionario').html(html);
            $('#funcao').removeAttr('disabled');       
        }
    });
}

function carregarRevisor(empresa){
    $('#empresa').attr('disabled', 'disabled');
    var data = {empresa: empresa};
    $.ajax({
        type: "POST",
        url: "/funcionario/carregar/revisor",
        data: data,
        success: function(html) {
            $('#revisor').html(html);
            $('#empresa').removeAttr('disabled');       
        }
    });
}

function CarregarFuncionariosByUnidade(unidade){
    $('#unidade').attr('disabled', 'disabled');
    var data = {unidade: unidade};
    $.ajax({
        type: "POST",
        url: "/ajudas/carregarfuncionario",
        data: data,
        success: function(html) {
            $('#funcionario').html(html);
            $('#unidade').removeAttr('disabled');       
        }
    });
}

function CarregaCidade(estado){
    $('#cidade').attr('disabled', 'disabled');
	var data = {estado: estado};
	$.ajax({
        type: "POST",
        url: "/cidade",
        data: data,
        success: function(html) {
            $('#cidade').html(html);
            $('#cidade').removeAttr('disabled');       
        }
	});
}


function CarregaRecursos(modulo){
    $('#recurso').attr('disabled', 'disabled');
    var data = {modulo: modulo};
    $.ajax({
        type: "POST",
        url: "/recursos",
        data: data,
        success: function(html) {
            $('#recurso').html(html);
            $('#recurso').removeAttr('disabled');       
        }
    });
}

function carregarUnidadeTI(empresa, tipo){
    $('#empresa').attr('disabled', 'disabled');
    var data = {empresa: empresa, tipo: tipo};
    $.ajax({
        type: "POST",
        url: "/unidade/ti/carregar",
        data: data,
        success: function(html) {
            $('#unidade').html(html);
            $('#empresa').removeAttr('disabled');       
        }
    });
}

//BUSCAR A DESCRIÇÃO DE UM RECURSO
function BuscaDescricaoRecurso(recurso){
    $('#recurso').attr('disabled', 'disabled');
    var data = {recurso: recurso};
    $.ajax({
        type: "POST",
        url: "/descricaorecurso",
        data: data,
        success: function(html) {
            $('#descricao_recurso').html(html);
            $('#recurso').removeAttr('disabled');       
        }
    });
}

  function alerta(url, mensagem){
    if(mensagem == 'desativar'){
        mensagem = "Tem certeza que deseja desativar?";
    }else{
        if(mensagem == 'deletar'){
            mensagem = "Tem certeza que deseja deletar?";
        }else{
            if(mensagem == 'desvincular'){
                mensagem = "Tem certeza que deseja desvincular?";
            }
        }
    }
    bootbox.confirm(mensagem, function(result) {
          if(result){
            location.href = url;
          }
        }); 
  }

function exibirEsconder(div){
    if($(div).is(":visible")){
        $(div).hide();
    }else{
        $(div).show();
        //$(div).show();
    }
}

function number_format( numero, decimal, decimal_separador, milhar_separador ){ 
    numero = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+numero) ? 0 : +numero,
        prec = !isFinite(+decimal) ? 0 : Math.abs(decimal),
        sep = (typeof milhar_separador === 'undefined') ? ',' : milhar_separador,
        dec = (typeof decimal_separador === 'undefined') ? '.' : decimal_separador,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix para IE: parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }

    return s.join(dec);
}

function numberToMySql(str){
          str = str.replace(".", "");
          str = str.replace("R$ ", "");
          str = str.replace(",", "."); 
          return str;
      }

//CELULAR COM 9
function mascaraCelular(){
    // jQuery Masked Input
    $('#telefone').mask("(99) 9999-9999?9").ready(function(event) {
        var target, phone, element;
        target = (event.currentTarget) ? event.currentTarget : event.srcElement;
        phone = target.value.replace(/\D/g, '');
        element = $(target);
        element.unmask();
        if(phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
    });
     
    $('#telefone').focusout(function(){
        var phone, element;
        element = $(this);
        element.unmask();
        phone = element.val().replace(/\D/g, '');
        if(phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9"); 
        }
    }).trigger('focusout'); 

}

function mensagem(url, mensagem){
    bootbox.confirm(mensagem, function(result) {
          if(result){
            location.href = url;
          }
        }); 
}

function canvasToPng(nomeImagem){
    html2canvas(document.getElementById('exportarPNG'), {
      onrendered: function(canvas) {
          canvas.toBlob(function(blob) {
              saveAs(blob, nomeImagem+".png");
          });
      }
    });
}

function check(id){    
    /*if($(id).is(':checked')){
        $(id).prop('checked', false);
    }else{
        $(id).prop('checked', true);
    }*/
}

function eventoCalendario(date, tipo, unidade, calendario){
    //pesquisar o texto pela data e tipo (ajax)
    var value = '';
    var data = {data: date, tipo: tipo, unidade: unidade};
    $.ajax({
        type: "POST",
        url: "/comentario/calendario",
        data: data,
        success: function(html) {
            bootbox.prompt({
                title: "Observações do dia: "+date,
                inputType: 'textarea',
                value: html,
                callback: function (result) {
                    //salvar o texto ou alterar pela data e tipo (ajax)
                    salvarTextoCalendario(date, tipo, result, unidade);
                    $(calendario).css('background-color', '#FFDEAD');
                }
            });       
        }
    });
    
}

function salvarTextoCalendario(date, tipo, texto, unidade){
    var data = {data: date, tipo: tipo, texto: texto, unidade: unidade};
    $.ajax({
        type: "POST",
        url: "/comentario/calendario",
        data: data,
        success: function(html) {
                  
        }
    });
}
