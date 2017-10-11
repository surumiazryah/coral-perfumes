<?php

namespace backend\modules\cms\controllers;

use Yii;
use common\models\ContactPage;
use common\models\ContactPageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContactPageController implements the CRUD actions for ContactPage model.
 */
class ContactPageController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function beforeAction($action) {
		if (!parent::beforeAction($action)) {
			return false;
		}
		if (Yii::$app->user->isGuest) {
			$this->redirect(['/site/index']);
			return false;
		}
		return true;
	}

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
	 * Lists all ContactPage models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new ContactPageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			    'searchModel' => $searchModel,
			    'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single ContactPage model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		return $this->render('view', [
			    'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new ContactPage model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new ContactPage();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				    'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing ContactPage model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
			$model->shoroom_content = Yii::$app->request->post()['ContactPage']['shoroom_content'];
			if ($model->validate() && $model->save())
				Yii::$app->getSession()->setFlash('success', "Updated Successfully");
		}
		return $this->render('update', [
			    'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ContactPage model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the ContactPage model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ContactPage the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = ContactPage::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

}
