1.在php中创建空对象

     $obj = new stdClass(); 或者 $obj =  null;
另外创建对象的一种方式是：

    $obj = (object)arrar(
         'per1'=>1,
         'per2'=>false,
         'per3'=>'somestr'
       );

2.在php 中创建空数组  

1)在<5.4的php版本中 

          $arr = array();
          
2)在>= 5.4 的php版本中

          $arr = array(); 或者 $arr = [];
