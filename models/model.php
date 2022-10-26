<?php
    require_once("models/car.php");
    require_once("models/db_module.php");

    class Model 
    {
        public function getproductofpage(){
            $productofpage = 3;
            return $productofpage;
        }
        public function getpage(){
            $page = isset($_GET['page'])?$_GET['page']:1;
            $page = is_numeric($page)?$page:1;
            return $page;
        }
        public function getfrom(){
            $page = $this->getpage();
            $productofpage = $this->getproductofpage();
            $from = ($page - 1)*$productofpage;
            return $from;
        }

        // Lấy danh sách xe theo trang sản phẩm
        public function getcarlistbypage()
        {
            $link = null;
            taoKetNoi($link);
            $from = $this->getfrom();
            $productofpage = $this->getproductofpage();

            if(isset($_GET['dm'])){
                $result = chayTruyVanTraVeDL($link, "select * from tbl_car where id=".$_GET['dm']." limit ".$from.", ".$productofpage);
            }else{
                $result = chayTruyVanTraVeDL ($link, "select * from tbl_car limit ".$from.", ".$productofpage);
            }
            
            $data = array ();
            while ($rows = mysqli_fetch_assoc($result)){
                $car = new Car ($rows["id"], $rows["name"], $rows["title"], $rows["price"], $rows["color"], $rows["image"], $rows["image1"], $rows["image2"], $rows["description"], $rows["numberofseats"], $rows["style"], $rows["fuel"], $rows["origin"], $rows["gear"]);
                array_push($data, $car);
            }
            giaiPhongBoNho($link, $result);
            return $data;
        }

        // Lấy danh sách tất cả các xe
        public function getallcarlist()
        {
            $link = null;
            taoKetNoi($link);
            $result = chayTruyVanTraVeDL ($link, "select * from tbl_car ");
            $data = array ();
            while ($rows = mysqli_fetch_assoc($result)){
                $car = new Car ($rows["id"], $rows["name"], $rows["title"], $rows["price"], $rows["color"], $rows["image"], $rows["image1"], $rows["image2"], $rows["description"], $rows["numberofseats"], $rows["style"], $rows["fuel"], $rows["origin"], $rows["gear"]);
                array_push($data, $car);
            }
            giaiPhongBoNho($link, $result);
            return $data;
        }

        // Lấy xe theo id
        public function getcar($id)
        {
            $allcars = $this->getallcarlist();
            foreach($allcars as $car){
                if ($car->getid()===$id){
                    return $car;
                }
            }
            return null;   
        }

        //Lấy tổng số sản phẩm
        public function getnumberrow(){
            $link = null;
            taoKetNoi($link);
            if(isset($_GET['dm'])){
                $result = chayTruyVanTraVeDL($link, "select count(*) from tbl_car where id = ".$_GET["dm"]);
            }else{
                $result = chayTruyVanTraVeDL ($link, "select count(*) from tbl_car");
            }
            $numberrow = mysqli_fetch_row($result);
            giaiPhongBoNho($link, $result);
            return $numberrow[0];
        }

        // Hàm đăng nhập
        public function Login($username, $password)
        {
            $link = null;
            taoKetNoi($link);
            $cautruyvan = "select * from tbl_user  where username = '$username' AND password='$password'";
            $result = chayTruyVanTraVeDL($link, $cautruyvan);
        
            $count = mysqli_num_rows($result);
            giaiPhongBoNho($link, $result);
            
            return $count;
        }

        // Hàm tìm kiếm
        public function Search($keyword)
        {
            $link = null;
            taoKetNoi($link);
            $from = $this->getfrom();
            $productofpage = $this->getproductofpage();
        
            $result = chayTruyVanTraVeDL($link, "select * from tbl_car where name like '%$keyword%'");
            $data = array();
            while ($rows = mysqli_fetch_assoc($result)) {
                $car = new Car($rows["id"], $rows["name"], $rows["title"], $rows["price"], $rows["color"], $rows["image"], $rows["image1"], $rows["image2"], $rows["description"], $rows["numberofseats"], $rows["style"], $rows["fuel"], $rows["origin"], $rows["gear"]);
                array_push($data, $car);
            }
            giaiPhongBoNho($link, $result);
            return $data;
        }

        // Hàm đăng ký
        public function Register($username, $password)
        {
            $link = null;
            taoKetNoi($link);
        
            $result = chayTruyVanKhongTraVeDL($link, "INSERT INTO tbl_user (username, password) VALUES ('$username','$password')");
        
        }

        // Hàm update
        public function getupdatecar($carname, $cartitle, $carprice, $carimage, $carimage1, $carimage2, $cardescription, $carnumberofseats, $carstyle, $carfuel, $carorigin, $cargear, $id)
        {
            $link = null;
            taoKetNoi($link);
            $result = chayTruyVanKhongTraVeDL ($link, "update tbl_car set  name = '".$carname."',
                                                                            title = '".$cartitle."',
                                                                            price = '".$carprice."',
                                                                            image = '".$carimage."',
                                                                            image1 = '".$carimage1."',
                                                                            image2 = '".$carimage2."',
                                                                            description = '".$cardescription."',
                                                                            numberofseats = '".$carnumberofseats."',
                                                                            style = '".$carstyle."',
                                                                            fuel = '".$carfuel."',
                                                                            origin = '".$carorigin."',
                                                                            gear = '".$cargear."'
                                                                            where id = ".$id);
            giaiPhongBoNho($link, $result);
        }
        
    }
?>