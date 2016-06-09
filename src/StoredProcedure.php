<?php namespace Mathiasd88\Proceduribility;

use Illuminate\Support\Facades\DB;
use PDO;

class StoredProcedure
{
    /**
     * Nombre del procedimiento
     * 
     * @var string
     */
    protected $procedureName;

    /**
     * Parámetros de entrada para el procedimiento
     * 
     * @var array
     */
    protected $paramsIn = [];

    /**
     * Parámetros de salida del procedimiento
     * 
     * @var array
     */
    protected $paramsOut = [];

    /**
     * Instamcoa del procedimiento almacenado
     * 
     * @var Illuminate\Support\Facades\DB
     */
    protected $stmt;

    /**
     * Salida del procedimiento
     * 
     * @var array
     */
    protected $output = [];

    /**
     * Retorna si ocurrieron errores en la ejecución del procedimiento almacenado.
     * 
     * @return int
     */
    public function errores()
    {
        return $this->output['salida'];
    }

    /**
     * Retorna el mensaje de la ejecución del procedimiento almacenado.
     * 
     * @return string
     */
    public function mensaje()
    {
        return $this->output['mensaje'];
    }

    /**
     * Alias método salida.
     * 
     * @param  string $param
     * 
     * @return string
     */
    public function output($param = null)
    {
        return $this->salida($param);
    }

    /**
     * Retorna un parámetro de salida de la ejecución del procedimiento almacenado, en caso de no detallar el parámetro, retorna toda la salida.
     * 
     * @param  string $param
     * 
     * @return string
     */
    public function salida($param = null)
    {
        if (is_null($param)) {
            return $this->output;
        }

        return $this->output[$param];
    }

    /**
     * Seteo del nombre del procedimiento a llamar.
     * 
     * @param  string $procedureName
     */
    public function name($procedureName)
    {
        $this->procedure = $procedureName;

        return $this;
    }

    /**
     * Setea los parámetros de entrada a la instancia.
     * 
     * @param  array $paramsIn
     */
    public function paramsIn($paramsIn = [])
    {
        $this->paramsIn = $paramsIn;

        return $this;
    }

    /**
     * Setea los parámetros de salida a la instancia.
     * 
     * @param  array  $paramsOut
     */
    public function paramsOut($paramsOut = [])
    {
        $this->paramsOut = $paramsOut;

        return $this;
    }

    /**
     * Binding de los parámetros por defecto de salida que deberían tener todos los procedimientos almacenados.
     */
    private function defaultParamsOut()
    {
        $stmt->bindParam('p_n_salida', $salida, PDO::PARAM_INT);
        $stmt->bindParam('p_v_mensaje', $mensaje, PDO::PARAM_STR, 3000);
    }

    /**
     * Alias del método execute.
     */
    public function run()
    {
        $this->execute();
    }

    /**
     * Ejecuta el procedimiento almacenado
     */
    public function execute()
    {
        $this->buildQuery();

        return $this;
    }

    /**
     * Ejecución de los pasos para la preparación y ejecución de un procedimiento almacenado.
     */
    private function buildQuery()
    {
        $this->prepareSql();
        $this->bindParamsIn();
        $this->bindParamsOut();
    }

    /**
     * Se prepara la instancia del procedimiento junto con los parámetros de entrada y salida que recibirá.
     */
    private function prepareSql()
    {
        $params = array_merge($this->paramsIn, $this->paramsOut);
        $params = $this->formatParams($params);

        $this->stmt = DB::getPdo()->prepare("BEGIN $this->procedure($params); END;");
    }

    /**
     * Se hace binding de los parámetros de entrada para la ejecución del procedimiento almacenado.
     */
    private function bindParamsIn()
    {
        foreach ($this->paramsIn as $key => $param) {
            $this->stmt->bindParam($key, $param);
        }
    }

    /**
     * Se hace binding de los parámetros de salida del procedimiento almacenado y se prepara la propiedad de output para la salida.
     */
    private function bindParamsOut()
    {
        $this->stmt->bindParam('p_n_salida', $salida, PDO::PARAM_INT);
        $this->stmt->bindParam('p_v_mensaje', $mensaje, PDO::PARAM_STR, 3000);
        $this->stmt->execute();

        $this->output = [
            'salida' => $salida,
            'mensaje' => $mensaje
        ];
    }

    /**
     * Se formatean los parámetros de entrada y salida para la llamada del procedimiento.
     * 
     * @param  array $params
     * 
     * @return array
     */
    private function formatParams($params)
    {
        $formatedParams = '';

        foreach ($params as $key => $value) {
            $formatedParams = $formatedParams . ':' . $key . ',';
        }

        $formatedParams = substr_replace($formatedParams, '', -1);

        return $formatedParams;
    }

}