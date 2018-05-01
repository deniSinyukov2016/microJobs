JOBL is a Laravel based applications for outsourcing jobs. You can utilize it as an internal job-board, a micro-jobs site or as a standalone job website. 
It also gives applicants a one click job application having completed their profile. 
<img src="https://i.snag.gy/iAIcNC.jpg"/>

Each approved job application has dedicated conversation page that you can to communicate with the applicant. JOBL is easy to install and configure. 
<img src="https://snag.gy/tFjcMT.jpg"/>

You can view demo at
http://jobl-demo.amdtllc.com

##Admin
admin@app.com / password

##User
user@app.com / password

##Features
- Responsive
- User management
- Roles and permissions
- Custom skills and categories
- Email notifications when user applies, job status changes or posts on conversation.
- Access to debug logs to monitor user generated errors in your application.

<img src="https://snag.gy/pSoeXD.jpg"/>

##Installation
Note: This is a Laravel based application. You should be familiar with how Laravel application is installed.
If not, contact us through our website https://amdtllc.com. 

###Requirements
- SSH access
- MySQL database

###Steps
- Create a database

- Unzip files to your installation directory

- Run <code> composer install </code>

- Copy .env.example to .env, then open .env file and enter you database information and SMTP settings

- Run <code>php artisan migrate --seed</code>

- Run <code>php artisan key:migrate</code> Note: if you are on a local server, you can run php artisan serve to access your installation from browser at http://localhost:8000

You will have a default account

admin@app.com / password

You will have a default account 
admin@app.com / password

###Enabling payouts using PayPal
-​You must have a business account to use this feature.
- Go to <code>businessprofile → mytools → my selling tools → website preferences</code>​
- Turn on Auto Return
- Enter your return url as <code>https://your-website.com/paypal/success</code>​
- Turn on Payment Data Transfer
- Turn on Instant payment notifications and enter <code>https://your-website.com/paypal/notify​</code>


### Change log
Version 1.3
- PayPal payout integration
- Seeder for modules
- Payouts history
- Close work if payment is completed

Version 1.2
- Updated roles and permissions to allow assigning roles that enable users to post jobs and access other modules in the application.
- Squashed a few bugs with authentication and roles modules

Version 1.0
- Initial public release
- Job board by owner/admin
- User management
- Job bookmarking
- Job application
- Interactive work module for approved applications
- Responsive design
- System configuration and debug log for admins
- Job sharing