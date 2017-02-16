<?php
/*********************************************************************************
 * OpenACH is an ACH payment processing platform
 * Copyright (C) 2011 Steven Brendtro, ALL RIGHTS RESERVED
 * 
 * Refer to /legal/license.txt for license information, or view the full license
 * online at http://openach.com/community/license.txt
 ********************************************************************************/

class UserController extends OAController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('changePassword','update'),
				'users'=>array('@'),
			),
			array('allow', 
				'actions'=>array('index','view','create','update','search','delete','lookup'),
				'users'=>array('@'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$this->render('view',array(
			'model'=>$this->loadModel(),
		));
	}

	public function changePassword()
	{
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		$this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if ( $model->validate() )
			{
				$model->hashPassword();
				if($model->save())
					$this->redirect(array('view','id'=>$model->user_id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();

		$this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->user_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//$criteria = array( 'order' => 'user_login ASC' );
		$dataProvider=new CActiveDataProvider( 'User', array(
				/*
				'pagination'=>array(
					'pageSize' => 10,
				),*/
				/* 'criteria' => $criteria, */
			));
		$dataProvider->sort->defaultOrder='user_login ASC';
		if ( Yii::app()->request->getParam( 'ajax' ) )
		{
			$this->showOAListView( $dataProvider );
			Yii::app()->end();
		}
		else
		{
			$this->render('index',array(
				'dataProvider'=>$dataProvider,
			));
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionSearch()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		if ( Yii::app()->request->getParam( 'ajax' ) )
		{
			$this->showOAListView( $model->search() );
			Yii::app()->end();
		}
		else
		{
			$this->render('search',array(
				'model'=>$model,
			));
		}
	}

	public function actionLookup()
	{

		$term = Yii::app()->request->getParam( 'term' );

		if ( strlen( $term ) < 3 )
		{
			echo json_encode( array() );
			Yii::app()->end();
		}

		$sql = 'SELECT user_id, user_login FROM user WHERE user_login LIKE :user_login';
		$command = Yii::app()->db->createCommand( $sql );
		$command->bindValue( ':user_login', '%' . $term . '%', PDO::PARAM_STR );
		echo json_encode( $command->queryAll() );
		Yii::app()->end();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=User::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		// Check for authorization to access the model
		if ( ! Yii::app()->user->model()->isAuthorized( $this->_model ) )
		{
			throw new CHttpException( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
