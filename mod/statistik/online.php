<?

function now(){

if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', getenv("HTTP_X_FORWARDED_FOR")) == ''){
$uipanda = getenv('REMOTE_ADDR');
}else{
$uipanda = getenv('HTTP_X_FORWARDED_FOR');
}
$uproxyserver=getenv("HTTP_VIA");
$uipproxy=getenv("REMOTE_ADDR");
$uhost=gethostbyaddr($uipproxy);
$utime=time();
$now=$utime-600; // (in seconds)

@mysql_query("delete from useronline where timevisit<$now");
$qw = mysql_query("SELECT count(id) as total FROM useronline WHERE ipproxy = '$uipproxy'");
$countdataquery = mysql_fetch_assoc($qw);
$uexists= $countdataquery['total'];
if ($uexists>0){
@mysql_query("update useronline set timevisit='$utime' where ipproxy='$uipproxy'");
} else {
@mysql_query("insert into useronline (ipproxy,host,ipanda,proxyserver,timevisit) values ('$uipproxy','$uhost','$uipanda','$uproxyserver','$utime')");
}

//$rs=@mysql_query("select * from useronline");
$qw = mysql_query("SELECT count(id) as total FROM useronline");
$countdataquery = mysql_fetch_assoc($qw);
$jmlonline= $countdataquery['total'];
return "<b>$jmlonline</b>";

}

function day(){

if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', getenv("HTTP_X_FORWARDED_FOR")) == ''){
$uipanda = getenv('REMOTE_ADDR');
}else{
$uipanda = getenv('HTTP_X_FORWARDED_FOR');
}
$uproxyserver=getenv("HTTP_VIA");
$uipproxy=getenv("REMOTE_ADDR");
$uhost=gethostbyaddr($uipproxy);
$utime=time();
$day=$utime-86400; // (in seconds)

@mysql_query("delete from useronlineday where timevisit<$day");
$qw = mysql_query("SELECT count(id) as total FROM useronlineday WHERE ipproxy = '$uipproxy'");
$countdataquery = mysql_fetch_assoc($qw);
$uexists= $countdataquery['total'];
if ($uexists>0){
@mysql_query("update useronlineday set timevisit='$utime' where ipproxy='$uipproxy'");
} else {
@mysql_query("insert into useronlineday (ipproxy,host,ipanda,proxyserver,timevisit) values ('$uipproxy','$uhost','$uipanda','$uproxyserver','$utime')");
}

//$rs=@mysql_query("select * from useronlineday");
$qw = mysql_query("SELECT count(id) as total FROM useronlineday");
$countdataquery = mysql_fetch_assoc($qw);
$jmlonline= $countdataquery['total'];
return "<b>$jmlonline</b>";


}

function month(){

if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', getenv("HTTP_X_FORWARDED_FOR")) == ''){
$uipanda = getenv('REMOTE_ADDR');
}else{
$uipanda = getenv('HTTP_X_FORWARDED_FOR');
}

$uproxyserver=getenv("HTTP_VIA");
$uipproxy=getenv("REMOTE_ADDR");
$uhost=gethostbyaddr($uipproxy);
$utime=time();
$month=$utime-2592000; // (in seconds)
@mysql_query("delete from useronlinemonth where timevisit<$month");
$qw = mysql_query("SELECT count(id) as total FROM useronlinemonth WHERE ipproxy = '$uipproxy'");
$countdataquery = mysql_fetch_assoc($qw);
$uexists= $countdataquery['total'];
if ($uexists>0){
@mysql_query("update useronlinemonth set timevisit='$utime' where ipproxy='$uipproxy'");
} else {
@mysql_query("insert into useronlinemonth (ipproxy,host,ipanda,proxyserver,timevisit) values ('$uipproxy','$uhost','$uipanda','$uproxyserver','$utime')");
}

//$rs=@mysql_query("select * from useronlinemonth");
$qw = mysql_query("SELECT count(id) as total FROM useronlinemonth");
$countdataquery = mysql_fetch_assoc($qw);
$jmlonline= $countdataquery['total'];
return "<b>$jmlonline</b>";



}

?>