<?

function temp2farbe($temperatur)
{
  $anzahl = 10;
  $colors[0] = "022887"; $temp[0] = -20;
  $colors[1] = "326DE1"; $temp[1] = -10;
  $colors[2] = "80A7FD"; $temp[2] = 0;
  $colors[3] = "A4EE27"; $temp[3] = 5;
  $colors[4] = "FEEC00"; $temp[4] = 10;
  $colors[5] = "FFBA01"; $temp[5] = 15;
  $colors[6] = "FF7A01"; $temp[6] = 20;
  $colors[7] = "F62304"; $temp[7] = 25;
  $colors[8] = "C40305"; $temp[8] = 30;
  $colors[9] = "8D0000"; $temp[9] = 40;

  for ($i=0;$i<$anzahl;$i++)
  {
    if (($temperatur >= $temp[$i]) && ($temperatur < $temp[$i+1]))
    {
      $color1 = $colors[$i];
      $color2 = $colors[$i+1];
      $temp1 = $temp[$i];
      $temp2 = $temp[$i+1];
    }
  }
  
  $perc = ($temperatur - $temp1)/($temp2-$temp1);

  return farbinterpol($color1, $color2, $perc);
}

function farbinterpol($color1, $color2, $perc)
{
  $rgb1 = hex2rgb($color1);
  $rgb2 = hex2rgb($color2);

  for($i=0;$i<=2;$i++)
  {
    $rgb1[$i] += ($rgb2[$i] - $rgb1[$i]) * $perc;
  }
  
  $color1 = str_pad(dechex($rgb1[0]), 2, '0', STR_PAD_LEFT);
  $color1 .= str_pad(dechex($rgb1[1]), 2, '0', STR_PAD_LEFT);
  $color1 .= str_pad(dechex($rgb1[2]), 2, '0', STR_PAD_LEFT); 
 
  return $color1;
}

function hex2rgb ($hex)
{
  $rgb = array();
  $rgb[0] = hexdec ($hex[0] . $hex[1]);
  $rgb[1] = hexdec ($hex[2] . $hex[3]);
  $rgb[2] = hexdec ($hex[4] . $hex[5]);
  return $rgb;
}

?>
