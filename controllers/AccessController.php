<?php

namespace app\controllers;

use app\models\Note;
use app\models\User;
use Yii;
use app\models\Access;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessController implements the CRUD actions for Access model.
 */
class AccessController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'unshared-all' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Предоставить доступ к указанной заметке. Кому предоставить доступ выбирается в форме.
     * @param $noteId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate( $noteId )
    {
        $model = new Access();
        $model->note_id = $noteId;

        $modelNote = Note::findOne( $noteId );
        if ( !$modelNote->isUserNote( Yii::$app->user->getId() ) ) {
            throw new ForbiddenHttpException( 'Нет доступа' );
        }

        $users = User::find()->select( [ "trim( concat( ifnull( name, '' ), ' ', ifnull( surname, '' ) ) )" ] )->indexBy( 'id' )
            ->exceptUser( Yii::$app->user->getId() )->column();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash( 'success', "Предоставлен доступ к заметке $noteId пользователю {$model->user_id}" );
            return $this->redirect( ['note/my'] );
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users
        ]);
    }

    /**
     * Удалить доступ к указанной заметке.
     * @param $noteId
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionUnsharedAll( $noteId )
    {
        $modelNote = Note::findOne( $noteId );
        if ( !$modelNote->isUserNote( Yii::$app->user->getId() ) ) {
            throw new ForbiddenHttpException( 'Нет доступа' );
        }

        $modelNote->unlinkAll( Note::RELATION_ACCESSES_USERS, true );
        Yii::$app->session->setFlash( 'success', "Для всех пользователей удален доступ к заметке {$noteId}" );

        return $this->redirect( [ 'note/shared' ] );
    }

    /**
     * Finds the Access model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Access the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Access::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
