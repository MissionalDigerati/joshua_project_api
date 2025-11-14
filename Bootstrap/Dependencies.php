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
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;
use Utilities\Mailer;
use Utilities\APIErrorResponder;

 /**
  * Add the dependencies to the container
  */
return function(ContainerBuilder $containerBuilder, string $viewDirectory) {
    $containerBuilder->addDefinitions([
        'db'    =>  function(ContainerInterface $interface) {
            return DriverManager::getConnection([
                'driver' => 'pdo_mysql',
                'host' => $_ENV['DB_HOST'],
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'],
                'charset' => 'utf8'
            ]);
        },
        'errorResponder'    => fn(ContainerInterface $interface) => new APIErrorResponder(),
        'httpClient' => fn(ContainerInterface $interface) => new Client(),
        'mailer'    =>  function(ContainerInterface $interface) {
            $useSMTP = ($_ENV['EMAIL_USE_SMTP'] === 'true');
            return new Mailer(
                $_ENV['EMAIL_HOST'],
                $_ENV['EMAIL_USERNAME'],
                $_ENV['EMAIL_PASSWORD'],
                $_ENV['EMAIL_PORT'],
                $useSMTP
            );
        },
        'recaptchaValidator' => function(ContainerInterface $interface) {
            return new \Utilities\RecaptchaValidator(
                $_ENV['RECAPTCHA_API_KEY'] ?? '',
                $interface->get('httpClient'),
                $_ENV['RECAPTCHA_PROJECT'] ?? '',
                $_ENV['RECAPTCHA_SITE_KEY'] ?? ''
            );
        },
        'view'  =>  function(ContainerInterface $interface) use ($viewDirectory) {
            return new PhpRenderer(
                $viewDirectory,
                ['viewDirectory' => $viewDirectory]
            );
        }
    ]);
};
