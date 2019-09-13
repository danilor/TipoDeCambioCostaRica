<?php
    header("Access-Control-Allow-Origin: *");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $cache_expires = 3600;

    define( 'BCCR_BUY_ID', 317 );
    define( 'BCCR_SELL_ID', 318 );

    // Checks whether the page has been cached or not
    function is_cached($file) {
    	global $cache_folder, $cache_expires;
    	$cachefile = $cache_folder . $file;
    	$cachefile_created = (file_exists($cachefile)) ? @filemtime($cachefile) : 0;
    	return ((time() - $cache_expires) < $cachefile_created);
    }

    // Reads from a cached file
    function read_cache($file) {
    	global $cache_folder;
    	$cachefile = $cache_folder . $file;
    	return file_get_contents($cachefile);
    }

    // Writes to a cached file
    function write_cache($file, $out) {
	    global $cache_folder;
	    $cachefile = $cache_folder . $file;
	    $fp = fopen($cachefile, 'w');
	    fwrite($fp, $out);
	    fclose($fp);
    }

    function getFinalUrl( $id ){
        $baseUrl = 'http://indicadoreseconomicos.bccr.fi.cr/indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/ObtenerIndicadoresEconomicosXML?tcIndicador=[$id]&tcFechaInicio=[$startDate]&tcFechaFinal=[$endDate]&tcNombre=[$tcName]&tnSubNiveles=N';
        $newUrl = str_replace
        (
            [
                '[$id]',
                '[$startDate]',
                '[$endDate]',
                '[$tcName]'
            ],
            [
                $id,
                @$_REQUEST['tcFechaInicio'],
                @$_REQUEST['tcFechaFinal'],
                @$_REQUEST['tcNombre']
            ], 
            $baseUrl
        );
        return $newUrl;
    }

    function readUrlAndParseInformation( $url ){
        $data_file = file_get_contents( $url );
        
        $xml = simplexml_load_string( $data_file ) or die("Error: Cannot create object");
        $xml = simplexml_load_string( $xml[0] ) or die("Error: Cannot create object");

        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        // $xml = $xml[0];
        $results = $array['INGC011_CAT_INDICADORECONOMIC'];
        $ordered = [];
        
        foreach( $results AS $result ){
            $temp =  new \stdClass();

            $dated = strtotime( $result['DES_FECHA'] );
            $newDateFormat = date('d-m-Y',$dated);

            $temp->original_date = $result['DES_FECHA'];
            $temp->year =  date('Y',$dated);
            $temp->month =  date('m',$dated);
            $temp->day =  date('d',$dated);
            $temp->friendly_date = $newDateFormat;
            $temp->value = (float)$result['NUM_VALOR'];
            $temp->code = (int)$result['COD_INDICADORINTERNO'];

            $ordered[] = $temp;
        }


        return $ordered;
    }


    $cache_file = md5($_SERVER['REQUEST_URI']) . ".json";

    if (is_cached($cache_file)) {
        $information =  json_decode( read_cache($cache_file) );
    }else{
        $buyUrl = getFinalUrl( BCCR_BUY_ID );
        $sellUrl = getFinalUrl( BCCR_SELL_ID );
        $information = new \stdClass();    
        $information->buy = readUrlAndParseInformation( $buyUrl );
        $information->sell = readUrlAndParseInformation( $sellUrl );
        write_cache($cache_file, json_encode($information));
    }

    $data = new \stdClass();
    $data->status = 'OK';
    $data->code = 200;
    // $data->buy_url = $buyUrl;
    // $data->sell_url = $sellUrl; 
    $data->information = $information;

    header('Content-Type: application/json');
    echo json_encode($data);
    // $dstring = '{"status":"OK","code":200,"buy_url":"http:\/\/indicadoreseconomicos.bccr.fi.cr\/indicadoreseconomicos\/WebServices\/wsIndicadoresEconomicos.asmx\/ObtenerIndicadoresEconomicosXML?tcIndicador=317&tcFechaInicio=14\/08\/2019&tcFechaFinal=13\/09\/2019&tcNombre=dmm&tnSubNiveles=N","sell_url":"http:\/\/indicadoreseconomicos.bccr.fi.cr\/indicadoreseconomicos\/WebServices\/wsIndicadoresEconomicos.asmx\/ObtenerIndicadoresEconomicosXML?tcIndicador=318&tcFechaInicio=14\/08\/2019&tcFechaFinal=13\/09\/2019&tcNombre=dmm&tnSubNiveles=N","information":{"buy":[{"original_date":"2019-08-14T00:00:00-06:00","year":"2019","month":"08","day":"14","friendly_date":"14-08-2019","value":566.6,"code":317},{"original_date":"2019-08-15T00:00:00-06:00","year":"2019","month":"08","day":"15","friendly_date":"15-08-2019","value":565.42,"code":317},{"original_date":"2019-08-16T00:00:00-06:00","year":"2019","month":"08","day":"16","friendly_date":"16-08-2019","value":565.42,"code":317},{"original_date":"2019-08-17T00:00:00-06:00","year":"2019","month":"08","day":"17","friendly_date":"17-08-2019","value":563.82,"code":317},{"original_date":"2019-08-18T00:00:00-06:00","year":"2019","month":"08","day":"18","friendly_date":"18-08-2019","value":563.82,"code":317},{"original_date":"2019-08-19T00:00:00-06:00","year":"2019","month":"08","day":"19","friendly_date":"19-08-2019","value":563.82,"code":317},{"original_date":"2019-08-20T00:00:00-06:00","year":"2019","month":"08","day":"20","friendly_date":"20-08-2019","value":562.23,"code":317},{"original_date":"2019-08-21T00:00:00-06:00","year":"2019","month":"08","day":"21","friendly_date":"21-08-2019","value":561.39,"code":317},{"original_date":"2019-08-22T00:00:00-06:00","year":"2019","month":"08","day":"22","friendly_date":"22-08-2019","value":561.75,"code":317},{"original_date":"2019-08-23T00:00:00-06:00","year":"2019","month":"08","day":"23","friendly_date":"23-08-2019","value":562.94,"code":317},{"original_date":"2019-08-24T00:00:00-06:00","year":"2019","month":"08","day":"24","friendly_date":"24-08-2019","value":563.07,"code":317},{"original_date":"2019-08-25T00:00:00-06:00","year":"2019","month":"08","day":"25","friendly_date":"25-08-2019","value":563.07,"code":317},{"original_date":"2019-08-26T00:00:00-06:00","year":"2019","month":"08","day":"26","friendly_date":"26-08-2019","value":563.07,"code":317},{"original_date":"2019-08-27T00:00:00-06:00","year":"2019","month":"08","day":"27","friendly_date":"27-08-2019","value":562.87,"code":317},{"original_date":"2019-08-28T00:00:00-06:00","year":"2019","month":"08","day":"28","friendly_date":"28-08-2019","value":563.95,"code":317},{"original_date":"2019-08-29T00:00:00-06:00","year":"2019","month":"08","day":"29","friendly_date":"29-08-2019","value":565.17,"code":317},{"original_date":"2019-08-30T00:00:00-06:00","year":"2019","month":"08","day":"30","friendly_date":"30-08-2019","value":566.45,"code":317},{"original_date":"2019-08-31T00:00:00-06:00","year":"2019","month":"08","day":"31","friendly_date":"31-08-2019","value":567.66,"code":317},{"original_date":"2019-09-01T00:00:00-06:00","year":"2019","month":"09","day":"01","friendly_date":"01-09-2019","value":567.66,"code":317},{"original_date":"2019-09-02T00:00:00-06:00","year":"2019","month":"09","day":"02","friendly_date":"02-09-2019","value":567.66,"code":317},{"original_date":"2019-09-03T00:00:00-06:00","year":"2019","month":"09","day":"03","friendly_date":"03-09-2019","value":568.57,"code":317},{"original_date":"2019-09-04T00:00:00-06:00","year":"2019","month":"09","day":"04","friendly_date":"04-09-2019","value":573.02,"code":317},{"original_date":"2019-09-05T00:00:00-06:00","year":"2019","month":"09","day":"05","friendly_date":"05-09-2019","value":574.08,"code":317},{"original_date":"2019-09-06T00:00:00-06:00","year":"2019","month":"09","day":"06","friendly_date":"06-09-2019","value":576.2,"code":317},{"original_date":"2019-09-07T00:00:00-06:00","year":"2019","month":"09","day":"07","friendly_date":"07-09-2019","value":575.05,"code":317},{"original_date":"2019-09-08T00:00:00-06:00","year":"2019","month":"09","day":"08","friendly_date":"08-09-2019","value":575.05,"code":317},{"original_date":"2019-09-09T00:00:00-06:00","year":"2019","month":"09","day":"09","friendly_date":"09-09-2019","value":575.05,"code":317},{"original_date":"2019-09-10T00:00:00-06:00","year":"2019","month":"09","day":"10","friendly_date":"10-09-2019","value":573.58,"code":317},{"original_date":"2019-09-11T00:00:00-06:00","year":"2019","month":"09","day":"11","friendly_date":"11-09-2019","value":571.93,"code":317},{"original_date":"2019-09-12T00:00:00-06:00","year":"2019","month":"09","day":"12","friendly_date":"12-09-2019","value":571.18,"code":317},{"original_date":"2019-09-13T00:00:00-06:00","year":"2019","month":"09","day":"13","friendly_date":"13-09-2019","value":572.08,"code":317}],"sell":[{"original_date":"2019-08-14T00:00:00-06:00","year":"2019","month":"08","day":"14","friendly_date":"14-08-2019","value":572.43,"code":318},{"original_date":"2019-08-15T00:00:00-06:00","year":"2019","month":"08","day":"15","friendly_date":"15-08-2019","value":571.3,"code":318},{"original_date":"2019-08-16T00:00:00-06:00","year":"2019","month":"08","day":"16","friendly_date":"16-08-2019","value":571.3,"code":318},{"original_date":"2019-08-17T00:00:00-06:00","year":"2019","month":"08","day":"17","friendly_date":"17-08-2019","value":570.46,"code":318},{"original_date":"2019-08-18T00:00:00-06:00","year":"2019","month":"08","day":"18","friendly_date":"18-08-2019","value":570.46,"code":318},{"original_date":"2019-08-19T00:00:00-06:00","year":"2019","month":"08","day":"19","friendly_date":"19-08-2019","value":570.46,"code":318},{"original_date":"2019-08-20T00:00:00-06:00","year":"2019","month":"08","day":"20","friendly_date":"20-08-2019","value":569.92,"code":318},{"original_date":"2019-08-21T00:00:00-06:00","year":"2019","month":"08","day":"21","friendly_date":"21-08-2019","value":568.07,"code":318},{"original_date":"2019-08-22T00:00:00-06:00","year":"2019","month":"08","day":"22","friendly_date":"22-08-2019","value":568.45,"code":318},{"original_date":"2019-08-23T00:00:00-06:00","year":"2019","month":"08","day":"23","friendly_date":"23-08-2019","value":569.53,"code":318},{"original_date":"2019-08-24T00:00:00-06:00","year":"2019","month":"08","day":"24","friendly_date":"24-08-2019","value":570.2,"code":318},{"original_date":"2019-08-25T00:00:00-06:00","year":"2019","month":"08","day":"25","friendly_date":"25-08-2019","value":570.2,"code":318},{"original_date":"2019-08-26T00:00:00-06:00","year":"2019","month":"08","day":"26","friendly_date":"26-08-2019","value":570.2,"code":318},{"original_date":"2019-08-27T00:00:00-06:00","year":"2019","month":"08","day":"27","friendly_date":"27-08-2019","value":570.17,"code":318},{"original_date":"2019-08-28T00:00:00-06:00","year":"2019","month":"08","day":"28","friendly_date":"28-08-2019","value":570.03,"code":318},{"original_date":"2019-08-29T00:00:00-06:00","year":"2019","month":"08","day":"29","friendly_date":"29-08-2019","value":570.99,"code":318},{"original_date":"2019-08-30T00:00:00-06:00","year":"2019","month":"08","day":"30","friendly_date":"30-08-2019","value":572.89,"code":318},{"original_date":"2019-08-31T00:00:00-06:00","year":"2019","month":"08","day":"31","friendly_date":"31-08-2019","value":575.16,"code":318},{"original_date":"2019-09-01T00:00:00-06:00","year":"2019","month":"09","day":"01","friendly_date":"01-09-2019","value":575.16,"code":318},{"original_date":"2019-09-02T00:00:00-06:00","year":"2019","month":"09","day":"02","friendly_date":"02-09-2019","value":575.16,"code":318},{"original_date":"2019-09-03T00:00:00-06:00","year":"2019","month":"09","day":"03","friendly_date":"03-09-2019","value":577.15,"code":318},{"original_date":"2019-09-04T00:00:00-06:00","year":"2019","month":"09","day":"04","friendly_date":"04-09-2019","value":579.59,"code":318},{"original_date":"2019-09-05T00:00:00-06:00","year":"2019","month":"09","day":"05","friendly_date":"05-09-2019","value":580.82,"code":318},{"original_date":"2019-09-06T00:00:00-06:00","year":"2019","month":"09","day":"06","friendly_date":"06-09-2019","value":582.67,"code":318},{"original_date":"2019-09-07T00:00:00-06:00","year":"2019","month":"09","day":"07","friendly_date":"07-09-2019","value":581.13,"code":318},{"original_date":"2019-09-08T00:00:00-06:00","year":"2019","month":"09","day":"08","friendly_date":"08-09-2019","value":581.13,"code":318},{"original_date":"2019-09-09T00:00:00-06:00","year":"2019","month":"09","day":"09","friendly_date":"09-09-2019","value":581.13,"code":318},{"original_date":"2019-09-10T00:00:00-06:00","year":"2019","month":"09","day":"10","friendly_date":"10-09-2019","value":579.79,"code":318},{"original_date":"2019-09-11T00:00:00-06:00","year":"2019","month":"09","day":"11","friendly_date":"11-09-2019","value":577.96,"code":318},{"original_date":"2019-09-12T00:00:00-06:00","year":"2019","month":"09","day":"12","friendly_date":"12-09-2019","value":577.28,"code":318},{"original_date":"2019-09-13T00:00:00-06:00","year":"2019","month":"09","day":"13","friendly_date":"13-09-2019","value":578.83,"code":318}]}}';
    // echo $dstring;
    ?>
