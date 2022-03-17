<?php
define('db_host', 'localhost');
// database username
define('db_user', 'root');
// database password
define('db_pass', '');
// database name
define('db_name', 'filescantry');
include '../images/fsfunctions.php';
?>

<h1 style="text-align: center">Jos' audio filescanner</h1>
<hr>
<form action="" method="POST">
  <label for="catfoldername">folder/cat name. to add more categories : seperate by comma's</label><br>
  <input type="text" name="foldercategory" id="catfoldername"><Br>
  <label for="catdesc">Description of category</label><br>
  <textarea name="cat_description" id="catdesc" cols="30" rows="10"></textarea>
  <input type="submit" value="scanIt!">
</form>

<?php
// function catTest($path_to_scan, $cat_description) {
//   $pdo = pdo_connect_mysql();
//   $description = "no description yet!";
//   // Retrieve all the categories from the database
//   $stmt = $pdo->query('SELECT * FROM categories');
//   $stmt->execute();
//   $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

//   // var_dump($categories);

//   foreach($categories as $cat) {
//     if ($cat["title"] !== $path_to_scan);{
//       echo "Category title does not exist , creating new categorie: " . $path_to_scan . "<br>";
//     $stmt = $pdo->prepare('INSERT INTO categories (title, description) VALUES (?,?) ');
//     $stmt->execute([$path_to_scan, $description]);
//     echo "succes! categorie created! <br><hr>";
//     }
//   }

// }

?>

<?php
//todo open dir scan for files
function scanIt($path_to_scan, $cat_description)
{
  $pdo = pdo_connect_mysql();

  $description = "no description yet!";
  // // Retrieve all the categories from the database
  // $stmt = $pdo->query('SELECT * FROM categories');
  // $stmt->execute();
  // $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmt = $pdo->prepare('INSERT INTO categories (title, description) VALUES (?,?) ');
  $stmt->execute([$path_to_scan, $cat_description]);
  $category_id = $pdo->lastInsertId();

  $path = $path_to_scan;
  $files = scandir($path);
  $files = array_diff(scandir($path), array('.', '..'));



  $nothumbfiles = [];
  foreach (array_values($files) as $file) {
    $checkForThumb = substr($file, 0, 5);
    if ($checkForThumb !== strtolower("thumb")) {
      array_push($nothumbfiles, $file);
    }
  }


  $simpleArray = [];

  foreach (array_values($nothumbfiles) as $file) {

    $no_extension = explode(".", $file); // 

    $symbols = ['-', '_'];
    $infoArraySymbolReplace = explode(" ", str_replace($symbols, " ", $no_extension[0]));
    $cleanedArray = []; // cleans out empty values and converts numeric strings to integers
    foreach ($infoArraySymbolReplace as $index => $value) {
      if (!empty($value) || $value !== "thumb") {
        if (is_numeric($value)) {
          array_push($cleanedArray, $value + 0);
        } else {
          array_push($cleanedArray, $value);
        }
      }
    }
    array_push($simpleArray, $fullArray = array_merge($no_extension, $cleanedArray));
  }



  $final = [];

  foreach ($simpleArray as $entry => $keys) {
    $filename = array_shift($keys);
    $filext = array_shift($keys);

    $desc = implode(" ", $keys);


    $fin = [
      'filename' => $filename,
      'extension' => $filext,
      'description' => $desc,
    ];
    // var_dump("<pre>", $fin, "</pre>");

    array_push($final, $fin);
  }


  $fnr = 1;

  foreach ($final as $key) {
    $dir = "media/audios/" . $path;
    $title = '';
    $year = 1888;
    $description = '';
    $file = 'errorscan';
    $ext = '.err';


    if (!empty($key['filename']) && is_string($key['filename'])) {
      $file = $key['filename'];
      echo $file;
    }
    if (!empty($key['extension']) && is_string($key['extension'])) {
      $ext = $key['extension'];
      echo $ext;
    }
    echo "<br>";
    
    echo "<br>";
    if (!empty($key['description'])) {
      $description = $key['description'];

      echo "<p style='color:blue'>" . $description . "</p><br>";
    }
    $type = 'audio';
    $filepath = $dir . '/' . $file . '.' . $ext;
    echo "<p style='color:blue'>" . $filepath . "</p><br>";
    echo "<hr>";

    $stmt = $pdo->prepare('INSERT INTO media (title, year, fnr, filepath, type) VALUES (?,?,?,?,?) ');
    $stmt->execute([$description, $year, $fnr, $filepath, $type]);
    $media_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare('INSERT IGNORE INTO media_categories (category_id, media_id) VALUES (?,?) ');
    $stmt->execute([$category_id,$media_id]);
    
    $fnr = $fnr + 1;
    // addCategories($pdo, $media_id);
  }
}

if (isset($_POST['foldercategory']) && isset($_POST['cat_description'])) {
  echo "<h2>Scanning...</h2>";
  scanIt($_POST['foldercategory'], $_POST['cat_description']);
  echo "<h3>Scanning complete</h3>";
}
