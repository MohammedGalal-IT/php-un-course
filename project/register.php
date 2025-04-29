<?php
// include 'config1.php'; 
require_once 'config.php'; 
// if(isset($_POST['submit']))
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(empty($_POST['name'])){
       $messageErr = "يجب ان لا يكون الحقل فارغا"; 
    }else if(!preg_match("^[A-Za-z\s]+$",$_POST['name'])){
        $messageErr = "يجب ان الاسم يحتوي على احرف ومسافات فقط"; 
        
    }else
    {
        $name = trim($_POST['name']);
    }
    if(empty($_POST['email'])){
        $messageErr = "يجب ان لا يكون فارغا";
    }else if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
        $messageErr = "يجب ان يكون الايميل صالحا";
    }else{
        
        $email =$_POST['email'];
    }
    $email =$_POST['email'];
    $name = trim($_POST['name']);
    $password =$_POST['password'];
    $rpass =$_POST['rpassword'];
    $gender=$_POST['gender'];
    $dof =$_POST['date'];
    $image = $_FILES['image']['name'];

    $img = $_FILES['image']['tmp_name'];
    $target = "uploads/".$image;

    if(move_uploaded_file($img,$target)){
        echo "تم نقل الصورة بنجاح";
    }
    $result = mysqli_query($conn,"SELECT email from user where email = '$email'") or die("query failed");
    if(mysqli_num_rows($result)>0){
        $messageErr = "هذا المستخدم هله عندنا من امس";
    }else if($password !== $rpass){
        $messageErr = "كلمة السر غير متطابقة";

    }else{
        $password_hash = password_hash($password,PASSWORD_BCRYPT);
       $sql=  mysqli_query($conn,"INSERT INTO user(name,email,password,date_of_birth,gender,image)VALUES('$name','$email','$password_hash','$dof','$gender','$image')")or die("query failed");
    //    $sql=  mysqli_query($conn,"INSERT INTO user(name,email,password,date_of_birth,gender,image)VALUES(?,?,?,?,?,?)")or die("query failed");
        // mysqli_stmt_bind_param("ssssss",'$name','$email','$password_hash','$dof','$gender','$image');
        // mysqli_stmt_execute($sql);
        header('location:login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
        .card{
            width:600px;
            margin:0 auto;
            padding:0 10px;
        }
    </style>
</head>
<body class="bg-info">
    <div class="container text-center py-10 mt-5">
        <div class="card sm-shadow text-center">
            <h2 class="text-warning">إنشاء حساب</h2>
            <p class="text-danger">
                <?php if(isset($messageErr)) echo $messageErr;?>
            </p>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="" class="form-label">الاسم</label>
                        <input type="text" name="name" id="" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">الايميل</label>
                        <input type="email" name="email" id="" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">كلمة السر</label>
                        <input type="password" name="password" id="" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">تأكيد كلمة السر</label>
                        <input type="password" name="rpassword" id="" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">تاريخ الميلاد</label>
                        <input type="date" name="date" id="" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">الحنس</label>
                        <select name="gender" id="" class="form-control">
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">الصورة الشخصية</label>
                        <input type="file" name="image" id="" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="submit" value="إرسال" name="submit" class="btn btn-danger w-50">
                        </div>
                        <p>اذا كان لديك حساب <a href="login.php">تسجيل الدخول</a></p>

                </form>

        </div>
    </div>
</body>
</html>