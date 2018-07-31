<?php

namespace app\controllers;

use app\forms\EmployeeCreateForm;
use app\models\Contract;
use app\models\Interview;
use app\models\Order;
use app\models\Recruit;
use Yii;
use app\models\Employee;
use app\forms\search\EmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;
use app\services\StaffService;
use app\services\dto\RecruitData;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
    private $staffService;

    public function __construct($id, $module, StaffService $staffService, array $config = [])
    {
        $this->staffService = $staffService;
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
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
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

    public function actionCreate()
    {
        $form = new EmployeeCreateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $employee = $this->staffService->createEmployee(
                new RecruitData($form->firstName, $form->lastName, $form->address, $form->email),
                $form->orderDate,
                $form->contractDate,
                $form->recruitDate
            );
            Yii::$app->session->setFlash('success', 'Employee is recruit.');
            return $this->redirect(['view', 'id' => $employee->id]);
        }

        return $this->render('create', [
            'createForm' => $form,
        ]);
    }

    public function actionCreateByInterview($interview_id)
    {
        $interview = $this->findInterviewModel($interview_id);
        $form = new EmployeeCreateForm($interview);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $employee = $this->staffService->createEmployeeByInterview(
                $interview->id,
                new RecruitData($form->firstName, $form->lastName, $form->address, $form->email),
                $form->orderDate,
                $form->contractDate,
                $form->recruitDate
            );
            Yii::$app->session->setFlash('success', 'Employee is recruit.');
            return $this->redirect(['view', 'id' => $employee->id]);

            /////////////TODO:delete
//            $transaction = Yii::$app->db->beginTransaction();
//            try {
//                if ($interview) {
//                    $interview->status = Interview::STATUS_PASS;
//                    $interview->save(); // если транзакция откатится, то email всеравно отправиться из afterSave().
//                }
//
//                $model->save(false);
//
//                $order = new Order();
//                $order->date = $model->order_date;
//                $order->save();
//
//                $contract = new Contract();
//                $contract->employee_id = $model->id;
//                $contract->last_name = $model->last_name;
//                $contract->first_name = $model->first_name;
//                $contract->date_open = $model->contract_date;
//                $contract->save();
//
//                $recruit = new Recruit();
//                $recruit->employee_id = $model->id;
//                $recruit->order_id = $order->id;
//                $recruit->date = $model->recruit_date;
//                $recruit->save();
//
//                $transaction->commit();
//                Yii::$app->session->setFlash('success', 'Employee is recruit.');
//                return $this->redirect(['view', 'id' => $model->id]);
//            } catch (\Exception $e) {
//                $transaction->rollBack();
//                throw new ServerErrorHttpException($e->getMessage());
//            }
            ///////////
        }

        return $this->render('create', [
            'createForm' => $form,
        ]);
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param integer $id
     * @return Interview the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findInterviewModel($id)
    {
        if (($model = Interview::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
