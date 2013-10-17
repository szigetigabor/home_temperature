<?php
require("color.inc.php");
include 'menu.php';

?>
<center>
<table>
<thead>
<tr>
<th>Celsius</th>
<th>Color indoor in hex</th>
<th>Color outdoor in hex</th>
</tr>
</thead>

<?php

for($i=-10;$i<40;$i++){
  $colorin =temp2farbeindoor($i);
  $colorout=temp2farbeoutdoor($i);
#  var_dump(temp2farbe($i));

  echo " <tr>";
  echo " <td>$i</td>";
  echo " <td bgcolor=\"#$colorin\">";
  echo " <td bgcolor=\"#$colorout\">";
  echo " $color";
  echo " </td>";
  echo " </tr>";  
}
?>

</table>
</center>
