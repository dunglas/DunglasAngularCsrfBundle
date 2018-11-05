# JavaScript CSRF Protection Bundle

This [API Platform](http://api-platform.com) and [Symfony](http://symfony.com) bundle provides automatic
[Cross Site Request Forgery](http://en.wikipedia.org/wiki/Cross-site_request_forgery) (CSRF or XSRF) protection for
client-side applications.

Despite the name, it works with any client-side technology including [Angular](https://angular.io/),
[React](https://facebook.github.io/react/), [Vue.js](https://vuejs.org/) and [jQuery](https://jquery.com/).
Actually, any JavaScript code issuing [XMLHttpRequest](https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest) or using [the Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API) can leverage this bundle.

[![Build Status](https://travis-ci.org/dunglas/DunglasAngularCsrfBundle.png?branch=master)](https://travis-ci.org/dunglas/DunglasAngularCsrfBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4a1e438f-038e-4cd7-ab6e-8849c4586a08/mini.png)](https://insight.sensiolabs.com/projects/4a1e438f-038e-4cd7-ab6e-8849c4586a08)
[![Dependency Status](https://www.versioneye.com/user/projects/5583d39036386100150002dd/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5583d39036386100150002dd)
[![StyleCI](https://styleci.io/repos/15552938/shield?branch=master)](https://styleci.io/repos/15552938)

## How it Works

Thanks to this bundle, the server-side application (the Symfony app) will automatically set a cookie named `XSRF-Token`
containing a unique token during the first HTTP response sent to the browser.
Subsequent asynchronous requests made by the JavaScript app with `xhr` or `fetch` send back the value of the cookie in a
special HTTP header named `X-XSRF-Token`.

To prevent CSRF attacks, the bundle will check that the header's value match the cookie's value. This way, it will be
able to detect and block CSRF attacks.

AngularJS (v1)'s `ng.$http` service has
[a built-in support for this CSRF protection system](http://docs.angularjs.org/api/ng.$http#description_security-considerations_cross-site-request-forgery-protection).
If you use another framework or HTTP client (such as [Axios](https://github.com/axios/axios)), you just need to read the
cookie value and add the HTTP header containing it by yourself.

This bundle provides a [Symfony's Event Listener](http://symfony.com/doc/current/cookbook/service_container/event_listener.html)
that set the cookie and another one that checks the HTTP header to block CSRF attacks.

Thanks to DunglasAngularCsrfBundle, you get CSRF security without modifying your code base.

This bundle works fine with both [API Platform](https://api-platform.com) and
[FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle).

## Installation

Use [Composer](http://getcomposer.org/) to install this bundle:

    composer require dunglas/angular-csrf-bundle

If you use Symfony Flex, you're done.

Otherwise add the bundle in your application kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        // ...
        new Dunglas\AngularCsrfBundle\DunglasAngularCsrfBundle(),
        // ...
    );
}
```

Configure URLs where the cookie must be set and that must be protected against CSRF attacks:

```yaml
# app/config/security.yml
dunglas_angular_csrf:
    # Collection of patterns where to set the cookie
    cookie:
        set_on:
            - { path: ^/$ }
            - { route: ^app_, methods: [GET, HEAD] }
            - { host: example.com }
    # Collection of patterns to secure
    secure:
        - { path: ^/api, methods: [POST, PUT, PATCH, LINK] }
        - { route: ^api_v2_ }
        - { host: example.com, methods: [POST, PUT, PATCH, DELETE, LINK] }
    # Collection of patterns to exclude
    exclude:
        - { path: ^/api/exclude, methods: [POST, PUT, PATCH, LINK] }
        - { route: ^api_v2_exclude }
        - { host: exclude-example.com, methods: [POST, PUT, PATCH, DELETE, LINK] }
        
```

Your app is now secured.

## Examples

* [DunglasTodoMVCBundle](https://github.com/dunglas/DunglasTodoMVCBundle): an implementation of the TodoMVC app using Symfony,
Backbone.js and Chaplin.js

## Full Configuration

```yaml
dunglas_angular_csrf:
    token:
        # The CSRF token id
        id: angular
    header:
        # The name of the HTTP header to check (default to the AngularJS default)
        name: X-XSRF-TOKEN
    cookie:
        # The name of the cookie to set (default to the AngularJS default)
        name: XSRF-TOKEN
        # Expiration time of the cookie
        expire: 0
        # Path of the cookie
        path: /
        # Domain of the cookie
        domain: ~
        # If true, set the cookie only on HTTPS connection
        secure: false
        # Patterns of URLs to set the cookie
        set_on:
            - { path: "^/url-pattern", route: "^route_name_pattern$", host: "example.com", methods: [GET, POST] }
    # Patterns of URLs to check for a valid CSRF token
    secure:
        - { path: "^/url-pattern", route: "^route_name_pattern$", host: "example.com", methods: [GET, POST] }
    # Patterns to exclude from secure routes
    exclude:
        - { path: "^/url-pattern/exclude", route: "^route_name_pattern$", host: "example.com", methods: [GET, POST] }
```

## Integration with the Symfony Form Component

When using the Symfony Form Component together with DunglasAngularCsrfBundle, the bundle will automatically disable the
built-in form CSRF protection only if the CSRF token provided by the header is valid. 

If no CSRF header is found or if the token is invalid, the form CSRF protection will not be disabled by the bundle.

If you want your form to be validated only by the form component system, make sure to remove its URL from the config.

## Credits

This bundle has been created by [Kévin Dunglas](http://dunglas.fr).
