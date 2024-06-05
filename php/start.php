//run this together with sqlTableCreator to create database with tables

//Session key words variables:
//$_SESSION['shoppingList']; = contains Foodcards array added to the shoppinglist.
//$_SESSION['cardIndex'] = contains the current index of the "carosell" in HOME
//$_SESSION['currentCardName'] = the current card name
//$_SESSION['exportListStr'];
//$_SESSION['current_user_name']; ( email )
//$_SESSION['current_user_id'];
//$_SESSION['newPassword'];
//$_SESSION['transferInfo'];

//Execution paths:
//start.php -> authorization.php, echo login.html
// authorization.php -> home.html
//Once database is setup. authorization.php can be the initial starting page for
//Food Coordinator.

//https://stackify.com/display-php-errors/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'sqlTableCreator.php';
$sqlTableCreator = new sqlTableCreator('FoodCoordinator');

include '../php/authorization.php';
?>
