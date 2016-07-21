# org.civicoop.xdd
Xtended De-Duplicator

Introduction
============
De-duplication is often a combination of automatic and manual actions.
This extension is an attempt to optimize this process.

Using a scheduled job, it will automatically store possible duplicate contacts in three groups based on probability.

It is then up to the user to check the contacts in these three groups, and merge them or mark them as "not a duplicate".

Probability
===========
By probability, we mean the likelihood that two contacts are duplicates.

As a starting point, we defined 3 probabilities:
1) High
2) Medium
3) Low

It is up to the user to create CiviCRM de-dupe rules and link them to one of the three probabilities.

For example, one might create a rule that if the first name, last name, and mobile phone number are the same, there's a high probability that these contacts refer to the same person.

Setup
=====

Step 1: create groups
Because the possible duplicates will be automatically stored in a group, you have to create these groups first.

Create three groups, one for each probability.
http://[your-civicrm-website]/civicrm/group/add?reset=1

(you might want to start with just one group, to test the whole process)


Step 2: create de-dupe rules
Go to http://[your-civicrm-website]/civicrm/contact/deduperules?reset=1 and create three rules. One for each probability.

Please note that at this point, only Individual Rules of type General are supported.

(you might want to start with just one rule, to test the whole process)


Step 3: configure the extension
Go to http://[your-civicrm-website]/civicrm/admin/setting/xdd to configure the extension.

For each probability (high, medium, low) you have to configure 2 things:
- which de-dupe rule to use
- which group to store the contacts in

You can create de-dupe rules using the standard CiviCRM rules.

Only Individual Rules of type General can be used by this extension.

The settings for this extension can be found here:





