/**
 * @class Controller
 * @namespace $vs.app.controllers.master
 */
$vs.app.controllers.master.Controller = (function () {

    Controller.prototype = Object.create($vs.engine.objects.Controller.prototype);
    Controller.prototype.constructor = Controller;

    /**
     *
     * @constructor
     */
    function Controller() {
        model("Menu", function (menuModel) {
            menuModel.all(function (menus) {
                view().withVars({menus: initMenus(menus)});
            });
        });
    }

    /**
     *
     * @param {Array} menus
     * @returns {string}
     */
    function initMenus(menus) {
        var menusContent = "";

        menus.forEach(function (menu) {
            menusContent += '\n<li class="nav-item"> <a class="nav-link" href="' + menu.href + '">' + menu.title + '</a></li>';
        });

        return menusContent;
    }

    return Controller;
})();