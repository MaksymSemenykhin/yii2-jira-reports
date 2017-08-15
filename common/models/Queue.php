<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "queue".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $status
 * @property integer $progress
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $dataFrom
 * @property integer $dataTo
 * @property integer $spend
 * @property integer $estimation
 * @property string $data
 */
class Queue extends \yii\db\ActiveRecord
{
    
    public function behaviors() {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'data'], 'required'],
            [['user_id', 'progress','progress','dataFrom','dataTo'], 'integer'],
            [['type', 'data'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'status' => 'Status',
            'progress' => 'Progress',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'data' => 'Data',
            'dataFrom' => 'Data From',
            'dataTo' => 'Data To',
        ];
    }
}
