<?php

namespace app\controllers;

use app\models\Contract;
use app\models\Interview;
use app\models\Order;
use app\models\Recruit;
use Yii;
use app\models\Employee;
use app\forms\EmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
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

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws ServerErrorHttpException
     */
    public function actionCreate($interview_id = null)
    {
        $model = new Employee();
        $model->order_date = date('Y-m-d');
        $model->contract_date = date('Y-m-d');
        $model->recruit_date = date('Y-m-d');

        if ($interview_id) {
            $interview = $this->findInterviewModel($interview_id);
            $model->last_name = $interview->last_name;
            $model->first_name = $interview->first_name;
            $model->email = $interview->email;
        } else {
            $interview = null;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($interview) {
                    $interview->status = Interview::STATUS_PASS;
                    $interview->save(); // если транзакция откатится, то email всеравно отправиться из afterSave().
                }

                $model->save(false);

                $order = new Order();
                $order->date = $model->order_date;
                $order->save();

                $contract = new Contract();
                $contract->employee_id = $model->id;
                $contract->last_name = $model->last_name;
                $contract->first_name = $model->first_name;
                $contract->date_open = $model->contract_date;
                $contract->save();

                $recruit = new Recruit();
                $recruit->employee_id = $model->id;
                $recruit->order_id = $order->id;
                $recruit->date = $model->recruit_date;
                $recruit->save();

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Employee is recruit.');
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new ServerErrorHttpException($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
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
