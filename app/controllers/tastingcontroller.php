<?php

class TastingController extends BaseController implements Controller
{
    const viewDirectory = 'tasting/';

    public function __construct()
    {
        if ((isset($_GET['mode']))) {
            if (($_GET['mode'] != "visitor")) {
                header("Location:" . PAGE_SIGNIN);
            }
        } else if (!Session::getConnectedUser()) {
            header("Location:" . PAGE_SIGNIN);
        }
    }

    public function getAllTastings()
    {

        $this->h1 = "Tastings";
        $this->description = "All tastings";
        $this->title = "TasteMyBeer - All tastings";

        $view = "viewTastings.phtml";

        $limit = DEFAULT_PAGINATION;

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $offset = ($page - 1) * $limit;

        $tastings = Tasting::getAllTastings($offset, $limit);

        $tastingsCount = Tasting::count();

        $pages = ceil($tastingsCount / $limit);

        return App::get_content(
            self::viewDirectory . $view,
            array(
                'tastings' => $tastings,
                'pages' => $pages,
                'count' => $tastingsCount,
                'page' => $page
            )
        );
    }


    public function getUserTastings($userId)
    {
        $this->h1 = "My tastings";
        $this->description = "My tastings";
        $this->title = "TasteMyBeer - My tastings";

        $view = "viewTastings.phtml";

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $limit = DEFAULT_PAGINATION;

        $offset = ($page - 1) * $limit;

        $tastings = Tasting::getUserTastings($userId, $offset, $limit);

        $tastingsCount = Tasting::count(true);

        $pages = ceil($tastingsCount / $limit);

        return App::get_content(
            self::viewDirectory . $view,
            array(
                'tastings' => $tastings,
                'pages' => $pages,
                'count' => $tastingsCount,
                'page' => $page
            )
        );
    }

    public function getTastingById($id)
    {
        $this->h1 = "Tasting " . $id;
        $this->description = "Tasting " . $id;
        $this->title = "TasteMyBeer -" . "Tasting " . $id;

        $view = "viewTasting.phtml";

        $tasting = Tasting::getTastingById($id);

        return App::get_content(
            self::viewDirectory . $view,
            array('tasting' => $tasting)
        );
    }

    public function addNew()
    {
        $this->h1 = "Add tasting";
        $this->description = "Add tasting";
        $this->title = "TasteMyBeer - Add";

        $view = 'addTasting.phtml';
        $errors = [];
        $success = false;
        $beerStyles = BeerStyle::getBeerStyles();
        if (!empty($_POST)) {

            //s??lection de la bi??re d??gust??e
            if (!isset($_POST['' . Tasting::BEER_STYLE_ID . ''])) {
                $errors[] = 'Aucune Bi??re n\'a ??t?? s??lectionn??e.';
            }

            //nom de la bi??re
            if (!isset($_POST['' . Tasting::BEER_NAME . '']) || empty($_POST['' . Tasting::BEER_NAME . ''])) {
                $errors[] = 'Le nom de la bi??re est obligatoire.';
            }

            $values = array($_POST['' . Tasting::AROMA_SCORE . ''], $_POST['' . Tasting::APPEARANCE_SCORE . ''], $_POST['' . Tasting::FLAVOR_SCORE . ''], $_POST['' . Tasting::MOUTHFEEL_SCORE . ''], $_POST['' . Tasting::OVERALL_SCORE . '']);
            $res = App::checkValue($values);
            if ($res != false) {
                $errors[] = $res;
            }
            //Pr??cision stylistique
            /*if (!isset($_POST['stylisticAccuracy']) || empty($_POST['stylisticAccuracy'])) {
                $errors[] = 'La pr??cision stylistique est obligatoire.';
            } else if (!preg_match($_POST['stylisticAccuracy'], PATERN_ONE_DIGIT_BETWEEN_1_AND_5)) {
                $errors[] = PATERN_ONE_DIGIT_BETWEEN_1_AND_5_EXPL;
            }*/


            //Intangibilit??
            /*
            if (!isset($_POST['intangibles']) || empty($_POST['intangibles'])) {
                $errors[] = 'L\'intangibilit?? est obligatoire.';
            } else if (!preg_match($_POST['intangibles'], PATERN_ONE_DIGIT_BETWEEN_1_AND_5)) {
                $errors[] = PATERN_ONE_DIGIT_BETWEEN_1_AND_5_EXPL;
            }
            */

            /*

            //M??rite thechinique
            if (!isset($_POST['technicalMerit']) || empty($_POST['technicalMerit'])) {
                $errors[] = 'Le m??rite thechinique est obligatoire.';
            } else if (!preg_match($_POST['technicalMerit'], PATERN_ONE_DIGIT_BETWEEN_1_AND_5)) {
                $errors[] = PATERN_ONE_DIGIT_BETWEEN_1_AND_5_EXPL;
            }
            */

            if (empty($errors)) {


                $userId = Session::getConnectedUser()->id;
                $beerStyleId = $_POST['' . Tasting::BEER_STYLE_ID . ''];
                $beerName = Tasting::clearComments($_POST['' . Tasting::BEER_NAME . '']);
                $title = $_POST['' . Tasting::TITLE . ''];
                $aromaComment = Tasting::clearComments($_POST['' . Tasting::AROMA_COMMENT . '']);
                $appearanceComment = Tasting::clearComments($_POST['' . Tasting::APPEARANCE_COMMENT . '']);
                $flavorComment = Tasting::clearComments($_POST['' . Tasting::FLAVOR_COMMENT . '']);
                $mouthfeelComment = Tasting::clearComments($_POST['' . Tasting::MOUTHFEEL_COMMENT . '']);
                $overallComment = Tasting::clearComments($_POST['' . Tasting::OVERALL_COMMENT . '']);
                $bottleInspectionComment = Tasting::clearComments($_POST['' . Tasting::BOTTLE_INSPECTION_COMMENT . '']);
                $aromaScore = tasting::getFloat($_POST['' . Tasting::AROMA_SCORE . '']);
                $appearanceScore = tasting::getFloat($_POST['' . Tasting::APPEARANCE_SCORE . '']);
                $flavorScore = tasting::getFloat($_POST['' . Tasting::FLAVOR_SCORE . '']);
                $mouthfeelScore = tasting::getFloat($_POST['' . Tasting::MOUTHFEEL_SCORE . '']);
                $overallScore = tasting::getFloat($_POST['' . Tasting::OVERALL_SCORE . '']);
                $total = Tasting::calculateTotal($aromaScore, $appearanceScore, $flavorScore, $mouthfeelScore, $overallScore);

                $isAcetaldehyde = isset($_POST['' . Tasting::IS_ACETALDEHYDE . '']) ? 1 : 0;

                $isAlcoholic = isset($_POST['' . Tasting::IS_ALCOHOLIC . '']) ? 1 : 0;
                $isAstringent = isset($_POST['' . Tasting::IS_ASTRINGENT . '']) ? 1 : 0;
                $isDiacetyl = isset($_POST['' . Tasting::IS_DIACETYL . '']) ? 1 : 0;
                $isDms = isset($_POST['' . Tasting::IS_DMS . '']) ? 1 : 0;
                $isEstery = isset($_POST['' . Tasting::IS_ESTERY . '']) ? 1 : 0;
                $isGrassy = isset($_POST['' . Tasting::IS_GRASSY . '']) ? 1 : 0;
                $isLightStruck = isset($_POST['' . Tasting::IS_LIGHT_STRUCK . '']) ? 1 : 0;
                $isMetallic = isset($_POST['' . Tasting::IS_METALLIC . '']) ? 1 : 0;
                $isMusty = isset($_POST['' . Tasting::IS_MUSTY . '']) ? 1 : 0;
                $isOxidized = isset($_POST['' . Tasting::IS_OXIDIZED . '']) ? 1 : 0;
                $isPhenolic = isset($_POST['' . Tasting::IS_PHENOLIC . '']) ? 1 : 0;
                $isSolvent = isset($_POST['' . Tasting::IS_SOLVENT . '']) ? 1 : 0;
                $isAcidic = isset($_POST['' . Tasting::IS_ACIDIC . '']) ? 1 : 0;
                $isSulfur = isset($_POST['' . Tasting::IS_SULFUR . '']) ? 1 : 0;
                $isVegetal = isset($_POST['' . Tasting::IS_VEGETAL . '']) ? 1 : 0;
                $isBottleOk = isset($_POST['' . Tasting::IS_BOTTLE_OK . '']) ? 1 : 0;
                $isYeasty = isset($_POST['' . Tasting::IS_YEASTY . '']) ? 1 : 0;

                /*
                $stylisticAccuracy = $_POST['isYeasty'];
                $intangibles = $_POST['intangibles'];
                $technicalMerit = $_POST['technicalMerit'];
                */

                $tasting = new Tasting();
                $tasting->initValue(
                    $id = false,
                    $userId,
                    $beerStyleId,
                    $beerName,
                    $title,
                    $aromaComment,
                    $appearanceComment,
                    $flavorComment,
                    $mouthfeelComment,
                    $overallComment,
                    $bottleInspectionComment,
                    $aromaScore,
                    $appearanceScore,
                    $flavorScore,
                    $mouthfeelScore,
                    $overallScore,
                    $total,
                    $isAcetaldehyde,
                    $isAlcoholic,
                    $isAstringent,
                    $isDiacetyl,
                    $isDms,
                    $isEstery,
                    $isGrassy,
                    $isLightStruck,
                    $isMetallic,
                    $isMusty,
                    $isOxidized,
                    $isPhenolic,
                    $isSolvent,
                    $isAcidic,
                    $isSulfur,
                    $isVegetal,
                    $isBottleOk,
                    $isYeasty
                );
                if ($tasting->save()) {
                    $success = "La d??gustation a ??t?? enregistr?? avec succ??s.";
                } else {
                    $errors[] = "Une erreur s'est produite lors de l'ajout de cette d??gustation.";
                }
            }
        }
        return App::get_content(
            self::viewDirectory . $view,
            array(
                'beerStyles'         => $beerStyles,
                'errors'             => $errors,
                'success'            => $success
            )
        );
    }

    public function render()
    {
        $content = false;
        $operation = $_GET['operation'];
        switch ($operation) {
            case 'addTasting':
                $content = $this->addNew();
                break;
            case 'getUserTastings':
                $content = $this->getUserTastings($_GET['userId']);
                break;
            case 'getTastingById':
                $content = $this->getTastingById($_GET['id']);
                break;
            case 'getAllTastings':
            default:
                $content = $this->getAllTastings();
                break;
        }
        return $content;
    }
}