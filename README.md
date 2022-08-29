# FormTool Skeleton

This skeleton will provide you a boost start with our [FormTool](https://github.com/biswadeep-sarkar/form-tool) package. The skeleton is based on AdminLTE 2.x.

*This package still under primary development.*

## Steps to Setup

### Step 1:
Download or clone this skeleton and set it up *(git command will only run if you have git installed)*
```
git clone https://github.com/biswadeep-sarkar/form-tool-skeleton.git your-project-name
cd your-project-name
composer install
```

### Step 2:
Copy .env file and generate key, you can run this command in the root directory

```
cp .env.example .env
php artisan key:generate
```

### Step 3:
Create database and **setup the database connection** in the .env file

### Step 4:
Run this command to install form-tool
```
composer require biswadeep/form-tool
```

### Step 5:
Copy the vendor config file only
```
php artisan vendor:publish --provider="Biswadeep\FormTool\FormToolServiceProvider" --tag=config
```

### Step 6:
Modify login email and password in "database\seeders\UserSeeder.php"<br>
Default credential:
> Email: `admin@gmail.com`
>
> Password: `form12345`

### Step 7:
Run the migration with seeder:
```
php artisan migrate --seed
```
Great! You are done now open your project.

## How to update the skeleton?
You can just always download, copy and replace all the files.<br>
Here is a git clone process that will work from your project's root directory:

Windows:
```
git clone https://github.com/biswadeep-sarkar/form-tool-skeleton.git temp
xcopy /e /h /y temp .
rmdir temp -Recurse -Force
```
Mac/Linux:
```
git clone https://github.com/biswadeep-sarkar/form-tool-skeleton.git temp
mv -rf temp/.* ../
rm -rf temp
```

## How to update the FormTool?
```
composer update biswadeep/form-tool
```

## What's modified from fresh a Laravel project?
- Modified "boot" method in "app\Providers\AppServiceProvider.php"
- Under "database" directory
  - Removed default migrations files
  - Added "create_demo_pages_table" migration *(For demo purpose, you can delete this)*
  - Modified seeders\DatabaseSeeder.php
  - Added UserSeeder.php under "database\seeders"
- Added Controllers:
  - Admin\AdminControllers.php
  - Admin\DashboardController.php
  - Admin\DemoController.php *(For demo purpose, you can delete this)*
- Added Models:
  - Admin\DemoModel.php *(For demo purpose, you can delete this)*
- Added Views:
  - admin\dashboard.blade.php
- Modified "routes\web.php"
- Added assets under "public/assets" directory
- Modified README.md

## Disclaimer
This package is in early access.
Please do not use yet, or use it just for local development needs