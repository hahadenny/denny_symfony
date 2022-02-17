### 1. To Clone Denny Test Auto API:

```bash
git clone git@github.com:hahadenny/denny_test.git
```

### 2. Install vendor dependencies:

```bash
cd denny_test
composer install
```

### 3. Update database data in .env file

```bash
vi .env

DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

### 4. Import `Vehicle` and `User` tables:

```bash
php artisan migrate
```

### 5. Fixture testing with PHPUnit:

```bash
./vendor/bin/phpunit
```

### 6. Swagger Documentation

```bash
unzip denny-auto-api-swagger.zip
```

### 7. Testing API

Use the following authentication headers for testing the API: 

```bash
header('UserName', 'denny_test');
header('Token', 'CjwKCAiA9aKQBhBREiwAyGP5lU0Fw85cvboak0HgbBkoU2xKS15kkiBHjHiKLlQ9FSBwnmxrnjutQRoChAIQAvD_BwE');
```
