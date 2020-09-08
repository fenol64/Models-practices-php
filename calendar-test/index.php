<?php

    require __DIR__."/vendor/autoload.php";

    $calendar = new Calendar();
    $calendario = $calendar->getyearmouths()->build();

    //var_dump($calendario);

    function getdata($data): string
    {
        $data = explode("/", $data);
        list($dia, $mes, $ano) = $data;
        $data = "$ano/$mes/$dia";
        return $dia;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/style.css">
    <title>Document</title>
</head>
<body>
    <main border="1">
        <?php foreach ($calendario as $mouth => $days): ?>
            <div>
                <p> <?= $mouth ?> </p>
            </div>   
            <div class="box">
                <?php foreach ($days as $day => $events): ?>
                    <article>
                        <?=getdata(end($events))?> <p>
                        <spam style="font-size: 10px;">
                            <?= $events[0] ?>
                        </spam>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>


