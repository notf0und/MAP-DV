<?php
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
// log in at server1.example.com on port 22
if(!($con = ssh2_connect("localhost", 22))){
    echo "fail: unable to establish connection\n";
} else {
    // try to authenticate with username root, password secretpassword
    if(!ssh2_auth_password($con, "sistemas", "Maxell")) {
        echo "fail: unable to authenticate\n";
    } else {
        // allright, we're in!
        echo "okay: logged in...\n";
        // execute a command
        if (!($stream = ssh2_exec($con, "echo Da Vinci Joao Fermandes > /dev/lp0" ))) {
            echo "fail: unable to execute command\n";
        } else {
			ssh2_exec($con, "echo --- > /dev/lp0");
			ssh2_exec($con, "echo Data: 2013-06-08 14:13:09 > /dev/lp0");
			ssh2_exec($con, "echo Nome do Pax: Juan Martin Dominguez X 2 > /dev/lp0");
			ssh2_exec($con, "echo --- > /dev/lp0");
			ssh2_exec($con, "echo Pousada: Alegravila > /dev/lp0");
			ssh2_exec($con, "echo Operador: TOP DEST > /dev/lp0");
			ssh2_exec($con, "echo Num. de Voucher: 1324 > /dev/lp0");
			ssh2_exec($con, "echo --- > /dev/lp0");
			ssh2_exec($con, "echo --- > /dev/lp0");
			ssh2_exec($con, "echo Mensaje gar".chr(231)."on: Comida sin sal > /dev/lp0");
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
        }
    }
}
?>


<?php
/*
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo Da Vinci Joao Fermandes > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo --- > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo Data: 2013-06-08 14:13:09 > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo Nome do Pax: Juan Martin Dominguez X 2 > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo --- > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo Pousada: Alegravila > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo Operador: TOP DEST > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo Num. de Voucher: 1324 > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo --- > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo --- > /dev/lp0');
$salida = shell_exec('echo "Maxell" | sudo -u root -S echo Mensaje gar'.chr(231).'on: Comida sin sal > /dev/lp0');
echo $salida;
*/
?>



<?php
/*
    echo shell_exec('echo éêèëàâäîïùûüôöç');
    // The output of this will be something like: ‚ˆŠ‰…ƒ„Œ‹—–“”‡
    // Not quite what was expected...

    // This is the function that fixes accented characters.
    function fix_string($str) {
        return strtr($str,
            chr(130).chr(136).chr(138).chr(137).
            chr(133).chr(131).chr(132).chr(140).
            chr(139).chr(151).chr(150).chr(129).
            chr(147).chr(148).chr(135),
            chr(233).chr(234).chr(232).chr(235).
            chr(224).chr(226).chr(228).chr(238).
            chr(239).chr(249).chr(251).chr(252).
            chr(244).chr(246).chr(231)
        );
    }

    echo fix_string(shell_exec('echo éêèëàâäîïùûüôöç'));
    // We now get a proper output!
*/
?>
