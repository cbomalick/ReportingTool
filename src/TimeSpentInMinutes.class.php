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

            //Loop through and sort into groups by each Case Owner
            foreach($uniqueOwners as $editedBy){
                $filteredArray[] = 
                array_filter($data, function($element) use($editedBy){
                return $element['Edited By'] == $editedBy;
                });
            }

            //Build each analyst
            foreach($filteredArray as $groupedCases){
                $analyst = new Analyst($groupedCases);

                //Calculate grand totals from all analysts
                $this->grandCount += $analyst->totalCases;
                $this->grandTime += $analyst->totalTime;

                //Collect analysts into array
                $this->analysts[] = $analyst;
            }
            
            //Print the report
            $this->printTable();

            // Echo"<pre>";
            //     var_dump($this);
            //     Echo"</pre><br/><br/>";
            
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
        //Cycle through each analyst
            foreach($this->analysts as $analyst){
                    //Print each Case Reason and the counts associated with them
                foreach($analyst->caseReasons as $caseReason){
                Echo"
                <tr class=\"group{$count}\">
                    <td class=\"\">{$analyst->name}</td>
                    <td class=\"\">{$caseReason}</td>
                    <td class=\"\">{$analyst->caseCount[$caseReason]}</td>
                    <td class=\"\">{$analyst->caseTime[$caseReason]}</td>
                </tr>";
                }

                //Print the subtotals
                Echo"<tr class=\"group{$count} darken\">
                    <td class=\"bold\">Subtotal</td>
                    <td class=\"\"></td>
                    <td class=\"bold\">{$analyst->totalCases} cases</td>
                    <td class=\"bold\">{$analyst->totalTime} minutes</td>
                </tr>"; 

                $count++; //Used to make color unique for each analyst
            }
            
            Echo"<tr class=\"grandtotal\">
                <td class=\"bold\">Grand Total</td>
                <td class=\"\"></td>
                <td class=\"bold\">{$this->grandCount} cases</td>
                <td class=\"bold\">{$this->grandTime} minutes</td>
            </tr>
        </tbody>"; 
    
        Echo"</table>";
    }
}

?>