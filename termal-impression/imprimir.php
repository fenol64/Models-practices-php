<?php

    require __DIR__ . '/vendor/autoload.php';

    use Mike42\Escpos\Printer;
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

    try {
        $connector = new WindowsPrintConnector("COM3");
        
        /* Print a "Hello world" receipt" */
        $printer = new Printer($connector);
        $printer->text("Hello World!\n");
        $printer->feed(3);
        $printer->cut();
        
        /* Close printer */
        $printer->close();
    } catch (Exception $e) {
        echo "Couldn't print to this printer: {$e->getMessage()}";
    }
    