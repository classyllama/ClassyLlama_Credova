# Credova Magento 2 Extension

## Description

This extension adds a payment method utilizing Credova's financing services.

## Installation Instructions

### Option 1 - Install extension by copying files into project

```bash
mkdir -p app/code/ClassyLlama/Credova
git archive --format=tar --remote=git@github.com:classyllama/ClassyLlama_Credova.git master | tar xf - -C app/code/ClassyLlama/Credova/
bin/magento module:enable --clear-static-content ClassyLlama_Credova
bin/magento setup:upgrade
bin/magento cache:flush
```

### Option 2 - Install extension using Composer

```bash
composer config repositories.classyllama/module-credova git git@github.com:classyllama/ClassyLlama_Credova.git
composer require classyllama/module-credova:dev-develop
bin/magento module:enable --clear-static-content ClassyLlama_Credova
bin/magento setup:upgrade
bin/magento cache:flush
```

## Uninstallation Instructions

These instructions work regardless of how you installed the extension:

```bash
bin/magento module:disable --clear-static-content ClassyLlama_Credova
rm -rf app/code/ClassyLlama/Credova
composer remove classyllama/module-credova
mr2 db:query 'DELETE FROM `setup_module` WHERE `module` = "ClassyLlama_Credova"'
bin/magento cache:flush
```

## Extension Configuration

Once  the extension is installed, a new payment method will exist in the  Magento admin under Stores > Configuration > Sales > Payment  Methods.

* The following fields should be filled in:
* Extension enabled
* API URL
* API Username
* API Password
* Store Code
* Minimum Amount that can be financed
* Payment from Applicable Countries
* Payment from Specific Countries
* Sort Order

## Front End Functionality

* When the extension is enabled, you'll see a pre-qualification button on each product page.
* This also displayes the estimated monthly payment for the item. This estimate is calculated off the base product price.
* If a user clicks on the pre-qualification button, a popup will open allowing them to enter thier application information.
* When the extension is enabled a new Credova payment method will display in the list of payment methods in the checkout.
* When  the Credova payment option option is chosen, a popup is presented that  allows the user to finish the Credova application and submit the order.

## Order Management

## Federal License

When viewing a Credova order in the Magento admin there will be a button to create a new federal license.

When  the button to add a new federal license is clicked, the user will be  presented with a popup form. The form has one field for license number  and once the license number is provided, a request is made to see if the  license has already been created.

If the license already exists the form is closed.

If the license does not exist, the following fields are presented to complete:

1. License Number
2. Expiration
3. Address
4. Address 2
5. City
6. State
7. Zip

(All fields but address 2 are required.)

When the form is submitted, a new federal license is sent to Credova, and is saved on the Magento order.

## Invoice

When payment is captured, an invoice for the order is automatically sent to Credova.

## Return

When  a credit memo it issued for an order, an order refund requres is sent  to Credova. The refund can be verified in the Credova merchant  dashboard.

## License

This project is licensed under the Open Software License 3.0 (OSL-3.0). See included LICENSE file for full text of OSL-3.0
