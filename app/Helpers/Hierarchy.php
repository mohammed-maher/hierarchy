<?php 
namespace App\Helpers;

use \Exception;

class Hierarchy{
    
    /**
     * Generate base hierarchy from manager=>employee pairs
     */
    public static function sortHierarchy($data){
        $hierarchy = [];
        foreach($data as $employee=>$manager){
            $hierarchy[$manager][$employee]=[];
        }

        foreach($hierarchy as $key=>$value){
            if(!empty($hierarchy[$key])){
                self::replaceHeirachyElement($hierarchy,$hierarchy[$key]);
            }
        }

        //Throw an exception if more than one root boss found
        if(count($hierarchy)>1){
            throw new Exception("Invalid data submitted");
        }

        return $hierarchy;
    }

    /**
     * Recursively loop through array elements and replace keys from root level to their respective 
     * sub-level position.
     */
    private static function replaceHeirachyElement(&$baseArray,&$subArray){
        foreach($subArray as $k=>$v){
            if(!empty($baseArray[$k])){
                $subArray[$k]=$baseArray[$k];
                unset($baseArray[$k]);
            }
            self::replaceHeirachyElement($baseArray,$subArray[$k]);
        }
    }
}