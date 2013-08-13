Installing Scrumptious Web
=============================

View the live demo
------------------

See the finished product in action at [FacebookSampleApp.com](http://facebooksampleapp.com/ "Scrumptious Web demo app")

Place files on your webserver
-----------------------------

Scrumptious Web requires a web server addressable from the public Internet running [PHP](http://www.php.net/) with [cURL enabled](http://www.php.net/manual/en/intro.curl.php).

Clone the repository from GitHub into a folder inside your website's document root.

    git clone git@github.com:fbsamples/web-scrumptious.git scrumptious
    cd scrumptious

Initialize and update the [Facebook PHP SDK](https://github.com/facebook/facebook-php-sdk) [submodule](http://git-scm.com/book/en/Git-Tools-Submodules "git submodules").

    git submodule init
    git submodule update

Configure your application
--------------------------

Edit the `config.php` file with the appropriate values for your application, audience, and server.

1. Add information from [your Facebook application](https://developers.facebook.com/apps "Facebook Developers site applications") including your app id, secret, and namespace.
1. Set your [Facebook locale](https://developers.facebook.com/docs/internationalization/ "Facebook internationalization") to display JavaScript SDK strings in a language other than English.
1. Specify a base URI, including the trailing slash, representing the URI to this directory's content on your webserver. e.g. `http://example.com/`
1. Specify a static base URI, including the trailing slash, representing the URI to the sample's static content. e.g. `http://example.com/static/` if on the same server or `http://s.example.com/` if you choose to serve static files from a CDN or separate hostname.

Scrumptious Web overview
---------------------------

Scrumptious Web allows a visitor to browse a list of meals and view a detail page with more information about a meal of interest.

Scrumptious Web paired with a properly configured Facebook application adds identity and sharing features powered by Facebook:

* Share [basic Facebook information](https://developers.facebook.com/docs/reference/login/public-profile-and-friend-list/) with your Facebook application through [Facebook Login](https://developers.facebook.com/docs/concepts/login/) including his or her name, gender, and Facebook profile photo
* Grant your Facebook application permission to publish new [Open Graph actions](https://developers.facebook.com/docs/opengraph/using-actions/) to his Facebook profile on his behalf
* Publish a new custom Open Graph action associated with your application with support for a [personal message](https://developers.facebook.com/docs/submission-process/opengraph/guidelines/action-properties/#usermessages "Facebook Open Graph action user messages"), [tagged friends](https://developers.facebook.com/docs/opengraph/guides/tagging/#people "Facebook Open Graph action tagged friends"), [tagged place](https://developers.facebook.com/docs/opengraph/guides/tagging/#places "Facebook Open Graph action tagged place"), and [explicit sharing](https://developers.facebook.com/docs/opengraph/guides/explicit-sharing/ "Facebook Open Graph action explicit sharing")

External libraries
------------------

Scrumptious Web uses a few external libraries for demonstration purposes. The choice of these libraries for our example should demonstrate general development patterns but should not limit your development using your own language or libraries of choice.

* [Bootstrap](http://twbs.github.io/bootstrap/ "Bootstrap front-end framework") CSS for a responsive layout adaptive to both desktop and mobile client widths and general CSS template styling; modal JavaScript to assist with showing and dismissing a modal share dialog. Bootstrap is licensed under the [Apache License version 2.0](http://www.apache.org/licenses/LICENSE-2.0.html)
* [jQuery](http://jquery.com/) JavaScript for DOM selectors, DOM manipulation, layout computations, event handling and general browser abstraction functions. jQuery is licensed under the [MIT license](http://opensource.org/licenses/MIT)
* [JQuery UI](http://jqueryui.com/) autocomplete widget to display search results for Facebook friends and Facebook places. jQuery UI is licensed under the [MIT license](http://opensource.org/licenses/MIT)
