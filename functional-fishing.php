<?php
/**********************************************
 * STARTER CODE
 **********************************************/

/**
 * clearSession
 * This function will clear the session.
 */
function clearSession()
{
  session_unset();
  header("Location: " . $_SERVER['PHP_SELF']);
}

/**
 * Invokes the clearSession() function.
 * This should be used if your session becomes wonky
 */
if (isset($_GET['clear'])) {
  clearSession();
}

/**
 * getResponse
 * Gets the response history array from the session and converts to a string
 * 
 * This function should be used to get the full response array as a string
 * 
 * @return string
 */
function getResponse()
{
  return implode('<br><br>', $_SESSION['functional_fishing']['response']);
}

/**
 * updateResponse
 * Adds a new response to the response array found in session
 * Returns the full response array as a string
 * 
 * This function should be used each time an action returns a response
 * 
 * @param [string] $response
 * @return string
 */
function updateResponse($response)
{
  echo 'update start';
  if (!isset($_SESSION['functional_fishing'])) {
    createGameData();
  }

  array_push($_SESSION['functional_fishing']['response'], $response);

  return getResponse();
}

/**
 * help
 * Returns a formatted string of game instructions
 * 
 * @return string
 */
function help()
{
  return 'Welcome to Functional Fishing, the text based fishing game. Use the following commands to play the game: <span class="red">eat</span>, <span class="red">fish</span>, <span class="red">fire</span>, <span class="red">wood</span>, <span class="red">bait</span>. To restart the game use the <span class="red">restart</span> command For these instruction again use the <span class="red">help</span> command';
}

/**********************************************
 * YOUR CODE BELOW
 **********************************************/
session_start();
/**
 * createGameData
 */
function createGameData() {
  $_SESSION['functional_fishing'] = 
    array(
      'response' => (array) null,
      'fish' => 0,
      'wood' => 0,
      'bait' => 0,
      'fire' => false
    );
    echo 'game started';
}

//check if the user has send any command
if (!empty($_POST['command'])) {
  //check if the command is a function.
  if (function_exists($_POST['command'])) {
    $result = $_POST['command'];
    $_POST['command']();
  } else {
    $response = "{$_POST['command']} is not a command";
    updateResponse($response);
  }
}

/**
 * fire
 *
 */
function fire() {
  //if the fire is alreay on,  put it out
  if ($_SESSION['functional_fishing']['fire']) {
    $_SESSION['functional_fishing']['fire'] = false;
    $response = 'You have put out the fire';
    //if fire  is out, check if there is enough wood
  } elseif ($_SESSION['functional_fishing']['wood'] > 0) {
    $_SESSION['functional_fishing']['fire'] = true;
    $response = "You have started a fire";
    $_SESSION['functional_fishing']['wood']--;
  } else {
    $response = 'You do not have enough wood';
  }
  updateResponse($response);
}

/**
 * bait
 * 
 */
function bait() {
  //check if fire is out; if not ask user to put it out first
  if ($_SESSION['functional_fishing']['fire']) {
    $response = "You must put out the fire";
  } else {
    $_SESSION['functional_fishing']['bait']++;
    $response = "You have found some bait";
  }
  updateResponse($response);
}

/**
 * wood
 * 
 */
function wood() {
  //check if fire is out; if not ask user to put it out first
  if ($_SESSION['functional_fishing']['fire']) {
    $response = "You must put out the fire";
  } else {
    $_SESSION['functional_fishing']['wood']++;
    $response = "You have found some wood";
  }
  updateResponse($response);
  
}

/**
 * fish
 * 
 */
function fish() {
  //check if fire is out; if not ask user to put it out first
  if ($_SESSION['functional_fishing']['fire']) {
    $response = "You must put out the fire";
    //check if there is enougth bait to catch fish
  } elseif ($_SESSION['functional_fishing']['bait'] > 0) {
    $_SESSION['functional_fishing']['fish']++;
    $_SESSION['functional_fishing']['bait']--;
    $response = "You caught a fish";
  } else {
    $response = "You do not have enought bait";
  }
  updateResponse($response);
}


/**
 * eat
 * 
 */
function eat() {
  //check if fire is on; if not ask user to put it on first
  if ($_SESSION['functional_fishing']['fire']) {
    if($_SESSION['functional_fishing']['fish'] > 0) {
      $_SESSION['functional_fishing']['fish']--;
      $response = "You have eaten a fish";
      //check if there is any fish to eat
    } else {
      $response = "You have no fish";
    }
  } else {
    $response = "You must start a fire";
  }
  updateResponse($response);
}

/**
 * inventory
 * 
 */
function inventory() {
  $response = 
  "{$_SESSION['functional_fishing']['fish']} fish<br>
  {$_SESSION['functional_fishing']['bait']} bait<br>
  {$_SESSION['functional_fishing']['wood']} wood<br>";
  if($_SESSION['functional_fishing']['fire']) {
    $response .= "The fire is going";
  } else {
    $response .= "The fire is out";
  }
  updateResponse($response);
}

/**
 * restart
 * 
 */
function restart() {
  clearSession();
}
?>