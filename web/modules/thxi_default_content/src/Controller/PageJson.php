<?php
namespace Drupal\thxi_default_content\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \GuzzleHttp\Exception\RequestException;
use Drupal\node\NodeInterface;
use Drupal\Core\Url;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

class PageJson extends ControllerBase {

    
    /**
       * PageJson Controller constructor.
       *
       * @param \Drupal\Core\Config $config
       *   Entity storage for node entities.
       */
    public function __construct() {
        $this->config = \Drupal::config('system.site');
    }

    /**
       * Get configuration or state setting for this Fpx integration module.
       *
       * @param string $name this module's config or state.
       *
       * @return mixed
       */
    public function checkPage() {
        $node = $this->getPath();
        $serializer = \Drupal::service('serializer');
        $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
        print_r($data); 
        exit;
    }

    protected function getPath(){
        $current_path = \Drupal::service('path.current')->getPath();
        $url_object = \Drupal::service('path.validator')->getUrlIfValid($current_path);
        $pathexplode = explode("/",$current_path);
        $node = Node::load($pathexplode[2]);
        return $node;
    }
    
      /**
       * Get configuration or state setting.
       *
       * @param string $name this module's config or state.
       *
       * @return mixed
       */
        protected function getConfig($name) {
            return $this->config->get($name);
        }

     /**
       * Check Access
       *
       * @param array $node
       * @param array account
       *
       * @return string
       */
        public function checkAccess() {
            $node = $this->getPath();
            $site_api = $this->getConfig('siteapikey');
            return AccessResult::allowedif(!empty($node) && ($node->bundle() === 'page') && ($site_api != 'No API Key yet' || !empty($site_api) ));
        }

}