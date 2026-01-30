<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Liste des messages à afficher à l'utilisateur.
     * @var array
     */
    protected $messages = [];

    /**
     * Titre de la page.
     *
     * @var string
     */
    protected $title = 'Home';

    /**
     * Préfixe ajouté automatiquement au titre de la page.
     *
     * @var string
     */
    protected $title_prefix = 'Template';

    /**
     * Chemin de navigation pour la gestion des breadcrumbs.
     *
     * @var array
     */
    protected $breadcrumb = [];

    protected $menu = 'accueil';
    protected $isAdmin = false;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
    }

    public function render($vue = null, $datas = [], $admin = true)
    {

        if (session()->has('messages')) {
            $this->messages = session()->getFlashdata('messages');
        }
        // Récupérer les données flashdata
        $flashData = session()->getFlashdata('data');

        if ($flashData) {
            $datas = array_merge($datas, $flashData);
        }

        // Récupérer le titre du site depuis les settings
        $siteName = setting('App.siteName') ?? $this->title_prefix;

        // Préparer les données globales
        $datas['title'] = sprintf('%s : %s',$siteName,$datas['title'] ??  $this->title);
        $datas['menus'] = $this->loadMenu($this->isAdmin);
        $datas['breadcrumb'] = $this->breadcrumb;
        $datas['menu'] = $this->menu;
        $datas['messages'] = $this->messages;

        if ($vue === null) {
            return '';
        }

        return view($vue, $datas);
    }

    protected function loadMenu($admin): array
    {

        $filename = APPPATH . "Config/Menus/";
        $filename .= $admin ? "admin.json" : "site.json";

        if (!file_exists($filename)) {
            log_message('error', "Menu JSON file not found: $filename");
            return [];
        }

        $json = file_get_contents($filename);
        $menu = json_decode($json, true);

        if (!is_array($menu)) {
            log_message('error', "Invalid JSON in menu file: $filename");
            return [];
        }

        return $menu;
    }

    public function redirect(string $url, array $data = [])
    {
        if (!empty($this->messages)) {
            session()->setFlashdata('messages', $this->messages);
        }
        if (!empty($data)) {
            session()->setFlashdata('data', $data);
        }

        return redirect()->to($url);
    }

    /**
     * Ajoute un message de succès.
     *
     * @param string $txt Message à afficher.
     * @return void
     */
    public function success($txt)
    {
        log_message('debug', $txt);
        $this->messages[] = ['txt' => $txt, 'class' => 'alert-success', 'toast' => 'success'];
    }

    /**
     * Ajoute un message informatif.
     *
     * @param string $txt Message à afficher.
     * @return void
     */
    public function message($txt)
    {
        log_message('debug', $txt);
        $this->messages[] = ['txt' => $txt, 'class' => 'alert-info', 'toast' => 'info'];
    }

    /**
     * Ajoute un message d'avertissement.
     *
     * @param string $txt Message à afficher.
     * @return void
     */
    public function warning($txt)
    {
        log_message('debug', $txt);
        $this->messages[] = ['txt' => $txt, 'class' => 'alert-warning', 'toast' => 'warning'];
    }

    /**
     * Ajoute un message d'erreur.
     *
     * @param string $txt Message à afficher.
     * @return void
     */
    public function error($txt)
    {
        log_message('error', $txt);
        $this->messages[] = ['txt' => $txt, 'class' => 'alert-danger', 'toast' => 'error'];
    }

    /**
     * Ajoute un élément au fil d'Ariane.
     *
     * @param string $text Texte de l'élément.
     * @param string|array $url URL ou segments de l'élément.
     * @param string $info Informations supplémentaires.
     * @return void
     */
    protected function addBreadcrumb($text, $url = null, $info = '')
    {
        if ($this->breadcrumb === null) {
            $this->breadcrumb = [];
        }
        $this->breadcrumb[] = [
            'text' => $text,
            'url' => (is_array($url) ? '/' . implode('/', $url) : $url),
            'info' => $info,
        ];
    }
}
