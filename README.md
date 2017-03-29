# mage2-bug-6803
Code used to reproduce Magento2 issue #6803

## Installation via composer

Please go to the Magento2 root directory and run the following commands in the shell:

```
composer config repositories.quickshiftin_mage2_bug_6803 vcs git@github.com:quickshiftin/mage2-bug-6803
composer require quickshiftin/mage2-bug-6803:dev-master
php bin/magento module:enable Bug_Demo
php bin/magento setup:upgrade
```
