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
use GraphQL\Oxwall\AppContext;
use GraphQL\Oxwall\Types;
use \GraphQL\Schema;
use \GraphQL\GraphQL;
use \GraphQL\Error\FormattedError;

class GRAPHQL_CTRL_Action extends OW_ActionController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
//        OW::getResponse()->clearHeaders();
//        OW::getResponse()->setHeader('Content-Type', 'application/json; charset=utf-8');
        // Prepare context that will be available in all field resolvers (as 3rd argument):
        $appContext = new AppContext();
        $appContext->viewer = 1; // simulated "currently logged-in user"
        $appContext->rootUrl = OW_URL_HOME;
        $appContext->request = $_REQUEST;
        $appContext->service = GRAPHQL_BOL_GeneralService::getInstance();
        $appContext->userService = GRAPHQL_BOL_UserService::getInstance();
        $appContext->blogService = GRAPHQL_BOL_BlogService::getInstance();
        $appContext->photoService = GRAPHQL_BOL_PhotoService::getInstance();
        $appContext->profileService = GRAPHQL_BOL_ProfileService::getInstance();
        $appContext->newsfeedService = GRAPHQL_BOL_NewsfeedService::getInstance();
        $appContext->groupService = GRAPHQL_BOL_GroupsService::getInstance();
        $appContext->videoService = GRAPHQL_BOL_VideoService::getInstance();
        $appContext->birthdayService = GRAPHQL_BOL_BirthdayService::getInstance();

        // Parse incoming query and variables
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $raw = file_get_contents('php://input') ?: '';
            $data = json_decode($raw, true);
        } else {
            $data = $_REQUEST;
        }
        $data += ['query' => null, 'variables' => null];
        if (null === $data['query']) {
            $data['query'] = '{hello}';
        }

        try {
            // GraphQL schema to be passed to query executor:
            $schema = new Schema([
                'query' => Types::query()
            ]);
            $result = GraphQL::execute(
                            $schema, $data['query'], null, $appContext, (array) $data['variables']
            );
            // Add reported PHP errors to result (if any)
            if (!empty($_GET['debug']) && !empty($phpErrors)) {
                $result['extensions']['phpErrors'] = array_map(
                        ['GraphQL\Error\FormattedError', 'createFromPHPError'], $phpErrors
                );
            }
            $httpStatus = 200;
        } catch (\Exception $error) {
            $httpStatus = 500;
            if (!empty($_GET['debug'])) {
                $result['extensions']['exception'] = FormattedError::createFromException($error);
            } else {
                $result['errors'] = [FormattedError::create('Unexpected Error')];
            }
        }

        OW::getResponse()->sendHeaders();

        echo json_encode($result);
        exit;
    }

}
