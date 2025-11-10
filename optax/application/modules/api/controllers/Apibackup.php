<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apibackup extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
		));
	}

  public function calcFertilizer(){
    $data = varPost();
    $landArea = $data['landArea'];
    $expectingYield = $data['expectingYield'];
    $soilAnalysisN = 0;
    $soilAnalysisOg = 0;
    
    $expectingYieldAcre = $expectingYield * 15.932;
    $fertilizerN = 35 + (1.2 * $expectingYieldAcre) - (8 * $soilAnalysisN) - (0.14 * $expectingYieldAcre * $soilAnalysisOg);
    $fertilizerNKg = $fertilizerN * 1.1198;
    
    $outFertilizerN = $fertilizerNKg * 100 / 45;
    $outWithoutAnalysis = $outFertilizerN * $landArea;
    
    // SP36
    $soilAnalysisBray = 15;
    $pFertilizer = (25 - $soilAnalysisBray) * 4;
    $pFertilizerKg = $pFertilizer * 1.1198;
    
    $outPFertilizer = ($pFertilizerKg * 100) / 36;
    
    // KCL
    $resAnalysisK2O = 25;
    $recomKalium = 125 - $resAnalysisK2O;
    $recomKaliumKg = $recomKalium * 1.1198;
    $outRecomKalium = $recomKaliumKg * 100 / 60;
    
    // Application Single
    $singleUrea = $outFertilizerN * $landArea;
    
    // Application Compound
    $compNpk = $pFertilizerKg * 100 / 15;
    $compUrea = ($fertilizerNKg - $pFertilizerKg) * 100 / 45;
    $compKcl = ($recomKaliumKg - $pFertilizerKg) * 100 / 60;
    
    $arrUreaF = [20, 40, 40];
    $urea = $this->retriveFertilizer($arrUreaF, $singleUrea);
    
    $arrSp36F = [20, 80];
    $sp36 = $this->retriveFertilizer($arrSp36F, $outPFertilizer);
    
    $arrKclF = [20, 40, 40];
    $kcl = $this->retriveFertilizer($arrKclF, $outRecomKalium);
    
    $straight = [];
    $straight["urea"] = $urea;
    $straight["sp36"] = $sp36;
    $straight["kcl"] = $kcl;
    
    $arrCompNpk = [20, 80];
    $resCompNpk = $this->retriveFertilizer($arrCompNpk, $compNpk);
    
    $arrCompUreaF = [20, 40, 40];
    $resCompUrea = $this->retriveFertilizer($arrCompUreaF, $compUrea);
    
    $arrCompKclF = [20, 40, 40];
    $resCompKcl = $this->retriveFertilizer($arrCompKclF, $compKcl);
    
    $compound = [];
    $compound["npk"] = $resCompNpk;
    $compound["urea"] = $resCompUrea;
    $compound["kcl"] = $resCompKcl;
    
    $result = [];
    $result["landArea"] = $landArea;
    $result["straight"] = $straight;
    $result["compound"] = $compound;

    $this->response($result);
  }

  private function retriveFertilizer($arrF, $out){
    $res = [];
    $app = [];
    $index = 0;
    foreach($arrF as $key => $num){
        $fert = [];
        $fert["name"] = "F". ($index+=1);
        $fert["percentage"] = $num;
        $fert["result"] = $num / 100 * $out;
        $app[] = $fert;
    }
    $res["app"] = $app;
    $res["result"] = $out;
    return $res;
}
}
