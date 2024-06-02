<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .carousel {
            width: 100%; /* Set the desired width */
            height: 400px; /* Set the desired height */
        }
        .carousel-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .left-column, .right-column, .extra-left-column {
            padding: 10px;
        }
        .main-content {
            display: flex;
            flex-wrap: wrap;
        }
        .extra-left-column, .left-column, .right-column {
            margin-bottom: 20px;
        }
        
    </style>
</head>
<body>
<img src="Blaz.jpg" alt="Une belle photo">
<header>
        <?php require('header.php'); ?>
    </header>
    <main>
    <div class="main-content">
    <div class="col-lg-2 extra-left-column">
              <h2>INFOS ECE</h2>
            <div>
            <?php
                require('databaseinfosece.php');
                foreach ($_SESSION['infosece'] as $info) {
                    echo '<div class="info">';
                    echo '<h3>' . htmlspecialchars($info['titre'], ENT_QUOTES, 'UTF-8') . '</h3>';
                    echo '<p>' . htmlspecialchars($info['contenu'], ENT_QUOTES, 'UTF-8') . '</p>';
                    echo '</div>';
                }
            ?>
            </div>
        </div>
        <div class="col-lg-6 left-column">
                <form action="databasetweet.php" method="POST">
                <input type="hidden" name="form" value="publication">
                <label for="type">Type de publication:</label>
                <select name="type" id="type">
                    <option value="Statut">Statut</option>
                    <option value="Evenement">Événement</option>
                    <option value="Candidature">Candidature</option>
                </select>
                <br>
                <label for="titre">Titre:</label>
                <input type="text" name="titre" id="titre">
                <br>
                <label for="publication">Contenu:</label>
                <input type="text" name="publication" id="publication">
                <br>
                <label for="url">URL de l'image:</label>
                <input type="text" name="url" id="url">
                <button type="submit">Publier</button>
            </form>
            <div>
            <?php
            require('databasetweet.php');
            foreach ($tweets as $tweet) {
                foreach ($users as $user) {
                    if($user['ID_Utilisateur'] == $tweet['ID_Utilisateur']){
                        echo '
                            <section><div>
                                <h2>' . $user['Pseudo'] . '</h2>
                            </div>';
                    }
                } 
                echo '<div class="tweet">'. $tweet['Titre']. '</div>';
                if ($tweet['URL']) {
                    echo '<img src="' . $tweet['URL'] . '" alt="Image associée au tweet">';
                }
                echo '<div class="tweet">'. $tweet['Contenu']. '</div>';
                echo '</section>';
            }
            ?>
            </div>
        </div>
        <div class="col-lg-4 right-column">
             <h2 class="mt-4">Evénement de la Semaine :</h2>
                    <h5 class="mt-4">Journée portes ouvertes à l'ECE Paris le mardi 4 Juin !</h5>
                    <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="5000">
                                <img src="EM009.jpg" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <!-- Caption content here -->
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="5000">
                                <img src="paris.jpg" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <!-- Caption content here -->
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="5000">
                                <img src="dehors.jpg" class="d-block w-100" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <!-- Caption content here -->
                                </div>
                            </div>
                        </div>
                        
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
        </div>
    </div>
    </main>
    <footer></footer>

      <!-- Bootstrap JS and Popper.js -->
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
<!-- #region -->