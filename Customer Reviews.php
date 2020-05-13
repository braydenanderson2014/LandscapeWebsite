<?php
    $conn = new mysqli('localhost', 'root', '', 'ratingSystem');

    if (isset($_POST['save'])) {
        $uID = $conn->real_escape_string($_POST['uID']);
        $ratedIndex = $conn->real_escape_string($_POST['ratedIndex']);
        $ratedIndex++;

        if (!$uID) {
            $conn->query("INSERT INTO stars (rateIndex) VALUES ('$ratedIndex')");
            $sql = $conn->query("SELECT id FROM stars ORDER BY id DESC LIMIT 1");
            $uData = $sql->fetch_assoc();
            $uID = $uData['id'];
        } else
            $conn->query("UPDATE stars SET rateIndex='$ratedIndex' WHERE id='$uID'");

        exit(json_encode(array('id' => $uID)));
    }

    $sql = $conn->query("SELECT id FROM stars");
    $numR = $sql->num_rows;

    $sql = $conn->query("SELECT SUM(rateIndex) AS total FROM stars");
    $rData = $sql->fetch_array();
    $total = $rData['total'];

    $avg = $total / $numR;
?>






<!DOCTYPE html>
<html lang="en">
	<head>
		<title> Joes Fishing and Fish </title>
   <link rel="stylesheet" type="text/css" href="mystyle.css">
   <link rel="stylesheet" type="text/html" href="https://fontawesome.com/kits/27e431ebdc/use">
   
		 <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	</head>
	<body>
		<header> 
			<h1>Joes Fishing and Fish</h1>
			<h2>
				<nav class="navigation">
  <ul>
	<li><a href="index.html"><span class="Home">Home</span></a></li>
    <li><a href="Gallery.html"><span class="Gallery.html">Gallery</span></a></li>
    <li><a href="About Us.html"><span class="AboutUs.html">About Us</span></a></li>
    <li><a href="Contact Us.php"><span class="ContactUs.php">Contact Us</span></a></li>
    <li><a class="active" href="Customer Reviews.php"><span class="CustomerReviews.php">Customer Reviews</span></a></li>
  	<li><a class="element" href="http://www.facebook.com" target="_blank"><img src="Images/facebook-icon_square_30x30.png" /></a></li>
	<li><a class="element" href="http://www.twitter.com" target="_blank"><img src="Images/Twitter2.jpg" /></a></li>
	<li><a class="element" href="http://www.youtube.com" target="_blank"><img src="Images/youtube-icon_30x30.png" /></a></li>
					</ul>
</nav></h2> </header>
		<h2>Reviews:</h2>
		<div align="center" style="background:#000; padding:50px;">
			<i class="fa fa-star fa-2x" data index="0"> </i>
			<i class="fa fa-star fa-2x" data index="1"> </i>
			<i class="fa fa-star fa-2x" data index="2"> </i>
			<i class="fa fa-star fa-2x" data index="3"> </i>
			<i class="fa fa-star fa-2x" data index="4"> </i>
			<br><br>
        <?php echo round($avg,2) ?>
	    </div>
		<script src="http://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
		<script>
        var ratedIndex = -1, uID = 0;

        $(document).ready(function () {
            resetStarColors();

            if (localStorage.getItem('ratedIndex') != null) {
                setStars(parseInt(localStorage.getItem('ratedIndex')));
                uID = localStorage.getItem('uID');
            }

            $('.fa-star').on('click', function () {
               ratedIndex = parseInt($(this).data('index'));
               localStorage.setItem('ratedIndex', ratedIndex);
               saveToTheDB();
            });

            $('.fa-star').mouseover(function () {
                resetStarColors();
                var currentIndex = parseInt($(this).data('index'));
                setStars(currentIndex);
            });

            $('.fa-star').mouseleave(function () {
                resetStarColors();

                if (ratedIndex != -1)
                    setStars(ratedIndex);
            });
        });

        function saveToTheDB() {
            $.ajax({
               url: "Customer Reviews.php",
               method: "POST",
               dataType: 'json',
               data: {
                   save: 1,
                   uID: uID,
                   ratedIndex: ratedIndex
               }, success: function (r) {
                    uID = r.id;
                    localStorage.setItem('uID', uID);
               }
            });
        }

        function setStars(max) {
            for (var i=0; i <= max; i++)
                $('.fa-star:eq('+i+')').css('color', 'green');
        }

        function resetStarColors() {
            $('.fa-star').css('color', 'white');
        }
    </script>
</body>
</html>