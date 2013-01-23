<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Brad and Joey
 * Date: 1/22/13
 */
class calc
{
    public static function solar($lat, $lon)
    {

        if ($lat >= 0) {
            $latdir = "N";
            $point = "South";
        } else {
            $latdir = "S";
            $point = "North";
            $lat = abs($lat);
        }

        if ($lon >= 0) {
            $londir = "E";
        } else {
            $londir = "W";
            $lon = abs($lon);
        }

        if ($lat < 25) {
            $optimal = round(abs($lat * .87),1);
        } elseif ($lat <50) {
            $optimal = round(abs($lat * 0.89 + 24),1);
        } else {
            $optimal = round(abs($lat + 24),1);
        }
        $output = "Latitude: " .$lat . $latdir . " Longitude: " .$lon . $londir . "<br />";
        $output .= "Optimal Tilt: " . $optimal . " degrees from horizonal<br />";
        $output .= "Point the Panel to the " . $point . "<br />";

        $lat_round_dec = $lat - intval($lat);
        $lat_round_dec = round($lat_round_dec-.05,1) +.05;
        $lat_lookup = (intval($lat) + $lat_round_dec) * 100;

        $lon_round_dec = $lon - intval($lon);
        $lon_round_dec = round($lon_round_dec-.05,1)+ .05 ;
        $lon_lookup = (intval($lon) + $lon_round_dec) * 100;
        $gridcode = $lon_lookup . $lat_lookup;

        $potential = solar_potential::where('gridcode', '=', $gridcode)->first();
        if ($potential == null or $lon > -65 or $lon < -161 or $lat < 25 or $lat > 50 ) {
            $output .= "Unable to Calculate Solar Potential<br />";
        } else {
            $avg = round($potential->avg,2);
            $min = round($potential->min,2);
            $output .= "Average Potential: " . $avg . "kWh/m^2/day<br />";
            $output .= "Minimum Potential: " . $min . "kWh/m^2/day<br />";
            if ($avg > 5.5) {
                $output .= "Great Solar Potential! <br />";
            }
        }
        $response = Response::make($output, 200);
        return $response;
    }
}
