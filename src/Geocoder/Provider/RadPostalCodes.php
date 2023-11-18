<?php
//
//namespace Drupal\radicati_postal_geocoder\Geocoder\Provider;
//
//use Geocoder\Exception\NoResult;
//use Geocoder\Exception\UnsupportedOperation;
//use Geocoder\Provider\AbstractProvider;
//use Geocoder\Provider\Provider;
//
///**
// * Provides a file handler to be used by 'file' plugin.
// */
//class RadPostalCodes extends AbstractProvider implements Provider {
//
//  /**
//   * {@inheritdoc}
//   */
//  public function getName(): string {
//    return 'rad_postal_codes';
//  }
//
//  public function geocodeQuery($input) : \Geocoder\Collection {
//
//      return $this->geocode($input->getText());
//  }
//
//  public function reverseQuery($input) : \Geocoder\Collection {
//      return null;
//
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public function geocode($input) {
//    $latitude = null;
//    $longitude = null;
//
//    // Look for five numbers together in the input. If there aren't any, bail, it can't be a zip code.
//    $re = '/\d{5}/m';
//    preg_match_all($re, $input, $matches, PREG_SET_ORDER, 0);
//
//    if(count($matches) === 0) {
//      throw new NoResult(sprintf('Could not find a postal code in: "%s".', $input));
//    } else {
//
//      $input = $matches[count($matches) - 1][0];
//
//      $fp = fopen(drupal_get_path('module', 'radicati_postal_geocoder') . '/data/us_postal_codes.csv', 'r');
//
//      while (($row = fgetcsv($fp, 0, ",")) !== FALSE) {
//        if($row[0] == $input) {
//          $latitude = $row[5];
//          $longitude = $row[6];
//
//          // Immediately return the results
//          return $this->returnResults([[
//              'latitude' => $latitude,
//              'longitude' => $longitude,
//            ] + $this->getDefaults(),
//          ]);
//        }
//      }
//
//      // If we get here, no result was found
//      throw new NoResult(sprintf('Postal code not in location data: "%s".', $input));
//    }
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public function reverse($latitude, $longitude) {
//    throw new UnsupportedOperation('The File plugin is not able to do reverse geocoding.');
//  }
//
//}
