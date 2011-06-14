<?
if(ereg("menu.php",$_SERVER['PHP_SELF']))
{header("location:index.php");
die; }

global $koneksi_db;


$hasil = $koneksi_db->sql_query( "SELECT * FROM menu WHERE published=1 ORDER BY ordering" );

while ($data = $koneksi_db->sql_fetchrow($hasil)) {

        $parent= $data['id'];
        $target= "";
        if (eregi("http://",$data['url'])) $target="target=_blank";
        $link_menu = $data['menu'];


        echo '<h1 class="bg">'.$link_menu.'</h1>';



        $subhasil = $koneksi_db->sql_query( "SELECT * FROM submenu WHERE published=1 AND parent='$parent' ORDER BY ordering" );
        $jmlsub = $koneksi_db->sql_numrows( $subhasil );

       if ($jmlsub>0) {
            echo "<div class='box'><ul>";

            while ($subdata = $koneksi_db->sql_fetchrow($subhasil)) {
                $target="";
                if (eregi("http://",$subdata['url'])) $target="target=\"_blank\"";

                echo '<li><a href="'.$subdata['url'].'" '.$target.' title="'.$subdata[1].'">'.$subdata['menu'].'</a></li>';

            }
          echo "</ul></div>";


        }

}

?>