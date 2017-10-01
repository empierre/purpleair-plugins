<?
$type = getArg('type');                        
$val  = getArg('val'); 

if ($type=="1") {
        //echo "type 1".'http://'.$val.'/json';
        $json_pa = jsonToXML(httpQuery('http://'.$val.'/json'));
        $pm2_5_atm              =  xpath($json_pa,'/root/pm2_5_atm');
        $pm10_0_atm             =  xpath($json_pa,'/root/pm10_0_atm');
        $pm1_0_atm              =  xpath($json_pa,'/root/pm1_0_atm');
        $pressure               =  xpath($json_pa,'/root/pressure');
        $current_humidity       =  xpath($json_pa,'/root/current_humidity');
        $current_temp_f         =  xpath($json_pa,'/root/current_temp_f');
        $current_temp_c         =  round(($current_temp_f-32)*.5556);
       
  	$xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<purpleair>';
        $xml .= '<AQIShortTerm>'.sdk_aqiFromPM($pm2_5_atm).'</AQIShortTerm>';
        $xml .= '<AQIrank>'.sdk_getAQIrank($pm2_5_atm).'</AQIrank>';
        $xml .= '</purpleair>'; 
        echo $xml;
} else {
        echo "type 3";
        $pm2_5_atm              =  xpath($json_pa,'/root/results/PM2_5Value');
        $pm10_0_atm             =  xpath($json_pa,'/root/pm10_0_atm');
        $pm1_0_atm              =  xpath($json_pa,'/root/pm1_0_atm');
        $pressure               =  xpath($json_pa,'/root/results/pressure');
        $current_humidity       =  xpath($json_pa,'/root/results/humidity');
        $current_temp_f         =  xpath($json_pa,'/root/results/temp_f');
        echo $current_temp_f;

}
function sdk_calcAQI($Cp, $Ih, $Il, $BPh, $BPl) {

        $a = ($Ih - $Il);
        $b = ($BPh - $BPl);
        $c = ($Cp - $BPl);
        return round(($a/$b) * $c + $Il);

      }
      
 function sdk_aqiFromPM($pm) {
      //if (isNaN($pm)) return "-";
      if ($pm == undefined) return "-";
      if ($pm < 0) return $pm;
      if ($pm > 1000) return "-";
        /*
        Good                              0 - 50        0.0 - 15.0      0.0 – 12.0
        Moderate                        51 - 100        >15.0 - 40      12.1 – 35.4
        Unhealthy for Sensitive Groups 101 – 150        >40 – 65        35.5 – 55.4
        Unhealthy                      151 – 200        > 65 – 150      55.5 – 150.4
        Very Unhealthy                 201 – 300        > 150 – 250     150.5 – 250.4
        Hazardous                      301 – 400        > 250 – 350     250.5 – 350.4
        Hazardous                      401 – 500        > 350 – 500     350.5 – 500
        */
        if ($pm > 350.5) {
          return sdk_calcAQI($pm, 500, 401, 500, 350.5);
        } else if ($pm > 250.5) {
          return sdk_calcAQI($pm, 400, 301, 350.4, 250.5);
        } else if ($pm > 150.5) {
          return sdk_calcAQI($pm, 300, 201, 250.4, 150.5);
        } else if ($pm > 55.5) {
          return sdk_calcAQI($pm, 200, 151, 150.4, 55.5);
        } else if ($pm > 35.5) {
          return sdk_calcAQI($pm, 150, 101, 55.4, 35.5);
        } else if ($pm > 12.1) {
          return sdk_calcAQI($pm, 100, 51, 35.4, 12.1);
        } else if ($pm >= 0) {
          return sdk_calcAQI($pm, 50, 0, 12, 0);
        } else {
               return undefined;
        }
      }
function sdk_getAQIDescription($aqi) {
        if ($aqi >= 401) {
          return 'Hazardous';
        } else if ($aqi >= 301) {
          return 'Hazardous';
        } else if ($aqi >= 201) {
          return 'Very Unhealthy';
        } else if ($aqi >= 151) {
          return 'Unhealthy';
        } else if ($aqi >= 101) {
          return 'Unhealthy for Sensitive Groups';
        } else if ($aqi >= 51) {
          return 'Moderate';
        } else if ($aqi >= 0) {
          return 'Good';
        } else {
          return undefined;
        }
      }
function sdk_getAQIrank($aqi) {
        if ($aqi >= 401) {
          return 7;
        } else if ($aqi >= 301) {
          return 6;
        } else if ($aqi >= 201) {
          return 5;
        } else if ($aqi >= 151) {
          return 4;
        } else if ($aqi >= 101) {
          return 3;
        } else if ($aqi >= 51) {
          return 2;
        } else if ($aqi >= 0) {
          return 1;
        } else {
          return undefined;
        }
      }
?>
