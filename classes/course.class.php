<?php
// Class for courses
class Course{

    // Declaring properties
    private $connection;
    private $name;
    private $startdate;
    private $enddate;
    private $schoolid;

    // Class constructor that takes database connection and puts it in property $connection
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Method that selects all from database and returns informatin as an associative array
    function getList(){
        $sql = "SELECT * FROM course";
        $result = mysqli_query($this->connection, $sql);
        $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return  $resultArray;
    }

    // Method that selects specific course in database based on id
    function getCourse($id){
        $sql = "SELECT * FROM course WHERE id='$id'";
        $result = mysqli_query($this->connection, $sql);
        $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return  $resultArray;
    }

    // Method that creates new course in database
    function createCourse($name, $startdate, $enddate, $schoolid){

            // Turn all input into solid strings
            $escapedschoolid = $this->connection->real_escape_string($schoolid);
            $escapedName = $this->connection->real_escape_string($name);
            $escapedstartdate = $this->connection->real_escape_string($startdate);
            $escapedenddate = $this->connection->real_escape_string($enddate);

            // Insert inputs into database
            $sql = "INSERT INTO course (schoolid, name, startdate, enddate) VALUES ('$escapedschoolid', '$escapedName', '$escapedstartdate', '$escapedenddate')";
            $result = mysqli_query($this->connection, $sql);
                if ($result==true){
                    return $result;
                }else{
                    return false;
                }
    }

    // Method that updates existing course in database
    function updateCourse($schoolid, $name, $startdate, $enddate, $id){
        
        // Checks so that the course with that id exists
        if(sizeof($this->getCourse($id))>0){

            // Turns all inputted info into pure strings
            $escapedschoolid = $this->connection->real_escape_string($schoolid);
            $escapedName = $this->connection->real_escape_string($name);
            $escapedstartdate = $this->connection->real_escape_string($startdate);
            $escapedenddate = $this->connection->real_escape_string($enddate);
    
            // Updates that course with that id in the database
            $sql = "UPDATE course SET schoolid='$escapedschoolid', name='$escapedName', startdate='$escapedstartdate', enddate='$escapedenddate' WHERE id=$id";
            $result = mysqli_query($this->connection, $sql);
            if ($result==true){
                http_response_code(200);
                return $this->getCourse($id);
            }else{
                http_response_code(500);
                return array("message" => "Uppdatering misslyckades!");
            }
        }else{
            http_response_code(404);
            return array("message" => "Det finns ingen kurs med id $id!");
        }
        
    }

    // Method to delete course in database
    function deleteCourse($id){

        // Checks so that course with that id exists
        if(sizeof($this->getCourse($id))>0){
            
            // Deletes that course from the database.
            $sql = "DELETE FROM course WHERE id=$id";
            $result = mysqli_query($this->connection, $sql);

            if ($result==true){
                http_response_code(200);
                return array("message" => "Kursen är borttagen!");
            }else{
                http_response_code(500);
                return array("message" => "Borttagning misslyckades!");
            }
        }else{
            http_response_code(404);
            return array("message" => "Det finns ingen kurs med id $id!");
        }
    }
}