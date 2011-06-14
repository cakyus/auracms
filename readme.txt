/**
 * AuraCMS v2.3.0
 * auracms.org
 * September 20, 2010 07:30:56 AM 
 * Author: 	Arif Supriyanto     - arif@ayo.kliksini.com  
 *			Iwan Susyanto, S.Si - admin@auracms.org      
 *			Rumi Ridwan Sholeh  - floodbost@yahoo.com    
 * 			http://www.auracms.org
 *			http://www.iwan.or.id
 *			http://www.ridwan.or.id
 */
 
Langkah² Instalansi :
1.  Ekstrak file auracms2.3.0.tar.gz ke dalam folder website anda, contoh konfigurasi disini di extrak ke folder auracms2.3

2.  buat database dan user database, pastikan bahwa user tersebut mempunyai hak access terhadap database yang di buat.

3.  edit file includes/config.php sesuaikan dengan database yang anda buat.
	
	$mysql_user = 'root';
	$mysql_password = '';
	$mysql_database = 'auracms_ver2.3';
	$mysql_host = 'localhost';
	
4.	Jika Anda mengintal di dalam folder "namafolder" maka pada file :
	a. 	config.php
		pada baris ke 33 tulisan 
			$url_situs= 'http://localhost/auracms2.3';
		diganti dengan 
			$url_situs= 'http://localhost/namafolder';
		jika tidak di dalam folder maka di ganti jadi
			$url_situs= 'http://localhost';
			
		pada baris ke 171 tulisan
			$_basedir = $_SERVER["DOCUMENT_ROOT"] . '/auracms2.3/';
		diganti dengan
			$_basedir = $_SERVER["DOCUMENT_ROOT"] . '/namafolder/';
		jika tidak di dalam folder maka diganti dengan
			$_basedir = $_SERVER["DOCUMENT_ROOT"] . '/';
			
	b.	.htaccess
		pada baris ke 2 tulisan 
			RewriteBase /auracms2.3
		diganti dengan
			RewriteBase /namafolder
		jika tidak di dalam folder diganti dengan
			RewriteBase /
		

5.  Masuk ke phpmyadmin, dan dump database auracms melalui phpmyadmin tersebut file database auracms namanya auracms_ver2.3.sql

6.  Sekarang website anda sudah jadi silahkan buka web Anda http://localhost/namafolder.

7. 	Login defaul ke halaman admin adalah :
    	username : admin
    	password : auracms
    silahkan login pada form yang sudah ada
8. 	Selamat bergabung dengan auracms.

9.	catatan bagi anda yang mau memakai themes auracms 2.2.x ke auracms 2.3.x, rubah file theme-1.html menjadi namathemes.html



NB : Saran dan Kritik serta informasi bug sangat kami harapkan dari anda para pemakai AuraCMS ini kirimkan ke :
     1. Arif Supriyanto
   		arif@ayo.kliksini.com

	 2. Iwan Susyanto, S.Si
   		iwansusyanto@yahoo.com
   		081 327 575 145

	 3. Rumi Ridwan Sholeh
   		floodbost@yahoo.com

     


    

 
