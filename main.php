<?php

include_once 'AppointmentController.php';
include_once 'Validation.php';
include_once 'Appointment.php';

$appointmentController = new AppointmentController();
//dummy data
$data = array(
    $appointment = new Appointment(0,'name','new@mail.ru' , '862443304','39912163318','2020-12-12 15:15'),
    $appointment1 = new Appointment(1,'name','new@mail.ru' , '862443304','39912163318','2020-12-12 23:12')
);

//runner code
while(true){
    $appointmentController->printMenu();

    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);

    if(trim($line) == 1){
        $data = $appointmentController->register($data);
    }

    if(trim($line) == 2){
        $appointmentController->printAppointments($data);
        $appointmentController->editAppointment($data);
    }

    if(trim($line) == 3){
        $appointmentController->printAppointments($data);
        $data = $appointmentController->deleteAppointment($data);
    }

    if(trim($line) == 4){
        $appointmentController->printAppointments($data);
    }

    if(trim($line) == 5){
        $filteredArray = $appointmentController->printAppointmentsByDate($data);
        $appointmentController->printAppointments($filteredArray);
    }
}
