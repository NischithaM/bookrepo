<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$bid = $name= $author = $price = "";
$bid_err = $name_err = $author_err = $price_err= "";
 
// Processing form data when form is submitted
if(isset($_POST["bid"]) && !empty($_POST["bid"])){
    // Get hidden input value
    $bid = $_POST["bid"];
	 
	$input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter the name.";     
    } elseif(!ctype_digit($input_name)){
        $name_err = "Please enter a positive integer value.";
    } else{
        $name= $input_name;
    }
    
    
    // Validate name
    $input_author = trim($_POST["author"]);
    if(empty($input_author)){
        $author_err = "Please enter a name.";
    } elseif(!filter_var($input_author, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $author_err = "Please enter a valid name.";
    } else{
        $author = $input_author;
    }
    
    // Validate address address
    $input_price= trim($_POST["price"]);
    if(empty($input_blang)){
        $price_err = "Please enter an address.";     
    } else{
        $price= $input_price;
    }
  
    // Check input errors before inserting in database
    if( empty($bid_err) &&empty($name_err) && empty($author_err) && empty($price_err)){
        // Prepare an update statement
        $sql = "UPDATE book SET name=?, author=?, price=? WHERE bid=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_name, $param_author, $param_price, $param_bid);
            
            // Set parameters
            $param_name = $name;
            $param_author = $author;
            $param_price = $price;
            $param_bid = $bid;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["bid"]) && !empty(trim($_GET["bid"]))){
        // Get URL parameter
        $bid =  trim($_GET["bid"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM book WHERE bid = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_bid);
            
            // Set parameters
            $param_bid = $bid;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
					$bid = $row["bid"];
                    $name= $row["name"];
                    $author = $row["author"];
                    $price = $row["price"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
					    <div class="form-group <?php echo (!empty($bid_err)) ? 'has-error' : ''; ?>">
                            <label>bid</label>
                            <input type="text" name="bid" class="form-control" value="<?php echo $bid; ?>">
                            <span class="help-block"><?php echo $bid_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($author_err)) ? 'has-error' : ''; ?>">
                            <label>Author</label>
                            <textarea name="author" class="form-control"><?php echo $author; ?></textarea>
                            <span class="help-block"><?php echo $author_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                            <label>price</label>
                            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
                            <span class="help-block"><?php echo $price_err;?></span>
                        </div>
                        <input type="hidden" name="bid" value="<?php echo $bid; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>