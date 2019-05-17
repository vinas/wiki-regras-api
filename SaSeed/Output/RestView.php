<?php
/**
* Rest View Class
*
* This class holds basic general functions to render objects
* into standatized JSON responses as an output.
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2016/11/08
* @version 1.16.11108
* @license SaSeed\license.txt
*/

namespace SaSeed\Output;

use SaSeed\Handlers\Exceptions;

Final class RestView
{

    /**
    * Prints an array or object as a JSON
    *
    * @param mixed
    * @param object {code, message, content}
    */
    public static function render($data, $model = false)
    {
        try {
            if ($model) {
                $model->setContent($data);
                $data = $model;
            }
            ob_start();
            echo json_encode($data);
            ob_end_flush();
        } catch (Exception $e) {
            Exceptions::throwNew(
                __CLASS__,
                __FUNCTION__,
                'Not possible to render json: '.$e->getMessage()
            );
        }
    }
}
