<?php

/**
 * Created by Sam Grew.
 * User: developer
 * Date: 2/6/17
 * Time: 11:05 PM
 */
class requestController
{

    public $serverURL = '';
    public $serverName = '';
    public $uid = '';

    public function __construct()
    {
        $this->setConfig();
        $this->setUId();
    }

    public function setConfig()
    {
        //Get Config.
        $json = file_get_contents(__DIR__ . '/../config/config.json');
        $serverDetails = json_decode($json);

        $this->serverName = $serverDetails->serverName;
        $this->serverURL = $serverDetails->serverURL;
    }

    public function setUId()
    {
        if (file_exists(__DIR__ . '/../config/uid.json')) {
            $this->uid = $this->getUId();
        } else {
            $uId = shell_exec('cat /proc/sys/kernel/random/uuid');
            $file = fopen(__DIR__ . '/../config/uid.json', 'w');
            fwrite($file, json_encode(['key' => $uId]));
            fclose($file);
            $this->uid = $uId;
        }
    }

    private function getUId()
    {
        $uidFile = file_get_contents(__DIR__ . '/../config/uid.json');
        $json = json_decode($uidFile);
        return $json->key;
    }

    public function post($arguments, $location, $decode = true)
    {
        $service_url = $this->serverURL . $location;

        $curl = curl_init($service_url);
        $curl_post_data = $arguments;
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        $curl_response = curl_exec($curl);
        // also get the error and response code
        $errors = curl_error($curl);
        $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($response !== 201) {
            die(
            var_dump($errors, $response)
            );
        }
        curl_close($curl);
        if ($decode) {
            return json_decode($curl_response);
        }
        return $curl_response;
    }

    public function get($location) {

        $service_url =  $this->serverURL . $location;
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        $decoded = json_decode($curl_response);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occured: ' . $decoded->response->errormessage);
        }
        echo 'response ok!';
        var_export($decoded->response);
    }

}

$instance = new requestController();