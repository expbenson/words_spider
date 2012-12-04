<?php
/**
 * Ajax上传头像文件
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // 保存文件
  echo "HelloBenson";
  var_dump($_FILES);
  exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>上传文件</title>
<script>
  window.onload = function() {
    var btn = document.getElementById("upload");
    var logo = document.getElementById("logo");
    var url = "/upload.php";
    btn.addEventListener("click", function(event) {
      var file = logo.files[0];
      var xhr = new XMLHttpRequest();
      xhr.open("POST", url);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var result = xhr.getResponseText;
          console.log(result);
        }
      };
      xhr.send(file);
    });
  };
  
  function postFormData(url, data, callback) {
    
  }
</script>
</head>

<body>
  <div><img src="" alt="头像"></div>
  <div><input type="file" name="logo" id="logo"></div>
  <div><input type="button" id="upload" value="Upload"></div>
  <div>已上传<span id="process">0</span>%</div>
</body>
</html>