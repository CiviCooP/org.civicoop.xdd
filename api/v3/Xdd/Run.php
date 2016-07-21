<?php

function _civicrm_api3_xdd_Run_spec(&$spec) {
  $spec['probability']['api.required'] = 1;
}

function civicrm_api3_xdd_Run($params) {
  $validProbabilities = array('high', 'medium', 'low');
  
  if (array_key_exists('probability', $params) && in_array($params['probability'], $validProbabilities)) {
    $returnValues = _civicrm_api3_xdd_RunDedupeRule($params['probability']);
    
    return civicrm_api3_create_success($returnValues, $params, 'xdd', 'run');
  } else {
    // invalid parameter
    $msg = ts("'probability' is a required parameter, and must be one of these values: ") . implode(',', $validProbabilities) . '. ' . ts("You specified: ") . $params['probability'];
    throw new API_Exception($msg, 999);
  }
}

function _civicrm_api3_xdd_RunDedupeRule($probability) {
  $xddOptionGroupID = _civicrm_api3_xdd_getOptionGroupID();
  $xddDedupeRuleID = _civicrm_api3_xdd_getOption($xddOptionGroupID, 'dedupe_rule_' . $probability);
  $xddDedupeGroupID = _civicrm_api3_xdd_getOption($xddOptionGroupID, 'dedupe_group_' . $probability);
  
  if ($xddOptionGroupID == 0 || $xddDedupeRuleID == 0 || $xddDedupeGroupID == 0) {
    return ts('Nothing to do. Please check your XDD settings.');
  }
  
  $dedupeRule = 
  $dedupeGroup
  // get the settings
}

private function _civicrm_api3_xdd_getOptionGroupID() {
  // get the xdd option group
  $params = array(
    'name' => 'xdd_settings',
    'sequential' => 1,
  );
  $xddOptionGroup = civicrm_api3('OptionGroup', 'get', $params);
    
  if ($xddOptionGroup['count'] == 0) {
    return 0;
  }
  else {
    $xddOptionGroup = reset($xddOptionGroup['values']);        
    return $xddOptionGroup['id'];

  }
}

private function _civicrm_api3_xdd_getOption($groupID, $valueName) {
  $params = array(
    'option_group_id' => $groupID,
    'name' => $valueName,
  );
  $xddOptionValue = civicrm_api3('OptionValue', 'get', $params);
    
  if ($xddOptionValue['count'] == 0) {
    return 0;
  }
  else {
    $xddOptionValue = reset($xddOptionValue['values']);      
    return $xddOptionValue['value'];
  }
}
