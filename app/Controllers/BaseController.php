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
class BaseController extends Controller
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
     * @var array
     */
    protected $helpers = ['auth', 'number'];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        session();

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    public function fetchMenu()
    {
        $file = "public/menu/menu.json";
        $data = file_get_contents(ROOTPATH . $file);

        $data = json_decode($data, false);

        $titles = [];
        $parents = [];
        $childs = [];

        foreach ($data as $resource) {
            if ($resource->parent == 0 && $resource->level == 'title') {
                $titles[] = $resource;
            }

            if ($resource->parent != 0 && $resource->level == 'parent') {
                $parents[] = $resource;
            }

            if ($resource->parent != 0 && $resource->level == 'child') {
                $childs[] = $resource;
            }
        }

        $menu = "";
        foreach ($titles as $title) {
            $menu .= '<li class="xn-title">' . $title->nama . '</li>';

            foreach ($parents as $parent) {
                if ($title->id == $parent->parent) {
                    if ($parent->status) {
                        $menu .= '<li class="xn-openable"><a href="' . $parent->pages . '"><span class="' . $parent->icon . '"></span><span class="xn-text">' . $parent->nama . '</span></a><ul>';
                    } else {
                        $menu .= '<li class="xn-openable"><a href="/maintenance"><span class="' . $parent->icon . '"></span><span class="xn-text">' . $parent->nama . '</span></a><ul>';
                    }

                    foreach ($childs as $child) {
                        if ($parent->id == $child->parent) {
                            if ($child->status) {
                                $menu .= '<li><a href="' . $child->pages . '"><span class="' . $child->icon . '"></span><span class="xn-text">' . $child->nama . '</span></a></li>';
                            } else {
                                $menu .= '<li><a href="/maintenance"><span class="' . $child->icon . '"></span><span class="xn-text">' . $child->nama . '</span></a></li>';
                            }
                        }
                    }

                    foreach ($parents as $subParent) {
                        if ($parent->id == $subParent->parent) {
                            if ($subParent->status) {
                                $menu .= '<li class="xn-openable"><a href="#"><span class="xn-text">' . $subParent->nama . '</span></a><ul>';
                                foreach ($childs as $child) {
                                    if ($subParent->id == $child->parent) {
                                        if ($child->status) {
                                            $menu .= '<li><a href="' . $child->pages . '"><span class="xn-text">' . $child->nama . '</span></a></li>';
                                        } else {
                                            $menu .= '<li><a href="/maintenance"><span class="xn-text">' . $child->nama . '</span></a></li>';
                                        }
                                    }
                                }
                                $menu .= '</ul></li>';
                            }
                        }
                    }
                    $menu .= '</ul></li>';
                }
            }
            foreach ($childs as $child) {
                if ($title->id == $child->parent) {
                    if ($child->status) {
                        $menu .= '<li><a href="' . $child->pages . '"><span class="' . $child->icon . '"></span><span class="xn-text">' . $child->nama . '</span></a></li>';
                    } else {
                        $menu .= '<li><a href="/maintenance"><span class="' . $child->icon . '"></span><span class="xn-text">' . $child->nama . '</span></a></li>';
                    }
                }
            }
        }

        return $menu;
    }
}
