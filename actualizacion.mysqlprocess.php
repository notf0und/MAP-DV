<?php 
include "lib/sessionLib.php";
$script_name = $_SERVER['SCRIPT_NAME'];

if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
// log in at server1.example.com on port 22
if(!($con = ssh2_connect("localhost", 22))){
    echo "fail: unable to establish connection\n";
} else {
    // try to authenticate with username root, password secretpassword
    if(!ssh2_auth_password($con, $_SESSION["idlocales_PRN_USER"], $_SESSION["idlocales_PRN_PASS"])) {
        echo "fail: unable to authenticate\n";
    } else {
        // allright, we're in!
        echo "okay: logged in...\n";
        // execute a command
        if (!(ssh2_exec($con, "mysqldump --opt -u newuser -psome_pass DA_SERVER > /tmp/dasamericasBackup.sql"))) {
            echo "fail: unable to execute command\n";
        } else {
            echo "execute command\n <br>";
            echo "Backup OK <br>";
            
            $stream = ssh2_scp_recv($con, '/tmp/dasamericasBackup.sql', '/tmp/DA/dasamericasBackup222.sql');
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
            echo "Transferencia: ".$stream."<br>";
            echo "Documento recibido OK <br>";
            
			$restore = ssh2_exec($con, "mysql -u newuser -psome_pass DA_JF < /tmp/DA/dasamericasBackup222.sql");
            var_dump($restore);
            echo "Documento actualizado OK <br>";
/*
			$delete = ssh2_exec($con, "rm /tmp/DA/dasamericasBackup222.sql");
            echo "Documento Deleted OK <br>";
*/
        }
    }
}
?>
