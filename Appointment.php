<?php


class Appointment
{
    private $id;
    private $name;
    private $email;
    private $phoneNumber;
    private $nationalID;
    private $date;

    public function __construct($id, $name,$email,$phoneNumber,$nationalID,$date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->nationalID = $nationalID;
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Appointment
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Appointment
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Appointment
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     * @return Appointment
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNationalID()
    {
        return $this->nationalID;
    }

    /**
     * @param mixed $nationalID
     * @return Appointment
     */
    public function setNationalID($nationalID)
    {
        $this->nationalID = $nationalID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate($format)
    {
        return date($format,strtotime($this->date));
    }

    /**
     * @param mixed $date
     * @return Appointment
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function __toString(): string
    {
        return "id: $this->id name: $this->name email: $this->email phone number: $this->phoneNumber national ID : $this->nationalID date: $this->date";
    }

}