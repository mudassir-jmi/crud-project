<?php
require_once("db.php");
require_once("component.php");

$con = Createdb();

//Create button click
if(isset($_POST["create"])) {
    createData();
}
//update data click
if(isset($_POST['update'])) {
    UpdateData();
}

//Delete data by click trash icon
if(isset($_POST['delete'])) {
    deleteRecord();
}

// Delete All Record by click deleteAll btn
if(isset($_POST['deleteall'])) {
    deleteAll();
}

//************* ALL FUNCTION *************** 

//Create Funtion for data
function createData() {
    $bookname = textboxValue(value:"book_name");
    $bookpublisher = textboxValue(value:"book_publisher");
    $bookprice = textboxValue(value:"book_price");

    if($bookname && $bookpublisher && $bookprice) {

        $sql = "INSERT INTO books(book_name,book_publisher,book_price)
                VALUES('$bookname','$bookpublisher','$bookprice')";

                if(mysqli_query($GLOBALS['con'],$sql)) {
                    TextNode(classname:"success",msg:"Record Inserted Successfully...!");
                }else {
                    echo "Error";
                }
       }else {
          TextNode(classname:"error",msg:"Provide Data in the Textbox");   //Function Call
       }
    }

function textboxValue($value) {
    $textbox = mysqli_real_escape_string($GLOBALS['con'], trim($_POST[$value]));
    if(empty($textbox)) {
        return false;
    }else {
        return $textbox;
    }
}

//Messages

function TextNode($classname, $msg) {
    $element = "<h6 class='$classname'>$msg</h6>";
    echo $element;
}
// get Data from mysql database

function getData() {
   $sql = "SELECT * FROM books";
   
   $result = mysqli_query($GLOBALS['con'],$sql);

   if(mysqli_num_rows($result)> 0) {
      return $result;
   }
}
//Update Data

function UpdateData() {

  $bookid = textboxValue(value:"book_id");
  $bookname = textboxValue(value:"book_name");
  $bookpublisher = textboxValue(value:"book_publisher");
  $bookprice = textboxValue(value:"book_price");

  if($bookname && $bookpublisher && $bookprice) {
      $sql = "
            UPDATE books SET book_name = '$bookname',book_publisher = '$bookpublisher', book_price = '$bookprice'WHERE id ='$bookid';
            
            ";
            if(mysqli_query($GLOBALS['con'],$sql)) {
               TextNode(classname:"success",msg:"Data Successfully Updated....!");
            }else {
                TextNode(classname:"error",msg:"Enable to Update Data....!");
            }
  }else {
    TextNode(classname:"error",msg:"Select Data Using Edit Icon....!");
  }
}

//Function to Delete 

function deleteRecord() {

   $bookid = (int)textboxValue(value:"book_id");

   $sql = "DELETE FROM books WHERE id = $bookid";

   if(mysqli_query($GLOBALS['con'],$sql)) {
       TextNode(classname:"success",msg:"Record Deleted Successfully...!");
   }else{
       TextNode(classname:"error",msg:"Enable to Delete Record...!");
   }
}

//Function to delte All data

function deleteBtn() {

    $result = getData();
    $i = 0;
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $i++;
            if($i > 3) {
                buttonElement("btn-deleteall", "btn btn-danger" ,"<i class='fas fa-trash'></i> Delete All", "deleteall", "");
                return;
            }
        }
    }
}

function deleteAll() {

    $sql = "DROP TABLE books";

    if(mysqli_query($GLOBALS['con'],$sql)) {
        TextNode(classname:"success",msg:"All Record deleted Successfully....!"); 
        Createdb();
    }else {
        TextNode(classname:"error",msg:"Something Went Wrong Record Cannot deleted....!"); 
    }
}

//Set Id to Textbox
function setID() {
    
    $getid = getData();
    $id = 0;
    if($getid) {
        while($row = mysqli_fetch_assoc($getid)) {
            $id = $row['id'];
        }
    }
    return($id + 1);
}
?>