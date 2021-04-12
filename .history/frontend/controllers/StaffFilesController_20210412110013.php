<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\SignupForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ContactForm;

/**
 * Staff File controller
 */
class StaffFilesController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
//        return $this->redirect('staff-files/index');
    }


    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
       if ($model->load(Yii::$app->request->post()) && $model->signup()) {
           Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
           return $this->goHome();
       }

       return $this->render('signup', [
           'model' => $model,
       ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('index');
        }

        $model = new \common\models\LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['login']);
    }


    public function actionManage()
    {
        return $this->render('manage');

    }

     /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
       try {
           $model = new VerifyEmailForm($token);
       } catch (InvalidArgumentException $e) {
           throw new BadRequestHttpException($e->getMessage());
       }

       if ($user = $model->verifyEmail()) {
           if (Yii::$app->user->login($user)) {
               Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
               return $this->goHome();
           }
       }

       Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
       return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
       $model = new ResendVerificationEmailForm();
       if ($model->load(Yii::$app->request->post()) && $model->validate()) {
           if ($model->sendEmail()) {
               Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
               return $this->goHome();
           }
           Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
       }

       return $this->render('resendVerificationEmail', [
           'model' => $model
       ]);
    }


    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
       return $this->render('about');
    }
}
