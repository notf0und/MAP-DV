<?php 
$printer = "/dev/lp0"; 
if($ph = printer_open($printer)) 
{ 
   $data = " PRINT THIS ";  
   // Cut Paper
   $data .= "\x00\x1Bi\x00";
   printer_write($ph, $data); 
   printer_close($ph); 
} 

?>
