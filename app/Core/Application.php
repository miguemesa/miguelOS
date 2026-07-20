<?php
declare(strict_types=1);

namespace App\Core;

use Dotenv\Dotenv;
use RuntimeException;

final class Application
{
    private Router $router;

    public function __construct(private readonly string $basePath)
    {
        $this->bootstrap();
    }

    public function run(): void
    {
        $this->router->dispatch()->send();
    }

    private function bootstrap(): void
    {
        $dotenv = Dotenv::createImmutable($this->basePath);
        $dotenv->safeLoad();

        Config::load($this->basePath . '/config');

        date_default_timezone_set(
            (string) Config::get('app.timezone', 'America/Mexico_City')
        );

        $request = new Request();
        $this->router = new Router($request);

        $routesFile = $this->basePath . '/routes/web.php';

        if (!is_file($routesFile)) {
            throw new RuntimeException('No existe routes/web.php.');
        }

        $router = $this->router;
        require $routesFile;
    }
}
