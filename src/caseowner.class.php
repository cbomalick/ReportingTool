<?php

class Analyst {
    public function __construct($groupedCases){
        $this->populateAnalyst($groupedCases);
    }

    private function populateAnalyst($groupedCases){
        //Loop through the cases and perform counts, data manipulation, etc
       
        //Get analyst name
        $name = array_values(array_unique($groupedCases));
        $this->name = $name[0]["Edited By"];

        //Get unique Case Reasons
        foreach($groupedCases as $case){
            $caseReason = $case["Case Reason"];
            if (!in_array($caseReason, $this->caseReasons))
            {
                $this->caseReasons[] = $caseReason; 
            }

            //Perform subtotal count since we're already looping the data anyway
            $this->caseCount[$caseReason] += 1;
            $this->caseTime[$caseReason] += (int) $case["Time Spent"];
        }

        sort($this->caseReasons); //Sort case reasons alphabetically

        //Get total count
        foreach($this->caseCount as $count){
            $this->totalCases += $count;
        }

        //Get total time
        foreach($this->caseTime as $time){
            $this->totalTime += $time;
        }
    }

}

?>