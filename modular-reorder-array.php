<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Grav\Common\Page\Page;

/**
 * Class ModularReorderArrayPlugin
 * @package Grav\Plugin
 */
class ModularReorderArrayPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Only proceed if we are in the admin plugin
        if (! $this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onAdminControllerInit' => ['onAdminControllerInit', 0],
            'onAdminSave' => ['onAdminSave', 0]
        ]);
    }

    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function onAdminSave($event){

      $page = $event['object'];

      if (!$page instanceof Page) {
          return false;
      }

      $query_str = 'form/fields/tabs/fields/content/' .
                    'fields/header.content.order.custom';
      $custom_order_field = $page->blueprints()->get($query_str);
      $custom_order_field = (array)$custom_order_field;

      if( sizeOf($custom_order_field) < 1 ){
        return false;
      }

      if( sizeOf($page->children()) < 1 ){
        return false;
      }

      $header = (array)$page->header();
      if( !$header['content'] ){
        $header['content'] = [];
      }
      if( !$header['content']['items'] ){
        $header['content']['items'] = '@self.modular';
      }
      if( !$header['content']['order'] ){
        $header['content']['order'] = [
          'by' => 'default',
          'dir' => 'asc',
          'custom' => []
        ];
      }

      foreach( $page->children()->modular() as $modularChild ){
        $this->grav['log']->info('here', (array)$modularChild);
        if(! in_array($modularChild->slug(), $header['content']['order']['custom']) ){
          array_push($header['content']['order']['custom'], $modularChild->slug());
        }
      }
      $page->header($header);
      $page->save();
    }

    public function onAdminControllerInit($event){

      $data = $event['controller']->data;
      if( $data and $data['header'] and $data['header']['content'] ){
        $orig_order = $data['header']['content']['order']['custom'];
        if( $orig_order and $this->isAssoc($orig_order) ){
          $index = 0;
          $new_arr = [];
          foreach ( $orig_order as $key => $value){
            $new_arr[$index] = $value;
            $index += 1;
          }
          $event['controller']->data['header']['content']['order']['custom'] = $new_arr;
        }
      }
    }
}
