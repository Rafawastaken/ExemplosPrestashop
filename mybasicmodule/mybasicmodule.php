<?php

/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_'))
  exit;

class MyBasicModule extends Module implements WidgetInterface
{
  public function __construct()
  {
    $this->name = "mybasicmodule";
    $this->tab = "front_office_features";
    $this->version = "1.0";
    $this->author = "Rafael Pimenta";
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = "My Basic Module";
    $this->description = "First module of the course";
    $this->confirmUninstall = "Please dont db :c";
  }


  public function install(): Bool
  {
    return parent::install() &&
      $this->registerHook("registerGDPRConsent");
  }


  public function uninstall()
  {
    return parent::uninstall();
  }

  // Criar um hook (fim site cato inf direito)
  public function hookdisplayFooter()
  {

    $cart_id = $this->context->cart->id;

    // passar variaveis
    $this->context->smarty->assign(array(
      "nome" => "Rafael Pimenta",
      "cart_id" => $cart_id
    ));

    return $this->display(__FILE__, 'views/templates/hook/footer.tpl');

    // return $this->fetch($this->templateFile, $this->getCacheId('mybasicmodule'));
    // $this->getCacheId('nome') -> Adicioinar para lidar com cache corretamente
    // Necessario definir $this->templateFile
  }

  public function renderWidget($hookName, array $configuration)
  {
    // Caso modulo esteja render neste hook
    if ($hookName === 'displayFooter') {
      return 'Este hook foi condicionado';
    }

    // Ficheiro de template
    $templateFile = 'module:mybasicmodule/views/templates/hook/widget.tpl';

    // Associar valores de getWidgetVariables
    $this->context->smarty->assign($this->getWidgetVariables($hookName, $configuration));

    return $this->fetch($templateFile, $this->getCacheId($this->name));
  }


  public function getWidgetVariables($hookName, array $configuration)
  {
    $cart_id = $this->context->cart->id;

    return [
      "nome" => "rafael",
      "cart_id" => $cart_id
    ];
  }


  // # Display content personalizado
  // public function getContent()
  // {
  //   $message = null;
  //   $form_subm = false;

  //   if (Tools::isSubmit('submit_form')) {
  //     $form_subm = true;
  //     if (Tools::getValue("nome_input")) {
  //       Configuration::updateValue("VALOR_INPUT", Tools::getValue("nome_input"));
  //       $message = "Form saved correctly";
  //     }
  //   }

  //   $valor_input = Configuration::get('VALOR_INPUT');

  //   $this->context->smarty->assign([
  //     "valor_input" => $valor_input,
  //     "message" => $message,
  //     "form_subm" => $form_subm
  //   ]);

  //   return $this->fetch("module:mybasicmodule/views/templates/admin/configuration_manual.tpl");
  // }




  public function getContent()  // HelperForm
  {
    $output = "";
    if (Tools::isSubmit('submitmybasicmodule')) {
      $valor_input = Tools::getValue("valor_input");

      // Flash mensagem
      if ($valor_input && !empty($valor_input) && Validate::isGenericName($valor_input)) {
        Configuration::updateValue("VALOR_INPUT", $valor_input);
        $output = $output . $this->displayConfirmation($this->trans('From submitted successfully.'));
      } else {
        $output = $output . $this->displayError($this->trans('Something went wrong.'));
      }
    }

    return $output . $this->displayForm();
  }

  public function displayForm()
  {
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

    // Form inputs
    $fields[0]['form'] = [
      'legend' => [
        "title" => $this->trans('Ratting Setting')
      ],

      "input" => [
        [
          'type' => "text",
          'label' => $this->l('Valor Input: '),
          'name' => "valor_input",
          'size' => 20,
          'required' => true
        ],
      ],

      "submit" => [
        "title" => $this->trans('Save Changes'),
        "class" => "btn btn-primary pull-right"
      ]
    ];



    // Initialize helper form
    $helper = new HelperForm();
    // Module, token and currentIndex
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

    // Language
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;

    // Title and toolbar
    $helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit' . $this->name;
    $helper->toolbar_btn = array(
      'save' =>
      array(
        'desc' => $this->l('Save'),
        'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
          '&token=' . Tools::getAdminTokenLite('AdminModules'),
      ),
      'back' => array(
        'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
        'desc' => $this->l('Back to list')
      )
    );

    // Gerar form
    $helper->fields_value['valor_input'] = Configuration::get('VALOR_INPUT');
    return $helper->generateForm($fields);
  }
}
