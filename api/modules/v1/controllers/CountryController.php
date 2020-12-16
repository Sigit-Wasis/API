<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use Yii;
use api\modules\v1\models\Country;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class CountryController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Country';  
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        // unset($actions['delete'], $actions['create']);
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $models =  Yii::$app->db->createCommand('SELECT * FROM country')->queryAll();

        if ($models) {
            $header = $this->setHeader(200);
            echo json_encode(
                array(
                    'status'    => true,
                    'message'   => 'Successfully',
                    'data'      => $models,
                ), JSON_PRETTY_PRINT
            );
        } else {
            $header = $this->setHeader(404);
        }
    }

    /* Functions to set header with status code. eg: 200 OK ,400 Bad Request etc..*/	    
    private function setHeader($status)
    {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        $content_type="application/json; charset=utf-8";
    
        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "Sigit Wasis <sigit-wasis.github.io>");
    }

    private function _getStatusCodeMessage($status)
    {
        $codes = Array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }	   
}
