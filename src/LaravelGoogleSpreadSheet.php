<?php

namespace Yish\LaravelGoogleSpreadSheet;

use Google_Service_Sheets;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use PulkitJalan\Google\Facades\Google;

class LaravelGoogleSpreadSheet
{
    public $data;

    protected $endpoint;

    protected $result;

    protected $values;
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $sheet_id
     * @param $range
     * @param $unset
     * @param null $scope
     * @return $this
     */
    public function toJson($sheet_id, $range, $unset = [], $scope = null)
    {
        $client = Google::getClient();
        $client->useApplicationDefaultCredentials();

        $client->setScopes(empty($scope) ? Google_Service_Sheets::SPREADSHEETS_READONLY : $scope);
        $client->setAccessType($this->config['access_type']);

        $service = new Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $this->values = $response->getValues();

        $this->result = $this->map($this->values, $unset);

        $this->data = "[" . implode(',', $this->result) . "]";

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function storeTo($path, $disk = 'public')
    {
        return Storage::disk($disk)->put($path, $this->data);
    }

    /**
     * @param $sheet_id
     * @param int $sheet
     * @param string $format
     * @return mixed
     */
    public function toFeed($sheet_id, $sheet = 1, $format = 'json')
    {
        $end_point = "https://spreadsheets.google.com/feeds/cells/{$sheet_id}/{$sheet}/public/values?alt={$format}";

        $client = new Client();

        $response = $client->get($end_point)->getBody();

        return json_decode($response, true);
    }

    /**
     * @param $values
     * @param array $unset
     * @return array
     */
    protected function map($values, $unset = []): array
    {
        // Fetching column title and unset.
        $columns = $values[0];
        unset($values[0]);

        if (! empty($unset) && is_array($unset)) {
            foreach ($unset as $u) {
                unset($values[$u]);
            }
        }

        $result = [];
        foreach ($values as $key => $single) {
            $bucket = [];
            foreach ($columns as $k2 => $column) {
                $bucket[$key][$column] = isset($single[$k2]) ? $single[$k2]: '';
            }

            $result[] = json_encode($bucket[$key], JSON_FORCE_OBJECT);
        }

        return $result;
    }
}
