<?php

include_once 'Validation.php';
include_once 'Appointment.php';

//dummy data
$data = array(
    $appointment = new Appointment(0,'name','new@mail.ru' , '862443304','39912163318','2020-12-12 15:15'),
    $appointment1 = new Appointment(1,'name','new@mail.ru' , '862443304','39912163318','2020-12-12 23:12')
);

//runner code
while(true){
    printMenu();

    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);

    if(trim($line) == 1){
        $data = register($data);
    }

    if(trim($line) == 2){
        printAppointments($data);

        editAppointment($data);
    }

    if(trim($line) == 3){
        printAppointments($data);

        $data = deleteAppointment($data);
    }

    if(trim($line) == 4){
        printAppointments($data);
    }

    if(trim($line) == 5){
        $filteredArray = printAppointmentsByDate($data);
        printAppointments($filteredArray);
    }
}

function register(array $data): array
{
    $name = enterField('Enter name', 'Please enter your name', Validation::FIELD_TYPE_TEXT);

    $email = enterField('Enter email',
                        'Invalid email. Please enter it again (example of email: example@mail.com)',
                        Validation::FIELD_TYPE_EMAIL);

    $phoneNumber = enterField('Enter phone number (starts with 86)',
                              'Phone number should only contain 9 digits',
                              Validation::FIELD_TYPE_PHONE_NUMBER);

    $nationalID = enterField('Enter your national (Lithuanian) ID number. It should be 11 digits.' ,
                             'Invalid national ID. It should contain 11 digits.' ,
                             Validation::FIELD_TYPE_NATIONALID);

    $dateAndTime = enterField('Enter date and time of an appointment (example: 2020-12-12 15:15)',
                              'Invalid date. Please try again. Make sure it follows the date format (YYYY-MM-DD H:i)',
                              Validation::FIELD_TYPE_DATE);

    $date = date(Validation::FORMAT_DATE_AND_TIME, strtotime($dateAndTime));

    $appointment = new Appointment(count($data),$name,$email,$phoneNumber,$nationalID,$date);

    array_push($data, $appointment);

    echo "You have successfully registered for vaccination!\n";

    return $data;
}

function enterField($message, $errorMessage, $fieldType): string
{
    $field = trim(readline("$message: \n"));

    while (!Validation::$fieldType($field)) {
        echo $errorMessage . "\n";
        $field = trim(readline("$message: \n"));
    }
    return $field;
}

function printMenu(): void
{
    echo "\nPress 1 to REGISTER for vaccination.\n\n";
    echo "Press 2 if you want to EDIT an appointment.\n\n";
    echo "Press 3 if you want to DELETE an appointment.\n\n";
    echo "Press 4 if you are a worker and want to PRINT OUT ALL OF THE appointments.\n\n";
    echo "Press 5 if you are a worker and want to FILTER the appointments by day.\n\n";
}

function deleteAppointment($data) : array{

    $id = returnValidId($data,'delete');

    unset($data[$id]);

    echo "\nAPPOINTMENT HAS BEEN SUCCESSFULLY DELETED!\n";

    return $data;
}

function editAppointment($data) : array{

    $id = returnValidId($data, 'edit');

    $appointment = $data[$id];

    $name = trim(readline(sprintf("First name (current - %s)\n" , $appointment->getName())));

    while(empty($name)){
        echo "Please enter a name\n";
        $name = trim(readline(sprintf("First name (current - %s)\n" , $appointment->getName())));
    }

    $email = enterEditableField('Enter email',
                        'Invalid email. Please enter it again (example of email: example@mail.com)',
                        Validation::FIELD_TYPE_EMAIL, $appointment->getEmail());

    $phoneNumber = enterEditableField('Enter phone number (starts with 86)',
                              'Phone number should only contain 9 digits and start with 86',
                              Validation::FIELD_TYPE_PHONE_NUMBER, $appointment->getPhoneNumber());

    $nationalID = enterEditableField('Enter your national (Lithuanian) ID number. It should be 11 digits.' ,
                             'Invalid national ID. It should contain 11 digits.' ,
                             Validation::FIELD_TYPE_NATIONALID, $appointment->getNationalID());

    $dateAndTime = enterEditableField('Enter date and time of an appointment (example: 2020-12-12 15:15)',
                              'Invalid date. Please try again. Make sure it follows the date format (YYYY-MM-DD H:i)',
                              Validation::FIELD_TYPE_DATE, $appointment->getDate('Y-m-d H:i'));

    $date = date(Validation::FORMAT_DATE_AND_TIME, strtotime($dateAndTime));

    $appointment->setName($name);
    $appointment->setEmail($email);
    $appointment->setPhoneNumber($phoneNumber);
    $appointment->setNationalID($nationalID);
    $appointment->setDate($date);

    echo "\nAPPOINTMENT HAS BEEN SUCCESSFULLY EDITED!\n";

    return $data;
}

function returnValidId($data,$actionMessage): string
{
    $id = trim(readline("Enter the id of appointment that you want to $actionMessage."));
    $keys = array_keys($data);

    while (!in_array($id, $keys)) {
        echo "Invalid ID. Please try again \n";
        $id = trim(readline("Enter the id of appointment that you want to $actionMessage."));
    }
    return $id;
}

function enterEditableField($message,$errorMessage,$fieldType, $currentFieldValue): string
{
    $field = trim(readline(sprintf("$message (current - %s)\n", $currentFieldValue)));

    while (!Validation::$fieldType($field)) {
        echo "$errorMessage'\n";

        $field = trim(readline(sprintf("$message (current - %s)\n", $currentFieldValue)));
    }
    return $field;
}

function printAppointments($data): void{

    if(empty($data)) {
        echo "There are no appointments yet";
    }
    else{
        echo "\n Appointments: \n";
        foreach($data as $appointment) echo $appointment . "\n";
    }
}

function printAppointmentsByDate($data): array
{
    $dateAndTime = enterField("Date by which to filter appointments (format Year-Month-Day): \n",
                              'Invalid date. Please try again. Example of a valid date (2002-12-12)',
                              Validation::FIELD_TYPE_DATE);

    $date = date(Validation::FORMAT_DATE, strtotime($dateAndTime));

    $filteredArray = array_filter( $data,function ($var) use ($date) {
            return ($var->getDate(Validation::FORMAT_DATE) == $date);
        }
    );

    usort($filteredArray, function($a,$b){
        return $a->getDate(Validation::FORMAT_TIME) <=> $b->getDate(Validation::FORMAT_TIME);
    });
    return $filteredArray;
}
