# Setting up project

1. Clone project to desired folder
2. Point the server to folder where you have cloned this project
3. Turn on rewrites (if you are using nginx you can use sample config nginx/sample.conf)
   1. **Note: please change YOUR_PATH to your actual path in sample.conf**
4. Create MySQL database with user and password
5. Copy keys.php.example to keys.php and fill in your DB data
6. (GET) Run SITE/setup/run (or if you haven't set your domain you can use your IP or localhost)
## How to use
1. (POST) SITE/user/create [$_POST['name'],$_POST['year_of_birth']] 
   1. to create new record
2. (GET) SITE/user/read/ID  
   1. to read record with id
3. (POST) SITE/user/update/ID 
   1. [$_POST['name'],$_POST['year_of_birth']] to update record with ID
4. (GET) SITE/user/delete/ID 
   1. where ID is an ID of a user that you want to delete