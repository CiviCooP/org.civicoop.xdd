<p>{ts domain="org.civicoop.xdd"}The Xtended De-Duplicator finds duplicate contacts with one of these 3 predefined probabilities:{/ts}</p>
<ul>
  <li>{ts domain="org.civicoop.xdd"}High Probability{/ts}</li>
  <li>{ts domain="org.civicoop.xdd"}Medium Probability{/ts}</li>
  <li>{ts domain="org.civicoop.xdd"}Low Probability{/ts}</li>
</ul>
<p>{ts domain="org.civicoop.xdd"}Specify for each probability:{/ts}</p>
<ol>
  <li>{ts domain="org.civicoop.xdd"}Which de-dupe rule to use (only general rules for individuals are valid){/ts}</li>
  <li>{ts domain="org.civicoop.xdd"}In which group the matching contacts should be stored{/ts}</li>
</ol>

<h3>{ts domain="org.civicoop.xdd"}High Probability{/ts}</h3>
<div class="crm-section">
  <div class="label">{$form.dedupe_rule_high.label}</div>
  <div class="content">{$form.dedupe_rule_high.html}</div>
  <div class="clear"></div>
</div>
<div class="crm-section">
  <div class="label">{$form.dedupe_group_high.label}</div>
  <div class="content">{$form.dedupe_group_high.html}</div>
  <div class="clear"></div>
</div>

<h3>{ts domain="org.civicoop.xdd"}Medium Probability{/ts}</h3>
<div class="crm-section">
  <div class="label">{$form.dedupe_rule_medium.label}</div>
  <div class="content">{$form.dedupe_rule_medium.html}</div>
  <div class="clear"></div>
</div>
<div class="crm-section">
  <div class="label">{$form.dedupe_group_medium.label}</div>
  <div class="content">{$form.dedupe_group_medium.html}</div>
  <div class="clear"></div>
</div>
  
<h3>{ts domain="org.civicoop.xdd"}Low Probability{/ts}</h3>
<div class="crm-section">
  <div class="label">{$form.dedupe_rule_low.label}</div>
  <div class="content">{$form.dedupe_rule_low.html}</div>
  <div class="clear"></div>
</div>
<div class="crm-section">
  <div class="label">{$form.dedupe_group_low.label}</div>
  <div class="content">{$form.dedupe_group_low.html}</div>
  <div class="clear"></div>
</div>

{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="top"}
</div>
