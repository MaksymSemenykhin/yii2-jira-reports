<?php

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

function timeconvert($interval) {

    $accuracy = [
        1 => 's',
        60 => 'm',
        3600 => 'h',
    ];
    krsort($accuracy);

    $result = '';

    foreach ($accuracy as $seconds => $label) {
        if ($interval < $seconds)
            continue;

        $n = floor($interval / $seconds);
        $interval -=($seconds * $n);

        $result .= $n . $label . ' ';
    }

    return $result;
}
