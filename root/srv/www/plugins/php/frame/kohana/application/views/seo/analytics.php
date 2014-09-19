<?php

//Cloning Google API's
//git clone https://github.com/google/google-api-php-client.git

//Setting include_path in PHP.ini
//include_path = ".:/usr/local's/lib/php:/path/to/google-api-php-client/src"

//Alternatively, you can set the same ini directive dynamically in your code.
$lib_path = $_SERVER['DOCUMENT_ROOT'] . '/path/relative/to/DOCUMENT_ROOT/';
set_include_path(get_include_path() . PATH_SEPARATOR . $lib_path . 'google-api-php-client/src');

require_once 'Google/Client.php';
require_once 'Google/Service/Analytics.php';

$keyfile = 'path/relative/to/DOCUMENT_ROOT/xxxxxxxxxxxxxxxx.p12'; // keyfile location   
$gaEmail = 'xxxxxxxxxxxxxxxx@developer.gserviceaccount.com'; // email you added to GA
$gaAuth = 'https://www.googleapis.com/auth/analytics.readonly';

// Create Client Object
$client = new Google_Client();
$client->setApplicationName('Google_Analytics'); // name of your app
$client->setClientId('xxxxxxxxxxxxxxxx.apps.googleusercontent.com'); // from API console
$client->setAssertionCredentials(new Google_Auth_AssertionCredentials($gaEmail, array($gaAuth), file_get_contents($keyfile)));

/* Sample Grabbing Analytics data
$service = new Google_Service_Analytics($client);
var_dump($service->management_accounts->listManagementAccounts());
$response = $service->data_ga->get(
    'ga:87364223', // profile id
    '2014-09-01', // start date
    '2014-09-10', // end date
    'ga:uniquePageviews',
    array(
        'dimensions' => 'ga:pagePath',
        'sort' => '-ga:uniquePageviews',
        'filters' => 'ga:pagePath=~/[a-zA-Z0-9-]+/[a-zA-Z0-9-]+', // http://localhost/browse/style/3#showmoreexample url regex filter
        'max-results' => '25'
    )
);
var_dump($response);
*/

// Your analytics profile id. (Admin -> Profile Settings -> Profile ID)
$profile_id = 'ga:xxxxxxxx';
$start = 'yesterday';
$end = 'today';

try {
    $service = new Google_Service_Analytics($client);
    $results = $service->data_ga->get($profile_id, $start, $end, 'ga:visits');
    echo $results['totalsForAllResults']['ga:visits'];
} 
catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}

?>
