<?php
// Class for websites
class Website{

    // Declaring properties
    private $connection;
    private $title;
    private $description;
    private $url;


    // Class constructor that takes database connection and puts it in property $connection
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Method that selects all from database and returns informatin as an associative array
    function getList(){
        $sql = "SELECT * FROM website";
        $result = mysqli_query($this->connection, $sql);
        $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return  $resultArray;
    }

    // Method that selects specific website in database based on id
    function getWebsite($id){
        $sql = "SELECT * FROM website WHERE id='$id'";
        $result = mysqli_query($this->connection, $sql);
        $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return  $resultArray;
    }

    // Method that creates new website in database
    function createWebsite($title, $description, $url){

            // Turn all input into solid strings
            $escapedtitle = $this->connection->real_escape_string($title);
            $escapeddescription = $this->connection->real_escape_string($description);
            $escapedurl = $this->connection->real_escape_string($url);

            // Insert inputs into database
            $sql = "INSERT INTO website (title, description, url) VALUES ('$escapedtitle', '$escapeddescription', '$escapedurl')";
            $result = mysqli_query($this->connection, $sql);
                if ($result==true){
                    return $result;
                }else{
                    return false;
                }
    }

    // Method that updates existing website in database
    function updateWebsite($title, $description, $url, $id){
        
        // Checks so that the website with that id exists
        if(sizeof($this->getWebsite($id))>0){

            // Turns all inputted info into pure strings
            $escapedtitle = $this->connection->real_escape_string($title);
            $escapeddescription= $this->connection->real_escape_string($description);
            $escapedurl = $this->connection->real_escape_string($url);
    
            // Updates that website with that id in the database
            $sql = "UPDATE website SET title='$escapedtitle', description='$escapeddescription', url='$escapedurl' WHERE id=$id";
            $result = mysqli_query($this->connection, $sql);
            if ($result==true){
                http_response_code(200);
                return $this->getWebsite($id);
            }else{
                http_response_code(500);
                return array("message" => "Uppdatering misslyckades!");
            }
        }else{
            http_response_code(404);
            return array("message" => "Det finns ingen webbplats med id $id!");
        }
        
    }

    // Method to delete website in database
    function deleteWebsite($id){

        // Checks so that website with that id exists
        if(sizeof($this->getWebsite($id))>0){
            
            // Deletes that website from the database.
            $sql = "DELETE FROM website WHERE id=$id";
            $result = mysqli_query($this->connection, $sql);

            if ($result==true){
                http_response_code(200);
                return array("message" => "Webbplatsen är borttagen!");
            }else{
                http_response_code(500);
                return array("message" => "Borttagning misslyckades!");
            }
        }else{
            http_response_code(404);
            return array("message" => "Det finns ingen webbplats med id $id!");
        }
    }
}