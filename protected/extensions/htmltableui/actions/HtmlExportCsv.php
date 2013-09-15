<?php
/*
 * ExportCsvAction
 * 
 * To use this action you must register it under the controller
 * actions
 * 
 * public function actions()
 *  {
 *      return array(
           'export'=>array(
  				'class'=>'ext.htmltableui.actions.HtmlExportCsv',
  				'path'=>'/csv/',
  			),
 *      );
 *  }
 * 
 */
class HtmlExportCsv extends CAction
{
	public $path; 
	
	public function run(){
		if(isset($_POST)){
            // Check if the POST variable is an array
            if(is_array($_POST)){
				$secondsoffset = 2*intval($_POST['timezone'])*60*60;
				$timestamp = mktime(date("h"))+$secondsoffset;

				$filepath = Yii::getPathOfAlias("webroot").$this->path;
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
				
				if(isset($_POST["title"])){
					fwrite($fh,"\"".$_POST["title"]."\""."\n");
				}
				if(isset($_POST["subtitle"])){
					fwrite($fh,"\"".$_POST["subtitle"]."\""."\n");
				}
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
					
					if(is_array($row)){
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
					echo Yii::app()->baseUrl.$this->path.$filename.".csv";//"Export Successful!";				
				}
            }else{
	            //print_r($_POST);
	            echo "Error Exporting";        	
            }			
        }
	}	
}
?>