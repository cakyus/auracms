<?

//sset_include_path(get_include_path() . PATH_SEPARATOR . 'mod/phpids/lib');
if (!session_id()) {
session_start();	
}

require_once 'IDS/Init.php';

try {

    /*
    * It's pretty easy to get the PHPIDS running
    * 1. Define what to scan
    */
    global $Output,$mysql_user,$mysql_password,$mysql_database,$mysql_host;
	$_GET = !is_array($_GET) ? array() : $_GET;
	$_SESSION = !is_array($_SESSION) ? array() : $_SESSION;
    $request = array_merge($_GET, $_SESSION);
    $init = IDS_Init::init(dirname(__FILE__) . '/IDS/Config/Config.ini');

    /**
     * You can also reset the whole configuration
     * array or merge in own data
     *
     * This usage doesn't overwrite already existing values
     * $config->setConfig(array('General' => array('filter_type' => 'xml')));
     *
     * This does (see 2nd parameter)
     * $config->setConfig(array('General' => array('filter_type' => 'xml')), true);
     *
     * or you can access the config directly like here:
     */
    $init->config['General']['tmp_path'] = dirname(__FILE__) . '/IDS/tmp';
    $init->config['General']['filter_path'] = dirname(__FILE__) . '/IDS/default_filter.xml';
    $init->config['Caching']['caching'] = 'none';
    //$init->config['Logging']['wrapper'] = 'mysql:host=localhost;port=3306;dbname=auracms22';
    //$init->config['Logging']['user'] = 'root';
    //$init->config['Logging']['password'] = '';
    //$init->config['Logging']['table'] = 'intrusions';

    // 2. Initiate the PHPIDS and fetch the results
    $ids = new IDS_Monitor($request, $init);
    $result = $ids->run();

    /*
    * That's it - now you can analyze the results:
    *
    * In the result object you will find any suspicious
    * fields of the passed array enriched with additional info
    *
    * Note: it is moreover possible to dump this information by
    * simply echoing the result object, since IDS_Report implemented
    * a __toString method.
    */
    if (!$result->isEmpty()) {
        //echo $result;
       
		
        /*
        * The following steps are optional to log the results
        */
        require_once 'IDS/Log/File.php';
        require_once 'IDS/Log/Composite.php';
        
		
		
       $compositeLog = new IDS_Log_Composite();
       //$compositeLog->addLogger(IDS_Log_File::getInstance($init));

        /*
        * Note that you might also use different logging facilities
        * such as IDS_Log_Email or IDS_Log_Database
        *
        * Just uncomment the following lines to test the wrappers
        */
        
        require_once 'IDS/Log/Database.php';
        /*
        *
        require_once 'IDS/Log/Email.php';
        require_once 'IDS/Log/Database.php';

        $compositeLog->addLogger(
            IDS_Log_Email::getInstance($init),
            IDS_Log_Database::getInstance($init)
        );
        */
     $compositeLog->addLogger(
            IDS_Log_Database::getInstance($init)
        );
     $compositeLog->execute($result);
     if (is_array($Output)) {
	     $Connected = mysql_connect($mysql_host,$mysql_user,$mysql_password);
	     $SelectedDb = mysql_select_db($mysql_database);
	     $Output = array_map('mysql_escape_string',$Output);
	     $ids_name = $Output['name'];
	     $ids_value = $Output['value'];
	     $ids_page = $Output['page'];
	     $ids_ip = $Output['ip'];
	     $ids_impact = $Output['impact'];
	     $ids_created = date('Y-m-d H:i:s');
	    $query = mysql_query("INSERT INTO `intrusions` (`name`,`value`,`page`,`ip`,`impact`,`created`) VALUES ('$ids_name','$ids_value','$ids_page','$ids_ip','$ids_impact','$ids_created')");
	    mysql_close($Connected);


     }
        echo $result;

exit;

    } else {
        //echo '<a href="?test=%22>XXX<script>alert(1)</script>">No attack detected - click for an example attack</a>';
    }
} catch (Exception $e) {
    /*
    * sth went terribly wrong - maybe the
    * filter rules weren't found?
    */
   
    printf(
        'An error occured: %s',
        $e->getMessage()
    );
    
}









?>