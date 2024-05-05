## User Management Demo in Raw PHP

### Setup

```bash
git clone https://github.com/abirahmad/wallstreet.git
cd wallstreet


### Create database

Create database `wsd` from mariadb server

### Database config

go to class/User.php
$this->hostName = 'localhost';
$this->userName = 'root';
$this->password = 'root';
$this->dbName = 'wsd';
insert this
go to migration.php
$this->hostName = 'localhost';
$this->userName = 'root';
$this->password = 'root';
$this->dbName = 'wsd';
insert this
```

hit this link
http://localhost/wallstreet/migration.php from your browser
```
normal user-login:http://localhost/wallstreet/
admin user-login:http://localhost/wallstreet/admin/

hit these links for different logins



