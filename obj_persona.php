<?php
 class Persona{
    public $Id;
    public $Nombre ;
    public $Apellidos ;
    public $Email ;
    public $Telefono;
    public $Genero ;
    public $FechaNacimiento;


    public function __construct($_nombre, $_apellidos, $_email, $_telefono, $_genero, $_fechanacimiento, $_id)
    {
        $this->Id = $_id;
        $this->Nombre = $_nombre;          
        $this->Apellidos = $_apellidos;
        $this->Email = $_email;
        $this->Telefono = $_telefono;
        $this->Genero = $_genero;
        $this->FechaNacimiento = $_fechanacimiento;
    }

}
?>