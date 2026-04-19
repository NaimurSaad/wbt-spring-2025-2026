<?php 
//1
$length = 10;
$width = 5;

$area = $length * $width;
$perimeter = 2 * ($length + $width);

echo "Area = $area ";
echo "Perimeter = $perimeter <br>";

//2
$amount = 80;
$vat = (0.15 * $amount);

echo "VAT = $vat <br>";

//3
$number = 5;
if($number%2==0){
    echo "Even <br>";
}else{
    echo "Odd <br>";
}

//4
$num1 = 10;
$num2 = 20;
$num3 = 30;

if($num1>$num2 && $num1>$num3){
    echo "Largest = $num1 <br>";
}elseif($num2>$num1 && $num2>$num3){
}else{
    echo "Largest = $num3 <br>";
}

//5
for ($i = 10; $i <= 100; $i++){
if ($i % 2 != 0)
echo "$i ";
}
echo "<br>";

//6
$arr=[1,2,3,4,5,6,7,8,9];
$search = 5;
foreach($arr as $ar){
    if($search== $ar){
        echo "$search Found <br>";
    }
}

//7
for($i=1;$i<=3;$i++){
    for($j=1;$j<=$i;$j++){
        echo "* ";
    }
        echo "<br>";
}

for($i=3; $i>=1;$i--){
    for($j=1;$j<=$i;$j++){
        echo "$j ";
    }
        echo "<br>";
}

$char = 'A';
for($i=1;$i<=3;$i++){
    for($j=1;$j<=$i;$j++){
        echo "$char ";
        $char++;
    }
        echo "<br>";
}
?>