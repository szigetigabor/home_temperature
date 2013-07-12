<script type="text/javascript" src="prototype.js"></script>
<script type="text/javascript"> new Ajax.PeriodicalUpdater('clock', 'clock.php', {   method: 'get',   frequency: 1 });</script>

<div id="clock"></div>

<div id='cssmenu'>
<ul>
   <li class='active '><a href='home.php'><span>Home</span></a></li>
   <li class='has-sub '><a href='#'><span>Settings</span></a>
      <ul>
         <li><a href='temperature.php'><span>Temperature</span></a></li>
         <li><a href='mode.php'><span>Mode</span></a></li>
      </ul>
   </li>
   <li class='has-sub '><a href='#'><span>Graphs</span></a>
      <ul>
         <li><a href='temp_graph.php'><span>Temperature</span></a></li>
         <li><a href='lux_graph.php'><span>LUX</span></a></li>
         <li><a href='hw_graph.php'><span>Hardware Monitoring</span></a></li>
      </ul>
   </li>
<?php
require_once('includes.php');

if (isIPIn($ip, $net, $mask)) {
?>
   <li class='has-sub '><a href='#'><span>Admin</span></a>
   <ul>
      <li><a href='alias.php'><span>Alias</span></a></li>
      <li><a href='lang.php'><span>Language</span></a></li>
      <li><a href='timezones.php'><span>TimeZones</span></a></li>
      <li class='has-sub '><a href='#'><span>Mode<div id="menu_arrow">></div></span></a>
         <ul>
            <li><a href='add_mode.php'><span>Add Mode</span></a></li>
            <li><a href='add_mode.php?mode'><span>Set Mode</span></a></li>
            <li><a href='delete_mode.php'><span>Delete Mode</span></a></li>
         </ul>
      </li>
      <li class='has-sub '><a href='#'><span>Switch<div id="menu_arrow">></div></span></a>
        <ul>
          <li><a href='switch.php'><span>Temperature mapping</span></a></li>
          <li><a href='switch_status.php'><span>Switch status</span></a></li>
        </ul>
      </li>
      <li><a href='adc_status.php'><span>ADC status</span></a></li>
      <li><a href='color.php'><span>Color mapping</span></a></li>
   </ul>
   </li>
<?php
}
?>
   <li><a href='#'><span>Contact</span></a></li>
</ul>
</div>
<p>

<?php
/*
<table id="menu">
  <tr id="menu">
     <td id="menu_temp"><a href="temperature.php" class="buttonclass">Temperature</a></td>
     <td id="menu_alias"><a href="alias.php" class="buttonclass">Alias</a></td>
     <td id="menu_switch"><a href="switch.php" class="buttonclass">Switch </a></td>
  </tr>
</table>
<hr />
*/
?>
