function htmltableUiToggleDiv(myId){
	// Create the selectors
	var tbody = "#"+myId+" div.body";
	var theader = "#"+myId+" div.header";
	var hideButton = "#"+myId+" #hidecontrol";
	var showButton = "#"+myId+" #showcontrol";
	
	// Verify display property (CSS) of tbody
	var display = $(tbody).css("display");

	if(display=="none"){
		$(tbody).show("slide",{ direction: "up" }, 200);
		$(theader).removeClass("ui-corner-bottom");
		$(hideButton).show();
		$(showButton).hide();
	}else{
		$(tbody).hide("slide",{ direction: "up" }, 200);
		$(theader).addClass("ui-corner-bottom");
		$(hideButton).hide();
		$(showButton).show();
	}
}

function htmltableUiToggleMode(myId){
	var buttonSelector = "#"+myId+" div.header table.header-table tr td.header-mode div.header-mode-button";
	// If in edit mode, change the rows
	if($(buttonSelector).hasClass("editmode")){
		htmltableUiView(myId);
	}else{
		htmltableUiEdit(myId);
	}
}

function htmltableUiEdit(myId){
	var buttonSelector = "#"+myId+" div.header table.header-table tr td.header-mode div.header-mode-button";
	var rowSelector = "#"+myId+" div.body table tbody tr";
	// Enter Edit Mode
	$(rowSelector).addClass("editrow");
	$(buttonSelector).addClass("editmode");
}

function htmltableUiView(myId){
	var buttonSelector = "#"+myId+" div.header table.header-table tr td.header-mode div.header-mode-button";
	var rowSelector = "#"+myId+" div.body table tbody tr";
	var formSelector = "#"+myId+" div.form";

	$(rowSelector).removeClass("editrow");
	$(buttonSelector).removeClass("editmode");
	$(formSelector).hide();
	htmltableUi_globalLastPos=null;
}


/*
 * Function to return the 
 * column headers.
 * Returns an array with all the 
 * column headers.
 */
function htmltableUiGetColumns(myId){
	// Get the column's headers
	var columnsSelector = "#"+myId+" div.body table.body-table tr.body-header th div.column-name span"
	var columns = new Array();
	$(columnsSelector).each(function(index){
		columns[index] = $(this).text();
	});	
	return columns;
}

/*
 * Global Variables
 * 
 * These are variables needed to store globally
 * some important parameters such as:
 * htmltableUi_globalId - This is the id of the widget been
 * used.
 * htmltableUi_globalRow - This is the row being clicked
 * html_globalFormData - Data extracted from the form.
 */
//Global Variables needed for row manipulation
var htmltableUi_globalId=null;
var htmltableUi_globalRow=null;
var htmltableUi_globalFormData=null;
var htmltableUi_globalLastPos=null;

/*
 * htmltableShowUiForm
 *  
 * This function is used to show the
 * edit form when a row is clicked. It has the following 
 * arguments:
 * myId - This is the Id of the particular widget that is being manipulated
 * myRow - This is the element that was clicked
 * formTitle - The title to be shown in the form.
 */
function htmltableShowUiForm(myId,myRow,formTitle){
	// Widget Reference
	var widgetSelector = "#"+myId;
	var divSelector = "#edit-form";//+myId+" div.form";
	var titleSelector = "#edit-form-header-title";
	var formSelector = "#table-form";//+myId+"-form";
	var rowSelector = "#"+myId+" div.body table tbody tr";
	
	// Close and Destroy form if already
	// opened.
	htmltableCloseUiForm();
	
	// Check if row is in edit mode
	if($(rowSelector).hasClass("editrow")){
		// Set the global variable with the clicked row
		htmltableUi_globalId = myId;
		htmltableUi_globalRow = myRow;

		// Set Form Title
		//$(divSelector+" .ui-widget-header").text(formTitle);
		$(titleSelector).text(formTitle);
		
		// Show the form
		var widgetLeft = $(widgetSelector).offset().left;
		var widgetTop = $(widgetSelector).position().top;
		var widgetWidth = $(widgetSelector).outerWidth();
		var widgetHeight = $(widgetSelector).outerHeight();
		if(htmltableUi_globalLastPos == null){
			$(divSelector).css('top',widgetTop);
			$(divSelector).css('left',widgetLeft+widgetWidth/4);
		}else{
			$(divSelector).css('top',htmltableUi_globalLastPos.top);
			$(divSelector).css('left',htmltableUi_globalLastPos.left);
		}
		$(divSelector).show("slide",{ direction: "up" }, 100);
		
		var columns = htmltableUiGetColumns(myId);
		
		// Get the html elements of the row that was clicked
		// and create the form
		var myString = "";
		$(myRow).children().each(function(index) {
			var openDiv = "<div class='edit-table-row'><table><tbody><tr>";
			var label = "<td><label for='"+columns[index]+"'>"+columns[index]+"</label></td>";
			var input = "<td><input type='text' name='"+columns[index]+"' class='text' value='"+$(this).text()+"'></input></td>";
			myString = myString + openDiv + label + input + "</tr></tbody></table></div>";
		});
		var changeButton = "<button type='button' class='ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all' onclick='htmltableUiModifyRow(htmltableUi_globalRow);'>Change</button>";
		var closeButton = "<button type='button' class='ui-button  ui-button-text-only ui-widget ui-state-default ui-corner-all' onclick='htmltableCloseUiForm();'>Close</button>";
		var submitButton = "<input type='submit' class='ui-button  ui-button-text-only ui-widget ui-state-default ui-corner-all' value='submit' class='button'></input>";
		$(formSelector).append(myString+changeButton+closeButton);
		$(formSelector+" button").button();
		var position = $(rowSelector).offset();
		$(divSelector).offset(position);
		$(divSelector).draggable();
	}
}

/*
 * htmltableUiModifyRow
 * 
 * This function changes the table row
 * using the data in the form.
 * It uses one parameter:
 * myRow - Object representing the tr element
 * that was clicked.
 */
function htmltableUiModifyRow(myRow){
	htmltableUi_globalFormData = new Array();
	
	// Get all the form entries
	$("#table-form input.text").each(function(index){
		htmltableUi_globalFormData[index] = $(this).val();
	});
	
	// Write to the table
	$(myRow).children().each(function(index) {
		$(this).text(htmltableUi_globalFormData[index]);
	});
	$(myRow).addClass("modified");
	
	htmltableUi_globalFormData=null;
	
	// Show send button
	var sendButton = "#"+htmltableUi_globalId+" div.header table.header-table tr td.header-mode div.header-send-button";
	$(sendButton).show();
	
	//Update Table Sorter Plugin
	$("#"+htmltableUi_globalId+"-body-table").trigger("update");
}

/*
 * htmltableCloseUiForm
 * 
 * This function cleanups the widget
 * First it hides the Form's div container
 * then it removes all form elements from the form
 * Then it resets all the global variables
 */
function htmltableCloseUiForm(){
	$("#edit-form").hide();	
	$("#table-form").children().remove();
	htmltableUi_globalId=null;
	htmltableUi_globalRow=null;
	htmltableUi_globalFormData=null;
}


/*
 * htmltableExportCsv
 * 
 * This function sends the whole table to 
 * a controller action using an AJAX http request
 * Is the responsibility of the controller action to
 * create the CSV file with the data sent through this
 * function.
 */
function htmltableExportCsv(myId,url){
	var rowSelector = "#"+myId+" div.body table tbody tr";
	var titleSelector = "#"+myId+" div.header table.header-table div.header-container span.title";
	var extraSelector = "#"+myId+" div.header table.header-table div.header-container span.extra";
	var subtitleSelector = "#"+myId+" div.body table.body-table thead tr.body-subtitle span.subtitle"
	var footerSelector = "#"+myId+" div.body table.body-table tfoot tr.body-footer td span.footer";

	var rows = new Object();
	var columns = htmltableUiGetColumns(myId);
	
	rows['id'] = myId;
	rows['title'] = $(titleSelector).text();
	rows['subtitle'] = $(subtitleSelector).text();
	rows['extra'] = $(extraSelector).text();
	rows['columns'] = columns;
	
	//<---- GET ALL ROWS ---->
	$.each($(rowSelector),function(key,value){
		var rowKey = "row-"+key;
		var myElements = new Object();

		$(value).children().each(function(index){
			myElements[columns[index]] = $(this).text();
		});
		
		//...and add them as key-value pairs
		//...in an object called rows
		rows[rowKey] = myElements;
		myElements = null;
	});
	
	rows['footer'] = $(footerSelector).text();
	rows['timezone'] = get_time_zone_offset();
	
	//<---- SEND XHR ----->
	var jqXHR = $.ajax({
		url: url,
		type: "POST",
		data: $.param(rows),
		beforeSend: function(xhr,settings){
			$("#"+myId+"-send-button")
				.addClass("ajaxLoading");
		},
		success: function(fileUrl,status,xhr){
			// Open the file URL
			window.open(fileUrl);
		},
		error: function(xhr,errorText,errorObj){
			alert(errorText);
		},
		complete: function(xhr,status){
			$(rowSelector).removeClass("modified");
			$("#"+myId+"-send-button")
				.removeClass("ajaxLoading")
				.hide();
		}
	});
	
	htmltableUiView(myId);
	htmltableCloseUiForm();
}


/*
 * htmltableUiSend
 * 
 * This function sends the the modified 
 * rows through an AJAX XHTTPRequest. 
 * It uses three parameters:
 * myId - the widget id
 * url - url for the request in a Controller/Action format
 * target - an element where the answer of the request should be
 * directed to
 */
function htmltableUiSend(myId,url,target){
	var modifiedSelector = "#"+myId+" div.body table tbody tr.modified";
	var rowSelector = "#"+myId+" div.body table tbody tr";
	var titleSelector = "#"+myId+" div.header table.header-table div.header-container span.title";
	var extraSelector = "#"+myId+" div.header table.header-table div.header-container span.extra";
	var subtitleSelector = "#"+myId+" div.body table.body-table thead tr.body-subtitle span.subtitle"
	var footerSelector = "#"+myId+" div.body table.body-table tfoot tr.body-footer td span.footer";

	var rows = new Object();
	var columns = htmltableUiGetColumns(myId);
	
	rows['id'] = myId;
	rows['title'] = $(titleSelector).text();
	rows['subtitle'] = $(subtitleSelector).text();
	rows['extra'] = $(extraSelector).text();
	rows['columns'] = columns;
	
	//<---- GET MODIFIED ROWS ---->
	// For each modified row....
	$.each($(modifiedSelector),function(key,value){
		//var rowKey = "htmltable-"+myId+"-row-"+key;
		var rowKey = "row-"+key;
		var myElements = new Object();

		$(value).children().each(function(index){
			myElements[columns[index]] = $(this).text();
		});
		
		//...and add them as key-value pairs
		//...in an object called rows
		rows[rowKey] = myElements;
		myElements = null;
	});
	
	rows['footer'] = $(footerSelector).text();
	rows['timezone'] = get_time_zone_offset();
	
	//<---- SEND XHR ----->
	var jqXHR = $.ajax({
		url: url,
		type: "POST",
		data: $.param(rows),
		beforeSend: function(xhr,settings){
			$("#"+myId+"-send-button")
				.addClass("ajaxLoading");
			//alert(decodeURIComponent($.param(rows)));
		},
		success: function(returnedData,status,xhr){
			alert(returnedData);
			//$(target).text(returnedData);
		},
		error: function(xhr,errorText,errorObj){
			alert(errorText);
		},
		complete: function(xhr,status){
			$(rowSelector).removeClass("modified");
			$("#"+myId+"-send-button")
				.removeClass("ajaxLoading")
				.hide();
		}
	});
	
	htmltableUiView(myId);
	htmltableCloseUiForm();
}


/*
 * htmltableUiSort
 * 
 * This function enables the TableSorter 2.0
 * jQuery plugin on the table for client sided
 * sorting. It has three parameters:
 * myId - the widget's id
 * sortColumn - an index starting from 0 referring
 * to the column to use for sorting initially
 * sortOrder - 0-ASC, 1-DESC
 * 
 */
function htmltableUiSort(myId,sortColumn,sortOrder){
	// Enable table sorter for the table
	var selector = '#'+myId+'-body-table';
	
	$(selector).tablesorter({
		cssHeader: "unsorted",
		sortList: [[sortColumn,sortOrder]]
	});

	// Set the cursor to a hand or pointer over the
	// cells used to sort.
	selector = selector + ' thead tr.body-header th';
	$(selector).css("cursor","pointer");
}

/*
 * get_time_zone_offset
 * 
 * This function provides the GMT timezone difference
 * from the browser to be used in the server.
 * http://josephscott.org/archives/2009/08/detecting-client-side-time-zone-offset-via-javascript/
 */
function get_time_zone_offset( ) {
	var current_date = new Date( );
	var gmt_offset = current_date.getTimezoneOffset( ) / 60;
	return (gmt_offset);
}
