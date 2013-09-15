# HtmlTableUi Yii Extension
![](http://i1181.photobucket.com/albums/x437/jerullan/150f97f6.jpg "Cover")

This is the official repository for the htmlTable widget. This widget is an extension for the Yii framework.
The htmlTable widget is an HTML based data grid used to display tabular data in a Yii web application.
It was intentionally developed using a `<table>` structure to leverage the available javascript codes used to sort and manipulate tables in HTML.

## Features

The widgets main functions are to display data in a collapsible table that can be configured to use a jQuery UI theme and that allows modifications to its presented data and exporting in CSV.
Originally, I developed this to avoid the overhead of Yii's GridView. Now the widget offers a range of powerful unique features, while preserving the simplicity.
To this extent the widget's features are:

- ** *Collapsible* **: Its title is clickable, to provide a collapsible body, for applications that need to draw several tables in the same page. 
- ** *Sortable* **: The table includes client-sided sorting. 
- ** *Editable* **: When editable is set to true, the table can be edited live! Then its possible to send the edited rows to the server through AJAX.
- ** *Themeable* **: This new version supports passing the css file location of a jQuery UI's theme and will apply the look and feel to the widget.
- ** *Export to CSV functionality* **. This requires the use of a new property: exportUrl and a controller/action handler. Includes an action class to take care of exporting to CSV for you with minimal configuration needed in your controller. Just add it to the actions() declaration in your controller and specify the css export path under webroot. You might modify it to fit your needs.
- ** *Uniquely Identified* **: Each instance of the widget is uniquely identified to allow multiple tables working independently in the same page. 


##Parameters
1. **title** - The title of the table
2. **sub-title** - A subtitle, shown below the title
3. **collapsed** - Determines if the table will be drawn collapsed or expanded. False by default.
4. **extra** - Additional information about the table contents that will be shown in the title area. If not provided, it will show the count of rows in the table.
5. **arProvider** - An instance of CActiveDataProvider from which to extract the data.
6. **enableSort** - Turn on or off sorting, true by default.
7. **sortColumn** - Used to specify which column to sort by default if sorting is enabled. Columns are specified by passing an zero-based integer. First column will be 0, second 1 and so on. Will sort first column by default.
8. **sortOrder** - Used to specify the order of the default column sort. 'asc' will sort default sort column in ascending order, and 'desc' will sort the column in descending order. Ascending by default.
9. **editable** - If true, sets the mode of the table to edit mode, allowing changes to the table live and sending modified rows to the server for appropriate action.
10. **ajaxUrl** - A string describing the controller/action that will handle the updates to the table data. This is used only when editable is set to true.
11. **cssFile** - A string representing the path to a css file compliant with jQuery's UI Themes. Example: 'cssFile'=>'/css/trontastic/jquery-ui-1.8.16.custom.css'. You can create your own theme in here: [jQuery UI ThemeRoller](http://jqueryui.com/themeroller/ "jQuery UI Themeroller"). If not specified the table will use its internal CSS definition.
13. **exportUrl** - (Required for CSV exporting) A string describing the controller/action that will create the CSV file. If not provided the export button will not be shown.

## Requirements
I developed this extension using Yii 1.1.7 and PHP 5.3. Static calls to methods of a class have been fixed to support PHP < 5.3.
Also might be too obvious but Javascript must be enabled.

## Installation
To install extract the files under **yourapplication/protected/extensions/**.

The widget can be included in any view file by using


	$this->widget('ext.htmlTable.htmlTable',array(
    	'ajaxUrl'=>'site/handleHtmlTable',
    	'arProvider'=>'',    
    	'collapsed'=>false,
    	'columns'=>$columnsArray,
    	'cssFile'=>'',
    	'editable'=>true,
    	'enableSort'=>true,
    	'exportUrl'=>'site/export',
    	'extra'=>'Additional Information',
    	'footer'=>'Total rows: '.count($rowsArray).' By: José Rullán',
    	'formTitle'=>'Form Title',
    	'rows'=>$rowsArray,
    	'sortColumn'=>1,
    	'sortOrder'=>'desc',
    	'subtitle'=>'Rev 1.3.5',
    	'title'=>'Table 2',
	));


##How to load data
This widget can be used with static data or by providing a CActiveDataProvider. 
You will probably want to "massage" the data coming from the provider, and then prepare the two arrays required. 
However if you want to tabulate the data directly from the provider then you can omit the rows and columns arrays. 
See below for examples.

####Usage with static data
To use this extension first prepare the data to be shown as explained above. Minimally you should create the columns array and a rows array. The columns array will consist of an array that holds strings to be used as columns header.
The rows array holds the data to be tabulated. Data in the rows must correspond to the columns.

Let's see the following example. 

	$columnsArray = array('id','name','lastname','tel','email');
	$rowsArray = array(
		array(1,'Jose','Rullan','123-123-1234','jose@email.com'),
		array(2,'Fred','Frederick','123-123-1234','fred@email.com'),
		array(3,'Paul','Horstmann','123-123-1234','phor@email.com'),
		array(4,'Kim','Guptha','123-123-1234','kgup@email.com'),
		array(5,'Fred','Frederick','123-123-1234','fred@email.com'),
		array(6,'Querty','Uiop','123-123-1234','querty@email.com'),
		array(7,'Albert','Febensburg','123-123-1234','a@email.com'),
		array(8,'Dan','Sieg','123-123-1234','da@email.com'),
		array(9,'Janice','Breyfogle','123-123-1234','janice@email.com'),
		array(10,'Cornelious','Ape','123-123-1234','potapes@email.com'),	
	);

	$this->widget('ext.htmlTable.htmlTable',array(
		'collapsed'=>true,
		'enableSort'=>true,
		'title'=>'My Simple HTML Table',
		'subtitle'=>'Rev 1.3.3',
		'columns'=>$columnsArray,
		'rows'=>$rowsArray,
		'footer'=>'Total rows: '.count($rowsArray).' By: José Rullán'
	));
	
	$this->widget('ext.htmlTable.htmlTable',array(
	    'ajaxUrl'=>'site/handleHtmlTable',
	    'arProvider'=>'',    
	    'collapsed'=>false,
	    'columns'=>$columnsArray,
	    'cssFile'=>'',
	    'editable'=>true,
	    'enableSort'=>true,
	    'exportUrl'=>'site/export',
	    'extra'=>'Additional Information',
	    'footer'=>'Total rows: '.count($rowsArray).' By: José Rullán',
	    'formTitle'=>'Form Title',
	    'rows'=>$rowsArray,
	    'sortColumn'=>1,
	    'sortOrder'=>'desc',
	    'subtitle'=>'Rev 1.3.5',
	    'title'=>'Table 2',
	));	


This example will render two tables. The first table is initially collapsed, and by default it will be sorted using the first column in ascending order. The second will be expanded and it will use the second column as the default sorting column in descending order.

####Usage with CActiveDataProvider
As an alternative you can pass a CActiveDataProvider to the table as a parameter, arProvider, and it will get the rows and columns automatically from the model based on the provider's criteria. This is simpler to use, see the code:

	// <-- Create ActiveDataProvider -->
	$woProvider = new CActiveDataProvider('Workorder', array(
		'criteria'=>$criteria,
	));
	
	$this->widget('ext.htmlTable.htmlTable',array(
		'collapsed'=>false,
		'arProvider'=>$woProvider,
	));

In this example the htmltable receives a CActiveDataProvider. If the title is not set, it will use the data provider's model classname as title. 

##Edit data in the table

The feature to edit the data in the table requires you to set the editable parameter to true and specify a controller/action url parameter for the AJAX request. 

The table will verify all modified rows and will send them to an action for server-side processing, using the ajaxUrl property.

When editable is set to true, the table will display an edit icon ![edit mode](http://i1181.photobucket.com/albums/x437/jerullan/editmode.png "") in the upper right corner that will enable the edit mode. Once clicked, the table will be in edit mode and upon clicking any row, a form will pop-up with the row's data. 

To return to view mode click the view icon ![view icon](http://i1181.photobucket.com/albums/x437/jerullan/viewmode.png "").

When a change is made to the table, a send icon ![send icon](http://i1181.photobucket.com/albums/x437/jerullan/send.png "") will show up in the upper right corner. This icon indicates that a row has been modified (without validation) and upon clicking on it it will trigger the AJAX http POST request to the server.

##Export to CSV
To set up the CSV export feature you need to declare the "export" action in your controller.
The "export" action included in the widget's action folder will export the csv to a folder of your choosing.
The folder to put the csv files must be specified in the declaration of the action. 
For example, if you want to add this action to SiteController you would add the following code to the actions() function: 


	public function actions()
	{
	    return array(
	        'exportTable'=>array(
	            'class'=>'ext.htmltableui.actions.HtmlExportCsv',
	            'path'=>'/csv/',
	        ),
	    );
	}

Here I named my action `'exportTable'`. You could change that to any name you would like the action to have. 
You then need to configure the widget's exportUrl property with this controller/action. 
In this case I would set the exportUrl property to `'site/export'`. 
What's important is that the class property must be set to the right CAction class path as shown above. 
Here I'm telling the widget that the generated files will be in /path/to/myapplication/csv/.

=====================================================================================

## Example

![version 1.3.3](http://i1181.photobucket.com/albums/x437/jerullan/HtmlTableUi_New-1.png "HtmlTableUi 1.3.3")

#### View Code
This is the code in the demo example.

	$columnsArray = array('id','name','lastname','tel','email');
	$rowsArray = array(
		array(1,'Andres','Irizarry','123-123-1234','jose@email.com'),
		array(2,'Fred','Frederick','123-123-1234','fred@email.com'),
		array(3,'Paul','Horstmann','123-123-1234','phor@email.com'),
		array(4,'Kim','Guptha','123-123-1234','kgup@email.com'),
		array(5,'Fred','Frederick','123-123-1234','fred@email.com'),
		array(6,'Elizabeth','Espiano','123-123-1234','querty@email.com'),
		array(7,'Albert','Febensburg','123-123-1234','a@email.com'),
		array(8,'Dan','Sieg','123-123-1234','da@email.com'),
		array(9,'Janice','Breyfogle','123-123-1234','janice@email.com'),
		array(10,'Cesar','Subots','123-123-1234','potapes@email.com'),	
	);
	
	$this->widget('ext.htmltableui.htmlTableUi',array(
	    'ajaxUrl'=>'site/handleHtmlTable',
	    'arProvider'=>'',    
	    'collapsed'=>true,
	    'columns'=>$columnsArray,
	    'cssFile'=>'',
	    'editable'=>true,
	    'enableSort'=>true,
	    'exportUrl'=>'site/export',
	    'extra'=>'Additional Information',
	    'footer'=>'Total rows: '.count($rowsArray).' By: José Rullán',
	    'formTitle'=>'Form Title',
	    'rows'=>$rowsArray,
	    'sortColumn'=>1,
	    'sortOrder'=>'desc',
	    'subtitle'=>'Rev 1.3.5',
	    'title'=>'Table 2',
	));



#### Controller Code
You might want to handle the data sent after editing the table in the controller. Basically you need to add an action to the controller and then do whatever you want with the data. This example just returns the data back to the widget to show it in a javascript pop-up.

	class SiteController extends Controller
	{
		
		public function actionHandleHtmlTable(){
			sleep(2);
			if(isset($_POST)){
				//return the POST variable back
				//the widget will show an alert() with this data
				print_r($_POST);
			}
		}
	}


#### Data Format
This is the resulting array as received by the controller/action:

	Array
	(
	    [id] => yw1
	    [title] => Table 2
	    [subtitle] => Rev 1.3.3
	    [extra] => Additional Information
	    [columns] => Array
	        (
	            [0] => id
	            [1] => name
	            [2] => lastname
	            [3] => tel
	            [4] => email
	        )
	
	    [row-0] => Array
	        (
	            [id] => 1
	            [name] => Jose
	            [lastname] => Rullan
	            [tel] => 123-123-1234
	            [email] => jose@email.com
	        )
	
	    [row-1] => Array
	        (
	            [id] => 2
	            [name] => Fred
	            [lastname] => Frederick
	            [tel] => 123-123-1234
	            [email] => fred@email.com
	        )
	
	    [footer] => Total rows: 10 By: José Rullán
	    [timezone] => 4
	)


Each table element is sent using an associative array key. For example the widget's id is sent using the [id] key.

#### How the editable and export functionalities work

To implement these two functionalities the widget relies on jQuery selectors. When editing the widget will use the controller/action specified in **ajaxUrl**. The jQuery selectors will include only the modified rows in the data to be sent. Then the widget will use a jQuery ajax request to send the data to the controller/action. 

Similarly, when exporting the widget will use the **exportUrl** to specify the controller/action that will handle the data sent. The difference is that when exporting the jQuery selectors will include all the data in the table, not just the modified values. Because they are very similar that code might be refactored in future iterations of the widget.

Thanks to mesmer for his feedback.

### Themes
To use themes you just need to specify the location of your css file in the cssFile property. If left blank or not used, the widget will use its default theme.


	$this->widget('ext.htmlTableUi.htmlTableUi',array(
		'title'=>'Simple, Sortable, and Editable Table',
		'subtitle'=>'Rev 1.3.3',
		'columns'=>$columnsArray,
		'rows'=>$rowsArray,
		'footer'=>'Total rows: '.count($rowsArray).' By: José Rullán',
		'collapsed'=>false,
		'enableSort'=>true,
		'editable'=>true,
		'ajaxUrl'=>'site/handleHtmlTable',
		'cssFile'=>'/css/trontastic/jquery-ui-1.8.16.custom.css',
	));


