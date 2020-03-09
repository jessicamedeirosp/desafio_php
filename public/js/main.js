var cep = document.querySelector("input[name=cep]");
cep.addEventListener("blur", function(e) {
  e.preventDefault();
  valorCep = this.value;
  pesquisacep(valorCep);
});
function meu_callback(conteudo) {
  console.log("fgdgd");
  if (!("erro" in conteudo)) {
    //Atualiza os campos com os valores.
    console.log("fgdgd");
    logradouro = document.getElementById("logradouro");
    bairro = document.getElementById("bairro");
    cidade = document.getElementById("cidade");
    estado = document.getElementById("estado");
    numero = document.getElementById("numero");
    logradouro.value = conteudo.logradouro;
    bairro.value = conteudo.bairro;
    cidade.value = conteudo.localidade;
    estado.value = conteudo.uf;

    numero.focus();
  } //end if.
  else {
    //CEP não Encontrado.
    alert("CEP não encontrado.");
  }
}

function pesquisacep(valor) {
  var cep = valor.replace(/\D/g, "");
  if (cep != "" && cep.length <= 8) {
    var validacep = /^[0-9]{8}$/;
    if (validacep.test(cep)) {
      var script = document.createElement("script");
      script.src =
        "https://viacep.com.br/ws/" + cep + "/json/?callback=meu_callback";
      document.body.appendChild(script);
    }
  } else {
    alert("Informe o cep corretamente!");
  }
}

var cpf = document.getElementById("cpf");
var rg = document.getElementById("rg");
var data_nascimento = document.getElementById("data_nascimento");
cpf.addEventListener("keyup", function() {
  if (this.value.length <= 14) {
    cpf = this.value.replace(/\D/g, "");
    cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
    cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
  }
  this.value = cpf;
});
rg.addEventListener("keyup", function() {
  if (this.value.length <= 12) {
    rg = this.value.replace(/\W/g, "");
    rg = rg.replace(/(\d{1,2})(\d)/, "$1.$2");
    rg = rg.replace(/(\d{3})(\d)/, "$1.$2");
    rg = rg.replace(/(\d{3})([\d+a-zA-Z]{1})$/, "$1-$2");
  }
  this.value = rg;
});
data_nascimento.addEventListener("keyup", function() {
  if (this.value.length <= 10) {
    data = this.value.replace(/\D/g, "");
    data = data.replace(/(\d{2})(\d)/, "$1/$2");
    data = data.replace(/(\d{2})(\d)/, "$1/$2");
  }
  this.value = data;
});
