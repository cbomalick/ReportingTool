<?php

//Display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Echo"<pre>";
// var_dump(get_defined_vars());
// Echo"</pre><br><br>";


require_once("src/TimeSpentInMinutes.class.php");
require_once("src/row.class.php");
require_once("src/caseowner.class.php");

if(!empty($_POST)){
    $data = htmlentities($_POST['data'], ENT_QUOTES);
    $inputType = htmlentities($_POST['inputType'], ENT_QUOTES);
  } else {
    $data = '';
    $inputType = '';
  }
  
$report = new TimeSpentInMinutes();

?>

<html>
	<head>
		<title>Time Spent in Minutes</title>
		<link href="css/styles.css" rel="stylesheet" type="text/css" />
        <script src="js/jquery-3.4.1.js"></script>
        <script type="text/javascript" src="js/jquery.tablesorter.js"></script>
        <script>
            //Table sorting
            $(function() {
                $(".sortable").tablesorter({
                });
            });
            </script>
            <style>
                <?php
                    $report->generateStyles();
                ?>

            </style>
	</head>
	<body>
        <!-- Navigation Bar -->
        <div class="navigationbar" >
            <div class="navigation" style="min-height: 65px;">
            </div>
        </div> 
        
        <div class="panel">
            
        <!-- Page Content -->
            <div class="content">
                <h2>Time Spent in Minutes</h2>
                <div class="boxwrapper">
                    <div class="box smallbox">
                        <div class="boxcontent">
                            <h2>Paste CSV below</h2>
                                <form autocomplete="off" action="" id="data" method="post" enctype="multipart/form-data">

                                    <?
                                    //Paste from CSV Source<input type="radio" name="inputType" value="test1"> <br/>
                                    //Paste from Excel<input type="radio" name="inputType" value="test2"> <br/><br/>
                                    ?>

                                    <textarea name="data" id="data">Case Number,Edit Date,Case Reason,Old Value,New Value,Edited By
03173907,6/13/2022 3:25 PM,Tech Interface Activation,240,380,Albaro Moya
03201262,6/10/2022 4:01 PM,Support Tasks,,60,Albaro Moya
03231699,6/13/2022 2:47 PM,Service Request,,45,Anthony Trokey
03187410,6/13/2022 1:11 PM,Tech Interface Activation,115,205,Anthony Trokey</textarea>

                                    <br/><br/>
                                    <button class="button" type="submit" name="submit" id="submit">Submit</button>
                                  </form>
                        </div>
                    </div>
                </div>
                <div class="boxwrapper">
                    <div class="box largebox">
                        <div class="boxcontent">
                            <div class="table">
                                <?php
                                //$report->printTable();
                                $report->generateReport($data);
                                // Echo"<pre>";
                                // var_dump($clean);
                                // Echo"</pre><br/><br/>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div id="footer" class="footer">
            <?php 
            $date = date("Y");
            Echo"<p>Copyright &copy; $date Collin Bomalick</p>";
            ?>
        </div>	
        
    </body>
</html>