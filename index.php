<?php
    //  spl_autoload_register(function ($class_name) {
    //     include 'classes/' . $class_name . '.class.php';
    // });
    // Defining header info
    header('Access-Control-Allow-Origin: *');
    header('Content-Type:application/json');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
  
    // Declaring request method as a variable
    $method = $_SERVER['REQUEST_METHOD'];
  
    // Require database classes
    require('classes/dbhandler.class.php');
    require('classes/course.class.php');
    require('classes/job.class.php');
    require('classes/school.class.php');
    require('classes/website.class.php');
  
    // Declaring id variable based on whether id is set or not
    if (isset($_GET['id'])){
        $id = $_GET['id'];
    }

    // Declaring variables for parameters
    $course = false;
    $school = false;
    $job = false;
    $website = false;

    // Changing parameter variables according to parameters 
    if (isset($_GET['course'])){
        $course = true;
    }else if (isset($_GET['school'])){
        $school = true;
    }else if (isset($_GET['job'])){
        $job = true;
    } else if (isset($_GET['website'])){
        $website = true;
    } 
  
    // Instanciate database connection and send it to classes.
    $dbInstance = new DBHandler();
    $dbInstance->connect();
    $courses = new Course($dbInstance->getConnection());
    $schools = new School($dbInstance->getConnection());
    $jobs = new Job($dbInstance->getConnection());
    $websites = new Website($dbInstance->getConnection());
  
    // Switch through possible request methods
    switch($method){
        // Runs if method is GET
        case 'GET':
            // Runs getList method if id is not set.
            if(!isset($id)){
                if ($course == true){
                    $response = $courses->getList();

                } else if ($school == true){
                    $response = $schools->getList();

                } else if ($job == true){
                    $response = $jobs->getList();

                } else if ($website == true){
                    $response = $websites->getList();

                }else{
                    $response =  array("course" => $courses->getList(), "school" => $schools->getList(), "job" => $jobs->getList(), "website" =>$websites->getList() );
                }
            }else{
                if ($course == true){
                    $response = $courses->getCourse($id);
                } else if ($school == true){
                   $response = $schools->getSchool($id);
                
                } else if ($job == true){
                   $response = $jobs->getJob($id);

                } else if ($website == true){
                   $response = $websites->getWebsite($id);

                } else{
                    http_response_code(400);
                    $response =  array("message" => "Tyvärr, du måste definiera parameter.");
                }
            }
              
              // Checks size of returning data and returns data if it contains anything.
            if(sizeof($response)>0){
                http_response_code(200);
            }else{
                // If returning data is empty, checks if id was set and returns different error message depending on that.
                http_response_code(404);
                if(!isset($id)){
                    $response =  array("message" => "Tyvärr, det finns inget att hämta");
                }else{
                    $response =  array("message" =>"Tyvärr, det finns ingenting med id $id");
                }
            }
        break;
  
        // Runs if method used was POST
        case 'POST':
            // decodes input informations from json to PHP
            $data = json_decode(file_get_contents('php://input'));

            if ($course == true){
                // Declares inputted information as variables
                $name = $data->name;
                $startdate = $data->startdate;
                $enddate = $data->enddate;
                $schoolid = $data->schoolid;
  
                // Checks if code or name are empty or only consists of whitespace or are empty
                if(rtrim($name)==""||rtrim($schoolid)==""){
  
                    // Return bad request and message if variables were whitespace
                    http_response_code(400);
                    $response =  array("message" =>"Tyvärr, det saknas nödvändig information");
  
                }else{
  
                    // Runs createCourse method in courses class with inputted information
                    $posting = $courses->createCourse($name, $startdate, $enddate, $schoolid);
  
                    // Checks if method was successful and returns information accordingly 
                    if ($posting == true){
                        http_response_code(200);
                        $response = $courses->getList();
                    }else{
                        http_response_code(500);
                        $response =  array("message" =>"Tyvärr, det gick inte att lägga till informationen");
                    }
                }

            } else if ($school == true){
                // Declares inputted information as variables
                $schoolname = $data->schoolname;
                $programname = $data->programname;
                $degree = $data->degree;
                $startdate = $data->startdate;
                $enddate = $data->enddate;
   
                // Checks if code or name are empty or only consists of whitespace or are empty
                if(rtrim($schoolname)==""||rtrim($programname)==""||rtrim($startdate)==""){
   
                    // Return bad request and message if variables were whitespace
                    http_response_code(400);
                    $response =  array("message" =>"Tyvärr, det saknas nödvändig information");
   
                }else{
   
                    // Runs createSchool method in school class with inputted information
                    $posting = $schools->createSchool($schoolname, $programname, $degree, $startdate, $enddate);
   
                    // Checks if method was successful and returns information accordingly 
                    if ($posting == true){
                        http_response_code(200);
                        $response = $schools->getList();
                    }else{
                        http_response_code(500);
                        $response =  array("message" =>"Tyvärr, det gick inte att lägga till informationen");
                    }
                }

            } else if ($job == true){
                $title = $data->title;
                $workplace = $data->workplace;
                $startdate = $data->startdate;
                $enddate = $data->enddate;
  
                // Checks if code or name are empty or only consists of whitespace or are empty
                if(rtrim($title)==""||rtrim($workplace)==""||rtrim($startdate)==""){
  
                    // Return bad request and message if variables were whitespace
                    http_response_code(400);
                    $response =  array("message" =>"Tyvärr, det saknas nödvändig information");
  
                }else{
  
                    // Runs createJob method in job class with inputted information
                    $posting = $jobs->createJob($title, $workplace, $startdate, $enddate);
  
                    // Checks if method was successful and returns information accordingly 
                    if ($posting == true){
                        http_response_code(200);
                        $response = $jobs->getList();
                    }else{
                        http_response_code(500);
                        $response =  array("message" =>"Tyvärr, det gick inte att lägga till informationen");
                    }
                }

            } else if ($website == true){
                $title = $data->title;
                $description = $data->description;
                $url = $data->url;
  
                // Checks if code or name are empty or only consists of whitespace or are empty
                if(rtrim($title)==""||rtrim($description)==""||rtrim($url)==""){
  
                    // Return bad request and message if variables were whitespace
                    http_response_code(400);
                    $response =  array("message" =>"Tyvärr, det saknas nödvändig information");
  
                }else{
  
                    // Runs createWebsite method in website class with inputted information
                    $posting = $websites->createWebsite($title, $description, $url);
  
                    // Checks if method was successful and returns information accordingly 
                    if ($posting == true){
                        http_response_code(200);
                        $response = $websites->getList();
                    }else{
                        http_response_code(500);
                        $response =  array("message" =>"Tyvärr, det gick inte att lägga till informationen");
                    }
                }

            }else{
                $response = array("message" =>"Tyvärr, parameter saknas för det du vill skapa");
            }
   
        break;
  
        // Runs if method PUT was used
        case 'PUT': 
            // Checks if id is set
            if(!isset($id)){
                http_response_code(404);
                $response = array("message" => "Inget id är angivet");;
            }else{
                $data = json_decode(file_get_contents('php://input'));
                // PUT for course 
                if ($course == true){
                    $name = $data->name;
                    $startdate = $data->startdate;
                    $enddate = $data->enddate;
                    $schoolid = $data->schoolid;

                    $response = $courses->updateCourse($schoolid, $name, $startdate, $enddate, $id);

                // PUT for school
                } else if ($school == true){
                    $schoolname = $data->schoolname;
                    $programname = $data->programname;
                    $degree = $data->degree;
                    $startdate = $data->startdate;
                    $enddate = $data->enddate;

                    $response = $schools->updateSchool($schoolname, $programname, $degree, $startdate, $enddate, $id);

                // PUT for job
                } else if ($job == true){
                    $title = $data->title;
                    $workplace = $data->workplace;
                    $startdate = $data->startdate;
                    $enddate = $data->enddate;

                    $response = $jobs->updateJob($title, $workplace, $startdate, $enddate, $id);

                // PUT for website
                } else if ($website == true){
                    $title = $data->title;
                    $description = $data->description;
                    $url = $data->url;

                    $response = $websites->updateWebsite($title, $description, $url, $id);

                }else{
                    $response =  array("message" =>"Tyvärr, parameter saknas för det du vill ändra");
                }
            }
        break;
  
        // Runs if method DELETE was used
        case 'DELETE':
            // Checks if id is set
            if(!isset($id)){
                http_response_code(404);
                $response = array("message" => "Inget id är angivet");;
            }else{
                if ($course == true){
                    $response = $courses->deleteCourse($id);

                } else if ($school == true){
                   $response = $schools->deleteSchool($id);
                
                } else if ($job == true){
                    $response = $jobs->deleteJob($id);

                } else if ($website == true){
                    $response = $websites->deleteWebsite($id);

                } else{
                    http_response_code(400);
                    $response =  array("message" => "Tyvärr, du måste definiera parameter.");
                }
            }
        break;
    }
      
    // Writes out json version of whatever response is.
    echo json_encode($response);
  
    // Closing database connection.
    $dbInstance->closeConnection();