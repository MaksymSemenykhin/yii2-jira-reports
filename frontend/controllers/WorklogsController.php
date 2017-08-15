<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Jql;
use \common\models\Queue;

class WorklogsController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['group', 'queue'],
                'rules' => [
                    [
                        'actions' => ['group', 'queue'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionQueue($queueId) {

        $Queue = Queue::findOne(['id' => $queueId]);

        if (\yii::$app->request->get('toxls', false))
            $this->layout = 'empty';

        return $this->render('group_queue', ['Queue' => $Queue, 'toxls' => \yii::$app->request->get('toxls', false)]);
    }

    public function actionGroup() {
        $result = [];

        $attributes = [
            'user_id' => \yii::$app->user->id,
            'type' => 'jql_aggregation'
        ];
        if (\yii::$app->request->isAjax)
            $this->layout = 'empty';

        if (!\yii::$app->request->isAjax && $jql = \yii::$app->request->post('jql', false)) {
            $attributes['data'] = json_encode([
                'jql' => $jql
            ]);
            $Queue_new = new Queue($attributes);
            $Queue_new->dataFrom = strtotime(\yii::$app->request->post('datafrom') . ' 00:00');
            $Queue_new->dataTo = strtotime(\yii::$app->request->post('datato') . ' 23:59');
            if ($Queue_new->save())
                return $this->redirect('/worklogs/group');

            unset($attributes['data']);
            $result['Queue_new'] = $Queue_new;
        }

        $result['queue'] = Queue::find()->where($attributes)->asArray()->orderBy([
                    'created_at' => SORT_DESC
                ])->limit(10)->all();

        return $this->render('group_index', $result);
    }

}
