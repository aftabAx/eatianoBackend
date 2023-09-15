<?php

function haversineGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
}

 $distance = haversineGreatCircleDistance(25.178577,88.246117, 25.077990, 87.897942);
 // echo $distance;



        
      function twopoints_on_earth($latitudeFrom, $longitudeFrom,
                                    $latitudeTo,  $longitudeTo)
      {
           $long1 = deg2rad($longitudeFrom);
           $long2 = deg2rad($longitudeTo);
           $lat1 = deg2rad($latitudeFrom);
           $lat2 = deg2rad($latitudeTo);
             
           //Haversine Formula
           $dlong = $long2 - $long1;
           $dlati = $lat2 - $lat1;
             
           $val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2);
             
           $res = 2 * asin(sqrt($val));
             
           $radius = 3958.756;
             
           return ($res*$radius);
      }
 
      // latitude and longitude of Two Points
      $latitudeFrom = 19.017656 ;
      $longitudeFrom = 72.856178;
      $latitudeTo = 40.7127;
      $longitudeTo = -74.0059;
        
      // Distance between Mumbai and New York 25.00387395395857, 88.13107569733778, 
      $distance = twopoints_on_earth(25.00387395395857, 88.13107569733778, 25.076093048765753, 87.90278145501112);
      echo $km = 1.6 *  $distance;
 
// This code is contributed by akash1295
// https://auth.geeksforgeeks.org/user/akash1295/articles
?>