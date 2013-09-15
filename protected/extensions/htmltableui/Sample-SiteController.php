<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	/*
	 * Sample controller action, just return the sent data
	 * The javascript code will show it as a pop-up
	 */
	public function actionHandleHtmlTable(){
        sleep(2);
        if(isset($_POST)){
            //return the POST variable back
            //the widget will show an alert() with this data
 	            print_r($_POST);        	
        }
    }

	/*
	 * Sample controller action
	 * The javascript code will create a CSV file
	 * in 'protected/csv'. That directory must be manually
	 * created and the permissions should be set to allow php
	 * to write to it.
	 */
	public function actionExportCsv(){
		if(isset($_POST)){
            // Check if the POST variable is an array
            if(is_array($_POST)){
				// 1. Create and open a file for writing
				// must have directory /csv/ created and writable 
				
				// Create a timestamp to use in the file name.
				// Note: the correct timezone is relevant only
				// to the client connecting to the server application
				// that's why it needs to be detected by the browser
				// and that's what the htmlTableUi.js is providing
				// STILL NEEDS WORK - NOT SURE IF WORKING OK
				$secondsoffset = 2*intval($_POST['timezone'])*60*60;
				$timestamp = mktime(date("h"))+$secondsoffset;

				// Find the path of the public folder where the files
				// will be stored.
				$filepath = Yii::getPathOfAlias("webroot")."/csv/";
				$filename = preg_replace('/\s+/', '', $_POST['title'])."_".date("Ymdhisa",$timestamp);
				$file = $filepath.$filename.".csv";
				if(file_exists($file)){
					if(is_writable($file)){
						unlink($file); //delete file!
						$fh = fopen($file,"x");//create and open for writing	
					}
				}else{
					$fh = fopen($file,"x");//create and open for writing
				}
				
				
				// 2. Write data to the file
				
				// Write csv table title
				if(isset($_POST["title"])){
					fwrite($fh,"\"".$_POST["title"]."\""."\n");
				}
				// Write csv table subtitle
				if(isset($_POST["subtitle"])){
					fwrite($fh,"\"".$_POST["subtitle"]."\""."\n");
				}
				// Write csv table extra
				if(isset($_POST["extra"])){
					fwrite($fh,"\"".$_POST["extra"]."\""."\n");
				}

				// Write the column headers and the table rows
				// Note: This code depends on the order in which
				// the column headers array and the rows array
				// were added in the javascript routine. 
				// Column headers must be added first so they are
				// printed before the rows of data.
				unset($rows);
				$rowIndex = 0;
				foreach($_POST as $key=>$row){
					$rows[]=$row;
					
					// If POST variable is array break down and write
					// to file, else write directly to file (non data rows)
					if(is_array($row)){
						
						// 2.b. Write to the file each element's (row) data
						// and end it with new line
						$columnIndex = 0;
						$totalColumns = count($rows[$rowIndex]);
						foreach($rows[$rowIndex] as $rkey=>$rvalue){
							fwrite($fh,"\"".$rvalue."\"");
							if($columnIndex<$totalColumns-1){
								fwrite($fh,",");
							}else{
								fwrite($fh,"\n");
							}
							$columnIndex = $columnIndex + 1;
						}
					}
					$rowIndex = $rowIndex + 1;
				}	 

				// Write csv table footer
				if(isset($_POST["footer"])){
					fwrite($fh,"\"".$_POST["footer"]."\""."\n");
				}
						
				// 3. Close the file and send the URL path of the file to 
				// the jQuery script that generates the XHRequest. 
				if(fclose($fh)){
					echo Yii::app()->baseUrl."/csv/".$filename.".csv";//"Export Successful!";				
				}
            }else{
	            //print_r($_POST);
	            echo "Error with POST";        	
            }
			
        }
	}
	
	
}