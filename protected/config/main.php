<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

//google key
$domain = $_SERVER['SERVER_NAME'];
if($domain==='www.trippytripper.org'){
    $googleKey = 'AIzaSyD-o3Di-HaEWv6q81Sa-Kh5n5jaZ-Exkr8';
    $dbUser = 'trippytripper';
    $dbPass = 'aOJWXegj6tG';
    $dbHost = 'localhost';
    $userDebug = false;
    $debug = array(
            'class' => 'CWebLogRoute',
            'levels' => 'trace, info, error, warning',
            );
}elseif($domain==='trippytripper.herokuapp.com'){
      $googleKey = 'AIzaSyD-o3Di-HaEWv6q81Sa-Kh5n5jaZ-Exkr8';
      $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
      $dbUser = $url["user"];
      $dbPass = $url["pass"];
      $dbHost = $url["host"];
      $userDebug = false;
      $debug = array(
              'class' => 'CWebLogRoute',
              'levels' => 'trace, info, error, warning',
              );

}else if($domain==='trippytripper.herokuapp.com'){
      $googleKey = 'AIzaSyD-o3Di-HaEWv6q81Sa-Kh5n5jaZ-Exkr8';
      $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
      $dbUser = $url["user"];
      $dbPass = $url["pass"];
      $dbHost = $url["host"];
      $userDebug = false;
      $debug = array(
              'class' => 'CWebLogRoute',
              'levels' => 'trace, info, error, warning',
              );
}else{
    $googleKey = 'AIzaSyAisOhSjoLbzL_hEtuBhUoS3pr71vhwtu4';
    $dbUser = 'root';
    $dbPass = 'root';
    $dbHost = 'localhost';
    $userDebug = false;
    $debug = array(
            'class' => 'CWebLogRoute',
            'levels' => 'trace, info, error, warning',
            );
}
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Trippy Tripper',
    'sourceLanguage'=>'en',
    // preloading 'log' component
    'preload'=>array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.ext.time.*',
        'application.models.*',
        'application.components.*',
        'application.modules.*',

        //for user
        'application.modules.user.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'application.modules.user.controllers.*',

        //for friendship
        'application.modules.friendship.*',
        'application.modules.friendship.components.*',
        'application.modules.friendship.controller.*',
        'application.modules.friendship.models.*',

        //for profile
        'application.modules.profile.components.*',
        'application.modules.profile.controller.*',
        'application.modules.profile.models.*',

        //for registration
        'application.modules.registration.models.*',

        //for role
        'application.modules.role.models.*',

        // Star rank module
        'application.modules.PcStarRank.*',
        'application.modules.PcStarRank.models.*',
        'application.modules.PcStarRank.controllers.*',
        'application.modules.PcStarRank.components.*',
        'application.modules.PcStarRank.extensions.PcStarRankWidget.*',

    ),

    'defaultController' => 'site',
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'dontcry',
        ),
        'avatar' => array(),
        'friendship' => array(),
        'membership' => array(),
        'message' => array(),
        'starRank' => array(
            'class' => 'application.modules.PcStarRank.PcStarRankModule'
        ),
        'profile' => array(),
        'registration' => array(),
        'role' => array(),
        'user' => array(
            'debug' => $userDebug,
            'facebookConfig'=>array(
                'appId'=>'830328023674875',
                'secret'=>'5a102cb541201ec9a15b70e7f2cb2b69',
                'domain'=>'http://www.trippytripper.org',
                'status'=>true,
                'xfbml'=>true,
                'cookie'=>true,
                'lang'=>'en_US',
            ),
            //'loginType' => 8, //By username and Facebook should be 9.
        ),

        'usergroup' => array(),
        'hybridauth' => array(
            'baseUrl' => 'http://'. $_SERVER['SERVER_NAME'] . '/index.php/hybridauth',
            'withYiiUser' => true, // Set to true if using yii-user
            "providers" => array (
                // "openid" => array (
                //     "enabled" => true
                // ),

                // "yahoo" => array (
                //     "enabled" => true
                // ),

                /*"Google" => array (
                     "enabled" => true,
                     "keys"    => array ( "id" => "", "secret" => "" ),
                     "scope"   => ""
                ),*/

                "Facebook" => array (
                    "enabled" => true,
                    "keys"    => array ( "id" => "711026555619509", "secret" => "c2af46a92b8f760191eb5b6be64404ab" ),
                    "scope"   => "email,publish_stream",
                    "display" => ""
                ),

                // "Twitter" => array (
                //     "enabled" => true,
                //     "keys"    => array ( "key" => "", "secret" => "" )
                // )
            )
        ),

    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'class' =>'application.modules.user.components.YumWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('//user/user/login'),
        ),

        'cache' => array('class' => 'system.caching.CDummyCache'),
        // uncomment the following to use a MySQL database

        'db' => array(
            'connectionString' => 'mysql:host='.$dbHost.';dbname=trippytripper',
            'emulatePrepare' => true,
            'username' => $dbUser,
            'password' => $dbPass,
            'charset' => 'utf8',
            'enableParamLogging' => true
            //'tablePrefix' => 'tbl_',
        ),

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                'gii' => 'gii',
                'gii/<controller:\w+>' => 'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
            ),
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                $debug
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),

);
