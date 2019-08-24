<?php

namespace App\Controller;

use Cake\Event\Event;

/**
 * Answers Controller
 */
class AnswersController extends AppController
{
    const ANSWER_UPPER_LIMIT = 100;

    /**
     * @inheritdoc
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->request->allowMethod(['post']);
    }

    /**
     * 回答投稿処理
     * 
     * @return \Cake\Http\Response|null 回答投稿後に質問詳細画面へ遷移する
     */
  public function add()
  {
      $answer = $this->Answers->newEntity($this->request->getData());
      $count = $this->Answers
          ->find()
          ->where(['question_id' => $answer->question_id])
          ->count();

      if ($count >= self::ANSWER_UPPER_LIMIT) {
        $this->Flash->error('回答の上限数に達しました');

        return $this->redirect(['controller' => 'Questuions', 'action' => 'view', $answer->question_id]);
      }

      $answer->user_id = 1; //＠TODO ユーザー管理機能実装時に修正する
      if ($this->Answers->save($answer)) {
        $this->Flash->success('回答を投稿しました');
      } else {
        $this->Flash->error('回答の投稿に失敗しました');
      }

      return $this->redirect(['controller' => 'Questions', 'action' => 'view', $answer->question_id]);
  }
}