# mage2-bug-6803
Code used to reproduce [Magento2 issue #6803](https://github.com/magento/magento2/issues/6803#issuecomment-289407131)

This plugin contains a task that when run, tries to assign an image to a simple product, and set media types on the image. Even with the [workaround plugin](https://github.com/DIE-KAVALLERIE/magento2-product-image-fix/blob/master/composer.json) the media types aren't set.

The plugin uses this code to append an image to the product's image gallery and set media types
```
$oProduct->addImageToMediaGallery($sMediaPath, ['image', 'small_image', 'thumbnail'], true, false);
```

## Installation via composer

Please go to the Magento2 root directory and run the following commands in the shell:

```
composer config repositories.quickshiftin_mage2_bug_6803 vcs git@github.com:quickshiftin/mage2-bug-6803
composer require quickshiftin/mage2-bug-6803:dev-master
php bin/magento module:enable Bug_Demo
php bin/magento setup:upgrade
```

## To reproduce

### Run the task
```
php bin/magento bug:demo:6803
```

### Find the product in the admin

You will see there are no media attributes when you first look in the admin.

![Media attrs missing 1](https://cloud.githubusercontent.com/assets/96733/24435944/903f8cca-13f5-11e7-81fa-43c86351ca01.png)

![Media attrs missing 2](https://cloud.githubusercontent.com/assets/96733/24435945/92b76d60-13f5-11e7-809b-eae23050ddcd.png)

### Manually set the media attributes and save

![Media attrs set](https://cloud.githubusercontent.com/assets/96733/24435948/955d9f94-13f5-11e7-9d8f-e93ef17358d6.png)

![Media attrs set 2](https://cloud.githubusercontent.com/assets/96733/24436269/d71a84c2-13f7-11e7-8dd8-8e91da86ee62.png)
