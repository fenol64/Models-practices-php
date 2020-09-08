<?php

    require __DIR__."/vendor/autoload.php";

    $calendar = new Calendar();
    $calendario = $calendar->getyearmouths()->build();

    //var_dump($calendario);

    function getdata($data): array
    {
        $data = explode("/", $data);
        list($dia, $mes, $ano) = $data;
        $data = "$ano/$mes/$dia";
        return array(
            "dia" => $dia,
            "mes" => $mes,
            "ano" => $ano
        );
    }

    $i = 0
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/style.css">
    <title>calendar</title>
</head>
<body>
    <main>
        <div class="box_content">
            <?php foreach ($calendario as $mouth => $days): ?>
                <div class="box"  id="<?=$i?>">
                    <?php foreach ($days as $day => $events): ?>
                        <article>
                            <?=getdata(end($events))["dia"]?> <p>
                            <spam style="font-size: 10px;">
                                <?= $events[0] ?>
                            </spam>
                        </article>
                    <?php endforeach; ?>
                
                    <div>
                        <p class="mes">
                            <span class="prev" id="<?= $i ?>" onclick="pass('prev', <?=$i-1?>)"> < </span>
                                <?= $mouth ?> 
                            <span class="next" id="<?= $i+1 ?>" onclick="pass('next', <?=$i+1?>)"> > </span>
                        </p>
                    </div>   
                </div>
                <?php $i++ ?>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>