## This is a Sample Symfony CRUD REST API built by Denny Choi


### 1. To Clone Denny Test Auto API:

```bash
git clone git@github.com:hahadenny/denny_symfony.git
```

### 2. Install vendor dependencies:

```bash
cd denny_symfony
composer install
```

### 3. Update database data in .env file

```bash
vi .env

DATABASE_URL=
```

### 4. Import `Vehicle` and `User` tables:

```bash
php bin/console doctrine:migrations:migrate
```

### 5. Fixture testing with PHPUnit:

```bash
./vendor/bin/phpunit
```

### 6. Swagger Documentation

wget [https://github.com/hahadenny/denny_symfony/raw/master/denny-auto-api-swagger.zip](https://github.com/hahadenny/denny_symfony/raw/master/denny-auto-api-swagger.zip)
```bash
unzip denny-auto-api-swagger.zip
```

### 7. Testing API

Use the following authentication headers for testing the API: 

```bash
header('UserName', 'denny_test');
header('Token', 'CjwKCAiA9aKQBhBREiwAyGP5lU0Fw85cvboak0HgbBkoU2xKS15kkiBHjHiKLlQ9FSBwnmxrnjutQRoChAIQAvD_BwE');
```
