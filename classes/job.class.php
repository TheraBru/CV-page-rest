<?php
// Class for jobs
class Job{

    // Declaring properties
    private $connection;
    private $title;
    private $workplace;
    private $startdate;
    private $enddate;

    // Class constructor that takes database connection and puts it in property $connection
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Method that selects all from database and returns informatin as an associative array
    function getList(){
        $sql = "SELECT * FROM job";
        $result = mysqli_query($this->connection, $sql);
        $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return  $resultArray;
    }

    // Method that selects specific job in database based on id
    function getJob($id){
        $sql = "SELECT * FROM job WHERE id='$id'";
        $result = mysqli_query($this->connection, $sql);
        $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return  $resultArray;
    }

    // Method that creates new job in database
    function createJob($title, $workplace, $startdate, $enddate){

            // Turn all input into solid strings
            $escapedTitle = $this->connection->real_escape_string($title);
            $escapedWorkplace = $this->connection->real_escape_string($workplace);
            $escapedStartdate = $this->connection->real_escape_string($startdate);
            $escapedEnddate = $this->connection->real_escape_string($enddate);

            // Insert inputs into database
            $sql = "INSERT INTO job (title, workplace, startdate, enddate) VALUES ('$escapedTitle', '$escapedWorkplace', '$escapedStartdate', '$escapedEnddate')";
            $result = mysqli_query($this->connection, $sql);
                if ($result==true){
                    return $result;
                }else{
                    return false;
                }
    }

    // Method that updates existing job in database
    function updateJob($title, $workplace, $startdate, $enddate, $id){
        
        // Checks so that the job with that id exists
        if(sizeof($this->getJob($id))>0){

            // Turns all inputted info into pure strings
            $escapedTitle = $this->connection->real_escape_string($title);
            $escapedWorkplace= $this->connection->real_escape_string($workplace);
            $escapedStartdate = $this->connection->real_escape_string($startdate);
            $escapedEnddate = $this->connection->real_escape_string($enddate);
    
            // Updates that job with that id in the database
            $sql = "UPDATE job SET title='$escapedTitle', workplace='$escapedWorkplace', startdate='$escapedStartdate', enddate='$escapedEnddate' WHERE id=$id";
            $result = mysqli_query($this->connection, $sql);
            if ($result==true){
                http_response_code(200);
                return $this->getJob($id);
            }else{
                http_response_code(500);
                return array("message" => "Uppdatering misslyckades!");
            }
        }else{
            http_response_code(404);
            return array("message" => "Det finns inget jobb med id $id!");
        }
        
    }

    // Method to delete job in database
    function deleteJob($id){

        // Checks so that job with that id exists
        if(sizeof($this->getJob($id))>0){
            
            // Deletes that job from the database.
            $sql = "DELETE FROM job WHERE id=$id";
            $result = mysqli_query($this->connection, $sql);

            if ($result==true){
                http_response_code(200);
                return array("message" => "Jobbet är borttagen!");
            }else{
                http_response_code(500);
                return array("message" => "Borttagning misslyckades!");
            }
        }else{
            http_response_code(404);
            return array("message" => "Det finns inget jobb med id $id!");
        }
    }
}