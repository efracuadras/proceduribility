<?php

return [

    /**
     * Parámetros por defecto de las variables de salida a utilizar en los procedimientos almacenados.
     */
    'params' => [
        'str_length' => 3000
    ],

    /**
     * Se utilizará o no variables de salida por defecto.
     */
    'default_output' => true,

    /**
     * Variables de salida por defecto que se entregarán todos los procedimientos almacenados.
     */
    'default_output_parameters' => [
        'salida' => PDO::PARAM_INT,
        'mensaje' => PDO::PARAM_STR
    ],

];


