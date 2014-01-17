<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public $languageArray = array();

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function init() {
        parent::init();
        $this->languageArray[0] = array();
        $this->languageArray[1] = array();
        $this->languageArray[2] = array();
        $this->languageArray[0][0] = "Введіть Імя, фамілію та групу.";
        $this->languageArray[0][1] = "Ім'я";
        $this->languageArray[0][2] = "Прізвище";
        $this->languageArray[0][3] = "Група";
        $this->languageArray[0][4] = "Виберіть категорію тестування.";
        $this->languageArray[0][5] = "Почати";
        $this->languageArray[0][6] = "Ваша";
        $this->languageArray[0][7] = "оцінка";
        $this->languageArray[0][8] = "Підсумок";
        $this->languageArray[0][9] = "ВАША СТАТИСТИКА.";




        $this->languageArray[1][0] = "Введите имя, фамилию и группу.";
        $this->languageArray[1][1] = "Имя";
        $this->languageArray[1][2] = "Фамилия";
        $this->languageArray[1][3] = "Группа";
        $this->languageArray[1][4] = "Выберите категорию тестирования.";
        $this->languageArray[1][5] = "Начать";
        $this->languageArray[1][6] = "Ваша";
        $this->languageArray[1][7] = "оценка";
        $this->languageArray[1][8] = "Результат";
        $this->languageArray[1][9] = "ВАША СТАТИСТИКА.";


        $this->languageArray[2][0] = "Enter the name, last name and group.";
        $this->languageArray[2][1] = "Name";
        $this->languageArray[2][2] = "Last name";
        $this->languageArray[2][3] = "Group";
        $this->languageArray[2][4] = "Choose a testing category.";
        $this->languageArray[2][5] = "Start";
        $this->languageArray[2][6] = "Your";
        $this->languageArray[2][7] = "rating";
        $this->languageArray[2][8] = "Result";
        $this->languageArray[2][9] = "YOUR STATISTIC.";
    }

    private function _renderIndex() {

        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/generate.js');

        $session = new CHttpSession;
        $session->open();

        $API = TestAPI::model();
        $testType = $API->getTestTheam($session["language"]);


        $this->render('index', array("languages" => $this->languageArray, "testType" => $testType));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $session = new CHttpSession;
        $session->open();

        if ($session['test_process']) {
            $this->redirect('/test');
        }
        $session["language"] = 0;
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->_renderIndex();
    }

    public function actionLanguageSet() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }
        $lang = Yii::app()->request->getParam("lang", 0);
        $session = new CHttpSession;
        $session->open();
        $session["language"] = $lang;

        $API = TestAPI::model();
        $testType = $API->getTestTheam($session["language"]);

        $result = array(
            "status" => true,
            "content" => "",
            "title" => ""
        );
        if (!empty($testType)) {
            $result["title"] = $testType;
        } else {
            $result['status'] = false;
            $result["title"] = "Для даної мови немає теми, або модуль не наповнений.";
        }
        $result["content"] = $this->renderPartial('input-data', array(
            "language" => $session["language"],
            "languages" => $this->languageArray,
                ), true);


        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionGenerate() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }
        $API = TestAPI::model();
        $request = Yii::app()->request;

        $result = array(
            'status' => false,
            'errors' => '',
        );
        $testType = $API->getTestType();

        $session = new CHttpSession;
        $session->open();
        $session["max_question"] = $testType['quest_count'];

        $params = array(
            'language' => $session["language"]
        );

        $userValidate = new UserForm();

        foreach ($userValidate as $attr => $val) {
            $userValidate->$attr = $request->getParam($attr);
            $params[$attr] = $userValidate->$attr;
        }

        $userValidate->validate();
        $result['errors'] = $userValidate->errors;

        if ($result['errors']) {
            echo json_encode($result);
            Yii::app()->end();
        }


        $result['status'] = $API->generateTest($params);
        $session['test_process'] = true;
        $session['question_num'] = 1;
        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionTest() {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/test.js');
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        if ($API->isTestTimeNotEnd($session["user_id"])) {
            $session['test_process'] = FALSE;
        }

        if (!$session['test_process']) {
            $this->redirect('/');
        }
        $testType = $API->getTestType();
        $params = array(
            'user_id' => $session['user_id'],
            'question_num' => $session['question_num'],
        );
        $result = $API->getUserQuestion($params);

        // $API->setQuestionTime($params);


        $this->render('index', array(
            "testProcess" => $session['test_process'],
            "qResult" => $result,
            'question_number' => $session['question_num'],
            "max_question" => $testType["quest_count"]
        ));
    }

    public function actionUserStat() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }
        $session = new CHttpSession;
        $session->open();
        $params = array(
            'user_id' => $session['user_id'],
        );
        $API = TestAPI::model();

        $result = array(
            'status' => false,
            'errors' => '',
        );


        $userInfo = $API->calcUser($params);

        $result['content'] = $this->renderPartial('user_stat', array('user' => $userInfo,
            'languages' => $this->languageArray,
            'language' => $session["language"],
            'max_quest' => $session["max_question"]
                ), true);

        if (!empty($result['content'])) {
            $result['status'] = true;
        }
        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionFinishTest() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }
        $session = new CHttpSession;
        $session->open();

        $session['test_process'] = FALSE;
    }

    public function actionGetQuestion() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();
        $quest_num = Yii::app()->request->getParam('quest_num', 0);
        $params = array(
            'user_id' => $session['user_id'],
            'question_num' => $quest_num,
        );

        $result ['status'] = true;
        $session['question_num'] = $quest_num;
        $res = $API->getUserQuestion($params);

        $result ['content'] = $this->renderPartial('test', array(
            'qResult' => $res,
            'question_number' => $quest_num
                ), true);
        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionAnswer() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        $result = array(
            'status' => false,
            'content' => '',
            'errors' => '',
        );

        if ($API->isTestTimeNotEnd($session["user_id"])) {
            $session['test_process'] = FALSE;
        }

        $params = array(
            'user_id' => $session['user_id'],
            'question_num' => Yii::app()->request->getParam('quest_num', 0),
            'user_ansver_num' => Yii::app()->request->getParam('user_answer', 0),
        );
        $session['question_num'] = Yii::app()->request->getParam('quest_num', 0);
        $result['status'] = $API->userAnswer($params);
        if ($result['status']) {
            $testType = $API->getTestType();
            if ($session['question_num'] >= $testType['quest_count']) {
                $session['question_num'] = 1;
            } else {
                $session['question_num'] = $session['question_num'] + 1;
            }



            $params = array(
                'user_id' => $session['user_id'],
                'question_num' => $session['question_num'],
                'question_time' => $testType['time']
            );
//            if ($session["max_question"] < $session['question_num']) {
//                $result['status'] = 'finish';
//                $session['test_process'] = false;
//                echo json_encode($result);
//                Yii::app()->end();
//            }
            $res = $API->getUserQuestion($params);
            $result ['content'] = $this->renderPartial('test', array(
                'qResult' => $res,
                'question_number' => $session['question_num']
                    ), true);
            $API->setQuestionTime($params);
        }

        echo json_encode($result);
        Yii::app()->end();
    }

    public function actiongetUserStats() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }

        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();


        $params = array(
            'user_id' => $session['user_id'],
        );
        $res = $API->getUserTimeAll($params);
        $result["start_test_at"] = $res["start_test_at"];
        $result["end_test_at"] = $res["end_test_at"];
        $result["now"] = time();

        if (!empty($result)) {
            echo json_encode($result);
        } else {
            return FALSE;
        }
    }

    public function actiongetUserInfo() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();


        $params = array(
            'user_id' => $session['user_id'],
            'question_num' => $session['question_num'],
        );

        $result = $API->getUserTime($params);
        $result['now'] = date("Y-m-d H:i:s");
        if (!empty($result)) {
            echo json_encode($result);
        } else {
            return FALSE;
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionAdmin() {
        if (CHttpRequest::getUserHostAddress() != "127.0.0.1") {
            Yii::app()->end();
        }
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/admin.js');
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        $lang = $session['language'];



        $modules = $API->getModules();
        $theams = $API->getTheams($lang);
        $theamsInModule = $API->getTheamsInModule($lang);



        $this->render('admin-index', array(
            "language" => $lang,
            "modules" => $modules,
            "theams" => $theams,
            "theamsInModule" => $theamsInModule
        ));
    }

    public function actionGetImagePoint() {
        header("Content-type: image/png");
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();
        $user_id = $session['user_id'];
        $user_info = $API->getUsersInfo($user_id);
        $true_result = 0;
        $all_result = 0;
        foreach ($user_info["answers_count"] as $value) {
            $true_result+=(int) $value["true_count"];
            $all_result+=(int) $value["count"];
        }
        $img = imagecreate(60, 20);
        $background_color = imagecolorallocate($img, 249, 249, 249);
        $text_color = imagecolorallocate($img, 233, 14, 91);
        $text_color_2 = imagecolorallocate($img, 213, 114, 191);
        $str = "$true_result";
        imagestring($img, 34, 20, 5, $str, $text_color);
        imagestring($img, 34, 30, 5, "($all_result)", $text_color_2);
        imagepng($img);
        imagedestroy($img);
    }

    public function actionLoadImages() {
        require('/uploader/UploadHandler.php');
        $upload_handler = new UploadHandler();
    }

    public function actionLoadFromFile() {

        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        $lang = $session['language'];


        if (is_uploaded_file($_FILES["tfile"]["tmp_name"])) {


            $tfile = file($_FILES["tfile"]["tmp_name"]);

            $result = $API->parseArrayFromFile($tfile, $lang);
            $result['language'] = $lang;
            $pattern1 = '/([0-9]+)/';
            $arr1 = array();
            if (preg_match($pattern1, $_FILES["tfile"]["name"], $arr1)) {
                $result['number'] = (int) $arr1[0];
            }
            $addResult = $API->insertTemaInBd($result);

            $this->render("view-upload-data", array(
                'number' => $result['number'],
                'nameTheama' => $result['nameTheama'],
                "is_have_images" => !empty($result["upload_img"])
            ));
            // Если файл загружен успешно, перемещаем его
            // из временной директории в конечную
            //move_uploaded_file($_FILES["filename"]["tmp_name"], "/path/to/file/" . $_FILES["filename"]["name"]);
        } else {
            $this->render("upload-view");
        }
    }

    public function actionCreateModule() {
        if (CHttpRequest::getUserHostAddress() != "127.0.0.1") {
            Yii::app()->end();
        }
        $request = Yii::app()->request;
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        $lang = $session['language'];

        $params = array(
            'language' => "$lang",
            'title' => $request->getParam('title', 'не названий модуль')
        );
        $API->createModule($params);
    }

    public function actionSaveModuleStatus() {
        if (CHttpRequest::getUserHostAddress() != "127.0.0.1") {
            Yii::app()->end();
        }
        $request = Yii::app()->request;
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        $lang = $session['language'];

        $params = array(
            'language' => "$lang",
            'modul_id' => $request->getParam('module', 0),
            'theam_id' => $request->getParam('theam', 0),
        );


        $API->saveModule($params);
    }

    public function actionSetTestTheam() {
        if (CHttpRequest::getUserHostAddress() != "127.0.0.1") {
            Yii::app()->end();
        }


        $request = Yii::app()->request;
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        $lang = $session['language'];

        $params = array(
            'language' => "$lang",
            'test_type' => "2",
            'type_num' => $request->getParam('visible_theam_id', '0'),
            'time' => $request->getParam('time', '0'),
            'quest_count' => $request->getParam('quest_count', '0'),
        );
        $API->setTestTheam($params);
    }

    public function actionSetTestModule() {
        if (CHttpRequest::getUserHostAddress() != "127.0.0.1") {
            Yii::app()->end();
        }


        $request = Yii::app()->request;
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        $lang = $session['language'];

        $params = array(
            'language' => "$lang",
            'test_type' => "1",
            'type_num' => $request->getParam('module_id', '0'),
            'time' => $request->getParam('time', '0'),
            'quest_count' => $request->getParam('quest_count', '0'),
        );
        $API->setTestModule($params);
    }

    public function actionDeleteFromModule() {
        if (CHttpRequest::getUserHostAddress() != "127.0.0.1") {
            Yii::app()->end();
        }

        $result = array(
            "status" => false
        );
        $request = Yii::app()->request;
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        $lang = $session['language'];

        $params = array(
            'language' => "$lang",
            'theam_id' => $request->getParam('id', 0),
            'modul_id' => $request->getParam('module', 0),
        );
        $result['status'] = $API->deleteFromModule($params);

        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionStatistic() {

        $request = Yii::app()->request;
        $session = new CHttpSession;
        $session->open();

        if ($session['test_process']) {
            $this->redirect('/test');
        }
        $API = TestAPI::model();

        $date = Yii::app()->request->getParam('date', date("Y-m-d"));


        $params = array(
            'date' => $date
        );

        $result = $API->getUsers($params);
        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial("stat_load", array(
                'users' => $result,
                'date' => $date
            ));
        } else {
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/stat.js');
            $this->render("stat", array(
                'users' => $result,
                'date' => $date
            ));
        }
    }

    public function actionUserInfo() {
        $id = Yii::app()->request->getParam('id', 0);
        $API = TestAPI::model();
        $result = $API->getUsersInfo($id);

        $this->render("user-info", array(
            'list' => $result,
        ));
    }

    public function actionDeleteTheam() {
        if (CHttpRequest::getUserHostAddress() != "127.0.0.1") {
            Yii::app()->end();
        }
        $id = Yii::app()->request->getParam('id', 0);
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        if ($id <= 0) {
            Yii::app()->end();
        }
        $params = array(
            'vis_id' => $id,
            'language' => $session['language']
        );
        $result = $API->deleteTheamFromBd($params);
        Yii::app()->end();
    }

    public function actionDeleteModul() {
        if (CHttpRequest::getUserHostAddress() != "127.0.0.1") {
            Yii::app()->end();
        }
        $id = Yii::app()->request->getParam('id', 0);
        $API = TestAPI::model();
        if ($id <= 0) {
            Yii::app()->end();
        }
        $result = $API->deleteModulFromBd($id);
        Yii::app()->end();
    }

    public function actiongetQuestList() {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        }
        $API = TestAPI::model();
        $session = new CHttpSession;
        $session->open();

        $list = $API->getUserQuestList($session["user_id"]);
        echo json_encode($list);
        Yii::app()->end();
    }

}
