<?php

    $especialidades = $_POST["data"];

    $json_result = json_encode($especialidades);

    echo $json_result;