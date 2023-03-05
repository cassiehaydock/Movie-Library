<?php
//start session
session_start();
//if user is not logged in then exit to login
if (!isset($_SESSION['id'])) {
    header("Location:login.php");
    exit();
}

//errors array
$errors = array();

//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//Prepopulate the edit account screen 
//build query to select all info for user withe the id being taking from session
$query = "SELECT * FROM cois3420_movies WHERE Userid = ? AND id = ?";

//prepare & execute query
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['id'], $_GET['id']]);
$row = $stmt->fetch();

//set variables from either post or database
$title = $_POST['title'] ?? $row['title'];
$cover = $_FILES['imgupload'] ?? $row['cover'];
$rating = $_POST['rating'] ?? $row['rating'];
$genre = $row['genre'];
$MPAA = $_POST['MPAA'] ?? $row['mpaa'];
$year = $_POST['year'] ?? $row['years'];
$runtime = $_POST['runtime'] ?? $row['runtime'];
$studio = $_POST['studio'] ?? $row['studio'];
$release = $_POST['release'] ?? $row['releases'];
$streaming = $_POST['streaming'] ?? $row['streaming'];
$actors = $_POST['actors'] ?? $row['actors'];
$summary = $_POST['summary'] ?? $row['summary'];
$videotype = $row['videotype'];
$coverlink = $_POST['coverlink'] ?? $row['url'];

//explode string for checkboxes to make array
$genre_array = explode(',', $genre);
$videotype_array = explode(',', $videotype);

//check for errors when form has been submitted
if (isset($_POST['submit'])) {

    //if submitted use Post of checkboxes
    $genre = $_POST['genre'] ?? array();
    $videotype = $_POST['videotype'] ?? array();

    //check that user inputted a title
    if (strlen($title) === 0) {
        $errors['firstname'] = true;
    }

    //check if user entered a rating
    if (!is_numeric($rating) || $rating === null) {
        $errors['rating'] = true;
    }

    //check if user selected a genre
    if (count($genre) === 0) {
        $errors['genre'] = true;
    }

    //check if user selected a mpaa rating
    if ($MPAA === "0") {
        $errors['MPAA'] = true;
    }

    //check if user entered a year
    if (!is_numeric($year) || $year === null) {
        $errors['year'] = true;
    }

    //check if user entered a runtime
    if (!is_numeric($runtime) || $runtime === null) {
        $errors['runtime'] = true;
    }

    //check if user entered a stuio
    if (strlen($studio) === 0) {
        $errors['studio'] = true;
    }

    //check if user entered a release date
    if ($release === null) {
        $errors['release'] = true;
    }

    //check if user entered a streaming date
    if ($streaming === null) {
        $errors['streaming'] = true;
    }

    //check if user entered a list of actors
    if (strlen($actors) === 0) {
        $errors['actors'] = true;
    }

    //check if useres entered a summary
    if (strlen($summary) === 0) {
        $errors['summary'] = true;
    }

    //check if useres entered a videotype
    if (count($videotype) === 0) {
        $errors['videotype'] = true;
    }

    //if no errors
    if (count($errors) === 0) {
        //implode checkbox arrays into strings
        $genre_str = implode(",", $genre);
        $videotype_str = implode(",", $videotype);

        //build query to update movie details
        $query = "UPDATE cois3420_movies SET title = ?, rating = ?, genre = ?, mpaa = ?, years = ?, runtime = ?, studio = ?, releases = ?, streaming = ?, actors = ?, summary = ?, videotype = ?, url = ? WHERE id = ?";

        //prepare & execute query
        $stmt = $pdo->prepare($query);
        $stmt->execute([$title, $rating, $genre_str, $MPAA, $year, $runtime, $studio, $release, $streaming, $actors, $summary, $videotype_str, $coverlink, $_GET['id']]);


        //if there is a image file to upload
        if (is_uploaded_file($_FILES['imgupload']['tmp_name'])) {

            //unique id which is the movie id for
            $uniqueID = $_GET['id'];

            if (is_uploaded_file($_FILES['imgupload']['tmp_name'])) {

                //check for errors with upload if so echo error message
                $results = checkErrors('imgupload', 102400);
                if (strlen($results) > 0) {
                    //echo $results;
                    $errors['results'] = true;
                } else {
                    //call fucnction to create unqiue new filename 
                    $newname = createFilename('imgupload', '', 'cover', $uniqueID);

                    //if failed to upload echo an error else update table for movie to include cover
                    if (!move_uploaded_file($_FILES['imgupload']['tmp_name'], WEBROOT."www_data/$newname")) {
                        //echo "Failed to move uploaded file.";
                        $errors['failed'] = true;
                    } 
                    else 
                    {
                        // //unlink cover if there is one for the new one
                        // if($row['cover'] == true)
                        // {
                        //     unlink('/home/cassandrahaydock/public_html/www_data/'.$row['cover']);
                        // }
                        
                        $query = "UPDATE cois3420_movies SET cover = ? WHERE id = ?";

                        $stmt = $pdo->prepare($query)->execute([$newname, $uniqueID]);
                    }
                }
            }
        }

        if (count($errors) === 0)
        {
            //then exit to index
            header("Location:index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/6245260836.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assn3/styles/main.css">
    <title>Edit Movie</title>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <?php include 'includes/nav.php'; ?>

    <!-- Main Body -->
    <main>
        <form id="editvid" method="post" enctype="multipart/form-data">

            <h1>Edit Movie</h1>

            <!-- Look up title -->
            <div>
                <label for="title">Title</label>
                <input type="search" name="title" value="<?= $title ?>" id="title" />
                <span class="error <?= !isset($errors['title']) ? 'hidden' : "" ?>">Please enter a movie Title</span>
            </div>

            <!-- Ratings -->
            <div>
                <label class="rating-label" for="rating">Rating</label>
                <input name="rating" id="rating" class="rating" max="5" oninput="this.style.setProperty('--value', `${this.valueAsNumber}`)" step="1" style="--value:<?= $rating ?>" type="range" value="<?= $rating ?>">
            </div>

            <!-- Genre Options -->
            <fieldset>
                <legend>Genre</legend>
                <input type="checkbox" name="genre[]" id="Comedy" value="Comedy" <?= in_array("Comedy", $genre_array) ? 'checked' : ''; ?> />
                <label for="Comedy">Comedy</label>

                <input type="checkbox" name="genre[]" id="Romance" value="Romance" <?= in_array("Romance", $genre_array) ? 'checked' : ''; ?> />
                <label for="Romance">Romance</label>

                <input type="checkbox" name="genre[]" id="Fantasy" value="Fantasy" <?= in_array("Fantasy", $genre_array) ? 'checked' : ''; ?> />
                <label for="Fantasy">Fantasy</label>

                <input type="checkbox" name="genre[]" id="Action" value="Action" <?= in_array("Action", $genre_array) ? 'checked' : ''; ?> />
                <label for="Action">Action</label>

                <input type="checkbox" name="genre[]" id="Horror" value="Horror" <?= in_array("Horror", $genre_array) ? 'checked' : ''; ?> />
                <label for="Horror">Horror</label>

                <input type="checkbox" name="genre[]" id="RomCom" value="Romantic Comedy" <?= in_array("Romantic Comedy", $genre_array) ? 'checked' : ''; ?> />
                <label for="RomCom">Romantic Comedy</label>

                <input type="checkbox" name="genre[]" id="Drama" value="Drama" <?= in_array("Drama", $genre_array) ? 'checked' : ''; ?> />
                <label for="Drama">Drama</label>

                <input type="checkbox" name="genre[]" id="Thriller" value="Thriller" <?= in_array("Thriller", $genre_array) ? 'checked' : ''; ?> />
                <label for="Thriller">Thriller</label>

                <input type="checkbox" name="genre[]" id="Documentary" value="Documentary" <?= in_array("Documentary", $genre_array) ? 'checked' : ''; ?> />
                <label for="Documentary">Documentary</label>
            </fieldset>
            <span class="error <?= !isset($errors['genre']) ? 'hidden' : "" ?>">Please select at least one</span>

            <div class="grid">
                <!-- MPAA Rating Options -->
                <div>
                    <div>
                        <label for="MPAA">MPAA</label>
                        <select name="MPAA" id="MPAA">
                            <option value="0" <?= $MPAA == "0" ? 'selected' : '' ?>>Please Choose One</option>
                            <option value="G" selected <?= $MPAA == "G" ? 'selected' : '' ?>>G</option>
                            <option value="PG" <?= $MPAA == "PG" ? 'selected' : '' ?>>PG</option>
                            <option value="PG-13" <?= $MPAA == "PG-13" ? 'selected' : '' ?>>PG-13</option>
                            <option value="R" <?= $MPAA == "R" ? 'selected' : '' ?>>R</option>
                            <option value="NC-17" <?= $MPAA == "NC-17" ? 'selected' : '' ?>>NC-17</option>
                        </select>
                        <span class="error <?= !isset($errors['MPAA']) ? 'hidden' : "" ?>">Please select one mpaa rating</span>
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year">Year</label>
                        <input type="number" name="year" id="year" value="<?= $year ?>" />
                        <span class="error <?= !isset($errors['year']) ? 'hidden' : "" ?>">Please enter a year</span>
                    </div>

                    <!-- Running Time -->
                    <div>
                        <label for="runtime">Run Time (hours)</label>
                        <input type="number" name="runtime" id="runtime" value="<?= $runtime ?>" />
                        <span class="error <?= !isset($errors['runtime']) ? 'hidden' : "" ?>">Please enter a runtime</span>
                    </div>
                </div>

                <div>
                    <!-- Studio -->
                    <div>
                        <label for="studio">Studio</label>
                        <input type="text" name="studio" id="studio" value="<?= $studio ?>" />
                        <span class="error <?= !isset($errors['studio']) ? 'hidden' : "" ?>">Please enter a studio</span>
                    </div>


                    <!-- Theatrical Release -->
                    <div>
                        <label for="release">Theatrical Release</label>
                        <input type="date" name="release" id="release" value="<?= $release ?>" />
                        <span class="error <?= !isset($errors['release']) ? 'hidden' : "" ?>">Please enter a release date</span>
                    </div>

                    <!-- DVD/Streaming Release -->
                    <div>
                        <label for="streaming">DVD / Streaming Release</label>
                        <input type="date" name="streaming" id="streaming" value="<?= $streaming ?>" />
                        <span class="error <?= !isset($errors['streaming']) ? 'hidden' : "" ?>">Please enter a streaming date</span>
                    </div>
                </div>
            </div>

            <!-- Actors -->
            <div>
                <label for="actors">Actors</label>
                <input type="text" name="actors" id="actors" value="<?= $actors ?>" />
                <span class="error <?= !isset($errors['actors']) ? 'hidden' : "" ?>">Please enter a list of actors</span>
                <!-- pattern="^[a-zA-Z]+(,[a-zA-Z]+)*$" -->
            </div>

            <!-- Plot Summary -->
            <div>
                <label for="summary">Plot Summary</label>
                <textarea name="summary" id="summary" cols="25" rows="10" maxlength="500"><?php if (isset($row['summary'])) {echo $summary;}?></textarea>
                <span class="error <?= !isset($errors['summary']) ? 'hidden' : "" ?>">Please a summary</span>
            </div>

            <!-- Video Type -->
            <fieldset>
                <legend>Video Type</legend>
                <input type="checkbox" name="videotype[]" id="DVD" value="DVD" <?= in_array("DVD", $videotype_array) ? 'checked' : ''; ?> />
                <label for="DVD">DVD</label>

                <input type="checkbox" name="videotype[]" id="BluRay" value="BluRay" <?= in_array("BluRay", $videotype_array) ? 'checked' : ''; ?> />
                <label for="BluRay">BluRay</label>

                <input type="checkbox" name="videotype[]" id="DigitalSD" value="DigitalSD" <?= in_array("DigitalSD", $videotype_array) ? 'checked' : ''; ?> />
                <label for="DigitalSD">Digital SD</label>

                <input type="checkbox" name="videotype[]" id="DigitalHD" value="DigitalHD" <?= in_array("DigitalHD", $videotype_array) ? 'checked' : ''; ?> />
                <label for="DigitalHD">Digital HD</label>

                <input type="checkbox" name="videotype[]" id="4K" value="4K" <?= in_array("4K", $videotype_array) ? 'checked' : ''; ?> />
                <label for="4K">4K</label>

                <input type="checkbox" name="videotype[]" id="Digital4K" value="Digital4K" <?= in_array("Digital4K", $videotype_array) ? 'checked' : ''; ?> />
                <label for="Digital4K">Digital 4K</label>
            </fieldset>
            <span class="error <?= !isset($errors['videotype']) ? 'hidden' : "" ?>">Please select a video format</span>

            <!-- Cover Image Upload -->
            <div>
                <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                <label for="imgupload">Upload Cover</label>
                <input type="file" name="imgupload" id="imgupload" />
                <span class="error <?= !isset($errors['results']) ? 'hidden' : "" ?>"><?= $results ?></span>
                <span class="error <?= !isset($errors['failed']) ? 'hidden' : "" ?>">Failed to Upload File</span>
            </div>

            <!-- Link to Cover Image -->
            <div>
                <label for="coverlink">Cover Link</label>
                <input type="url" name="coverlink" id="coverlink" value="<?= $coverlink ?>" />
            </div>

            <!-- Add Video Button -->
            <div>
                <button type="submit" name="submit" id="submit">Edit Video</button>
            </div>

        </form>
    </main>


    <?php include 'includes/footer.php'; ?>
</body>

</html>