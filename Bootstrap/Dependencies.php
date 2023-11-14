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
use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;
use PHPToolbox\PDODatabase\PDODatabaseConnect;
use Utilities\Mailer;
use Utilities\APIErrorResponder;

 /**
  * Add the dependencies to the container
  */
return function(ContainerBuilder $containerBuilder, string $viewDirectory) {
    $containerBuilder->addDefinitions([
        'db'    =>  function(ContainerInterface $interface) {
            $dbSettings = new \stdClass();
            $dbSettings->default = [
                'host'      =>  $_ENV['DB_HOST'],
                'name'      =>  $_ENV['DB_NAME'],
                'username'  =>  $_ENV['DB_USERNAME'],
                'password'  =>  $_ENV['DB_PASSWORD']
            ];
            $pdoDb = PDODatabaseConnect::getInstance();
            $pdoDb->setDatabaseSettings($dbSettings);
            return $pdoDb->getDatabaseInstance();
        },
        'errorResponder'    => function(ContainerInterface $interface) {
            return new APIErrorResponder();
        },
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
        'view'  =>  function(ContainerInterface $interface) use ($viewDirectory) {
            return new PhpRenderer(
                $viewDirectory,
                ['viewDirectory' => $viewDirectory]
            );
        }
    ]);
};
