<?php

class TestAPI extends SimpleModel {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function isTestTimeNotEnd($user_id) {
        $user = TUsers::model()->find('id = ' . $user_id);
        return $user["end_test_at"] < time();
    }

    public function getTestType() {
        $tblTTtype = TTestType::model();
        $criteria = new CDbCriteria();

        $criteria->limit = 1;

        return TTestType::model()->find($criteria);
    }

    public function getUserTimeAll($params = array()) {
        return TUsers::model()->find('id = ' . $params['user_id']);
    }

    public function getUserQuestList($id) {
        $list = TUserAnswers::model()->findAll("user_id = " . $id);

        $q_list = array();
        foreach ($list as $value) {
            $q_list[$value["question_num"]] = $value["is_answered"];
        }
        return $q_list;
    }

    public function createUser($params = array()) {
        $tblUser = new TUsers();


        $tblUser->name = $params['name'];
        $tblUser->last_name = $params['last_name'];
        $tblUser->group = $params['group'];
        $tblUser->begin_test = date("Y-m-d");

        $tblUser->start_test_at = time();
        $tblUser->end_test_at = $tblUser->start_test_at + ((int) $params['time'] * 60);
        $tblUser->max_points = $params['quest_count'];
        if ($params['test_type'] == 1) {
            $tblUser->module_number = $params['type_num'];
        }
        if ($params['test_type'] == 2) {
            $tblUser->them_num = $params['type_num'];
        }

        $tblUser->language = $params['language'];

        $tblUser->insert();

        return $tblUser;
    }

    public function getQuestionArray($params = array()) {
        $tblQuestion = TQuestions::model()->tableAlias;
        $criteria = new CDbCriteria();


        $criteria->select = $tblQuestion . ".*";

        if (is_array($params['theams'])) {
            $count = count($params['theams']);
            for ($i = 0; $i < $count; $i++) {
                if (!empty($params['theams'][$i]))
                    $criteria->addCondition('theam_id = ' . $params['theams'][$i], 'OR');
            }
        } else {
            $theams = TThems::model()->find('theam_visible_num  = ' . $params['theams'] . ' and language =  ' . (int) $params['language']);
            $criteria->addCondition('theam_id = ' . $theams['id']);
        }

        $criteria->addCondition('language = ' . (int) $params['language']);

        return TQuestions::model()->findAll($criteria);
    }

    public function generateQuestions($params = array()) {
        $param = array(
            'language' => $params['language'],
            'theams' => $params['theams']
        );
        $questCount = (int) $params['question_count'];
        $questions = $this->getQuestionArray($param);
        $questCountInTema = count($questions);
        if (empty($questions)) {
            return false;
        }
        $questCount = (int) $params['question_count'];
        $aQuestonRndNum = array();
        $teamQuestCount = round($questCount / count($params['theams'])) + 1;
        shuffle($questions);

        $userId = $params['user_id'];
        $questionRnd = array();
        $questionRndId = array();
        $qNum = 1;
        if (is_array($params['theams'])) {
            $final_question = array();
            foreach ($params['theams'] as $theam_id) {
                $qCount = 0;
                foreach ($questions as $value) {
                    if ($value["theam_id"] == $theam_id && $qCount < $teamQuestCount) {
                        $final_question[] = $value;
                        $qCount++;
                        $qNum++;
                    }
                    if ($qCount >= $teamQuestCount) {
                        break;
                    }
                }
            }
            shuffle($final_question);
            $qNum = 1;
            foreach ($final_question as $value) {
                $questionRnd[$theam_id][] = array("question_id" => $value["id"]);
                $questionRndId[] = array(
                    "user_id" => "$userId",
                    "theam_id" => $theam_id,
                    "question_id" => $value["id"],
                    'true_answer' => $value['true_answer'],
                    'question_num' => "$qNum",
                    'question_cost' => "1"
                );
                $qNum++;
            }
        } else {
            $theam_id = (int) $params['theams'];
            if (empty($theam_id)) {
                return false;
            }
            $qNum = 1;
            foreach ($questions as $value) {
                $questionRnd[$theam_id][] = array("question_id" => $value["id"]);
                $questionRndId[] = array(
                    "user_id" => "$userId",
                    "theam_id" => $theam_id,
                    "question_id" => $value["id"],
                    'true_answer' => $value['true_answer'],
                    'question_num' => "$qNum",
                    'question_cost' => "1"
                );
                $qNum++;
            }
        }

        if (count($questionRndId) > $questCount) {
            $questionRndId = array_slice($questionRndId, 0, $questCount);
        }
        foreach ($questionRndId as $part) {
            $tblAnsw = new TUserAnswers;

            $tblAnsw->user_id = $part['user_id'];
            $tblAnsw->question_id = $part['question_id'];
            $tblAnsw->question_num = $part['question_num'];
            $tblAnsw->true_answer = $part['true_answer'];
            $tblAnsw->question_cost = $part['question_cost'];

            $tblAnsw->insert();

            if ($tblAnsw->question_num == 1) {
                $this->setQuestionTime(array('user_id' => $params['user_id'], 'question_num' => 1, 'question_time' => $params['question_time']));
            }
        }
        $session = new CHttpSession;
        $session->open();
        $session['user_id'] = $params['user_id'];
        return true;
    }

    public function getModuleTheams($moduleId, $lang) {
        $tblModuleList = TThemToModule::model()->find("modul_id = " . $moduleId . " and language = " . $lang);
        return explode(',', $tblModuleList['theams_ids']);
    }

    public function generateTest(array $params = array()) {
        $testType = $this->getTestType();

        if (empty($testType)) {
            return false;
        }
        $moduleTheamsArray = array();
        $params['test_type'] = $testType['test_type'];
        $params['time'] = $testType['time'];

        $params['quest_count'] = $testType['quest_count'];

        if ($testType['test_type'] == 1) {
            $params['module_number'] = $testType['type_num'];
            $moduleTheamsArray = $this->getModuleTheams($testType['type_num'], $params['language']);
            $params['type_num'] = $testType['type_num'];
        }
        if ($testType['test_type'] == 2) {
            $params['them_num'] = $testType['type_num'];
            $params['type_num'] = $testType['type_num'];
        }
        $createdUser = $this->createUser($params);



        $params['theams'] = (empty($moduleTheamsArray)) ? $testType['type_num'] : $moduleTheamsArray;

        $params['question_count'] = $testType['quest_count'];
        $params['question_time'] = $testType['time'];
        $params['user_id'] = $createdUser['id'];
        $result = $this->generateQuestions($params);

        return !empty($result);
    }

    public function getUserQuestion(array $params = array()) {
        $tblUser = TUsers::model()->tableName();
        $tblQuestion = TQuestions::model()->tableName();
        $tblQImg = TQuestionImages::model();
        $tblUserAnswer = TUserAnswers::model()->tableName();
        $question = Yii::app()->db->createCommand()
                ->select('*')
                ->from($tblUserAnswer . ' u')
                ->join($tblQuestion . ' p', 'u.question_id=p.id')
                ->where('user_id=' . $params['user_id'] . ' and question_num =' . $params['question_num']);
        $result = $question->queryRow();


        $rndQ = Yii::app()->db->createCommand()
                ->select('*')
                ->from(t_random_answers)
                ->where('id=' . rand(1, 772));
        $rnd = $rndQ->queryRow();
        $img = $tblQImg->find("question_id = " . $result["question_id"]);
        $img_src = NULL;
        if (!empty($img["img_src"])) {
            $img_src = $img["img_src"];
        }
        
        $answers = array(
            '1' => $result['answ_1'],
            '2' => $result['answ_2'],
            '3' => $result['answ_3'],
            '4' => $result['answ_4'],
            '5' => $result['answ_5'],
        );
        return array(
            'question' => $result['question_text'],
            'answers' => $answers,
            'rnd' => $rnd,
            'answer_num' => $result["user_ansver_num"],
            'img' => $img_src
        );
    }

    public function userAnswer(array $params = array()) {
        $userAnswer = TUserAnswers::model()->find('user_id = ' . $params['user_id'] . ' and question_num =' . $params['question_num']);
        $userAnswer->user_ansver_num = $params['user_ansver_num'];
        $userAnswer->is_answered = 1;
        return $userAnswer->update();
    }

    public function setQuestionTime(array $params = array()) {
        $userAnswer = TUserAnswers::model()->find('user_id = ' . $params['user_id'] . ' and question_num =' . $params['question_num']);
        $userAnswer->begin_time = date("Y-m-d H:i:s");
        $userAnswer->end_time = date("Y-m-d H:i:s", strtotime("+" . $params['question_time'] . ' minute'));
        return $userAnswer->update();
    }

    public function getUserTime(array $params = array()) {
        $tblAnswUser = TUserAnswers::model()->tableAlias;
        $criteria = new CDbCriteria();
        $criteria->select = "*";
        $criteria->limit = 1;
        $criteria->addCondition('user_id = ' . $params['user_id']);
        $criteria->addCondition('question_num =' . $params['question_num']);

        $result = TUserAnswers::model()->find($criteria);


        return array(
            'begin' => $result['begin_time'],
            'end' => $result['end_time'],
        );
    }

    public function calcUser(array $params = array()) {
        $tblUserAnsw = TUserAnswers::model()->tableAlias;
        $criteria = new CDbCriteria();
        $criteria->select = "*";
        $criteria->addCondition('user_id = ' . $params['user_id']);

        $result = TUserAnswers::model()->findAll($criteria);

        $userBal = 0;
        foreach ($result as $value) {
            if ((int) $value['user_ansver_num'] == (int) $value['true_answer']) {
                $userBal += (int) $value['question_cost'];
            }
        }

        $user = TUsers::model()->find('id = ' . $params['user_id']);
        $user->points = $userBal;

        $user->update();
        return $user;
    }

    public function getModules() {
        return TModules::model()->findAll();
    }

    public function getTheams($lang) {
        return TThems::model()->findAll('language =' . $lang);
    }

    public function getTheamsInModule($lang) {
        return TThemToModule::model()->findAll('language =' . $lang);
    }

    public function saveModule(array $params = array()) {

        $tblModuleList = TThemToModule::model()->find("modul_id = " . $params['modul_id'] . " and language = " . $params['language']);

        $command = Yii::app()->db->createCommand();

        if (empty($tblModuleList)) {
            $command->insert('t_them_to_module', array(
                'language' => $params['language'],
                'theams_ids' => $params['theam_id'],
                'modul_id' => $params['modul_id'],
            ));
        } else {
            $tlist = explode(',', $tblModuleList['theams_ids']);
            if (in_array($params['theam_id'], $tlist)) {
                return false;
            }
            $command->update('t_them_to_module', array(
                'theams_ids' => $tblModuleList['theams_ids'] . "," . $params['theam_id'],
                    ), "modul_id = " . $params['modul_id'] . " and language = " . $params['language']);
            $result = $command->execute();
            return $result;
        }
    }

    public function createModule(array $params = array()) {
        $modules = new TModules;
        $modules->title = $params['title'];
        $modules->language = $params['language'];
        $modules->insert();
        sleep(1);
    }

    public function setTestTheam(array $params = array()) {
        $this->clearType();
        $testType = new TTestType;

        $testType->test_type = 2;
        $testType->type_num = $params['type_num'];
        $testType->time = $params['time'];
        $testType->quest_count = $params['quest_count'];
        $testType->language = $params['language'];
        $testType->insert();
    }

    public function setTestModule(array $params = array()) {
        $this->clearType();
        $testType = new TTestType;

        $testType->test_type = 1;
        $testType->type_num = $params['type_num'];
        $testType->time = $params['time'];
        $testType->quest_count = $params['quest_count'];
        $testType->language = $params['language'];
        $testType->insert();
    }

    public function clearType() {
        $command = Yii::app()->db->createCommand();
        $command->truncateTable('t_test_type');
        $command->execute();
    }

    public function deleteFromModule(array $params = array()) {
        $tblModuleList = TThemToModule::model()->find("modul_id = " . $params['modul_id'] . " and language = " . $params['language']);

        $theamArray = explode(',', $tblModuleList['theams_ids']);

        $index = array_search($params['theam_id'], $theamArray);

        $newArray = array();
        $count = count($theamArray);

        for ($i = 0; $i < $count; $i++) {
            if ($theamArray[$i] != (int) $params['theam_id']) {
                $newArray[$i] = $theamArray[$i];
            }
        }
        $command = Yii::app()->db->createCommand();
        if (!empty($newArray)) {
            $command->update('t_them_to_module', array(
                'theams_ids' => implode(',', $newArray),
                    ), "modul_id = " . $params['modul_id'] . " and language = " . $params['language']);
        } else {
            $command->delete('t_them_to_module', array("modul_id" => $params['modul_id'], "language" => $params['language']));
        }
        $command->execute();
        return true;
    }

    public function parseArrayFromFile($tfile, $lang) {

        $count = count($tfile);

        $questArray = array(
            'title' => '',
            'answer_1' => '',
            'answer_2' => '',
            'answer_3' => '',
            'answer_4' => '',
            'answer_5' => '',
            'true' => 0,
        );

        $allQuestArray = array();
        $nameTheama = '';
        $imgArray = array();
        $qNum = 0;
        for ($i = 0; $i < $count; $i++) {

            $pattern1 = '/(name\:)/';
            $arr1 = array();
            if (empty($nameTheama) && preg_match_all($pattern1, $tfile[$i], $arr1)) {
                $nameTheama = trim(str_replace("name:", "", $tfile[$i]));
            }
            $pattern = '/(QuestName\:)/';
            $arr = array();
            if (preg_match_all($pattern, $tfile[$i], $arr)) {
                $questArray['title'] = trim(str_replace("QuestName:", "", $tfile[$i]));
                $questArray['answer_1'] = $tfile[$i + 1];
                $questArray['answer_2'] = $tfile[$i + 2];
                $questArray['answer_3'] = $tfile[$i + 3];
                $questArray['answer_4'] = $tfile[$i + 4];
                $questArray['answer_5'] = $tfile[$i + 5];
                $questArray['true'] = (int) trim(str_replace("trueNum:", "", $tfile[$i + 6]));
                $allQuestArray[] = $questArray;
                $qNum++;
            }
            $pattern_img = '/(Img\:)/';
            $arr_2 = array();
            if (preg_match_all($pattern_img, $tfile[$i], $arr_2)) {
                $imgArray[$qNum] = trim(str_replace("Img:", "", $tfile[$i]));
                $allQuestArray[$qNum - 1]["img"] = $imgArray[$qNum];
            }
        }
        return array(
            'allQuestArray' => $allQuestArray,
            'nameTheama' => $nameTheama,
            "upload_img" => $imgArray
        );
    }

    private function insertImg($theam_id, array $img = array()) {

        foreach ($img as $key => $value) {
            $tblQImg = new TQuestionImages();
            $tblQImg->question_id = $key;
            $tblQImg->img_src = "/public/" . $value;
            $tblQImg->theam_id = $theam_id;
            $tblQImg->insert();
        }
    }

    public function insertTemaInBd(array $params = array()) {
        $tblexistTema = TThems::model()->find("theam_visible_num = " . $params['number'] . " and language = " . $params['language']);

        if (!empty($tblexistTema)) {
            $id = $tblexistTema['id'];
            $tblexistTema->delete();
            $tblQuestExist = TQuestions::model()->findAll('theam_id = ' . $id);
            if (!empty($tblQuestExist)) {
                foreach ($tblQuestExist as $value) {
                    $tblQimages = TQuestionImages::model()->findAll("question_id = " . $value->id);
                    if (!empty($tblQimages)) {
                        foreach ($tblQimages as $img) {
                            $file_str = str_replace("\protected", "", Yii::app()->basePath) . $img->img_src;
                            if (is_file($file_str)) {
                                unlink($file_str);
                            }
                            $img->delete();
                        }
                    }
                    $value->delete();
                }
            }
        }
        $imgForQuestion = array();
        $tblTheams = new TThems;
        $tblTheams->title = $params['nameTheama'];
        $tblTheams->language = $params['language'];
        $tblTheams->theam_visible_num = $params['number'];
        $tblTheams->insert();
        $tId = $tblTheams['id'];


        foreach ($params['allQuestArray'] as $value) {
            $tblQuestion = new TQuestions;

            $tblQuestion->theam_id = $tId;

            $tblQuestion->question_text = $value['title'];
            $tblQuestion->answ_1 = $value['answer_1'];
            $tblQuestion->answ_2 = $value['answer_2'];
            $tblQuestion->answ_3 = $value['answer_3'];
            $tblQuestion->answ_4 = $value['answer_4'];
            $tblQuestion->answ_5 = $value['answer_5'];
            $tblQuestion->true_answer = $value['true'];
            $tblQuestion->language = $params['language'];
            $tblQuestion->insert();
            if (!empty($value["img"])) {
                $imgForQuestion[$tblQuestion->id] = $value["img"];
            }
        }

        if (!empty($imgForQuestion)) {
            $this->insertImg($tblTheams->id, $imgForQuestion);
        }

        return true;
    }

    public function getUsers(array $params = array()) {
        $tblUsers = TUsers::model()->tableAlias;
        $criteria = new CDbCriteria();
        $criteria->addCondition("begin_test = '" . $params['date'] . "'");

        return TUsers::model()->findAll($criteria);
    }

    public function getUsersInfo($id) {
        $command = Yii::app()->db->createCommand();


        $command->select('*')
                ->from('t_user_answers usranswer')
                ->join('t_questions q', 'q.id=usranswer.question_id')
                ->join('t_thems tq', 'q.theam_id = tq.id')
                ->join('t_question_imgs tqi', 'q.id = tqi.question_id')
                ->where('usranswer.user_id=:id', array(':id' => $id))
                ->order("q.theam_id")
        ;

        $res = $command->queryAll();
        $result = array();
        if (empty($res)) {
            return false;
        }
        $theams = array();
        $answers = array();
        $theam_id = $res[0]["theam_id"];
        $theam_name = $res[0]["title"];

        $theams[$theam_id] = $theam_name;
        foreach ($res as $value) {
            if ($theam_id != $value["theam_id"]) {
                $theam_id = $value["theam_id"];
                $theam_name = $value["title"];
                $theams[$theam_id] = $theam_name;
            }
            $answers[$theam_id][] = $value;
        }
        $answers_count = array();
        foreach ($answers as $key => $value) {
            $true_count = 0;
            $count = 0;
            foreach ($value as $question) {
                if ($question["user_ansver_num"] === $question["true_answer"]) {
                    $true_count++;
                }
            }
            $answers_count[$key] = array(
                "count" => count($value),
                "true_count" => $true_count
            );
        }
        $result = array(
            "theams" => $theams,
            "answers" => $answers,
            "answers_count" => $answers_count,
            "user" => TUsers::model()->find("id=:id", array(':id' => $id))
        );
        return $result;
    }

    public function getTestTheam($lang) {
        $testType = $this->getTestType();
        $titleStr = '';
        $result = null;
        if ($testType['test_type'] == 1) {
            $result = TModules::model()->find('id = ' . $testType['type_num']);
            if (empty($result))
                return '';
            $titleStr = $result['title'];
        }else {
            $result = TThems::model()->find('theam_visible_num = ' . $testType['type_num'] . ' and language = ' . $lang);
            if (empty($result))
                return '';
            $titleStr = "№" . $result['theam_visible_num'] . " | " . $result['title'];
        }

        return $titleStr;
    }

    public function deleteTheamFromBd(array $params = array()) {
        $tblexistTema = TThems::model()->find('theam_visible_num = ' . $params['vis_id'] . ' and language = ' . $params['language']);

        if (!empty($tblexistTema)) {
            $id = $tblexistTema['id'];
            $tblexistTema->delete();
            $tblQuestExist = TQuestions::model()->findAll('theam_id = ' . $id);
            if (!empty($tblQuestExist)) {
                foreach ($tblQuestExist as $value) {
                    $value->delete();
                }
            }
            $tblQImg = TQuestionImages::model()->findAll('theam_id = ' . $id);
            if (!empty($tblQImg)) {
                foreach ($tblQImg as $value) {
                    $file_str = str_replace("\protected", "", Yii::app()->basePath) . $value->img_src;
                    if (is_file($file_str)) {
                        unlink($file_str);
                    }
                    $value->delete();
                }
            }
        }
    }

    public function deleteModulFromBd($id) {
        $res1 = TModules::model()->find('id = ' . $id);
        if (!empty($res1)) {
            $res1->delete();
        }
        $res2 = TThemToModule::model()->find('modul_id = ' . $id);
        if (!empty($res2)) {
            $res2->delete();
        }
        return;
    }

}
