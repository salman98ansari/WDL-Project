<!DOCTYPE html>
<html>
<title>Price Compare Site</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" 
	integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ"
	 crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../css/style.css" />
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <ul>
    <li><a class ="logo" href="#"><img src="..." alt="Mobile Price Coparison"></a></li>
    <li><a href="../index.php"><i class="fas fa-home"></i> Home</a></li>
    <li><a href="https://www.flipkart.com/" target="blanck"> Flipkart</a></li>
    <li><a href="https://www.amazon.in/" target="blanck"> Amazon</a></li>

  </ul>
</nav>
<div class="w3-row-padding w3-margin-top"> 
<?php
error_reporting(E_ALL & ~E_NOTICE);
if(isset($_GET['searchdata']))
{
$search = $_GET['searchdata'];
$search = strtolower($search);

$search = str_replace(" ","+",$search);
  $web_page_data = file_get_contents("http://www.pricetree.com/search.aspx?q=".$search);
  
  $item_list = explode('<div class="items-wrap">', $web_page_data); //from entire page it will split based on word <div class="items-wrap">
  
  $i=1;
  if(sizeof($item_list)<2){
    echo '<p><b>No results,</b></p>';
    $i=5;
  }
$count = 4;
  
  for($i;$i<5;$i++){
    
    $url_link1 = explode('href="',$item_list[$i]);
    $url_link2 = explode('"', $url_link1[1]); 
   
    $image_link1 = explode('data-original="',$item_list[$i]);
    $image_link2 = explode('"', $image_link1[1]); 
    
    $title1 = explode('title="', $item_list[$i]);
    $title2 = explode('"', $title1[1]);    
    $avaliavle1 = explode('avail-stores">', $item_list[$i]);
    $avaliable = explode('</div>', $avaliavle1[1]);
    if(strcmp($avaliable[0],"Not available") == 0) {
      //means not avaliable
      $count = $count-1;
      continue;  
    }
    $item_title = $title2[0];
    if(strlen($item_title)<2){
      continue;
    }
    $item_link = $url_link2[0];
    $item_image_link = $image_link2[0];
    $item_id1 = explode("-", $item_link);
    $item_id = end($item_id1); 
    echo '
    <br>
    <div class="w3-row">
    <div class="w3-col l2 w3-row-padding">
    <div class="w3-card-2" style="background-color:teal;color:white;">
    <img src="'.$item_image_link.'" style="width:100%">
    <div class="w3-container">
    <h5>'.$item_title.'</h5>
    </div>
    </div>
    </div>
  ';
  
    $request = "http://www.pricetree.com/dev/api.ashx?pricetreeId=".$item_id."&apikey=7770AD31-382F-4D32-8C36-3743C0271699";
    $response = file_get_contents($request);
    $results = json_decode($response, TRUE);
    echo '
    <div class="w3-col l8">
    <div class="w3-card-2">
      <table class="w3-table w3-striped w3-bordered w3-card-4">
      <thead>
      <tr class="w3-blue">
        <th>Seller_Name</th>
        <th>Price</th>
        <th>Buy Here</th>
      </tr>
      </thead>
    ';
    foreach ($results['data'] as $itemdata) {
      $seller = $itemdata['Seller_Name'];
      $price = $itemdata['Best_Price'];
      $product_link = $itemdata['Uri'];

  echo '
      <tr>
        <td>'.$seller.'</td>
        <td>'.$price.'</td>
        <td><a href="'.$product_link.'">Buy</a></td>
      </tr>
      ';
    }    
    echo '
      </table>
      </div>
      </div>
      </div>
    ';
  }
  if($count == 0){
    echo '<p><b>No Products avaliable</b></p>';
  }
}
else {
  echo '<p>Use this to get Best Price from all Sites. <b>Search Product to Know Price from All Online Shops</b></p>';
}
?>
</div>
</div>
</div>
<footer class="w3-container w3-teal w3-opacity">
<p>Copyright @ Me</p>
</footer>
</body>
</html>