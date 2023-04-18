 # Dainsys Support and Recipients
 A full stack package to add support functionality to Laravel applications. This package allows you to associate recipients (contacts) to your department files.

 ### Installation
 1. Require using composer: `composer require dainsys/support`.
 2. You can install all package assets by running `php artisan support:install` command.
    1. Another option is installing each asset individually:
       1. Publish the assets: `@php artisan vendor:publish --force --tag=support:assets`.  
          1. optionally, you add the following line to your `composer` file, under the `scripts` and `post-update-cmd` key, to publish the assets every time you update your composer dependencies: `@php artisan vendor:publish --tag=support:assets --force --ansi`.
    2. If you may want to customize the migrations before next step, first publish them: `@php artisan vendor:publish --force --tag=support:migrations`.
    3. Run the migrations: `php artisan migrate`.   
 3. Only super admin users are allowed to interact with the app. You can register them using any of the following options:
    1. Using the register method of your `AuthServiceProvider`: `\Dainsys\Support\Support::registerSuperUsers(["super@user1.com", "super@user2.com"]);`.
    2. In your `.env` file, `SUPPORT_SUPER_USERS='super@user1.com,super@user2.com'`
##### Configure your application
 1. Visit package main route: `/dainsys/support/about`.
 2. Optionally, you may want to publish and tweek the config file: `@php artisan vendor:publish --force --tag=support:config`.
 3. This package has its own views, designed with livewire and AdminLte. However, if you may want to change them then you can publish them with `@php artisan vendor:publish --force --tag=support:views`. 
 4. Package views extend it's own layout app. However, you can change this by adding the key `SUPPORT_LAYOUT_VIEW` to your `.env` file. Or, change it directly in the `support` config file, under the `layout` key.

#### Usage
1. The package is configured to auto discover your departments within then `app\Mail` directory. However, if your departments reside outside this folder or if you want to register another directory, add the line `\Dainsys\Support\Support::bind(app_path('Mail'));` to your `AppServiceProvider`. The package will try to load all your departments for all directories added.
2. Visit route `/support/admin/recipients` to manage your recipients contacts.
3. Visit route `/support/admin/departments` to manage your departments and assign them to the recipients.
4. In your departments, you can access the array of recipients associated to that class with the code snippet  `\Dainsys\Support\Support::recipients($this);`. For example, `->to(\Dainsys\Support\Support::recipients($this))`;



Users with role 'super admin', 'support management' or permission to interact with models like 'create departments' or 'update departments'
    use \Dainsys\Support\Traits\HasSupport;

```
class User extends Authenticatable
{
    use \Dainsys\Support\Traits\HasSupport;
    ```

