<?php
// This controller handles the administration, upload and the deletion 
// of an Avatar image for the user profile.

Yii::import('application.modules.user.controllers.YumController');

class YumAvatarController extends YumController {
	public function beforeAction($action) {
		// Disallow guests
		if(Yii::app()->user->isGuest)
			$this->redirect(Yum::module()->loginUrl);

		if (Yii::app()->user->isAdmin())
			$this->layout = Yum::module('avatar')->adminLayout;
		else
			$this->layout = Yum::module('avatar')->layout;

		return parent::beforeAction($action);
	}

	public function actionRemoveAvatar($id = null) {
		if($id && Yii::app()->user->isAdmin())
			$model = YumUser::model()->findByPk($id);
		else
			$model = YumUser::model()->findByPk(Yii::app()->user->id);

		if(!$model)
			throw new CHttpException(404);

		$model = YumUser::model()->findByPk(Yii::app()->user->id);
		$model->avatar = '';
		$model->save();
		$this->redirect(array(
					Yum::module('profile')->profileViewRoute));	
	}

	public function actionEnableGravatar($id = null) {
		if($id && Yii::app()->user->isAdmin())
			$model = YumUser::model()->findByPk($id);
		else
			$model = YumUser::model()->findByPk(Yii::app()->user->id);

		if(!$model)
			throw new CHttpException(404);

		$model->avatar = 'gravatar';
		$model->save();
		$this->redirect(array(
					Yum::module('profile')->profileViewRoute));	
	}

	public function actionEditAvatar($id = null) {
		$this->layout = false;
		if($id && Yii::app()->user->isAdmin())
			$model = YumUser::model()->findByPk($id);
		else
			$model = YumUser::model()->findByPk(Yii::app()->user->id);

		if(!$model)
			throw new CHttpException(404);

		if(isset($_POST['YumUser']) && isset($_POST['YumUser']['avatar'])) {
			$model->attributes = $_POST['YumUser'];
			$model->setScenario('avatarUpload');

			if(Yum::module('avatar')->avatarMaxWidth != 0)
				$model->setScenario('avatarSizeCheck');

			$model->avatar = CUploadedFile::getInstanceByName('YumUser[avatar]');
			if($model->validate()) {
				if ($model->avatar instanceof CUploadedFile) {
					//set name of avatar is the user_id
					$ext = substr($_FILES['YumUser']['name']['avatar'], -4);
					$filename = Yum::module('avatar')->avatarPath .'/'.  $model->id . $ext;
					$model->avatar->saveAs($filename);
					//thumb+++++++++++++++++++++++++
					Yii::import("application.ext.EPhpThumb.EPhpThumb");
					$thumb=new EPhpThumb();
					$thumb->init(); //this is needed
					//small
					$thumbImg=$thumb->create($filename);
					$thumbImg->resize(45,45);
					$thumbImg->save(Yum::module('avatar')->avatarPath .'/thumbs/'.$model->id . $ext);
					//resize to standard and remove the original one
					$thumbImg=$thumb->create($filename);
					$thumbImg->resize(120,120);
					$thumbImg->save(Yum::module('avatar')->avatarPath .'/'.$model->id . $ext);
					//thumb-------------------------
					$model->avatar = $filename;
					if($model->save()) {
						//update avatar in comment table
						$this->updateAvatarForComment($filename,Yii::app()->user->id);
						Yum::setFlash(Yum::t('The image was uploaded successfully'));
						Yum::log(Yum::t('User {username} uploaded avatar image {filename}', array(
										'{username}' => $model->username,
										'{filename}' => $model->avatar)));
						$this->redirect(array('//profile/profile/view'));	
					}
				}
			}
		}

		$this->renderPartial('edit_avatar', array('model' => $model),false,true);
	}
	public function updateAvatarForComment($avatar,$userId){
		$sql = "UPDATE comments SET avatar =:avatar WHERE user_id = :userId";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':userId', $userId, PDO::PARAM_INT);
        $command->bindParam(':avatar', $avatar, PDO::PARAM_STR);

        $data = $command->execute();
	}
	public function actionAdmin() {
		$model = new YumUser();
		$model->unsetAttributes(); // display all users
		$this->render('admin', array('model' => $model));
	}
}
