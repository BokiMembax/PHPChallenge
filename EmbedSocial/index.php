<?php

function filt($var)
{
  global $_GET;
  return $var['rating'] >= $_GET['minimum-rating'];
}

if (isset($_GET["filter"])) {

  $url = 'reviews.json';
  $json = file_get_contents($url);
  $d = json_decode($json, true);
  $d2 = array_filter($d, "filt");

  usort($d2, function ($a, $b) use ($order) {
    global $_GET;
    if ($_GET['prioritize-by-text'] == 'yes') {
      if ($a['reviewText'] == '' xor $b['reviewText'] == '') {
        if ($a['reviewText'] == '' && $b['reviewText'] != '') return true; else return false;
      }
    }
    if ($_GET['order-by-rating'] != '') {

      if ($a['rating'] != $b['rating']) {
        if ($_GET['order-by-rating'] == 'highest' xor $a['rating'] > $b['rating'])
          return true;
        else return false;
      }
    }
    if ($_GET['order-by-date'] != '') {

      if ($a['reviewCreatedOnTime'] != $b['reviewCreatedOnTime']) {
        if ($_GET['order-by-date'] == 'newest' xor $a['reviewCreatedOnTime'] > $b['reviewCreatedOnTime'])
          return true;
        else return false;
      }
    }

    return false;
  });

  $output = "<table><tr><th>reviewId</th><th>rating</th><th>reviewCreatedOnDate</th><th>reviewerName</th><th>reviewFullText</th></tr>";

  foreach ($d2 as $review) {
    $output .= "<tr><td>" . $review['reviewId'] . "</td>";
    $output .= "<td>" . $review['rating'] . "</td>";
    $output .= "<td>" . $review['reviewCreatedOnDate'] . "</td>";
    $output .= "<td>" . $review['reviewerName'] . "</td>";
    $output .= "<td>FULL:" . $review['reviewFullText'] . "</td></tr>";
  }
  echo $output .= "</table>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap 4.6.1 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
          integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

</head>

<body>

<div class="row p-5">
    <div class="col-md-4">
        <h2>Filter Reviews</h2>
        <form action="" method="get">
            <div class="form-group">
                <label for="order-by-rating">Order by rating:</label>
                <select class="form-control" name="order-by-rating" id="order-by-rating">
                    <option value="highest" <? if ($_GET['order-by-rating'] == 'highest' || $_GET['order-by-rating'] == '') echo 'selected' ?>>
                        Highest
                        first
                    </option>
                    <option value="lowest" <? if ($_GET['order-by-rating'] == 'lowest') echo 'selected' ?>>Lowest
                        first
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="minimum-rating">Minimum rating:</label>
                <select class="form-control" name="minimum-rating" id="minimum-rating">
                    <option value="1" <? if ($_GET['minimum-rating'] == '1' || $_GET['minimum-rating'] == '') echo 'selected' ?>>
                        1
                    </option>
                    <option value="2" <? if ($_GET['minimum-rating'] == '2') echo 'selected' ?>>2</option>
                    <option value="3" <? if ($_GET['minimum-rating'] == '3') echo 'selected' ?>>3</option>
                    <option value="4" <? if ($_GET['minimum-rating'] == '4') echo 'selected' ?>>4</option>
                    <option value="5" <? if ($_GET['minimum-rating'] == '5') echo 'selected' ?>>5</option>
                </select>
            </div>
            <div class="form-group">
                <label for="order-by-date">Order by date:</label>
                <select class="form-control" name="order-by-date" id="order-by-date">
                    <option value="newest" <? if ($_GET['order-by-date'] == 'newest' || $_GET['order-by-date'] == '') echo 'selected' ?>>
                        Newest first
                    </option>
                    <option value="oldest" <? if ($_GET['order-by-date'] == 'oldest') echo 'selected' ?>>Oldest first
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="prioritize-by-text">Prioritize by text:</label>
                <select class="form-control" name="prioritize-by-text" id="prioritize-by-text">
                    <option value="yes" <? if ($_GET['prioritize-by-text'] == 'yes' || $_GET['prioritize-by-text'] == '') echo 'selected' ?>>
                        Yes
                    </option>
                    <option value="no" <? if ($_GET['prioritize-by-text'] == 'no') echo 'selected' ?>>No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" id="filter" name="filter">Filter</button>
        </form>
    </div>
</div>


<!-- Bootstrap 4.6.1 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF"
        crossorigin="anonymous"></script>

</body>

</html>