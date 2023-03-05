<?php
//start session
session_start();
//if user is not logged in exit to login
if(!isset($_SESSION['id']))
{
    header("Location:login.php");
    exit();   
}

//errors array
$errors = array();

//settings variables for form elements
$id = $_SESSION['id'];
$title = $_POST['title'] ?? null;
$rating = $_POST['rating'] ?? null;
$cover = $_FILES['imgupload'] ?? null;
$genre = $_POST['genre'] ?? array();
$MPAA = $_POST['MPAA'] ?? null;
$year = $_POST['year'] ?? null;
$runtime = $_POST['runtime'] ?? null;
$studio = $_POST['studio'] ?? null;
$release = $_POST['release'] ?? null;
$streaming = $_POST['streaming'] ?? null;
$actors = $_POST['actors'] ?? null;
$summary = $_POST['summary'] ?? null;
$videotype = $_POST['videotype'] ?? array();
$coverlink = $_POST['coverlink'] ?? null;

//include library
require 'includes/library.php';

//make database connection
$pdo = connectDB();

//check for errors when form has been submitted
if (isset($_POST['submit'])) {

    //if there are no erroes
    if (count($errors) === 0)
    {
        //make all checkboxes into strinsg to be stored in database
        $genre_str = implode(",", $genre);
        $videotype_str = implode(",", $videotype);

        //build query to insert information for movie into database
        $query = "INSERT INTO cois3420_movies (Userid, title, rating, genre, mpaa, years, runtime, studio, releases, streaming, actors, summary, videotype, url)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        //prepare & execute query
        $stmt = $pdo->prepare($query)->execute([$id, $title, $rating, $genre_str, $MPAA, $year, $runtime, $studio, $release, $streaming, $actors, $summary, $videotype_str, $coverlink]);
        
        //if there is a file to beuploaded
        if(is_uploaded_file($_FILES['imgupload']['tmp_name']))
        {
            //get the id of the last inserted movie which will be the one we just did above
            $uniqueID = $pdo->lastInsertId();
          
            if(is_uploaded_file($_FILES['imgupload']['tmp_name']))
            {
          
              //check of there is errors with file upload
              $results = checkErrors('imgupload',10000000);
              //if there are errors set an error
              if(strlen($results)>0)
              {
                $errors['results'] = true;
              }
               //else call createFilename to create a unique filename for the image
               //with the prefix 'cover' with the unique ID for the movie
               else
               {
                 $newname = createFilename('imgupload', '', 'cover', $uniqueID);
               }

               //if there is an error with the upload set an error and cover in database will be empty
                if(!move_uploaded_file($_FILES['imgupload']['tmp_name'], WEBROOT . "www_data/$newname"))
                {
                    $erros['failed'] = true;
                }
                //else update database to include the new filename
                else
                {
                    $query = "UPDATE cois3420_movies SET cover = ? WHERE id = ?;";

                    $stmt = $pdo->prepare($query)->execute([$newname, $uniqueID]);    
                }
            }
        }

        //after video added exit to index
        header("Location:index.php");
        exit();
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
    <script src="scripts/addvid.js"></script>
    <script src="scripts/summary.js"></script>
    <title>Add Movie</title>
</head>

<body>
     <?php include 'includes/header.php'; ?>

     <?php include 'includes/nav.php'; ?>

    <!-- Main Body -->
    <main>
        <form id="addvid" method="post" enctype="multipart/form-data">

            <h1>Add Video</h1>

            <!-- Look up title -->
            <div>
                <label for="title">Title</label>
                <input type="search" name="title" value="<?= $title ?>"  id="title" />
                <span class="error <?= !isset($errors['title']) ? 'hidden' : "" ?>">Please enter a movie Title</span>
            </div>

            <!-- Ratings -->
            <div>
                <label class="rating-label" for="rating">Rating</label>
                <input name="rating" id="rating" class="rating" max="5"
                    oninput="this.style.setProperty('--value', `${this.valueAsNumber}`)" step="1" style="--value:<?= isset($rating) ? $rating : '1' ?>"
                    type="range" value="<?= isset($rating) ? $rating : '1' ?>">
                <span class="error <?= !isset($errors['rating']) ? 'hidden' : "" ?>">Please enter a rating</span>
            </div>

            <!-- Genre Options -->
            <fieldset>
                <!-- To be sticky the php checks if the checkbox is in the genre array -->
                <legend>Genre</legend>
                <input type="checkbox" name="genre[]" id="Comedy" value="Comedy" <?= in_array("Comedy", $genre) ? 'checked' : ''; ?>/>
                <label for="Comedy">Comedy</label>

                <input type="checkbox" name="genre[]" id="Romance" value="Romance" <?= in_array("Romance", $genre) ? 'checked' : ''; ?>/>
                <label for="Romance">Romance</label>

                <input type="checkbox" name="genre[]" id="Fantasy" value="Fantasy" <?= in_array("Fantasy", $genre) ? 'checked' : ''; ?>/>
                <label for="Fantasy">Fantasy</label>

                <input type="checkbox" name="genre[]" id="Action" value="Action" <?= in_array("Action", $genre) ? 'checked' : ''; ?>/>
                <label for="Action">Action</label>

                <input type="checkbox" name="genre[]" id="Horror" value="Horror" <?= in_array("Horror", $genre) ? 'checked' : ''; ?>/>
                <label for="Horror">Horror</label>

                <input type="checkbox" name="genre[]" id="RomCom" value="Romantic Comedy" <?= in_array("Romantic Comedy", $genre) ? 'checked' : ''; ?>/>
                <label for="RomCom">Romantic Comedy</label>

                <input type="checkbox" name="genre[]" id="Drama" value="Drama" <?= in_array("Drama", $genre) ? 'checked' : ''; ?>/>
                <label for="Drama">Drama</label>

                <input type="checkbox" name="genre[]" id="Thriller" value="Thriller" <?= in_array("Thriller", $genre) ? 'checked' : ''; ?>/>
                <label for="Thriller">Thriller</label>

                <input type="checkbox" name="genre[]" id="Documentary" value="Documentary" <?= in_array("Documentary", $genre) ? 'checked' : ''; ?>/>
                <label for="Documentary">Documentary</label>
            </fieldset>
            <span class="error <?= !isset($errors['genre']) ? 'hidden' : "" ?>">Please select at least one</span>

            <div class="grid">
                <!-- MPAA Rating Options -->
                <div>
                    <div>
                        <label for="MPAA">MPAA</label>
                        <select name="MPAA" id="MPAA">
                            <option value="0" selected <?= $MPAA == "0" ? 'selected' : '' ?>>Please Choose One</option>
                            <option value="G" <?= $MPAA == "G" ? 'selected' : '' ?>>G</option>
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
            </div>

            <!-- Plot Summary -->
            <div>
                <label for="summary">Plot Summary</label>
                <textarea name="summary" id="summary" cols="25" rows="10" maxlength="2500"><?php if(isset($_POST['summary'])) { echo $summary;}?></textarea>
                <span class="error <?= !isset($errors['summary']) ? 'hidden' : "" ?>">Please a summary</span>
                <h3>Character Count: <span id="count">2500</span></h3>
            </div>

            <!-- Video Type -->
            <!-- To be sticky the php checks if the checkbox is in the videotype array -->
            <fieldset id="vidtypeid">
                <legend>Video Type</legend>
                <input type="checkbox" name="videotype[]" id="DVD" value="DVD" <?= in_array("DVD", $videotype) ? 'checked' : ''; ?>/>
                <label for="DVD">DVD</label>

                <input type="checkbox" name="videotype[]" id="BluRay" value="BluRay" <?= in_array("BluRay", $videotype) ? 'checked' : ''; ?>/>
                <label for="BluRay">BluRay</label>

                <input type="checkbox" name="videotype[]" id="DigitalSD" value="DigitalSD" <?= in_array("DigitalSD", $videotype) ? 'checked' : ''; ?>/>
                <label for="DigitalSD">Digital SD</label>

                <input type="checkbox" name="videotype[]" id="DigitalHD" value="DigitalHD" <?= in_array("Digita;HD", $videotype) ? 'checked' : ''; ?>/>
                <label for="DigitalHD">Digital HD</label>

                <input type="checkbox" name="videotype[]" id="4K" value="4K" <?= in_array("4K", $videotype) ? 'checked' : ''; ?>/>
                <label for="4K">4K</label>

                <input type="checkbox" name="videotype[]" id="Digital4K" value="Digital4K" <?= in_array("Digital4K", $videotype) ? 'checked' : ''; ?>/>
                <label for="Digital4K">Digital 4K</label>
            </fieldset>
            <span class="error <?= !isset($errors['videotype']) ? 'hidden' : "" ?>">Please select a video format</span>

            <!-- Cover Image Upload -->
            <div>
                <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                <label for="imgupload">Upload Cover</label>
                <input type="file" name="imgupload" id="imgupload" />
                <span class="error <?= !isset($errors['imgupload']) ? 'hidden' : "" ?>">Please enter a cover</span>
            </div>

            <!-- Link to Cover Image -->
            <div>
                <label for="coverlink">Cover Link</label>
                <input type="url" name="coverlink" id="coverlink" value="<?= $coverlink ?>"/>
                <span class="error <?= !isset($errors['coverlink']) ? 'hidden' : "" ?>">Please enter a coverlink</span>
            </div>

            <!-- Add Video Button -->
            <div>
                <button type="submit" name="submit" id="submit">Add Video</button>
            </div>

        </form>
    </main>


    <?php include 'includes/footer.php'; ?>
</body>

</html>