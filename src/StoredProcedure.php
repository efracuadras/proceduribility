<?php namespace Mathiasd88\Proceduribility;

use Illuminate\Support\Facades\DB;
use PDO;

class StoredProcedure
{
    /**
     * Nombre del procedimiento.
     * 
     * @var string
     */
    protected $procedureName;

    /**
     * Parámetros de entrada para el procedimiento.
     * 
     * @var array
     */
    protected $paramsIn = [];

    /**
     * Parámetros de salida del procedimiento.
     * 
     * @var array
     */
    protected $paramsOut = [];

    /**
     * Sentencia del procedimiento almacenado.
     * 
     * @var Illuminate\Support\Facades\DB
     */
    protected $stmt;

    /**
     * Datos de salida del procedimiento.
     * 
     * @var array
     */
    protected $output = [];

    /**
     * Retorna un parámetro de salida de la ejecución del procedimiento almacenado, en caso de no detallar el parámetro, retorna toda la salida.
     * 
     * @param  string $param
     * 
     * @return string
     */
    public function output($param = null)
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
        if (config('procedure.default_output')) {
            $paramsOut = array_merge($paramsOut, config('procedure.default_output_parameters'));
        }

        $this->paramsOut = $paramsOut;

        return $this;
    }

    /**
     * Alias del método execute. Ejecuta el procedimiento almacenado.
     */
    public function run()
    {
        return $this->execute();
    }

    /**
     * Ejecuta el procedimiento almacenado.
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
        $formatedParams = $this->formatParams($params);

        $this->stmt = DB::getPdo()->prepare("BEGIN $this->procedure($formatedParams); END;");
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
        foreach ($this->paramsOut as $param => $value) {

            switch ($value) {
                case PDO::PARAM_STR:
                    $this->stmt->bindParam($param, ${$param}, PDO::PARAM_STR, config('procedure.params.str_length'));
                    break;
                case PDO::PARAM_INPUT_OUTPUT:
                    $this->stmt->bindParam($param, ${$param}, PDO::PARAM_INPUT_OUTPUT, config('procedure.params.str_length'));
                    break;
                default:
                    $this->stmt->bindParam($param, ${$param}, $value);
                    break;
            }
        }

        $this->stmt->execute();

        // Prepare output
        foreach ($this->paramsOut as $param => $value) {
            $this->output[$param] = ${$param};
        }
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