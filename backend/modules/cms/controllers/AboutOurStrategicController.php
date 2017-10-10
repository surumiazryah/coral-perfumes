<?php

namespace backend\modules\cms\controllers;

use Yii;
use common\models\AboutOurStrategic;
use common\models\AboutOurStrategicSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AboutOurStrategicController implements the CRUD actions for AboutOurStrategic model.
 */
class AboutOurStrategicController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all AboutOurStrategic models.
     * @return mixed
     */
    public function actionIndex($id = null) {
        if (!empty($id)) {
            $model = $this->findModel($id);
            $message = 'Data Updated Successfully';
        }
//        else {
//            $model = new AboutOurStrategic();
//            $message = 'Data Added Successfully';
//        }
        if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', $message);
                return $this->redirect(['index?id=1']);
            }
        }
        $searchModel = new AboutOurStrategicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
        ]);
    }

    /**
     * Displays a single AboutOurStrategic model.
     * @param integer $id
     * @return mixed
     */
//    public function actionView($id) {
//        return $this->render('view', [
//                    'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new AboutOurStrategic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate() {
//        $model = new AboutOurStrategic();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                        'model' => $model,
//            ]);
//        }
//    }

    /**
     * Updates an existing AboutOurStrategic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionUpdate($id) {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('update', [
//                        'model' => $model,
//            ]);
//        }
//    }

    /**
     * Deletes an existing AboutOurStrategic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionDelete($id) {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the AboutOurStrategic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AboutOurStrategic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AboutOurStrategic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
