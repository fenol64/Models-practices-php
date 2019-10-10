<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./src/style.css">
    <title>Pesquisa no banco</title>
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./src/app.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="text-center pt-5 pb-5">
            Digite oque você quer procurar: 
        </h1>
        <form method="POST" >
            <input type="text" name="pesquisa" id="campo" placeholder="Digite o nome do aluno" autocomplete="off" autofocus class="pesquisa w-100 rounded myinput anima">
        </form>
        <div class="resultado w-100 anima">
            <!-- here will appear the whole outputs -->
            <?php
                include_once "./src/conexao.php";

                $con = mysqli_connect("localhost", "root", "@lablemos2019", "consulta");

                $sql2 = "SELECT * FROM alunos";
                $result2 = mysqli_query($con, $sql2);   

                echo"<table class='w-100 mt-4' border='1'>
                <tr>
                    <th>Id</th>
                    <th>Matricula</th>
                    <th>Nome</th>
                    <th>nascimento</th>
                    <th>Turma</th>
                    <th>serie</th>
                </tr>";


                while ($rows = mysqli_fetch_assoc($result2)) {
                    echo"<tr>";
                    echo "<td>".$rows['id']."</td>";
                    echo "<td>".$rows['matricula']."</td>";
                    echo "<td>".$rows['nome']."</td>";
                    echo "<td>".$rows['nascimento']."</td>";
                    echo "<td>".$rows['turma']."</td>";
                    echo "<td>".$rows['serie']."° ano</td>";
                    echo"<tr>";
                } 
            ?>
        </div>
    </div>
</body>
</html>