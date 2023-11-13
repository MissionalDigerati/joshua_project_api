<?php
declare(strict_types=1);
/**
 * This file is part of Joshua Project API.
 *
 * Joshua Project API is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Joshua Project API is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
use Slim\App;
use Middleware\CachingMiddleware;
use Middleware\GoogleAnalyticsMiddleware;
use Tuupola\Middleware\HttpBasicAuthentication;
use Middleware\APIAuthMiddleware;
use Middleware\APIStandardsMiddleware;

/**
 * Add all the required middleware
 *
 * @param App $app  The Slim application
 */
return function(App $app) {
    $container = $app->getContainer();
    /**
     * Setup Middleware.
     * IMPORTANT: Last one added is first executed.
     */
    $pathSettings = [
        'passthrough' => ['/v\d+/docs/column_descriptions'],
        'paths'  =>  [
            '/v\d+/continents',
            '/v\d+/countries',
            '/v\d+/languages',
            '/v\d+/people_groups',
            '/v\d+/regions'
        ]
    ];
    $cacheSettings = $pathSettings;
    $useCaching = ((isset($_ENV['USE_CACHE'])) && ($_ENV['USE_CACHE'] === 'true'));
    $cacheSettings['host'] = (isset($_ENV['CACHE_HOST'])) ? $_ENV['CACHE_HOST'] : '127.0.0.1';
    $cacheSettings['port'] = (isset($_ENV['CACHE_PORT'])) ? $_ENV['CACHE_PORT'] : '11211';
    $cacheSettings['expire_cache'] = (isset($_ENV['CACHE_SECONDS'])) ? intval($_ENV['CACHE_SECONDS']) : 86400;
    $app->add(new CachingMiddleware($useCaching, $cacheSettings));

    $analyticsSettings = $pathSettings;
    $isTracking = ((isset($_ENV['GA_TRACK_REQUESTS'])) && ($_ENV['GA_TRACK_REQUESTS'] === 'true'));
    $analyticsSettings['measurement_id'] = (isset($_ENV['GA_MEASUREMENT_ID'])) ? $_ENV['GA_MEASUREMENT_ID'] : '';
    $analyticsSettings['api_secret'] = (isset($_ENV['GA_SECRET'])) ? $_ENV['GA_SECRET'] : '';
    $app->add(new GoogleAnalyticsMiddleware($isTracking, $analyticsSettings));

    $authSettings = [
        'path'          =>  ['/api_keys'],
        'passthrough'   =>  ['/api_keys/new']
    ];
    $authSettings['users'][$_ENV['ADMIN_USERNAME']] = $_ENV['ADMIN_PASSWORD'];
    $app->add(new HttpBasicAuthentication($authSettings));
    // Add the routing  middleware
    $app->addRoutingMiddleware();

    $standardSettings = $pathSettings;
    $standardSettings['formats'] = ['json', 'xml'];
    $standardSettings['versions'] = [1];
    $app->add(new APIAuthMiddleware($container->get('db'), $pathSettings));
    $app->add(new APIStandardsMiddleware($standardSettings));
};
