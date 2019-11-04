# W-Log Backend

 Just a simple backend using laravel.

### Prerequisites

What things you need to install.

```
MySQL
```

### Setup

#### Step 00 - migrate
```
php artisan migrate --seed
```
#### Step 01 - passport init
```
php artisan passport:install
```
#### Step 01 - server start
```
php artisan serve
```

### Routes

Auth
```
POST
localhost/login
localhost/logout
```

Public
```
GET
localhost/public/posts
localhost/public/posts/:code
```
Required auth token
```
GET
localhost/posts/
localhost/posts/:code

POST
localhost/posts/

PUT
localhost/posts/:POST

DELETE
localhost/posts/:POST
```

## Built With

* [Larevel](https://laravel.com/) - The backend framework used

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Jafet** - *Initial work* - [F34th3R](https://github.com/F34th3R)


## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

