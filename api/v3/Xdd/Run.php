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
    $msg = ts("'probability' is a required parameter, and must be one of these values: ") . implode(', ', $validProbabilities) . '. ' . ts("You specified: ") . $params['probability'];
    throw new API_Exception($msg, 999);
  }
}

function _civicrm_api3_xdd_RunDedupeRule($probability) {
  // get the settings for this probability: dedupe rule + dedupe group
  $xddOptionGroupID = _civicrm_api3_xdd_getOptionGroupID();
  $xddDedupeRuleID = _civicrm_api3_xdd_getOption($xddOptionGroupID, 'dedupe_rule_' . $probability);
  $xddDedupeGroupID = _civicrm_api3_xdd_getOption($xddOptionGroupID, 'dedupe_group_' . $probability);
  
  // check if they are valid
  if ($xddOptionGroupID == 0 || $xddDedupeRuleID == 0 || $xddDedupeGroupID == 0) {
    return ts('Nothing to do. Please check your XDD settings.');
  }
  
  // run the dedupe rule
  $duplicates = CRM_Dedupe_Finder::dupes($xddDedupeRuleID);
  
  // store the found contacts in the group
  $count = 0;
  foreach ($duplicates as $duplicate) {
    // duplicate contains id1, id2, weight
    
    // add contact 1 to group
    $params = array(
      'group_id' => $xddDedupeGroupID,
      'contact_id' => $duplicate[0],
      'status' => 'Added',
    );
    civicrm_api3('GroupContact', 'create', $params);
    $count++;

    // add contact 2 to group
    $params['contact_id'] = $duplicate[1];
    civicrm_api3('GroupContact', 'create', $params);    
    $count++;
  }
  
  return ts('Number of duplicates processed: ') . $count;
}

function _civicrm_api3_xdd_getOptionGroupID() {
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

function _civicrm_api3_xdd_getOption($groupID, $valueName) {
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
