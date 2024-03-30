<?php

if (!defined("_PS_VERSION_")) {
  exit;
}


class AdminControllerExample extends Module
{
  private $databaseName = "testcomment";

  public function __construct()
  {
    $this->name = "admincontrollerexample";
    $this->tab = "front_office_features";
    $this->version = "1.0";
    $this->author = "Rafael Pimenta";
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);

    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = "Modulo Testar Controladores Admin";
    $this->description = "Modulo para aprender sobre contralodres backoffice PS";
  }

  public function install(): Bool
  {
    return
      $this->sqlInstall() &&
      $this->installTab() &&
      parent::install();
  }

  public function uninstall(): Bool
  {
    return parent::uninstall() &&
      $this->sqlUninstall() &&
      $this->uninstallTab();
  }

  public function sqlInstall(): Bool
  {
    $sqlCreate = "CREATE TABLE IF NOT EXISTS`" . _DB_PREFIX_ . $this->databaseName . "` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` varchar(255) DEFAULT NULL,
      `comment` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

    return Db::getInstance()->execute($sqlCreate);
  }

  public function sqlUninstall(): Bool
  {
    $sql = "DROP TABLE " . _DB_PREFIX_ . $this->databaseName;
    return Db::getInstance()->execute($sql);
  }


  // Adicionar controller ao backoffice
  public function installTab(): bool
  {
    // Criar uma nova instância de Tab
    $tab = new Tab();

    // Definir a classe da aba (o nome do controlador admin associado)
    $tab->class_name = "AdminTest";

    // Definir o nome do módulo ao qual a aba pertence
    $tab->module = $this->name;

    // Definir o ID do pai da aba (no caso, uma aba pai padrão)
    $tab->id_parent = (int)Tab::getIdFromClassName('DEFAULT');

    // Definir o ícone da aba
    $tab->icon = 'settings_applications';

    // Obter todos os idiomas disponíveis na loja
    $languages = Language::getLanguages();
    foreach ($languages as $lang) {
      // Definir o nome da aba para cada idioma
      $tab->name[$lang['id_lang']] = $this->l('Test Admin Controller');
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
    $idTab = (int)Tab::getIdFromClassName('AdminTest');

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
}

// 24:30;