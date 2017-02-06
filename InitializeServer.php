<?php

/**
 * Created by Sam Grew.
 * User: developer
 * Date: 2/6/17
 * Time: 8:47 PM
 */
class initializeServer implements RoutineInterface {

    /**
     * The Routines main function.
     * @param $arguments
     */
    public function main($arguments)
    {
        //Get Config.
        $json = file_get_contents(__DIR__ . '/config/config.json');
        $serverDetails = json_decode($json);
        //Get Details from the json.
        $serverName = $serverDetails->serverName;
        $serverURL = $serverDetails->serverURL;

        $this->createUIdFile();

        if (file_exists(__DIR__ . '/config/uid.json')) {
            $uidFile = file_get_contents(__DIR__ . '/config/uid.json');
            $json = json_decode($uidFile);
            $uid = $json->key;
        }

        //Check to see if the serverName is set in the JSON else override.
        if (empty($serverName)) {
            $serverName = shell_exec('hostname');
        }

        //Check to see if the correct serverURL is set correctly.
        if (empty($serverURL)) {
            die('serverURL is empty in the config/config.json');
        }

        //next example will insert new conversation
        $service_url = $serverURL . '/api/initializeServer';
        $curl = curl_init($service_url);
        $curl_post_data = array(
            'serverId' => $uid,
            'serverName' => $serverName
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additional info: ' . var_export($info));
        }
        curl_close($curl);
        $decoded = json_decode($curl_response);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occured: ' . $decoded->response->errormessage);
        }
        echo 'response ok!';
    }

    public function createUIdFile() {
        if (!file_exists(__DIR__ . '/config/uid.json')) {
            $uId = shell_exec('cat /proc/sys/kernel/random/uuid');
            $file = fopen(__DIR__ . '/config/uid.json', 'w');
            fwrite($file, json_encode(['key' => $uId]));
            fclose($file);
        }
    }
}