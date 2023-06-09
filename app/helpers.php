<?php

function createLog($modelClass, $data)
{
    $model = new $modelClass;
    $model->fill($data);
    $model->save();

    return $model;
}