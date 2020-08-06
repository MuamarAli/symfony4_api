<?php
/**
 * Created by PhpStorm.
 * User: muamar-ali
 * Date: 8/6/20
 * Time: 9:57 PM
 */

namespace App\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DefaultController
 *
 * @package App\APIBundle\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @return JsonResponse
     */
    public function indexAction()
    {
        return $this->json(['Connected']);
    }
}