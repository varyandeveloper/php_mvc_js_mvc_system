/**
 * @class MainController
 * @namespace vs.engine.objects
 */
$vs.app.controllers.MainController = (function () {

    MainController.prototype = Object.create($vs.app.controllers.master.Controller.prototype);
    MainController.prototype.constructor = MainController;

    /**
     *
     * @constructor
     */
    function MainController() {
        $vs.app.controllers.master.Controller.call(this);
    }

    /**
     *
     * @returns {void}
     */
    MainController.prototype.index = function () {
        $vs.engine.objects.Controller.title = "Home";

        api('users').get().then(function(response){
            console.log(response);
        });

        view().make('index', {
            title: $vs.engine.objects.Controller.title,
            users: ""
        });
    };

    /**
     *
     * @returns {void}
     */
    MainController.prototype.about = function () {
        $vs.engine.objects.Controller.title = "About";
        this.view('about', {
            title: "This is about view",
            user: {
                username: "Var Yan"
            }
        });
    };

    return MainController;
})();