# FormTool Skeleton

This skeleton will provide you a boost start with our <a href="https://github.com/biswadeep-sarkar/form-tool" target="_blank">FormTool</a> package. The skeleton is based on AdminLTE 2.x.

This package still under primary development.

## Steps to Setup

### Step 1:
Download or clone this skeleton
```
git clone https://github.com/biswadeep-sarkar/form-tool-skeleton.git your-project-name
```

### Step 2:
Create .env file and setup the database connection. You can run this command in the root directory!

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
Run this command to install form-tool
```
composer require biswadeep/form-tool
```

### Step 4:
Copy the vendor config file only
```
php artisan vendor:publish --provider="biswadeep\form-tool\FormToolServiceProvider" --tag=config
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
- Added assets under public/assets directory
- Removed default migrations files
- Modified README.md

## Disclaimer
This package is in early access.
Please do not use yet, or use it just for local development needs