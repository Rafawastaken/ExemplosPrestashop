<?php

if (!defined('_PS_VERSION_')) {
  exit;
}


class MyNewModule extends Module
{
  public function __construct()
  {
    $this->name = "mynewmodule";
    $this->tab = "front_office_features";
    $this->version = "1.0";
    $this->author = "Rafael Pimenta";
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);

    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = "My new module";
    $this->description = "Modulo conceito de config e renderização de Hook";
  }

  public function getContent()
  {

    $outputMessage = "";
    if (Tools::isSubmit("submit" . $this->name)) {
      try {
        $novoTextoHeader = Tools::getValue('textoHeader');
        $linkHeader = Tools::getValue('linkHeader');

        if ($novoTextoHeader and !empty($novoTextoHeader) and Validate::isGenericName($novoTextoHeader)) {
          Configuration::updateValue('TEXTO_HEADER', $novoTextoHeader);
        }

        Configuration::updateValue("LINK_HEADER", $linkHeader);

        $outputMessage = $outputMessage . $this->displayConfirmation($this->trans('Texto header atualizado'));
      } catch (Exception $exception) {
        $outputMessage = $outputMessage . $this->displayError($this->trans($exception));
      }
    }
    return $outputMessage . $this->displayForm();
  }

  public function displayForm()
  {
    $fields[0]['form'] = [
      'legend' => [
        "title" => $this->trans('Configuração Novo Módulo'),
      ],

      "input" => [
        [
          "type" => "text",
          "label" => "Texto a ser introduzido",
          "name" => "textoHeader",
          "size" => 30,
          "required" => true,
          "placeholder" => "Texto a ser introduzido no header",
          "class" => "form-control"
        ],
        [
          "type" => "text",
          "label" => "Link do Header",
          "name" => "linkHeader",
          "size" => 254,
          "required" => false,
          "placeholder" => "Inserir link do Header",
          "class" => "form-control"
        ]
      ],

      "submit" => [
        "title" => $this->trans('Guardar'),
        'class' => "form-cotrol btn btn-primary pull-right"
      ]
    ];

    $helper = new HelperForm();
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

    $helper->title = "Configuração do modulo: " . $this->displayName;
    $helper->show_toolbar = true;

    $helper->submit_action = 'submit' . $this->name;

    $helper->fields_value['textoHeader'] = Configuration::get('TEXTO_HEADER');
    $helper->fields_value['linkHeader'] = Configuration::get('LINK_HEADER');
    return $helper->generateForm($fields);
  }

  public function hookDisplayTop()
  {
    $templateFile = "module:mynewmodule/views/templates/hook/textoTop.tpl";
    $textoHeader = Configuration::get('TEXTO_HEADER');
    $linkHeader = Configuration::get('LINK_HEADER');

    if (empty($textoHeader)) {
      return;
    }

    $this->context->smarty->assign([
      "textoHeader" => $textoHeader,
      "linkHeader" => $linkHeader
    ]);

    return $this->fetch($templateFile);
  }
}
