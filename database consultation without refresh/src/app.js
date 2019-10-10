$(function(){
    //Pesquisar os nomes ou telefones
    $("#campo").keyup(function(){
        
        var pesquisa = $(this).val();
        if (pesquisa != ''){

            var dados = {
                palavra : pesquisa
            }

            $.post('./src/pesquisa.php', dados, function(retorna){
                $(".resultado").html(retorna);
            });

        }else{
            $.post('./src/pesquisa.php', dados, function(retorna){
                $(".resultado").html(retorna);
            });
        }

    });
});