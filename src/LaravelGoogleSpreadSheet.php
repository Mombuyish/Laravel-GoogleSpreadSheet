<?php

namespace Yish\LaravelGoogleSpreadSheet;

use Google_Service_Sheets;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use PulkitJalan\Google\Facades\Google;

class LaravelGoogleSpreadSheet
{
    protected $data;

    protected $result;

    protected $values;

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param null $scope
     * @return mixed
     */
    public function initClient($scope = null)
    {
        $client = Google::getClient();
        $client->useApplicationDefaultCredentials();

        $scope = empty($scope) ? Google_Service_Sheets::SPREADSHEETS_READONLY : $scope;

        $client->setScopes($scope);
        $client->setAccessType($this->config['access_type']);

        return $client;
    }

    /**
     * @param $client
     * @param $spreadsheetId
     * @param $range
     * @return mixed
     */
    public function callServiceSheet($client, $spreadsheetId, $range)
    {
        $service = new Google_Service_Sheets($client);
        return $service->spreadsheets_values->get($spreadsheetId, $range);
    }

    /**
     * @param $sheet
     * @param $title
     * @param $unset
     */
    public function setProperties($sheet, $title, $unset)
    {
        $this->values = $sheet->getValues();
        $this->result = $this->map($this->values, $title, $unset);
        $this->data = "[" . implode(',', $this->result) . "]";
    }

    /**
     * @param $sheet_id
     * @param $range
     * @param int $title
     * @param array $unset
     * @param null $scope
     * @return $this
     */
    public function json($sheet_id, $range, $title = 0, $unset = [], $scope = null)
    {
        $client = $this->initClient($scope);

        $sheet = $this->callServiceSheet($client, $sheet_id, $range);

        $this->setProperties($sheet, $title, $unset);

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

    public function storeAs($path, $disk = 'public')
    {
        return Storage::disk($disk)->put($path, $this->data);
    }

    public function feed($sheet_id, $sheet = 1, $format = 'json')
    {
        $end_point = "https://spreadsheets.google.com/feeds/cells/{$sheet_id}/{$sheet}/public/values?alt={$format}";

        $client = new Client();

        $response = $client->get($end_point)->getBody();

        return json_decode($response, true);
    }

    /**
     * @param $values
     * @param int $title
     * @param array $unset
     * @return array
     */
    public function map($values, $title, $unset): array
    {
        // Fetching column title and unset.
        $columns = $values[$title];
        unset($values[$title]);

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
