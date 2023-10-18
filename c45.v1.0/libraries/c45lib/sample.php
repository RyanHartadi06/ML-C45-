<?php
require 'vendor/autoload.php';
$c45 = new Algorithm\C45();
$input = new Algorithm\C45\DataInput;
$data = [
    ['Outlook' => 'Sunny', 'Temperature' => 'Hot', 'Humidity' => 'High', 'Windy' => 'False', 'Play' => 'No'],
    ['Outlook' => 'Sunny', 'Temperature' => 'Hot', 'Humidity' => 'High', 'Windy' => 'True', 'Play' => 'No'],
    ['Outlook' => 'Overcast', 'Temperature' => 'Hot', 'Humidity' => 'High', 'Windy' => 'False', 'Play' => 'Yes'],
    ['Outlook' => 'Rain', 'Temperature' => 'Mild', 'Humidity' => 'High', 'Windy' => 'False', 'Play' => 'Yes'],
    ['Outlook' => 'Rain', 'Temperature' => 'Cool', 'Humidity' => 'Normal', 'Windy' => 'False', 'Play' => 'Yes'],
    ['Outlook' => 'Rain', 'Temperature' => 'Cool', 'Humidity' => 'Normal', 'Windy' => 'True', 'Play' => 'No'],
    ['Outlook' => 'Overcast', 'Temperature' => 'Cool', 'Humidity' => 'Normal', 'Windy' => 'True', 'Play' => 'Yes'],
    ['Outlook' => 'Sunny', 'Temperature' => 'Mild', 'Humidity' => 'High', 'Windy' => 'False', 'Play' => 'No'],
    ['Outlook' => 'Sunny', 'Temperature' => 'Cool', 'Humidity' => 'Normal', 'Windy' => 'False', 'Play' => 'Yes'],
    ['Outlook' => 'Rain', 'Temperature' => 'Mild', 'Humidity' => 'Normal', 'Windy' => 'False', 'Play' => 'Yes'],
    ['Outlook' => 'Sunny', 'Temperature' => 'Mild', 'Humidity' => 'Normal', 'Windy' => 'True', 'Play' => 'Yes'],
    ['Outlook' => 'Overcast', 'Temperature' => 'Mild', 'Humidity' => 'High', 'Windy' => 'True', 'Play' => 'Yes'],
    ['Outlook' => 'Overcast', 'Temperature' => 'Hot', 'Humidity' => 'Normal', 'Windy' => 'False', 'Play' => 'Yes'],
    ['Outlook' => 'Rain', 'Temperature' => 'Mild', 'Humidity' => 'High', 'Windy' => 'True', 'Play' => 'No'],
];

// Initialize Data
$input->setData($data); // Set data from array
$input->setAttributes(array('Outlook', 'Temperature', 'Humidity', 'Windy', 'Play')); // Set attributes of data
// Initialize C4.5
$c45->c45 = $input; // Set input data
$c45->setTargetAttribute('Play'); // Set target attribute
// echo "<pre>";
// print_r($c45);
// echo "</pre>";
// die;
$initialize = $c45->initialize(); // initialize

// Build Output
$buildTree = $initialize->buildTree(); // Build tree
$arrayTree = $buildTree->toArray(); // Set to array
$stringTree = $buildTree->toString(); // Set to string
$jsonTree = $buildTree->toJson(); // Set to string

echo "<pre>";
print_r($arrayTree);
echo "</pre>";
echo "<pre>";
print_r($stringTree);
echo "</pre>";

?>
