<?php

class Analyst {
    public function __construct($groupedCases){

        $this->populateAnalyst($groupedCases);

        $this->name = "";
        $this->caseReasons = [
            "Case Count" => 0,
            "Time Spent" => 0
        ];
        $this->subtotal = 0;
    }

    private function populateAnalyst(){
        //Loop through the cases for each analyst and perform counts, data manipulation, etc
    }

}

?>