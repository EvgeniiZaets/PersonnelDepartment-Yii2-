<?php

namespace app\controllers;

use app\forms\InterviewEditForm;
use app\forms\InterviewJoinForm;
use app\forms\InterviewRejectForm;
use app\forms\InterviewMoveForm;
use app\services\InterviewService;
use Yii;
use app\models\Interview;
use app\forms\search\InterviewSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InterviewController implements the CRUD actions for Interview model.
 */
class InterviewController extends Controller
{
    private $interviewService;

    // Контроллер создастся через Yii::createObject(), и он через контейнер
    // спарсит конструктор и автоматически создаст экземпляр InterviewService.
    // Потом по цепочке парсит конструктор InterviewService и создаст экземпляры всех классов которые туда переданы.
    public function __construct($id, $module, InterviewService $interviewService, array $config = [])
    {
        $this->interviewService = $interviewService;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Interview models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InterviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Interview model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionJoin()
    {
        $form = new InterviewJoinForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $model = $this->interviewService->join(
                    $form->lastName,
                    $form->firstName,
                    $form->email,
                    $form->date
                );
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('join', [
            'joinForm' => $form,
        ]);
    }

    /**
     * Updates an existing Interview model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $interview = $this->findModel($id);
        $form = new InterviewEditForm($interview);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->interviewService->edit(
                $interview->id,
                $form->lastName,
                $form->firstName,
                $form->email
            );

            return $this->redirect(['view', 'id' => $interview->id]);
        }

        return $this->render('update', [
            'editForm' => $form,
            'model' => $interview
        ]);
    }

    public function actionMove($id)
    {
        $interview = $this->findModel($id);
        $form = new InterviewMoveForm($interview);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->interviewService->move($interview->id, $form->date);
            return $this->redirect(['view', 'id' => $interview->id]);
        } else {
            return $this->render('move', [
                'moveForm' => $form,
                'model' => $interview,
            ]);
        }
    }

    public function actionReject($id)
    {
        $interview = $this->findModel($id);
        $form = new InterviewRejectForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->interviewService->reject(
                $interview->id,
                $form->reason
            );

            return $this->redirect(['view', 'id' => $interview->id]);
        }

        return $this->render('reject', [
            'rejectForm' => $form,
            'model' => $interview
        ]);
    }

    public function actionDelete($id)
    {
        $interview = $this->findModel($id);
        // !!! Передаем id, а не Interview, потому что на фронтенде мы можем создать AR-модели для отображения
        // а классы которые мы используем здесь могут не совпадать с классами с которыми мы работем здесь.
        // Также можно передать массив $interview['id'] и тд
        $this->interviewService->delete($interview->id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Interview model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Interview the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Interview::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
