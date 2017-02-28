<?php

namespace backend\controllers;

use backend\models\ResetPasswordForm;
use common\models\AuthAssignment;
use common\models\AuthItem;
use Yii;
use common\models\Adminuser;
use common\models\AdminuserSearch;
use yii\rbac\Assignment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\SignupForm;

/**
 * AdminuserController implements the CRUD actions for Adminuser model.
 */
class AdminuserController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Adminuser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminuserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Adminuser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Adminuser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->redirect(['view', 'id' => $user->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Adminuser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Adminuser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Adminuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Adminuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Adminuser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求页面不存在！.');
        }
    }

    public function actionResetpwd($id)
    {
        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->resetPassword($id)) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('resetpwd', [
            'model' => $model,
        ]);
    }

    /**
     * 获取角色和权限
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionPrivilege($id)
    {
        // step1. 找出所有的角色，提供给 checkboxlist。type=1是角色，type=2是角色拥有的权限
        $allPrivileges = AuthItem::find()
            ->select(['name', 'description'])
            ->where(['type' => 1])
            ->orderBy('description')
            ->all();
        //zhi($allPrivileges);
        $allPrivilegesArray = [];
        foreach ($allPrivileges as $privilege) {
            $allPrivilegesArray[$privilege->name] = $privilege->description;
        }
        //zhi($allPrivilegesArray); 打印结果：
        /* Array
         * (
         *   [postOperator] => 文章操作员
         *   [postAdmin] => 文章管理员
         *   [admin] => 系统管理员
         *   [commentAuditor] => 评论审核员
         * )
         *
         */

        // step2. 获取当前用户的角色的权限，点进权限的时候已经选择的复选框
        $allAssignments = AuthAssignment::find()
            ->select(['item_name'])
            ->where(['user_id' => $id])
            ->orderBy('item_name')
            ->all();
        // zhi($allAssignment);
        // array_push() 将一个或多个单元压入数组的末尾（入栈）
        $AuthAssignmentArray = [];
        foreach ($allAssignments as $assignment) {
            array_push($AuthAssignmentArray, $assignment->item_name);
        }
        //zhi($AuthAssignmentArray); [0] => admin

        // step3. 获取从表单提交的数据，来更新 AuthAssignment 表，从而用户的角色发生了变化
        if (isset($_POST['privilege'])) {
            AuthAssignment::deleteAll('user_id=:id', [':id' => $id]);

            $privilege = $_POST['privilege'];
            $arrlength = count($privilege);
            //zhi($privilege);exit;

            for ($x = 0; $x < $arrlength; $x++) {
                $newPri = new AuthAssignment();
                $newPri->item_name = $privilege[$x];
                $newPri->user_id = $id;
                $newPri->created_at = time();

                $newPri->save();
            }

            return $this->redirect(['index']);
        }

        // step4. 渲染 checkboxlist 表单
        return $this->render('privilege', [
            'id' => $id,
            'allPrivilegesArray' => $allPrivilegesArray,
            'AuthAssignmentArray' => $AuthAssignmentArray,
        ]);
    }
}
