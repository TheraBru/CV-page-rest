<?php
// Class for schools
class School{

    // Declaring properties
    private $connection;
    private $schoolname;
    private $programname;
    private $degree;
    private $startdate;
    private $enddate;

    // Class constructor that takes database connection and puts it in property $connection
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Method that selects all from database and returns informatin as an associative array
    function getList(){
        $sql = "SELECT * FROM school";
        $result = mysqli_query($this->connection, $sql);
        $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return  $resultArray;
    }

    // Method that selects specific school in database based on id
    function getSchool($id){
        $sql = "SELECT * FROM school WHERE id='$id'";
        $result = mysqli_query($this->connection, $sql);
        $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return  $resultArray;
    }

    // Method that creates new school in database
    function createSchool($schoolname, $programname, $degree, $startdate, $enddate){

            // Turn all input into solid strings
            $escapedschoolname = $this->connection->real_escape_string($schoolname);
            $escapedprogramname = $this->connection->real_escape_string($programname);
            $escapeddegree = $this->connection->real_escape_string($degree);
            $escapedStartdate = $this->connection->real_escape_string($startdate);
            $escapedEnddate = $this->connection->real_escape_string($enddate);

            // Insert inputs into database
            $sql = "INSERT INTO school (schoolname, programname, degree, startdate, enddate) VALUES ('$escapedschoolname', '$escapedprogramname', '$escapeddegree', '$escapedStartdate', '$escapedEnddate')";
            $result = mysqli_query($this->connection, $sql);
                if ($result==true){
                    return $result;
                }else{
                    return false;
                }
    }

    // Method that updates existing school in database
    function updateSchool($schoolname, $programname, $degree, $startdate, $enddate, $id){
        
        // Checks so that the school with that id exists
        if(sizeof($this->getSchool($id))>0){

            // Turns all inputted info into pure strings
            $escapedschoolname = $this->connection->real_escape_string($schoolname);
            $escapedprogramname= $this->connection->real_escape_string($programname);
            $escapeddegree = $this->connection->real_escape_string($degree);
            $escapedStartdate = $this->connection->real_escape_string($startdate);
            $escapedEnddate = $this->connection->real_escape_string($enddate);
    
            // Updates that school with that id in the database
            $sql = "UPDATE school SET schoolname='$escapedschoolname', programname='$escapedprogramname', degree='$escapeddegree', startdate='$escapedStartdate', enddate='$escapedEnddate' WHERE id=$id";
            $result = mysqli_query($this->connection, $sql);
            if ($result==true){
                http_response_code(200);
                return $this->getSchool($id);
            }else{
                http_response_code(500);
                return array("message" => "Uppdatering misslyckades!");
            }
        }else{
            http_response_code(404);
            return array("message" => "Det finns ingen skola med id $id!");
        }
        
    }

    // Method to delete school in database
    function deleteSchool($id){

        // Checks so that school with that id exists
        if(sizeof($this->getSchool($id))>0){
            
            // Deletes that school from the database.
            $sql = "DELETE FROM school WHERE id=$id";
            $result = mysqli_query($this->connection, $sql);

            if ($result==true){
                http_response_code(200);
                return array("message" => "Skolan är borttagen!");
            }else{
                http_response_code(500);
                return array("message" => "Borttagning misslyckades!");
            }
        }else{
            http_response_code(404);
            return array("message" => "Det finns ingen skola med id $id!");
        }
    }
}