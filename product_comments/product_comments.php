<?php

use PhpParser\Node\Expr\Assign;

class Product_Comments extends Module
{
  public function __construct()
  {
    $this->name = "product_comments";
    $this->author = "Rafael Pimenta";
    $this->ps_versions_compliancy = ["min" => "1.7", "max" => "1.7.99"];
    $this->version = "1.0";
    $this->need_instance = 0;
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = "Comentarios de Produtos";
    $this->description = "Comentarios dos Produtos";
    $this->confirmUninstall = "Vais desinstalar este modulo?";
  }

  public function install()
  {
    return parent::install() &&
      $this->createDatabase() &&
      $this->registerHook('displayFooterProduct') &&
      $this->installTab();
  }

  public function uninstall()
  {
    return parent::uninstall() &&
      $this->unregisterHook('displayFooterProduct') &&
      $this->dropDatabase();
  }

  public function createDatabase()
  {
    $sql = "CREATE TABLE IF NOT EXISTS psdev_productcomments (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `username` varchar(255) DEFAULT NULL,
      `comment` varchar(255) DEFAULT NULL,
      `product_id` varchar(255) DEFAULT NULL,
      `visible` BOOLEAN DEFAULT FALSE,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";


    Db::getInstance()->execute($sql);
    return true;
  }

  public function installTab(): bool
  {
    // Criar uma nova instância de Tab
    $tab = new Tab();

    // Definir a classe da aba (o nome do controlador admin associado)
    $tab->class_name = "ProductComments";

    // Definir o nome do módulo ao qual a aba pertence
    $tab->module = $this->name;

    // Definir o ID do pai da aba (no caso, uma aba pai padrão)
    $tab->id_parent = (int)Tab::getIdFromClassName('AdminCatalog');

    // Definir o ícone da aba
    $tab->icon = 'settings_applications';

    // Obter todos os idiomas disponíveis na loja
    $languages = Language::getLanguages();
    foreach ($languages as $lang) {
      // Definir o nome da aba para cada idioma
      $tab->name[$lang['id_lang']] = $this->l('Comentarios Produtos');
    }

    try {
      // Salvar a aba no banco de dados
      $tab->save();
      return true; // Retornar verdadeiro se a operação for bem-sucedida
    } catch (Exception $e) {
      echo $e->getMessage(); // Se ocorrer uma exceção, exibir uma mensagem de erro
    }

    return false; // Retornar falso se algo der errado durante a instalação da aba
  }

  public function uninstallTab()
  {
    // Obtém o ID da aba com base na classe associada (AdminTest)
    $idTab = (int)Tab::getIdFromClassName('ProductComments');

    // Verifica se o ID da aba foi obtido com sucesso
    if ($idTab) {
      // Cria uma nova instância de Tab
      $tab = new Tab();

      try {
        // Tenta excluir a aba do banco de dados
        $tab->delete();
      } catch (Exception $e) {
        echo $e->getMessage(); // Se ocorrer uma exceção, exibe uma mensagem de erro
      }
    }
    // Se não houver ID de aba, não há nada a ser desinstalado
  }

  public function dropDatabase()
  {
    $sql = "DROP TABLE IF EXISTS psdev_product_comments";
    Db::getInstance()->execute($sql);
    return true;
  }


  public function hookHeader()
  {
    $this->context->controller->addCSS($this->_path . "views/css/style.css");
    $this->context->controller->addJS($this->_path . "views/js/submit_comment.js");
  }

  public function hookDisplayFooterProduct($params)
  {
    $id = Tools::getValue('id_product');
    $this->smarty->assign($this->handlePostComment());
    $comments = $this->loadComments($id);



    $this->context->smarty->assign(array(
      "id_product" => $id,
      "comments" => $comments
    ));

    return  $this->context->smarty->fetch('module:product_comments/views/templates/hooks/comment_front.tpl');
  }

  public function handlePostComment()
  {
    if (Tools::isSubmit("addComment")) {
      $id_product = (int)Tools::getValue('product_id');
      $username = Tools::getValue('username');
      $comment = Tools::getValue('comment');

      $sql = "INSERT INTO psdev_productcomments (`product_id`, `username`, `comment`, `visible`)
      VALUES ('$id_product', '$username', '$comment', FALSE)";

      Db::getInstance()->execute($sql);
    }
  }

  public function loadComments($id)
  {
    $id_product = (int)$id;
    $sql = "SELECT * FROM psdev_productcomments WHERE product_id = " . $id_product;
    $result = Db::getInstance()->executeS($sql);

    $comments = array();
    foreach ($result as $row) {
      $comments[] = array(
        "id" => $row['id'],
        "product_id" => $row['product_id'],
        "username" => $row['username'],
        "comment" => $row['comment'],
        "visible" => $row['visible'],
      );
    }

    return $comments;
  }
}
