<?php

if (!isset($_SESSION)) session_start();
if (!(isset($_SESSION['user']['logged']) && $_SESSION['user']['logged'])) {
   header('Location: /');
   exit();
}

require_once __DIR__ . './../config.php';

class User
{
   public static function isPasswordCorrect($password)
   {
      global $pdo;
      try {
         $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'id' => $_SESSION['user']['id']
         ]);
         $row = $stmt->fetch();
         return password_verify($password, $row['password']) ? true : false;
      } catch (PDOException $e) {
         return false;
      }
   }

   public static function updateUsername($username)
   {
      global $pdo;
      try {
         $sql = "UPDATE users SET username = :username WHERE id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'username' => $username,
            'id' => $_SESSION['user']['id']
         ]);

         echo json_encode([
            'error' => false,
            'msg' => 'Login został zmieniony!',
            'status' => 'success'
         ]);

         $_SESSION['user']['username'] = $username;
      } catch (PDOException $e) {
         echo json_encode([
            'error' => true,
            'msg' => 'Wystąpił błąd bazy danych!',
            'status' => 'danger'
         ]);
      }
   }

   public static function updateEmail($email)
   {
      global $pdo;
      try {
         $sql = "UPDATE users SET email = :email WHERE id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'email' => $email,
            'id' => $_SESSION['user']['id']
         ]);

         echo json_encode([
            'error' => false,
            'msg' => 'Adres e-mail został zmieniony!',
            'status' => 'success'
         ]);

         $_SESSION['user']['email'] = $email;
      } catch (PDOException $e) {
         echo json_encode([
            'error' => true,
            'msg' => 'Wystąpił błąd bazy danych!',
            'status' => 'danger'
         ]);
      }
   }

   public static function updatePhoneNo($phone_no)
   {
      global $pdo;
      try {
         $sql = "UPDATE users SET phone_no = :phone_no WHERE id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'phone_no' => $phone_no,
            'id' => $_SESSION['user']['id']
         ]);

         echo json_encode([
            'error' => false,
            'msg' => 'Nr telefonu został zmieniony!',
            'status' => 'success'
         ]);

         $_SESSION['user']['phone_no'] = $phone_no;
      } catch (PDOException $e) {
         echo json_encode([
            'error' => true,
            'msg' => 'Wystąpił błąd bazy danych!',
            'status' => 'danger'
         ]);
      }
   }

   public static function updatePassword($password)
   {
      global $pdo;
      try {
         $sql = "UPDATE users SET password = :password WHERE id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'id' => $_SESSION['user']['id']
         ]);

         echo json_encode([
            'error' => false,
            'msg' => 'Hasło zostało zmienione!',
            'status' => 'success'
         ]);
      } catch (PDOException $e) {
         echo json_encode([
            'error' => true,
            'msg' => 'Wystąpił błąd bazy danych!',
            'status' => 'danger'
         ]);
      }
   }

   public static function updatePicture($picture)
   {
      global $_CONFIG;
      global $pdo;

      $picture_ext = str_replace('image/', '', $picture['type']);
      $picture_name = uniqid(md5(time())) . '.' . str_replace('image/', '', $picture_ext);

      if (!in_array($picture_ext, ['gif', 'png', 'jpg', 'jpeg'])) {
         echo json_encode([
            'error' => true,
            'msg' => 'Dozwolonymi formatami zdjęć są: jpg, jpeg, gif, png!',
            'status' => 'danger'
         ]);
         return;
      }

      # delete old picture from server
      $current_picture_name = User::getPitureName();
      if ($current_picture_name !== $_CONFIG['app']['default_profile_picture_name'])
         if (file_exists(__DIR__ . '../../' . $_CONFIG['app']['profile_pictures_path'] . $current_picture_name))
            unlink(__DIR__ . '../../' . $_CONFIG['app']['profile_pictures_path'] . $current_picture_name);

      try {
         $sql = "UPDATE users SET picture = :picture WHERE id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'picture' => $picture_name,
            'id' => $_SESSION['user']['id']
         ]);

         list($old_x, $old_y) = getimagesize($picture['tmp_name']);
         $max_width = 500;

         if ($picture['size'] < 512000) {
            move_uploaded_file($picture['tmp_name'], '../../' . $_CONFIG['app']['profile_pictures_path'] . $picture_name);
         } else {
            User::createThumbnail($max_width, $old_x, $old_y, $picture['tmp_name'], '../../' . $_CONFIG['app']['profile_pictures_path'] . $picture_name);
         }

         echo json_encode([
            'error' => false,
            'msg' => 'Zdjęcie zostało zmienione!',
            'status' => 'success'
         ]);

         $_SESSION['user']['picture'] = $picture_name;
      } catch (PDOException $e) {
         echo json_encode([
            'error' => true,
            'msg' => 'Wystąpił błąd bazy danych!',
            'status' => 'danger'
         ]);
      }
   }

   public static function deletePicture()
   {
      global $_CONFIG;
      global $pdo;

      # delete old picture from server
      $current_picture_name = User::getPitureName();
      if ($current_picture_name !== $_CONFIG['app']['default_profile_picture_name'])
         if (file_exists(__DIR__ . '../../' . $_CONFIG['app']['profile_pictures_path'] . $current_picture_name))
            unlink(__DIR__ . '../../' . $_CONFIG['app']['profile_pictures_path'] . $current_picture_name);

      try {
         $sql = "UPDATE users SET picture = :picture WHERE id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'picture' => '',
            'id' => $_SESSION['user']['id']
         ]);

         echo json_encode([
            'error' => false,
            'msg' => 'Zdjęcie zostało usunięte!',
            'status' => 'success'
         ]);

         $_SESSION['user']['picture'] = $_CONFIG['app']['default_profile_picture_name'];
      } catch (PDOException $e) {
         echo json_encode([
            'error' => true,
            'msg' => 'Wystąpił błąd bazy danych!',
            'status' => 'danger'
         ]);
      }
   }

   public static function isUsernameUsed($username)
   {
      global $pdo;
      try {
         $sql = "SELECT * FROM users WHERE username = :username";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'username' => $username
         ]);
         return $stmt->rowCount() ? true : false;
      } catch (PDOException $e) {
         return true;
      }
   }

   public static function isPhoneNoUsed($phone_no)
   {
      global $pdo;
      try {
         $sql = "SELECT * FROM users WHERE phone_no = :phone_no";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'phone_no' => $phone_no
         ]);
         return $stmt->rowCount() ? true : false;
      } catch (PDOException $e) {
         return true;
      }
   }

   public static function isEmailUsed($email)
   {
      global $pdo;
      try {
         $sql = "SELECT * FROM users WHERE email = :email";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'email' => $email
         ]);
         return $stmt->rowCount() ? true : false;
      } catch (PDOException $e) {
         return true;
      }
   }

   private static function getPitureName()
   {
      global $pdo;
      global $_CONFIG;
      try {
         $sql = "SELECT * FROM users WHERE id = :id";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([
            'id' => $_SESSION['user']['id']
         ]);

         $db_pic = $stmt->fetch()['picture'];
         return $db_pic == '' ? $_CONFIG['app']['default_profile_picture_name'] : $db_pic;
      } catch (PDOException $e) {
         return $_CONFIG['app']['default_profile_picture_name'];
      }
   }

   private static function createThumbnail($new_width, $old_x, $old_y, $tmpLocation, $moveToDir)
   {
      $path = $tmpLocation;

      $mime = getimagesize($path);

      if ($mime['mime'] == 'image/png') {
         $src_img = imagecreatefrompng($path);
      } else if ($mime['mime'] == 'image/jpg' || $mime['mime'] == 'image/jpeg' || $mime['mime'] == 'image/pjpeg') {
         $src_img = imagecreatefromjpeg($path);
      } else if ($mime['mime'] == 'image/gif') {
         $src_img = imagecreatefromgif($path);
      }

      $ratio = $old_y / $old_x;

      $thumb_w    =   $new_width;
      $thumb_h    =   $new_width * $ratio;

      $dst_img        =   ImageCreateTrueColor($thumb_w, $thumb_h);

      imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);


      // New save location
      $new_thumb_loc = $moveToDir;


      // $dst_img = imagerotate($dst_img, 270, 0);

      if ($mime['mime'] == 'image/png') {
         $result = imagepng($dst_img, $new_thumb_loc, 80);
      } else if ($mime['mime'] == 'image/gif') {
         $result = imagegif($dst_img, $new_thumb_loc, 80);
      } else if ($mime['mime'] == 'image/jpg' || $mime['mime'] == 'image/jpeg' || $mime['mime'] == 'image/pjpeg') {
         $result = imagejpeg($dst_img, $new_thumb_loc, 80);
      }

      imagedestroy($dst_img);
      imagedestroy($src_img);

      return $result;
   }
}
