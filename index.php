<?php

/**
 * @package filesHandler
 * @author Rafael Perez <rafaelperez7461@gmail.com>
 * @copyright (c) 2017, Rafael Perez
 */

require_once 'fileHandler/fileHandler.php';
use fileHandler\fileHandler; // nombreCarpeta/nombreClase


$contenido = "esto es una prueba del contenido que puede tener un archivo\n";
$nuevoContenido = '{"titulo":"Altitud del monte Everest","categoria":"Cultura","respuestas":{"respuesta1":8850,"respuesta2":8900,"respuesta3":8875}}';

$fileName = "nuevo_0.txt";
$fileHandler = new fileHandler();
var_dump($fileHandler->getFileInfo($fileName));