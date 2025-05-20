<?php

declare(strict_types=1);

include_once 'src/Autoloader.php';

use Controllers\UsersController;
use Models\User;
use Models\Employee;
use Providers\Tools\Helper as HelpProvider;
use Utilities\Math;
use Views\MainView;
use Configs\AppConfig;
use Helpers\HelperFunctions;

# 1.
$user = new User("active", "Juan@example.com");
$user->sayHello();

# 2.
$employee = new Employee("Santiago", "Fonzo", 200);
$employee->sayHello();
$employee->work();

# 3.
$helpProvider = new HelpProvider();
$helpProvider->help();

# 4.
$mainView = new MainView();
$mainView->render();

# 5.
#Check Autoloader.php

# 6.
$usersController = new UsersController();
$usersController->init();

# 7.
$math = new Math();
$sum = $math->add(10, 5);
echo "Sum of 10 and 5 is: {$sum}.\n";

# 8. 
echo AppConfig::APP_NAME . "\n";

# 9.
HelperFunctions::sayHi();

# 10.
$usersController->showUserName($user);
