# PHP mvc for backend and JS MVC for frontend together

This project contains both a php framework for backend part work (REST,CRUD,....) and JavaScript framework for front part
with custom routes controllers view files ...

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
Give examples
```

### Installing

A step by step series of examples that tell you have to get a development env running

Say what the step will be

```
Open common/config.php file for php configurations
```

```
Open public/client/index.js file for JavaScript configurations
```

```
/**
*
*@class UserController
*@namespace $vs.app.controllers
*/
$vs.app.controllers.UserController = (function(){
    function UserController(){

    }

    UserController.prototype.index = function(){
        view().make('user.index',{
            title:"User | Profile"
        });
    }

    return UserController;
})();
```

## Deployment

As for now I do not recommend to use this system for live applications because it is in development mode

## Built With

* Core PHP
* Core JavaScript

## Versioning

Version 1.0

## Authors

* **Varazdat Stepanyan** - *Initial work* - [https://github.com/varyandeveloper)

## License

This project is licensed under the MIT License.