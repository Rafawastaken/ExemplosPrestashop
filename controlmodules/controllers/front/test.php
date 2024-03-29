<?php

class ControlModulesTestModuleFrontController  extends ModuleFrontController
{

  public function initContent()
  {
    parent::initContent();
    $this->context->smarty->assign([
      "nome" => "Adriana"
    ]);
    $this->setTemplate("module:controlmodules/views/templates/front/example.tpl");
  }
}
