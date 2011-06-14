<?

session_start();



function remove_comments(&$output)
{
    $lines = explode("\n", $output);
    $output = "";
    // try to keep mem. use down
    $linecount = count($lines);

    $in_comment = false;
    for($i = 0; $i < $linecount; $i++) {
        if (preg_match("/^\/\*/", preg_quote($lines[$i]))) {
            $in_comment = true;
        }

        if (!$in_comment) {
            $output .= $lines[$i] . "\n";
        }

        if (preg_match("/\*\/$/", preg_quote($lines[$i]))) {
            $in_comment = false;
        }
    }

    unset($lines);
    return $output;
}

// remove_remarks will strip the sql comment lines out of an uploaded sql file

function remove_remarks($sql)
{
    $lines = explode("\n", $sql);
    // try to keep mem. use down
    $sql = "";

    $linecount = count($lines);
    $output = "";

    for ($i = 0; $i < $linecount; $i++) {
        if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
            if ($lines[$i][0] != "#") {
                $output .= $lines[$i] . "\n";
            } else {
                $output .= "\n";
            }
            // Trading a bit of speed for lower mem. use here.
            $lines[$i] = "";
        }
    }

    return $output;
}

// split_sql_file will split an uploaded sql file into single sql statements.
// Note: expects trim() to have already been run on $sql.

function split_sql_file($sql, $delimiter)
{
    // Split up our string into "possible" SQL statements.
    $tokens = explode($delimiter, $sql);
    // try to save mem.
    $sql = "";
    $output = array();
    // we don't actually care about the matches preg gives us.
    $matches = array();
    // this is faster than calling count($oktens) every time thru the loop.
    $token_count = count($tokens);
    for ($i = 0; $i < $token_count; $i++) {
        // Don't wanna add an empty string as the last thing in the array.
        if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
            // This is the total number of single quotes in the token.
            $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
            // Counts single quotes that are preceded by an odd number of backslashes,
            // which means they're escaped quotes.
            $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

            $unescaped_quotes = $total_quotes - $escaped_quotes;
            // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
            if (($unescaped_quotes % 2) == 0) {
                // It's a complete sql statement.
                $output[] = $tokens[$i];
                // save memory.
                $tokens[$i] = "";
            } else {
                // incomplete sql statement. keep adding tokens until we have a complete one.
                // $temp will hold what we have so far.
                $temp = $tokens[$i] . $delimiter;
                // save memory..
                $tokens[$i] = "";
                // Do we have a complete statement yet?
                $complete_stmt = false;

                for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
                    // This is the total number of single quotes in the token.
                    $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                    // Counts single quotes that are preceded by an odd number of backslashes,
                    // which means they're escaped quotes.
                    $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

                    $unescaped_quotes = $total_quotes - $escaped_quotes;

                    if (($unescaped_quotes % 2) == 1) {
                        // odd number of unescaped quotes. In combination with the previous incomplete
                        // statement(s), we now have a complete statement. (2 odds always make an even)
                        $output[] = $temp . $tokens[$j];
                        // save memory.
                        $tokens[$j] = "";
                        $temp = "";
                        // exit the loop.
                        $complete_stmt = true;
                        // make sure the outer loop continues at the right point.
                        $i = $j;
                    } else {
                        // even number of unescaped quotes. We still don't have a complete statement.
                        // (1 odd and 1 even always make an odd)
                        $temp .= $tokens[$j] . $delimiter;
                        // save memory.
                        $tokens[$j] = "";
                    }
                } // for..
            } // else
        }
    }

    return $output;
}

function conn(){
$database = $_SESSION['database'];
if (!@mysql_connect($_SESSION['hostname'],$_SESSION['username'],$_SESSION['password'])){
session_destroy();
echo '<a href="'.$_SERVER['PHP_SELF'].'">'.$_SERVER['PHP_SELF'].'</a>';
exit;
}
if (!@mysql_select_db($database)){
session_destroy();
echo '<a href="'.$_SERVER['PHP_SELF'].'">'.$_SERVER['PHP_SELF'].'</a>';
exit;
}

	
}



if (isset($_POST['hostname'],$_POST['username'],$_POST['password'],$_POST['database'])){
	
$_SESSION['hostname'] = $_POST['hostname'];
$_SESSION['username'] = $_POST['username'];
$_SESSION['password'] = $_POST['password'];
$_SESSION['database'] = $_POST['database'];
$_SESSION['created'] = true;	
	
	
	
}



echo <<<html
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
 <head>
  <title>Backup SQL</title>
 </head>
 <style>
 body,p,input,select{
	 font-family:verdana,tahoma;
	 font-size:9px;
 }
 textarea {
font-family:"Courier New";
font-size:12px;	 
 }
 </style>
 <body>
html;






if(!session_is_registered('created')){
	echo '<form method="post" action=""><br />Hostname :<br /><input type="text" name="hostname" value=""><br />Username :<br /><input type="text" name="username" value=""><br />Password :<br /><input type="text" name="password" value=""><br />Dbname :<br /><input type="text" name="database" value=""><br /><input type="submit" name="submit" value="Submit" /></form>
';
	exit;	
}


if (isset($_POST['sql'])){
conn();
	

$output = remove_remarks(stripslashes($_POST['sql']));
$data = split_sql_file($output, ';');

for ($i=0; $i<count($data); $i++){
	
 $qid = mysql_query ($data[$i]);	
 if (!$qid){
	echo mysql_error(); 
	echo '<br />';
 }else {
	echo mysql_affected_rows(); 
	echo '<br />';
 }
	
}
	
}


echo '<form method="post" action=""><textarea name="sql" rows="10" cols="50"></textarea><br /> <input type="submit" name="login" value="login" /></form>';







echo '</body>
</html>';



?>