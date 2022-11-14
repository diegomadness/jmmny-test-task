<?php
declare(strict_types=1);
require 'vendor/autoload.php';

use App\Controllers\ConsoleController;
use App\Repositories\Txt\SilenceRepository;
use App\Services\DialogService;
use App\Services\SilenceService;

//to avoid using dotenv and make code dependent on anything I'm just throwing here this .env file  parsing simulation
putenv("USER_FILE_PATH=user-channel.txt");
putenv("CUSTOMER_FILE_PATH=customer-channel.txt");

// I've inserted a controller here just to make the code IoC-friendly
$controller = new ConsoleController(new DialogService(), new SilenceService(new SilenceRepository()));
echo $controller->index();