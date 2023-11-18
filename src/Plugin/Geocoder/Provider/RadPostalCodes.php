<?php

namespace Drupal\radicati_postal_geocoder\Plugin\Geocoder\Provider;

use Drupal\geocoder\ProviderBase;
use Drupal\geocoder\ProviderUsingHandlerBase;
use Geocoder\Model\Address;
use Geocoder\Model\AddressCollection;
use Geocoder\Model\AdminLevelCollection;

/**
 * Provides a File geocoder provider plugin.
 *
 * @GeocoderProvider(
 *   id = "postal_code_us_",
 *   name = "Postal Code (US)",
 * )
 */
class RadPostalCodes extends ProviderBase {


  protected function doGeocode($source) {
    // Look for five numbers together in the input. If there aren't any, bail, it can't be a zip code.
    $re = '/\d{5}/m';
    preg_match_all($re, $source, $matches, PREG_SET_ORDER, 0);

    // If we can't find a zip in the input, bail out.
    if(count($matches) === 0) {
      throw new NoResult(sprintf('Could not find a postal code in: "%s".', $source));
    }

    // We want the last five-digit number, in case someone entered a street address
    $input = $matches[count($matches) - 1][0];

    $result = $this->getCoordsFromZip($input);

    if(empty($result)) {
      // TODO: Is throwing an exception really the best way of handling this?

      throw new NoResult(sprintf('Could not find a postal code in: "%s".', $source));
    }

    $address = new Address("", new AdminLevelCollection());
    $result = $address->createFromArray($result);

    return new AddressCollection(
      [
        $result
      ]
    );
  }

  protected function doReverse($latitude, $longitude) {
    throw new UnsupportedOperation('The File plugin is not able to do reverse geocoding.');
  }


  private function getCoordsFromZip($zip) {
      $fp = fopen(drupal_get_path('module', 'radicati_postal_geocoder') . '/data/us_postal_codes.csv', 'r');

      while (($row = fgetcsv($fp, 0, ",")) !== FALSE) {
        if($row[0] == $zip) {
          $latitude = $row[5];
          $longitude = $row[6];

          // Immediately return the results
          return [
              'latitude' => $latitude,
              'longitude' => $longitude,
            ];
        }
      }

      // If we get here, no result was found
      throw new NoResult(sprintf('Postal code not in location data: "%s".', $input));
  }
}
