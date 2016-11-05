/**
 * Created by Var Yan on 21.10.2016.
 * @available methods are
 *
 * prefix(urlPrefix)
 * namespace(urlNamespace)
 * group({prefix:urlPrefix,namespace:urlNamespace})
 * make(routeKey,destination)
 * name(currentRouteName)
 */

router().make('/', 'MainController').name('home');
router().make('/about', function () {return "MainController.about";}).name('main.about');