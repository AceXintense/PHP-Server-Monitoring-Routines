<?php

include_once('lib/requestController.php');

/**
 * Created by Sam Grew.
 * User: developer
 * Date: 2/6/17
 * Time: 11:04 PM
 */
class uploadCPU extends requestController implements RoutineInterface {

    /**
     * The Routines main function.
     * @param $arguments
     */
    public function main($arguments) {

        $cpuCores = shell_exec('lscpu | grep "^CPU(s):" | awk \'{print  $2}\'');
        $cpuTemperature = 40;
        $cpuFrequency = shell_exec('lscpu | grep "CPU MHz:" | awk \'{print  $3}\'');
        $cpuLoad = shell_exec('awk -v a="$(awk \'/cpu /{print $2+$4,$2+$4+$5}\' /proc/stat; sleep 1)" \'/cpu /{split(a,b," "); print 100*($2+$4-b[1])/($2+$4+$5-b[2])}\'  /proc/stat');

        $postArguments = [
            'serverId' => $this->uid,
            'cpuCores' => $cpuCores,
            'cpuTemperature' => $cpuTemperature,
            'cpuFrequency' => $cpuFrequency,
            'cpuLoad' => $cpuLoad
        ];

        $this->post($postArguments, '/api/uploadCPU');

    }
}