<?php
//  <title>Traffic and load RRD stats</title>
echo("  <script type=\"text/javascript\" src=\"js/CalendarPopup.js\"></script>");
echo("  <script type=\"text/javascript\">document.write(getCalendarStyles());</script>");
echo "<style>";
echo "        form {";
echo "        padding:10px;";
echo "        margin:0px;";
echo "        background-color:#FFF9D8;";
echo"        }";
echo "</style>";



?>
<center>
<table>
  <tr><td>
  <form action=rrd.php method=get>
    <script type="text/javascript" ID="jscal1xx">
        var cal1xx = new CalendarPopup("testdiv1");
        cal1xx.setWeekStartDay(1);
        cal1xx.showNavigationDropdowns();
    </script>


<?php
echo "<label for=\"start_date\">Start date:</label>";
    if (isset($_GET["start_date"])) {
      $start=$_GET["start_date"];
    } else {
      $start=date("Y-m-d");
    }
    echo "<input color:#FFFFFF type=\"text\" name=start_date size=10 id=\"my_date\" value=$start>";
?>
  
  <input type=button name=sbutton id=sbutton value="Select date" onClick="cal1xx.select(document.forms[0].start_date,'sbutton','yyyy-MM-dd'); return false;"/>
<br>
<?php
echo "<label for=\"end_date\">End date: &nbsp</label>";
    if (isset($_GET["end_date"])) {
      $end=$_GET["end_date"];
    } else {
      $end="";
    }
    echo "<input color:#FFFFFF type=\"text\" name=end_date size=10 id=\"my_date\" value=$end>";
?>

  <input type=button name=sbutton id=sbutton value="Select date" onClick="cal1xx.select(document.forms[0].end_date,'sbutton','yyyy-MM-dd'); return false;"/>

  <input type=submit accesskey=c name=ACTION value="Show stat">

<br>

      <DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
    </form>
  </td></tr>
</table>
</center>

