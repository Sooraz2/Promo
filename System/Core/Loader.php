<?php

namespace System\Core;

use Admin\Repositories\MenuControlRepository;
use Infrastructure\SessionVariables;

class Loader
{
    public $site_js;
    public $configArray;
    private $allHiddenMenu;

    function __construct()
    {
        $this->configArray = parse_ini_file(BASE_PATH . DS . "config.ini", true);

        $this->configArray=json_decode (json_encode ($this->configArray), FALSE);
    }

    public function setAllHiddenMenu($hiddenMenu){
        $this->allHiddenMenu = $hiddenMenu;
    }

    function Library($class, $directory = NULL)
    {
        foreach (array(APP_PATH, SYSTEM_PATH) as $path) {
            $file = $path . DS . 'Libraries' . DS . $directory . DS . $class . '.php';
//echo '<br/>'.$file;exit;
            if (file_exists($file)) {
                if (class_exists($file) === FALSE) {
                    require_once($file);
                    return;
                } else
                    throw new \Exception("Unable to load the requested class: $class");
            } else
                throw new \Exception("Unable to load the requested class file: $class.php");
        }
    }

    function View($viewFile, $data = array())
    {
        global $langConfig;

        $data["Language"] = $langConfig->languageClass;

        $data["Config"] = $this->configArray;

        $filePathShared = APP_PATH . DS . "Shared" . DS . 'Views' . DS . $viewFile . '.php';

        if (preg_match('/WebInterface/', PANEL)) {
            $view = explode("/", $viewFile);

            $filePath = APP_PATH . DS . PANEL . DS . $view[0] . DS . 'Views' . DS . $view[1] . '.php';

        } else {
            $filePath = APP_PATH . DS . PANEL . DS . 'Views' . DS . $viewFile . '.php';
        }

        $data["HiddenMenu"] = $this->allHiddenMenu;

        //var_dump($filePath);exit;
        if (file_exists($filePath)) {
            if (!empty($data)) extract($data);

            include_once($filePath);

        } elseif (file_exists($filePathShared)) {
            if (!empty($data)) extract($data);

            include_once($filePathShared);
        } else
            throw new \Exception("Unable to load the requested view file: $viewFile.php");

        if (isset($_POST['SubmitTeaser'])) {
            if (isset($_POST['SaveClickedTimes']) && $_POST['SaveClickedTimes'] == "1") {
                $_SESSION[SessionVariables::$ConfirmationMessage] = "";
            }
        } else {
            $_SESSION[SessionVariables::$ConfirmationMessage] = "";
        }

    }

    function LoadJS($jsArray)
    {
        $this->site_js = "";
        if (count($jsArray) > 0) {
            foreach ($jsArray as $jsPath) {
                if (file_exists($jsPath)) {
                    $this->site_js .= "<script src='" . BASE_URL . "/" . $jsPath . "'></script>\n";
                }
            }
        }
        return $this->site_js;
    }

    function TwigView($viewFile, $data = array())
    {
        global $twig;


        if (preg_match('/WebInterface/', PANEL)) {
            $view = explode("/", $viewFile);

            $filePath = APP_PATH . DS . PANEL . DS . $view[0] . DS . 'Views' . DS . $view[1] . '.twig';

        } else {
            $filePath = APP_PATH . DS . PANEL . DS . 'Views' . DS . $viewFile . '.twig';
        }


        // echo $filePath;

        $filePathShared = APP_PATH . DS . "Shared" . DS . 'Views' . DS . $viewFile . '.twig';

        $configArray = parse_ini_file(BASE_PATH . DS . "config.ini", true);

        $data["AppPath"] = APP_PATH . DS;
        $data["BASE_URL"] = BASE_URL;
        $data["SERVER"] = $_SERVER;
        $data["SESSION"] = $_SESSION;
        $data["POST"] = $_POST;
        $data["GET"] = $_GET;
        $data["COOKIE"] = $_COOKIE;
        $data["Config"] = $configArray;


        if (file_exists($filePath)) {
            $twigTemplate = $twig->loadTemplate($filePath);
            $template = $twigTemplate->render($data);
            echo $template;
        } elseif (file_exists($filePathShared)) {
            $twigTemplate = $twig->loadTemplate($filePathShared);
            $template = $twigTemplate->render($data);
            echo $template;
        } else
            throw new \Exception("Unable to load the requested view file: $viewFile.php");

        if (isset($_POST['SubmitTeaser'])) {
            if (isset($_POST['SaveClickedTimes']) && $_POST['SaveClickedTimes'] == "1") {
                $_SESSION[SessionVariables::$ConfirmationMessage] = "";
            }
        } else {
            $_SESSION[SessionVariables::$ConfirmationMessage] = "";
        }


    }

    /*Generic File*/
    function File($path)
    {
        if (file_exists($path)) {
            include_once($path);
        } else
            throw new \Exception("Unable to load the requested file");
    }

}