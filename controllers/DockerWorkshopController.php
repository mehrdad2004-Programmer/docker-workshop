<?php

namespace app\controllers;

use Yii;

class DockerWorkshopController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //executing command
        exec('docker ps -a --format "{{.ID}},{{.Image}},{{.Command}},{{.Status}},{{.Ports}},{{.Names}}"', $output_cmd);

        return $this->render("index", [
            "data" => $output_cmd
        ]);
    }


    public function actionManager(){
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        
        if($request->get("type") == "logs"){
            // Execute multiple commands when type is logs
            exec("docker logs --tail 50 " . $request->get("con_id") . " 2>&1", $logs_output, $logs_exit);
            exec("docker port " . $request->get("con_id"), $port_output, $port_exit);
            exec("docker inspect -f '{{json .Mounts}}' " . $request->get("con_id"), $volumes_output, $volumes_exit);
            
            $session->setFlash("logs", $logs_output);
            $session->setFlash("ports", $port_output);
            $session->setFlash("volumes", $volumes_output);
            $session->setFlash("statuscode", $logs_exit); 
        }

        else {
            exec("docker ".$request->get("type")." " . $request->get("con_id"), $output_cmd, $exit_code);
            $session->setFlash("message", $output_cmd);
            $session->setFlash("statuscode", $exit_code);
        }

        $session->setFlash("type", $request->get("type"));
        $session->setFlash("con_id", $request->get("con_id"));
        
        return $this->redirect(['docker-workshop/index']);
    }

}
