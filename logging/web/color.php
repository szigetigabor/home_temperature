<?php
require("color.inc.php");
include 'menu.php';

?>
<center>
<table>
<thead>
<tr>
<th>Celsius</th>
<th>Color in hex</th>
</tr>
</thead>

<?php

for($i=-10;$i<40;$i++){
  $color=temp2farbe($i);
#  var_dump(temp2farbe($i));

  echo " <tr>";
  echo " <td>$i</td>";
  echo " <td bgcolor=\"#$color\">";
  echo " $color";
  echo " </td>";
  echo " </tr>";  
}
?>

</table>
</center>
