<?php

include_once('lib/requestController.php');

/**
 * Created by Sam Grew.
 * User: developer
 * Date: 2/6/17
 * Time: 8:47 PM
 */
class initializeServer extends requestController implements RoutineInterface {

    /**
     * The Routines main function.
     * @param $arguments
     */
    public function main($arguments) {

        //Check to see if the serverName is set in the JSON else override.
        if (empty($this->serverName)) {
            $this->serverName = shell_exec('hostname');
        }

        //Check to see if the correct serverURL is set correctly.
        if (empty($this->serverURL)) {
            die('serverURL is empty in the config/config.json');
        }

        $postArguments = [
            'serverId' => $this->uid,
            'serverName' => $this->serverName
        ];

        $this->post($postArguments, '/api/initializeServer');
    }
}