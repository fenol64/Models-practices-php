<?php

$especialidades = ['especialidades' => []];
foreach ($_POST as $campo) {
    $especialidades['especialidades'][] = filter_var($campo, FILTER_SANITIZE_STRING);

}

$data = json_encode($especialidades);

if ($data) {
   echo "<script>alert('salvo com sucesso!')</script>";
}