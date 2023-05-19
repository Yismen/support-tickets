 # Dainsys Support Tickets
 A full stack package to add tickets support functionality to Laravel applications. 
 ### Installation
 1. Require using composer: `composer require dainsys/support-tickets`.
 2. You can install all package assets by running `php artisan support:install` command or `@php artisan vendor:publish --force --tag=support:assets`.  
    1. optionally, you add the following line to your `composer` file, under the `scripts` and `post-update-cmd` key, to publish the assets every time you update your composer dependencies: `@php artisan vendor:publish --tag=support:assets --force --ansi`.
    2. If you may want to customize the migrations before next step, first publish them: `@php artisan vendor:publish --force --tag=support:migrations`.
 3. Run the migrations: `php artisan migrate`.   
### Usage
1. Identify at least one user as super admin, which will have no restrictions in the application, by running command `php artisan support:create-super-user`.
2. Add the following trait to the Athenticatable model, most like `App\Models\User`
```
class User extends Authenticatable
{
    use \Dainsys\Support\Traits\HasSupportTickets;
    .....
}
```
### Configure your application
 1. Optionally, you may want to publish and tweek the config file: `@php artisan vendor:publish --force --tag=support:config`.
### Features
1. Super admin admin users can perform all type of actions.
2. Ability to separate tickets and support by departments.
3. Specific subjects for tickets.
4. Notifications based on model actions:
   1. Ticket Created: Department admins, department agents.
   2. Ticket Assigned: ticket owner, ticket agent
   3. Reply Created: ticket owner, ticket agent, department admins.
   4. Ticket Completed: ticket owner, department admins, ticket agent.
   5. Ticket Reopened: ticktet owner, department admins, ticket agent.
   6. Ticket Deleted: ticktet owner, department admins, ticket agent.
   7. Ticket Rated: department admins, ticket agent.
5. Timeframe: amount of time a ticket is expected to be completed, based on the subject priority
6. Completion rate: % of tickets completed
7. Compliance rate: % of tickets within the required timeframe
8. Satisfaction rate: average stars given to tickets divided by 5
9. Ticket actions:
   1.  Owner / Regular users
       1.  Create and update
       2.  Delete
       3.  Close 
       4.  Reply 
       5.  Rate service
       6.  Reopen
       7.  Department admins can assign tickets to their agents
   2.  Department Admins
       1.  Access to department dashboard
       2.  Assign and reassign tickets to department agents
       3.  Close
       4.  Reopen
   3.  Department agents
       1.  Grab (assign to themself) unassigned tickets
       2.  Reply
       3.  Close
   4.  Super admins
       1.  Create or remove super admins
       2.  Manage departments
       3.  Manage reasons
       4.  manage department roles
       5.  View dashboard
       6.  Create, edit, delete, close, reply, assign tickets.

