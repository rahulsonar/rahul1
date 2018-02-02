<?php
require_once 'classes/modelClass.php';

$model=new modelClass();
$data=$model->getData();
print_r($data);
