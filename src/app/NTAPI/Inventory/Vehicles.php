<?php

namespace Newtech\NTAPIBridge\NTAPI\Inventory;

use Newtech\NTAPIBridge\Models\NTAPIModel;

class Vehicles extends NTAPIModel
{
    protected $path = "/v2/inventory/vehicles";

    public static function get($id, $meta = [])
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::url() . "/" . $meta['storeNumber'] . "/" . $meta['stockNumber'] . "/" . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = $data["data"];

        return new self($id, $data, $meta);
    }

    public static function all($meta = [])
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::url() . "/" . $meta['storeNumber'], [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = $data["data"];
        $collection = collect([]);
        foreach ($data as $index => $data_map) {
            $collection->push(new self($index, $data_map, $meta));
        }
        return $collection;
    }

    public static function getTestVehicle() {
        return [
              'general' => 
              [
                'flag' => 
                [
                  'doNotExport' => false,
                ],
              ],
              'store' => 
              [
                'number' => '15212',
              ],
              'stockNumber' => '100',
              'status' => 
              [
                'inInventory' => true,
                'added' => 
                [
                  'date' => '2018-10-02 04:00:00',
                  'reason' => '',
                  'reference' => '',
                ],
                'removed' => 
                [
                  'date' => '2020-04-16 00:31:14',
                  'reason' => 'Removed by Stock API',
                  'reference' => '',
                ],
                'vehicleStatus' => '',
                'location' => 'Sales Inventory',
                'saleType' => 'Retail',
                'specialFinanceEligible' => false,
              ],
              'acquired' => 
              [
                'buyer' => 
                [
                  'name' => '',
                  'reference' => '',
                ],
                'broker' => 
                [
                  'name' => '',
                  'reference' => '',
                  'fee' => 0,
                ],
                'floorPlan' => 
                [
                  'vendor' => 
                  [
                    'name' => '',
                    'reference' => '',
                  ],
                  'balance' => '',
                ],
              ],
              'newUsed' => 'New',
              'certification' => 
              [
                'certified' => false,
                'number' => '',
              ],
              'pricing' => 
              [
                'unit' => 'USD',
                'retail' => 42325,
                'internet' => 0,
                'wholesale' => 0,
              ],
              'cost' => 
              [
                'unit' => 'USD',
                'invoice' => 38000,
                'inspection' => 0,
                'reconditioning' => 0,
                'holdback' => 0,
                'actualCost' => 0,
                'adjustments' => 
                [
                  0 => 
                  [
                    'description' => 'Open RO',
                    'value' => 0,
                    'date' => '',
                    'unit' => 'USD',
                  ],
                ],
              ],
              'odometer' => 
              [
                'in' => 
                [
                  'value' => 0,
                  'unit' => 'Miles',
                ],
                'out' => 
                [
                  'value' => 0,
                  'unit' => 'Miles',
                ],
              ],
              'vehicle' => 
              [
                'vin' => '1M2P268C1TM001726',
                'year' => 2019,
                'make' => 'Jeep',
                'modelCode' => '',
                'model' => 'Grand Cherokee',
                'trim' => 'Altitude',
                'category' => '',
                'bodyStyle' => 'SUV',
                'bodyType' => '',
                'marketClass' => '',
                'condition' => 
                [
                  'status' => '',
                  'notes' => 
                  [
                  ],
                ],
                'exterior' => 
                [
                  'color' => 
                  [
                    'name' => 'DIAMOND BLACK CRYSTAL P/C',
                    'mfgCode' => '',
                    'rgbValue' => '',
                    'genericColor' => '',
                  ],
                ],
                'interior' => 
                [
                  'color' => 
                  [
                    'name' => 'BLACK',
                    'mfgCode' => '',
                    'rgbValue' => '',
                    'genericColor' => '',
                  ],
                  'type' => '',
                ],
                'mileage' => 
                [
                  'value' => 10,
                  'unit' => 'Miles',
                ],
                'media' => 
                [
                  'images' => 
                  [
                    array(
                        'dynamic' => array(
                            'url' => "https://www.autoblog.com/img/research/styles/photos/electric.jpg"
                        )
                    )
                  ],
                  'videos' => 
                  [
                  ],
                ],
                'reference' => 
                [
                  'NADA.UGC' => '201937554',
                ],
                'engine' => 
                [
                  'cylinder' => '6',
                  'displacement' => 
                  [
                    'value' => 0,
                    'type' => '',
                  ],
                  'description' => '',
                  'power' => 
                  [
                    'horsepower' => 0,
                    'rpm' => 0,
                  ],
                ],
                'drivetrain' => 
                [
                  'type' => '4WD',
                  'transmission' => 
                  [
                    'type' => '',
                    'speed' => '',
                    'description' => '8 Speed Automatic',
                  ],
                ],
                'manufacturer' => 
                [
                  'id' => '',
                  'country' => '',
                  'sequenceNumber' => 0,
                  'modelNumber' => '',
                  'pricing' => 
                  [
                    'unit' => '',
                    'msrp' => 42325,
                    'invoice' => 0,
                    'destinationCharge' => 0,
                    'custom' => 
                    [
                    ],
                  ],
                  'warranty' => 
                  [
                    'basic' => 
                    [
                      'duration' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                      'distance' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                    ],
                    'powertrain' => 
                    [
                      'duration' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                      'distance' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                    ],
                    'rust' => 
                    [
                      'duration' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                      'distance' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                    ],
                  ],
                ],
                'fuel' => 
                [
                  'type' => 'Gasoline',
                  'capacity' => 
                  [
                    'value' => 0,
                    'unit' => '',
                  ],
                  'unit' => '',
                  'economy' => 
                  [
                    'city' => 
                    [
                      'min' => 0,
                      'max' => 0,
                      'description' => '',
                      'unit' => '',
                    ],
                    'highway' => 
                    [
                      'min' => 0,
                      'max' => 0,
                      'description' => '',
                      'unit' => '',
                    ],
                  ],
                ],
                'standardFeatures' => 
                [
                ],
                'optionalEquipments' => 
                [
                ],
                'trait' => 
                [
                  'weight' => 
                  [
                    'value' => 0,
                    'unit' => '',
                  ],
                  'dimensions' => 
                  [
                    'overall' => 
                    [
                      'length' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                      'width' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                      'height' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                    ],
                    'bed' => 
                    [
                      'length' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                      'width' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                      'height' => 
                      [
                        'value' => 0,
                        'unit' => '',
                      ],
                    ],
                    'groundClearance' => 
                    [
                      'value' => 0,
                      'unit' => '',
                    ],
                    'wheelbase' => 
                    [
                      'value' => 0,
                      'unit' => '',
                    ],
                  ],
                  'payload' => 
                  [
                    'standard' => 
                    [
                      'value' => 0,
                      'unit' => '',
                    ],
                    'maximum' => 
                    [
                      'value' => 0,
                      'unit' => '',
                    ],
                  ],
                  'towing' => 
                  [
                    'standard' => 
                    [
                      'value' => 0,
                      'unit' => '',
                    ],
                    'maximum' => 
                    [
                      'value' => 0,
                      'unit' => '',
                    ],
                  ],
                  'gvwr' => 
                  [
                    'standard' => 
                    [
                      'value' => 0,
                      'unit' => '',
                    ],
                    'maximum' => 
                    [
                      'value' => 0,
                      'unit' => '',
                    ],
                  ],
                ],
                'description' => 
                [
                ],
              ],
              '_meta' => 
              [
                'storeNumber' => '15212',
                'stockNumber' => '100',
              ],
            ];
    }
}