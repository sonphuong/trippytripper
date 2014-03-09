<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Sharing community',

	// preloading 'log' component
	//'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.*',
		//for user 
		'application.modules.user.*',
		'application.modules.user.models.*',
		'application.modules.user.components.*',
		'application.modules.user.controllers.*',
		//for profile
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
	    //...
	),

	'defaultController'=>'site',
	'modules' => array(
		'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'dontcry',
        ),
		'user' => array(
			'debug' => true,
			),
        'role' => array(),
        'usergroup' => array(),
        'membership' => array(),
        'friendship' => array(),
		'message' => array(),
		'registration'=> array(),
		'avatar'=> array(),
		'profile'=> array(),
		'role' =>array(),
		//start rank...
    	'starRank' => array(
      		'class' => 'application.modules.PcStarRank.PcStarRankModule'
    	),
    	//...

		),
	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'class' => 'application.modules.user.components.YumWebUser',
      		'allowAutoLogin'=>true,
      		'loginUrl' => array('//user/user/login'),
		),

		'cache' => array('class' => 'system.caching.CDummyCache'),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=sharingcommunity',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			//'tablePrefix' => 'tbl_',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'gii'=>'gii',
            	'gii/<controller:\w+>'=>'gii/<controller>',
            	'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
				),
				
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	//'params'=>require(dirname(__FILE__).'/params.php'),
	
);