<?php

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class ControlModules extends Module implements WidgetInterface
{
  public function __construct()
  {
    $this->name = "controlmodules";
    $this->tab = "front_office_features";
    $this->version = "1.0";
    $this->author = "Rafael Pimenta";
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);

    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = "Modulo Testar Controladores";
    $this->description = "Modulo para aprender sobre contralodres PS";
  }

  public function renderWidget($hookName, array $configuration)
  {

    echo $this->context->link->getModuleLink($this->name, "test");
  }

  public function getWidgetVariables($hookName, array $configuration)
  {
    return true;
  }
}
