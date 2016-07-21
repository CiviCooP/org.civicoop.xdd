<?php

require_once 'CRM/Core/Form.php';

class CRM_Xdd_Form_Settings extends CRM_Core_Form {
  private $_dedupeRules = array();
  private $_groups = array();
  private $_xddOptionGroupID = 0;
    
  function __construct() {
    parent::__construct();
  }
  
  public function preProcess() {
    // get the dedupe rules
    $this->_dedupeRules = $this->getDedupeRules();

    // get the groups
    $this->_groups = $this->getGroups();
    
    // get the option group where we store the settings
    $this->_xddOptionGroupID = $this->getXddOptionGroupID();
  }
  
  public function buildQuickForm() {
    // high probability
    // - select list of rules
    // - group to store matches
    $this->add(
      'select', // field type
      'dedupe_rule_high', // field name
      ts('Dedupe rule'), // field label
      $this->_dedupeRules, // list of options
      FALSE // is required
    );
    $this->add(
      'select', // field type
      'dedupe_group_high', // field name
      ts('Dedupe group'), // field label
      $this->_groups, // list of options
      FALSE // is required
    );

    // medium probability
    // - select list of rules
    // - group to store matches
    $this->add(
      'select', // field type
      'dedupe_rule_medium', // field name
      ts('Dedupe rule'), // field label
      $this->_dedupeRules, // list of options
      FALSE // is required
    );
    $this->add(
      'select', // field type
      'dedupe_group_medium', // field name
      ts('Dedupe group'), // field label
      $this->_groups, // list of options
      FALSE // is required
    );

    // low probability
    // - select list of rules
    // - group to store matches
    $this->add(
      'select', // field type
      'dedupe_rule_low', // field name
      ts('Dedupe rule'), // field label
      $this->_dedupeRules, // list of options
      FALSE // is required
    );
    $this->add(
      'select', // field type
      'dedupe_group_low', // field name
      ts('Dedupe group'), // field label
      $this->_groups, // list of options
      FALSE // is required
    );
    
    // submit button
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    // assign form elements
    $this->assign('elementNames', $this->getRenderableElementNames());

    parent::buildQuickForm();
  }

  public function setDefaultValues() {
    $defaults = array();
    
    $defaults['dedupe_rule_high'] = $this->getXddOption('dedupe_rule_high');
    $defaults['dedupe_group_high'] = $this->getXddOption('dedupe_group_high');
    
    $defaults['dedupe_rule_medium'] = $this->getXddOption('dedupe_rule_medium');
    $defaults['dedupe_group_medium'] = $this->getXddOption('dedupe_group_medium');
    
    $defaults['dedupe_rule_low'] = $this->getXddOption('dedupe_rule_low');
    $defaults['dedupe_group_low'] = $this->getXddOption('dedupe_group_low');
    
    return $defaults;
  }
  
  public function postProcess() {
    $values = $this->exportValues();
    
    $this->setXddOption('dedupe_rule_high', $values['dedupe_rule_high']);
    $this->setXddOption('dedupe_group_high', $values['dedupe_group_high']);

    $this->setXddOption('dedupe_rule_medium', $values['dedupe_rule_medium']);
    $this->setXddOption('dedupe_group_medium', $values['dedupe_group_medium']);

    $this->setXddOption('dedupe_rule_low', $values['dedupe_rule_low']);
    $this->setXddOption('dedupe_group_low', $values['dedupe_group_low']);
    
    CRM_Core_Session::setStatus(ts('The Xtended De-Duplicator settings have been saved.'), ts('Success'), 'success');
    
    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
  
  /**
   * Get all general dedupe rules linked to individuals
   * 
   * @return array (id / title)
   */
  private function getDedupeRules() {
    $options = array('0' => ts('-- select --'));
    
    $rgBao = new CRM_Dedupe_DAO_RuleGroup();
    $rgBao->contact_type = 'Individual';
    $rgBao->used = 'General';
    if ($rgBao->find()) {
      while ($rgBao->fetch()) {
        $options[$rgBao->id] = ts($rgBao->title);      
      }
    }
        
    return $options;
  }
  
  /**
   * wrapper to get all the groups
   * 
   */
  private function getGroups() {
    $options = array('0' => ts('-- select --'));
    
    return $options + CRM_Core_PseudoConstant::group();
  }
  
  /**
   * Get the option group to store the XDD settings.
   * Create it, if it does not exist
   */
  private function getXddOptionGroupID() {
    // get the xdd option group
    $params = array(
      'name' => 'xdd_settings',
      'sequential' => 1,
    );
    $xddOptionGroup = civicrm_api3('OptionGroup', 'get', $params);
    
    if ($xddOptionGroup['count'] == 0) {
      // the option group does not exist, create it
      $params['title'] = 'Xtended De-Duplicator Settings';
      $params['is_active'] = 1;
      $params['is_reserved'] = 0;
      
      $xddOptionGroup = civicrm_api3('OptionGroup', 'create', $params);
      $xddOptionGroup = reset($xddOptionGroup['values']);
    }
    else {
      $xddOptionGroup = reset($xddOptionGroup['values']);
    }
    
    return $xddOptionGroup['id'];
  }
  
  private function getXddOption($valueName) {
    $params = array(
      'option_group_id' => $this->_xddOptionGroupID,
      'name' => $valueName,
    );
    $xddOptionValue = civicrm_api3('OptionValue', 'get', $params);
    
    if ($xddOptionValue['count'] == 0) {
      // the option value does not exist, create it
      $params['label'] = $valueName;
      $params['value'] = 0;
      $params['filter'] = 0;
      $params['is_reserved'] = 0;
      $params['is_active'] = 1;
      
      $xddOptionValue = civicrm_api3('OptionValue', 'create', $params);
      $xddOptionValue = reset($xddOptionValue['values']);      
    }
    else {
      $xddOptionValue = reset($xddOptionValue['values']);
    }
    
    return $xddOptionValue['value'];
  }
  
  private function setXddOption($valueName, $value) {
    $params = array(
      'option_group_id' => $this->_xddOptionGroupID,
      'name' => $valueName,
    );
    $xddOptionValue = civicrm_api3('OptionValue', 'getsingle', $params);
    
    $params['id'] = $xddOptionValue['id'];
    $params['value'] = $value;
    $xddOptionValue = civicrm_api3('OptionValue', 'create', $params);
  }
}
