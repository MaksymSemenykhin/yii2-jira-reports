<?php

    namespace console\controllers;

    use \yii\console\Controller;
    use \common\models\State;
    use \common\models\Queue;

    class QueueController extends Controller {

        public function actionRun() {
            
            if(!\Yii::$app->jira->jiraUrl)
                throw new \Exception ('JiraUrl is not defined');
            
            if(!\yii::$app->params['supportEmail'])
                throw new \Exception ('SupportEmail is not defined');
            
            $Queue = Queue::findOne(['status'=>'new']);
            
            if(empty($Queue))
                return;
            
            $Queue->status = 'in_progress';
            $Queue->save();
            
            try {
                if('jql_aggregation'===$Queue->type)
                    $this->jql_aggregation($Queue);
                
            } catch (Exception $exc) {
                $Queue->status = 'error';
                $Queue->save();
                throw new \Exception ('QueueController->Run:'.$exc->getTraceAsString());
            }
            
            $Queue->progress = 100;
            $Queue->estimation = 0;
            $Queue->status = 'done';
            $Queue->save();

        }
        
        function jql_aggregation(Queue $Queue) {
            $start_time = time();
            
            $user = \common\models\User::findOne(['id'=>$Queue->user_id]);

            \Yii::$app->jira->username = $user->username;
            \Yii::$app->jira->password = $user->password;

            $data = json_decode($Queue->data,true);
            $limit = 50;
            $page = 0;
            $data['issues_count'] = 0;
            $data['worklog_count_total'] = 0;
            $data['worklog_count'] = 0;
            $worklogs = [];
            $users = [];
            do {
                $page++;
                $from = $limit*($page-1);
                $to = $limit*($page);
                $url = '/search?jql='.$data['jql'].'&fields=key%&maxResults='.$limit.'&startAt='.$from.'&maxResults='.$to;
                $data['log'][] ='GET '.\Yii::$app->jira->jiraUrl.'/rest/api/2/'.$url;
                $json = \Yii::$app->jira->request('GET',$url,'');
                $issues = $json['issues'];
                
//                $count = count($issues);
//                $data['issues_count'] += $count;

                foreach ($issues as $key => $issue) {
                    echo $issue['key']."\n";
                    $data['log'][] ='GET '.\Yii::$app->jira->jiraUrl.'/rest/api/2/issue/'.$issue['key'].'/worklog?maxResults=1000';
                    $worklogs[$issue['key']] = \Yii::$app->jira->request('GET','issue/'.$issue['key'].'/worklog?maxResults=1000' ,'');
                    
                    $worklogs[$issue['key']]['key']=$issue['key'];
                    foreach ($worklogs[$issue['key']]['worklogs'] as $worklog) {
                        $data['worklog_count_total']++;
                        $time = strtotime($worklog['started']);
                        if($time>=$Queue->dataFrom && $time<=$Queue->dataTo ){
                            $data['worklog_count']++;
                            $entry_key = $worklog['author']['key'].'-'.$issue['key'];
                            $users[$entry_key]['timeSpentSeconds'] = (isset($users[$entry_key]['timeSpentSeconds'])?$users[$entry_key]['timeSpentSeconds']:0)+$worklog['timeSpentSeconds'];
                            $users[$entry_key]['key'] = $issue['key'];
                            $users[$entry_key]['name'] = $worklog['author']['displayName'];
                        }
                    }            

                    $Queue->issues = $json['total'];
                    $Queue->worklogs = $data['worklog_count_total'];
                    $Queue->data = json_encode($data);
                    $Queue->progress = round((100/$json['total'])*$key);
                    $Queue->spend = time()-$start_time;
                    if($Queue->progress)
                        $Queue->estimation = (100-$Queue->progress)*$Queue->spend/$Queue->progress;
                    
                    $Queue->save();
                    var_dump($Queue->errors);
                }
                
            } while ($json['total']>$to);



            
            $data['users'] = $users;
            $data['users_count'] = count($users);
            $data['issues_count'] = $json['total'];

            $Queue->data = json_encode($data);
            $Queue->save();

            $mailer = \Yii::$app->mailer;
            $mailer->htmlLayout = '@frontend/emails/layout';
            $result = $mailer->compose('@frontend/emails/report_is_ready')
                    ->setFrom(\yii::$app->params['supportEmail'])
                    ->setTo($user->email)
                    ->setSubject('Report redy')
                    ->send();
            
        }
        
        
    }