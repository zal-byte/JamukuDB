<?php
    include "lib/connection.php";
    include "lib/auth.php";
    include "lib/crud_barang.php";
    include "lib/crud_post.php";
    include "lib/crud_profile.php";
    include "lib/sql.php";
    include "lib/crud_petugas.php";
    include 'lib/image.php';

    $connection = Connection::getInstance();
    $auth = new Auth($connection->connects());
    $crud_b = new CRUD_B($connection->connects());
    $crud_p = new CRUD_P($connection->connects());
    $crud_pf = new CRUD_PF($connection->connects());
    $image = Image::getInstance();
    $sql_con = A::getInstance();
    $petugas = Petugas::getInstance($sql_con);
    // $sql_con->con(A::CONNECT_PREPARE ,"localhost","database","root","jamuku");
    //POST method
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $request = isset($_POST["Request"]) ? $_POST["Request"] : die("[ Request ] : null");
            //Autentikasi
            if($request == "login"){

                $PUsername = $_POST["PUsername"];
                $PPassword = $_POST["PPassword"];
                $param = array("PUsername"=>$PUsername,
                "PPassword"=>$PPassword);
                
                $auth->login($param);

            }else if($request == "signup"){

                $PUsername = $_POST["PUsername"];
                $PPassword = $_POST["PPassword"];
                $PName = $_POST["PName"];
                $PEmail = $_POST["PEmail"];
                $PPhone = $_POST["PPhone"];
                $PAddress = $_POST["PAddress"];
                $PProfilePicture = "default.jpg";

                $param = array("PUsername"=>$PUsername,
                "PPassword"=>$PPassword,
                "PName"=>$PName,
                "PEmail"=>$PEmail,
                "PPhone"=>$PPhone,
                "PAddress"=>$PAddress,
                "PProfilePicture"=>$PProfilePicture);

                $auth->signup($param);

            }

            //Produk
            // if( $request == "newProduct" ){
            //     $param = array('ProdName'=>$_POST['ProdName'],
            //     'ProdDesc'=>$_POST['ProdDesc'],
            //     'ProdType'=>$_POST['ProdType'],
            //     'ProdWeight'=>$_POST['ProdWeight'],
            //     'ProdPrice'=>$_POST['ProdPrice'],
            //     'ProdQuantity'=>$_POST['ProdQuantity'],
            //     'ProdDate'=>$_POST['ProdDate'],
            //     'ImageBase64'=>$_POST['ImageBase64']);

            //     $crud_b->newProduct($param);
            // }else
             if($request == "addToMyCart"){
                $param = array('ProdID'=>$_POST['ProdID'],
                'PUsername'=>$_POST['PUsername']);
                $crud_b->addToMyCart($param);
            }else if($request == "deleteMyCart"){
                $ProdID = $_POST["ProdID"];
                $crud_b->deleteMyCart($ProdID);                
            }else if($request == "deleteProduct"){
                $ProdID = $_POST['ProdID'];
                $crud_b->deleteProduct($ProdID);
            }else if($request == "buyProduct"){
                $param = array("ProdID"=>$_POST['ProdID'],
                "ProdImage"=>$_POST['ProdImage'],
                "Quantity"=>$_POST['Quantity'],
                "Address"=>$_POST['Address'],
                "PUsername"=>$_POST['PUsername']);
                
                $crud_b->buyProduct($param);              
            }else if($request == "addQuantity"){
                $data = array("ProdID"=>$_POST["ProdID"],"NewQuantity"=>$_POST["NewQuantity"]);
                $petugas->addQuantity($data);
            }else if($request == "newPrice"){
                $data = array("ProdID"=>$_POST["ProdID"],"NewPrice"=>$_POST["NewPrice"]);
                $petugas->changeProdPrice($data);
            }else if($request == "newDescription"){
                $data = array("ProdID"=>$_POST["ProdID"], "NewDescription"=>$_POST["NewDescription"]);
                $petugas->changeProdDescription($data);
            }else if($request == "newName"){
                $data = array("ProdID"=>$_POST["ProdID"],"NewName"=>$_POST["NewName"]);
                $petugas->changeProductName($data);
            }else if($request == "newProduct"){
                $file = @file_get_contents($_FILES["file"]["tmp_name"]);
                $filename = $_FILES["file"]["name"];

                $data = array("ProdName"=>$_POST["ProdName"],"ProdDesc"=>$_POST["ProdDesc"], "ProdQuantity"=>$_POST["ProdQuantity"],"ProdWeight"=>$_POST["ProdWeight"],"ProdPrice"=>$_POST["ProdPrice"], "PUsername"=>$_POST["PUsername"], "imageData"=>base64_encode($file), "ProdPict"=>$filename);
                if($petugas->AA($data)['status'] == true){
                    $img->ImageQuality(['1'=>Image::JPG_MIDDLE,'filename'=>$_POST['ProdPict'],'imageData'=>base64_decode($_POST['imageData']));
                }
            }

            //Post
            if($request == "sendComment"){
                $param = array('ProdID'=>$_POST['ProdID'],
                'PUsername'=>$_POST['PUsername'],
                'PName'=>$_POST['PName'],
                'PProfilePicture'=>$_POST['PProfilePicture'],
                'KMessage'=>$_POST['KMessage'],
                'KDate'=>$_POST['KDate']);
                $crud_p->sendComment($param);
            }else if($request == "postLike"){
                $param = array('ProdID'=>$_POST['ProdID'],
                'PUsername'=>$_POST['PUsername']);
                $crud_p->checkLike($param);
            }else if($request == "updateComment"){
                $ProdID = $_POST['ProdID'];
                $KMessage = $_POST['KMessage'];
                $crud_p->updateComment($ProdID, $KMessage);
            }else if($request == "deleteComment"){
                $KID = $_POST['KID'];
                $crud_p->deleteComment($KID);
            }
            //Profile
            if($request == "updateProfileData"){
              $param = array("Name"=>$_POST["Name"], "Value"=>$_POST["Value"], "PUsername"=>$_POST["PUsername"]);
              $crud_pf->updateProfileData($param);
            }else if($request == "changePassword"){
                $param = array("PUsername"=>$_POST["PUsername"],
                "PPassword"=>$_POST["PPassword"],"PPassword_verify"=>$_POST["PPassword_verify"]);
                $crud_pf->changePassword($param);
            }
        }
     

    //

    //GET method
        if( $_SERVER["REQUEST_METHOD"] == "GET"){
            $req = $_GET["Request"];

            $limit = isset($_GET["limit"]) ? (int) $_GET["limit"] : 5;
            $v = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
            $page = ($v > 1) ? ($v * $limit) - $limit : 0;
            //Product Stuff
            if($req == "fetchProduct"){
                $crud_b->fetchProduct($page, $limit);
            }else if($req == "fetchMyCart"){
                $PUsername = $_GET["PUsername"];
                $crud_b->fetchMyCart($PUsername, $page, $limit);
            }else if($req =="buyProductHistory"){
                $PUsername = $_GET["PUsername"];
                $crud_b->buyProductHistory($PUsername, $page, $limit);    
            }else if($req == "fetchPopularProduct"){
                $crud_b->fetchPopularProduct($page, $limit);
            }else if($req == "fetchProductData_view"){
                $crud_b->fetchProductDataView($_GET["ProdID"]);
            }else if($req == "checkingMyCart"){
                $ProdID = $_GET['ProdID'];
                $PUsername = $_GET['PUsername'];
                $crud_b->checkingMyCart($ProdID, $PUsername);
            }else if($req == "deletePaymentRequest"){
                $crud_b->deletePayment();
            }else if($req == "fetchMyPayment"){
                if(isset($_GET["by"])){
                    $by = $_GET["by"];
                    $crud_b->fetchMyPayment($_GET["PUsername"], $by);
                }else{
                    $crud_b->fetchMyPayment($_GET["PUsername"]);
                }
            }else if($req == "SamePayment"){
                $ProdID = $_GET["ProdID"];
                $PUsername = $_GET["PUsername"];
                $crud_b->SamePayment($ProdID, $PUsername);
            }
            //Post Stuff
            if($req == "getComment"){
                $ProdID=$_GET['ProdID'];
                $crud_p->getComment($ProdID, $page, $limit);
            }else if($req == "checkingLike"){
                $ProdID = $_GET['ProdID'];
                $PUsername = $_GET['PUsername'];
                $crud_p->checkingLike($ProdID, $PUsername);
            }
            //Profile Stuff
            if($req == "fetchProfileData"){
                $PUsername = $_GET["PUsername"];
                $crud_pf->fetchProfileData($PUsername);
            }

            //another stuff
            if($req == "fetchAllPayment"){
                $petugas->fetchAllPayment();
            }
        }
        //

   

?>