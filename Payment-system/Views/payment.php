<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
</head>
<body>
    <form method="post" action="<?= url("/payment/send") ?>">
        <!-- holder_name, number, expiration_date, cvv -->
        Number: <input type="number" name="card_number">
        Date: <input type="text" name="expiration_date" value="02/30" maxlength="5" size="2"><p>
        Nome do titular: <input type="text" size="28" name="holder_name"><p>
        cvv: <input type="text" name="cvv" maxlength="3" size="2">
        Valor a dar: <input type="text" value="R$ <?= formatBRL($amount)?>" name="amount" size="7" readonly><p>
        <input type="submit" value="PAGAR!" style="margin-left: 110px">
    </form>
</body>
</html>


