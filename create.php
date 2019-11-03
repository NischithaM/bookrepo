<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$bid = $name = $author = $price = "";
$bid_err = $name_err = $author_err = $price_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	$input_bid= trim($_POST["bid"]);
    if(empty($input_bid)){
        $bid_err = "Please enter book id.";
    
    } else{
        $bid= $input_bid;
    }
    //Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_author = trim($_POST["author"]);
    if(empty($input_author)){
        $author_err = "Please enter a author name.";     
    } else{
        $author = $input_author;
    }
    
    // Validate salary
    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the  amount.";     
    } elseif(!ctype_digit($input_price)){
        $price_err = "Please enter a positive integer value.";
    } else{
        $price = $input_price;
    }
    
    // Check input errors before inserting in database
    if(empty($bid_err) && empty($name_err) && empty($author_err) && empty($price_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO book (bid,name, author, price) VALUES (?,?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isss", $param_bid, $param_name, $param_author, $param_price);
            
            // Set parameters
			 $param_bid = $bid;
            $param_name = $name;
            $param_author = $author;
            $param_price= $price;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add book record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					    <div class="form-group <?php echo (!empty($bid_err)) ? 'has-error' : ''; ?>">
                            <label>bid</label>
                            <input type="text" name="bid" class="form-control" value="<?php echo $bid; ?>">
                            <span class="help-block"><?php echo $bid_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($author_err)) ? 'has-error' : ''; ?>">
                            <label>Author</label>
                            <textarea name="author" class="form-control"><?php echo $author; ?></textarea>
                            <span class="help-block"><?php echo $author_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
                            <span class="help-block"><?php echo $price_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>