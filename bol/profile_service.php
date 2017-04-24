<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is a proprietary licensed product. 
 * For more information see License.txt in the plugin folder.

 * ---
 * Copyright (c) 2012, Purusothaman Ramanujam
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without modification, are not permitted provided.

 * This plugin should be bought from the developer by paying money to PayPal account (purushoth.r@gmail.com).

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
class GRAPHQL_BOL_ProfileService {

    private static $classInstance;

    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct() {
        
    }

    public function getProfileById($userId) {
        $language = OW::getLanguage();
        $questionService = BOL_QuestionService::getInstance();

        $user = BOL_UserService::getInstance()->findUserById($userId);

        if (is_null($user)) {
            return [];
        }

        $accountType = $user->accountType;

        $questions = $questionService->findViewQuestionsForAccountType($accountType);

        $section = null;
        $questionArray = array();
        $questionNameList = array();

        foreach ($questions as $sort => $question) {
            if ($section !== $question['sectionName']) {
                $section = $question['sectionName'];
            }

            $questionArray[$section][$sort] = $questions[$sort];
            $questionNameList[] = $questions[$sort]['name'];
        }

        $questionData = $questionService->getQuestionData(array($userId), $questionNameList);

        $questionValues = $questionService->findQuestionsValuesByQuestionNameList($questionNameList);

        foreach ($questions as $sort => $question) {
            if ($section !== $question['sectionName']) {
                $section = $question['sectionName'];
            }

            $questionArray[$section][$sort] = $questions[$sort];
            $questionNameList[] = $questions[$sort]['name'];
        }

        $questionData = $questionService->getQuestionData(array($userId), $questionNameList);

        $questionValues = $questionService->findQuestionsValuesByQuestionNameList($questionNameList);

        foreach ($questionArray as $sectionKey => $section) {

            foreach ($section as $questionKey => $question) {

                if (!empty($questionData[$userId][$question['name']])) {
                    switch ($question['presentation']) {
                        case BOL_QuestionService::QUESTION_PRESENTATION_CHECKBOX:

                            if ((int) $questionData[$userId][$question['name']] === 1) {
                                $questionData[$userId][$question['name']] = $language->text('base', 'questions_checkbox_value_true');
                            } else {
                                unset($questionArray[$sectionKey][$questionKey]);
                            }

                            break;

                        case BOL_QuestionService::QUESTION_PRESENTATION_DATE:

                            $format = OW::getConfig()->getValue('base', 'date_field_format');

                            $value = 0;

                            switch ($question['type']) {
                                case BOL_QuestionService::QUESTION_VALUE_TYPE_DATETIME:

                                    $date = UTIL_DateTime::parseDate($questionData[$userId][$question['name']], UTIL_DateTime::MYSQL_DATETIME_DATE_FORMAT);

                                    if (isset($date)) {
                                        $format = OW::getConfig()->getValue('base', 'date_field_format');
                                        $value = mktime(0, 0, 0, $date['month'], $date['day'], $date['year']);
                                    }

                                    break;

                                case BOL_QuestionService::QUESTION_VALUE_TYPE_SELECT:

                                    $value = (int) $questionData[$userId][$question['name']];

                                    break;
                            }

                            if ($format === 'dmy') {
                                $questionData[$userId][$question['name']] = date("d/m/Y", $value);
                            } else {
                                $questionData[$userId][$question['name']] = date("m/d/Y", $value);
                            }

                            break;

                        case BOL_QuestionService::QUESTION_PRESENTATION_MULTICHECKBOX:

                            $value = "";
                            $multicheckboxValue = (int) $questionData[$userId][$question['name']];

                            $questionValues = BOL_QuestionService::getInstance()->findQuestionValues($question['name']);

                            foreach ($questionValues as $val) {


                                if (( (int) $val->value ) & $multicheckboxValue) {
                                    if (strlen($value) > 0) {
                                        $value .= ', ';
                                    }

                                    $value .= $language->text('base', 'questions_question_' . $question['name'] . '_value_' . ($val->value));
                                }
                            }

                            if (strlen($value) > 0) {
                                $questionData[$userId][$question['name']] = $value;
                            } else {
                                unset($questionArray[$sectionKey][$questionKey]);
                            }

                            break;

                        case BOL_QuestionService::QUESTION_PRESENTATION_SELECT:
                        case BOL_QuestionService::QUESTION_PRESENTATION_RADIO:

                            $value = "";
                            $multicheckboxValue = (int) $questionData[$userId][$question['name']];

                            $value = $language->text('base', 'questions_question_' . $question['name'] . '_value_' . $multicheckboxValue);

                            if (strlen($value) > 0) {
                                $questionData[$userId][$question['name']] = $value;
                            } else {
                                unset($questionArray[$sectionKey][$questionKey]);
                            }

                            break;

                        case BOL_QuestionService::QUESTION_PRESENTATION_URL:
                        case BOL_QuestionService::QUESTION_PRESENTATION_TEXT:
                        case BOL_QuestionService::QUESTION_PRESENTATION_TEXTAREA:

                            $value = trim($questionData[$userId][$question['name']]);

                            if (strlen($value) > 0) {
                                $questionData[$userId][$question['name']] = UTIL_HtmlTag::autoLink(nl2br($value));
                            } else {
                                unset($questionArray[$sectionKey]);
                            }

                            break;

                        default:
                            unset($questionArray[$sectionKey][$questionKey]);
                    }
                } else {
                    unset($questionArray[$sectionKey][$questionKey]);
                }
            }

            if (isset($questionArray[$sectionKey]) && count($questionArray[$sectionKey]) === 0) {
                unset($questionArray[$sectionKey]);
            }
        }

        $profileValues = array();

        foreach ($questionArray as $section => $questions) {
            $sectionLabel = $language->text('base', "questions_section_" . $section . "_label");

            $sectionString = htmlspecialchars($sectionLabel);

            foreach ($questions as $sort => $question) {
                $questionAnswerT = $questionData[$userId][$question['name']];
                $questionValueT = $language->text('base', "questions_question_" . $question['name'] . "_label");

                $q = htmlspecialchars($questionValueT);

                $a = htmlspecialchars($questionAnswerT);

                $profileValues[] = array('section' => $sectionString, 'question' => $q, 'value' => $a , 'name'=>$question['name']);
            }
        }

        return $profileValues;
    }

}
