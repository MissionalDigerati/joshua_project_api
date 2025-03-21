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
use Middleware\RequestTimeLoggerMiddleware;
use Slim\Middleware\MethodOverrideMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

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
        'passthrough' => [
            '/v\d+/docs/column_descriptions'
        ],
        'paths'  =>  [
            '/v\d+/continents',
            '/v\d+/countries',
            '/v\d+/languages',
            '/v\d+/people_groups',
            '/v\d+/people_groups_global',
            '/v\d+/regions',
            '/v\d+/totals',
        ]
    ];
    $cacheSettings = $pathSettings;
    $cacheSettings['passthrough'][] = '/v\d+/people_groups/daily_unreached.json';
    $cacheSettings['passthrough'][] = '/v\d+/people_groups/daily_unreached.xml';
    $useCaching = ((isset($_ENV['USE_CACHE'])) && ($_ENV['USE_CACHE'] === 'true'));
    $cacheSettings['host'] = (isset($_ENV['CACHE_HOST'])) ? $_ENV['CACHE_HOST'] : '127.0.0.1';
    $cacheSettings['port'] = (isset($_ENV['CACHE_PORT'])) ? $_ENV['CACHE_PORT'] : '11211';
    $cacheSettings['expire_cache'] = (isset($_ENV['CACHE_SECONDS'])) ? intval($_ENV['CACHE_SECONDS']) : 86400;
    $app->add(new CachingMiddleware($useCaching, $cacheSettings));

    /**
     * Add the RequestTimeLoggerMiddleware second so it wraps the entire process. We also
     * want to log cached requests, so that is why we are adding it here.
     */
    $logFile = __DIR__ . '/../Logs/request.log';
    $loggedPaths = [
        '/v1/continents',
        '/v1/countries',
        '/v1/languages',
        '/v1/people_groups',
        '/v1/people_groups_global',
        '/v1/regions',
        '/v1/totals',
    ];
    $logRequestTimes = ((isset($_ENV['LOG_REQUEST_TIMES'])) && ($_ENV['LOG_REQUEST_TIMES'] === 'true'));
    if ($logRequestTimes) {
        $app->add(new RequestTimeLoggerMiddleware($logFile, $loggedPaths));
    }
    $analyticsSettings = $pathSettings;
    $isTracking = ((isset($_ENV['GA_TRACK_REQUESTS'])) && ($_ENV['GA_TRACK_REQUESTS'] === 'true'));
    $analyticsSettings['measurement_id'] = (isset($_ENV['GA_MEASUREMENT_ID'])) ? $_ENV['GA_MEASUREMENT_ID'] : '';
    $analyticsSettings['api_secret'] = (isset($_ENV['GA_SECRET'])) ? $_ENV['GA_SECRET'] : '';
    $app->add(new GoogleAnalyticsMiddleware($isTracking, $analyticsSettings));

    $authSettings = [
        'path'          =>  ['/api_keys'],
        'ignore'        =>  ['/api_keys/new']
    ];
    $authSettings['users'][$_ENV['ADMIN_USERNAME']] = $_ENV['ADMIN_PASSWORD'];
    $app->add(new HttpBasicAuthentication($authSettings));
    // Add the routing  middleware
    $app->addRoutingMiddleware();
    // Allow forms to send the _method param
    $app->add(new MethodOverrideMiddleware());

    $standardSettings = $pathSettings;
    $standardSettings['formats'] = ['json', 'xml'];
    $standardSettings['versions'] = [1];
    $app->add(new APIAuthMiddleware($container->get('db'), $pathSettings));
    $app->add(new APIStandardsMiddleware($standardSettings));

    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    /**
     * Handle 404 errors
     */
    $errorMiddleware->setErrorHandler(
        HttpNotFoundException::class,
        function(
            Request $request,
            Throwable $exception,
            bool $displayErrorDetails,
            bool $logErrors,
            bool $logErrorDetails
        ) use ($app): Response {
            $response = $app->getResponseFactory()->createResponse();
            $path = $request->getUri()->getPath();

            // Extract the format from the path using a regular expression
            $format = 'json'; // Default format
            if (preg_match('/\.(json|xml)$/', $path, $matches)) {
                $format = $matches[1];
            }
            return $this->get('errorResponder')->get(
                404,
                'The requested resource could not be found.',
                $format,
                'Not Found',
                $response
            );
    });
};
