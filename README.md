# FormTool Skeleton

This skeleton will provide you a boost start with our [FormTool](https://github.com/biswadeep-sarkar/form-tool) package. The skeleton is based on AdminLTE 2.x.

*This package still under primary development.*

## Steps to Setup

### Step 1:
Download or clone this skeleton
```
git clone https://github.com/biswadeep-sarkar/form-tool-skeleton.git your-project-name
```

### Step 2:
Copy .env file, you can run this command in the root directory

For Windows :
```
copy .env.example .env
php artisan key:generate
```

For Mac:
```
cp .env.example .env
php artisan key:generate
```

### Step 3:
Create database and ***setup the database connection*** in the .env file

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
Modify login email and password in "database\seeders\UserSeeder.php"
Default credential:
> Email: admin@gmail.com
> Password: form12345

### Step 7:
Run the migration with seeder:
```
php artisan migrate --seed
```

## How to update the skeleton?
You can just always copy and replace all the files.
I will show you a git clone process, let's assume your root directory named as "project":
```
git clone https://github.com/biswadeep-sarkar/form-tool-skeleton.git temp
mv temp/.git project/.git
rm -rf temp
```

## What's modified from fresh Laravel project?
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