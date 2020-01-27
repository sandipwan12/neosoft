This file is created to demonstrate "Neosoft Demo Project"

 Installion
------------------------------------------------------------------------------------------------------------------------
Step1: Download zip folder for website source code and database file.
Step2: Unzip downloaded zip file.
Step3: Open drupal 8 suppoted enviorment like XAMMP, MAMP or WAMP.
Step4: Copy source code in require folder (For eg: XAMMP put it onto XAMPP⁩ ▸ ⁨xamppfiles⁩ ▸ ⁨htdocs⁩ folder ▸ neosoft)
Step5: Opne PHPMyAdmin console of XAMMP and create new databse with below details.

$databases['default']['default'] = array (
  'database' => 'neosoft_db',
  'username' => 'root',
  'password' => '',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

Step6: import neosoft_db.sql (inside db folder in roor directory) file that you already downloaded.
Step7: Ensure that there will be total 74 tables created.

Step8: Open any Drupal 8 suppoted web browser to above directory path (like http://localhost/neosoft)
Step9: You will see the Neosoft Demo Drupal 8 website.

Step10: Use below login credential to login as admin user.
username: Neosoft
password: Neosoft@123

Contact Demo details
------------------------------------------------------------------------------------------------------------------------
Step1: Please click on "Add Content" main menu link.
You will get add contant detail form, please fill require value and submit.

Step2: Once you push submit button it will add a contact record into databse after proper data validation.
Step3: After this you will redireted to "Content List Section",
Step4: Here you will find all Content details and you can edit, delete, sort and move to next and preview page.

Drupal Module details
------------------------------------------------------------------------------------------------------------------------
Here I creted one custom module using drupal fprm API to add, edit and delete Content
Please have a look neosoft.info.yml file for more details.
custom module name: neosoft
path: XAMPP⁩ ▸ ⁨xamppfiles⁩ ▸ ⁨htdocs⁩ ▸ ⁨evolent⁩ ▸ ⁨modules⁩ ▸ ⁨custom⁩ ▸ neosoft

Also for content list I creaded a drupal view table.
admin path: http://localhost/neosoft/admin/structure/views/view/neosoft_demo

Also here I used two druapl constributed moduel
1. view_custom_table module
2. view_conditional module

