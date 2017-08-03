<?php
/**
 * runs a stored procedure and returns results if any
 * @param string    $sProcedure
 * @param array     $aParams
 */
function cf_database__call_stored_procedure($sProcedure, $aParams = null)
{
    // create database connection
    $db = DB::connection()->getPdo();

    // if any params are present, add them
    $sParamsIn = '';
    if(isset($aParams) && is_array($aParams) && count($aParams)>0) {
        // loop through params and set
        foreach($aParams as $sParam) {
            $sParamsIn .= '?,';
        }

        // trim the last comma from the params in string
        $sParamsIn = substr($sParamsIn, 0, strlen($sParamsIn)-1);
    }

    // create initial stored procedure call
    $stmt = $db->prepare("CALL $sProcedure($sParamsIn)");

    // if any params are present, add them
    if(isset($aParams) && is_array($aParams) && count($aParams)>0) {
        $iParamCount = 1;

        // loop through params and bind value to the prepare statement
        foreach ($aParams as &$value) {
            $stmt->bindParam($iParamCount, $value);
            $iParamCount++;
        }
    }

    // execute the stored procedure
    $stmt->execute();

    // loop through results and place into array if found
    $aData = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

    // if the resultset has only 1 record, check the name of the stored procedure
    // if the name of the procedure has sel_rec within it, just return the one record
    if(count($aData) == 1 && strpos($sProcedure, 'sel_rec')) {
        $aData = $aData[0];
    }

    // return the data
    return $aData;
}
