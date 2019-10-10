<?php
    include_once "./conexao.php";
    
    function pesquisa($number, $con, $pesq){

        $sql = "SELECT * FROM alunos";

        if ($number = 1) {
            $sql = $sql . " WHERE nome LIKE '$pesq%'";
        }
    
        $result = mysqli_query($con, $sql);   

        echo"<table class='w-100 mt-4' border='1'>
        <tr>
            <th>Id</th>
            <th>Matricula</th>
            <th>Nome</th>
            <th>nascimento</th>
            <th>Turma</th>
            <th>serie</th>
        </tr>";


        while ($rows = mysqli_fetch_assoc($result)) {
            echo"<tr>";
            echo "<td>".$rows['id']."</td>";
            echo "<td>".$rows['matricula']."</td>";
            echo "<td>".$rows['nome']."</td>";
            echo "<td>".$rows['nascimento']."</td>";
            echo "<td>".$rows['turma']."</td>";
            echo "<td>".$rows['serie']."° ano</td>";
            echo"<tr>";
        }

    }

    $pesq =  isset($_POST["palavra"])?$_POST["palavra"]:"";  
    
    $sql = "SELECT * FROM alunos WHERE nome LIKE '%$pesq%'";
    $result = mysqli_query($conn, $sql); 

    if(mysqli_num_rows($result) <= 0 || $pesq == ""){
        pesquisa(2, $conn, $pesq);
    }else{
        pesquisa(1, $conn, $pesq);
    }
?>


