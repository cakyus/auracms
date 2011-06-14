<?

//sset_include_path(get_include_path() . PATH_SEPARATOR . 'mod/phpids/lib');
if (!session_id()) {
session_start();	
}
function sql_connect() {
	global $mysql_host,$mysql_user,$mysql_password,$mysql_database,$Connected;
$Connected = mysql_connect($mysql_host,$mysql_user,$mysql_password);
$SelectedDb = mysql_select_db($mysql_database);	
return $Connected;
}
function sql_close() {
	global $Connected;
	mysql_close($Connected);
}
require_once 'IDS/Init.php';

try {
$init = IDS_Init::init(dirname(__FILE__) . '/lib/IDS/Config/Config.ini');	
$init->config['General']['tmp_path'] = dirname(__FILE__) . '/lib/IDS/tmp';
$init->config['General']['filter_path'] = dirname(__FILE__) . '/lib/IDS/default_filter.xml';
$init->config['Caching']['caching'] = 'none';

$request = $_GET;

 if (isset($_SERVER['HTTP_VIA'])) {
    	//array_push($request,array('HTTP_VIA'=>$_SERVER['HTTP_VIA']));
        }
   if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    	//array_push($request,array('HTTP_X_FORWARDED_FOR'=>$_SERVER['HTTP_X_FORWARDED_FOR']));
       }
   if (isset($_SERVER['HTTP_USER_AGENT'])) {
    	//array_push($request,$_SERVER['HTTP_USER_AGENT']);
       }
   if (!session_is_registered('UserName')) {
	  //array_push($request,$_POST);
   }
   
 $ids = new IDS_Monitor($request, $init);
    $result = $ids->run();
    
    
    
     if (!$result->isEmpty()) {
     require_once 'IDS/Log/Composite.php';
     require_once 'IDS/Log/Database.php';   
		
		
       $compositeLog = new IDS_Log_Composite();
    	
    	 $compositeLog->addLogger(
            IDS_Log_Database::getInstance($init)
        );
     $compositeLog->execute($result);
    
	    if (is_array($Output)) {
	    sql_connect();
	    
	     $Output = array_map('mysql_escape_string',$Output);
	     $ids_name = $Output['name'];
	     $ids_value = $Output['value'];
	     $ids_page = $Output['page'];
	     $ids_ip = $Output['ip'];
	     $ids_impact = $Output['impact'];
	     $ids_created = date('Y-m-d H:i:s');
	    $query = mysql_query("INSERT INTO `intrusions` (`name`,`value`,`page`,`ip`,`impact`,`created`) VALUES ('$ids_name','$ids_value','$ids_page','$ids_ip','$ids_impact','$ids_created')");
	   
	    
    	
    	 
	   sql_close();


     }
        echo $result;
        exit;
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