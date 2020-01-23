<?
   require_once 'jwt-check.php';

   if(isset($_FILES['image']) && isset($_POST['type'])) {
      $data = $_POST;
      $file_name = time() . hash('md5', json_encode($_FILES['image']));
      $data['name'] = $file_name;
      $file_tmp = $_FILES['image']['tmp_name'];
      $data = json_encode($data);
      
      if($_FILES['image']['error'] == 0) {
         move_uploaded_file($file_tmp,"media/".$file_name);
         file_put_contents("data/".$file_name.".json", $data);
         echo '{"action": "task-done"}';
      } else {
         echo '{"action": "task-failed"}';
      }
   } else {
      echo '{"action": "empty-required-fields"}';
   }