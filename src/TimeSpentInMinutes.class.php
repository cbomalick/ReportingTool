<?php

class TimeSpentInMinutes {
    public function __construct(){
        
        $this->earliestDate = "";
        $this->latestDate = "";
        $this->colors = [
            "ddedea",
            "daeaf6",
            "e8dff5",
            "fce1e4",
            "fcf4dd",
            "dcd9f8",
            "f7d8c3",
            "ffc8c3",
            "bccef4",
            "b5dcf9",
            "a9e5e3",
            "a2edce",
            "a0d995",
            "c5d084",
            "d2c897",
            "fae187",
            "e8ba86"
            ];

            $this->colors = [
                "ddedea",
                "daeaf6",
                "e8dff5",
                "fce1e4",
                "fcf4dd",
                "dcd9f8"
                ];


    }

    public function generateStyles(){
        foreach($this->colors as $count=>$color){
            Echo".table .group{$count} {
                background-color: #{$color};
            } \n";
        }
    }

    public function generateReport($rawData){
        $data = $this->importData($rawData); //Convert data into arrays

        //Get unique Edited By values
        $uniqueOwners = array_unique(array_column($data, 'Edited By'));
        $caseReasons = array_unique(array_column($data, 'Case Reason'));

            //Loop through and sort into groups by each Case Owner
            foreach($uniqueOwners as $editedBy){
                $filteredArray[] = 
                array_filter($data, function($element) use($editedBy){
                return $element['Edited By'] == $editedBy;
                });
            }

            //Loop through the individual arrays for each analyst
            foreach($filteredArray as $groupedCases){
                $analyst = new Analyst($groupedCases);
                Echo"<pre>";
                var_dump($analyst);
                Echo"</pre><br/><br/>";
            }
            
            

        //Final output
        //Case Owner
        //Subtotal Cases
        //Subtotal Time
    }

    private function importData($rawData){
        $data = explode(PHP_EOL, $rawData);
        $headers = explode(",", substr_replace($data[0],"", -1));
        unset($data[0]); //Remove headers from array after storing in $headers
        $currentRow = 1;

        foreach($data as $row){
            //Remove newline from end of string, except if last row
            if($currentRow < count($data)){
                $row = substr_replace($row,"", -1);
                $currentRow++;
            }
            
            //Break row string into individual columns
            $columns = explode(",", $row);

            //Begin importDataing the  values in each column
                //Transform array into key=>value
                $i = 0;
                foreach ($columns as $column){
                    $case[$headers[$i]] = $column;
                    $case["Time Spent"] = (int) $case["New Value"] - (int) $case["Old Value"]; //Calculate the difference between Old and New values
                    $i++;
                }
                unset($case["Old Value"]); //Discard data that is no longer needed
                unset($case["New Value"]);
                unset($case["Edit Date"]);
                $cleanData[] = $case;
        }
        return $cleanData;
    }

    public function printTable(){
        $count = 1;
        $reasons = ["Coaching","Inquiry","Service Request","Support Task","Tech Interface Activation"];
        $totalCases = 0;
        $totalTime = 0;

        Echo"<table class=\"table padded\">";

        Echo"<thead>
            <tr>
                <th class=\"\">Analyst</th>
                <th class=\"\">Case Reason</th>
                <th class=\"\">Case Count</th>
                <th class=\"\">Time Spent</th>
            </tr>
        </thead>";

        Echo"<tbody>";
            foreach($this->colors as $count=>$color){
                $subtotalCases = 0;
                $subtotalTime = 0;

                foreach($reasons as $reason){
                    $caseCount = rand(2,20);
                    $timeSpent = round((rand(35,95)/10) * $caseCount);

                    //Add to subtotal
                    $subtotalCases += $caseCount ?? 0;
                    $subtotalTime += $timeSpent ?? 0;
                    
                    Echo"
                    <tr class=\"group{$count}\">
                        <td class=\"\">Analyst {$count}</td>
                        <td class=\"\">{$reason}</td>
                        <td class=\"\">{$caseCount}</td>
                        <td class=\"\">{$timeSpent}</td>
                    </tr>";
                }
                
    
                Echo"<tr class=\"group{$count} darken\">
                    <td class=\"bold\">Subtotal</td>
                    <td class=\"\"></td>
                    <td class=\"bold\">{$subtotalCases} cases</td>
                    <td class=\"bold\">{$subtotalTime} minutes</td>
                </tr>"; 
                $totalCases += $subtotalCases;
                $totalTime += $subtotalTime;
            }
            
            Echo"<tr class=\"grandtotal\">
                <td class=\"bold\">Grand Total</td>
                <td class=\"\"></td>
                <td class=\"bold\">{$totalCases} cases</td>
                <td class=\"bold\">{$totalTime} minutes</td>
            </tr>
        </tbody>"; 
    
        Echo"</table>";
    }
}

?>