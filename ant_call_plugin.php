<?php
/*
 Plugin Name: Advanced User callbacks
 Description: Прием Звонков
 Version: 1.0
 Author: Антощенко Владимир
 Author URI: http://vexell.ru/
*/
//ini_set('display_errors', '1');
//ini_set('error_reporting', E_ALL);
// Stop direct call
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

if (!class_exists('AdvUserCallback')) {
    class AdvUserCallback
    {

        ## Хранение внутренних данных
        public $data = array();

        ## Конструктор объекта
        ## Инициализация основных переменных
        function AdvUserCallback()
        {
            global $wpdb;

            ## Объявляем константу инициализации нашего плагина
            DEFINE('AdvUserCallback', true);

            ## Название файла нашего плагина
            $this->plugin_name = plugin_basename(__FILE__);

            ## URL адресс для нашего плагина
            $this->plugin_url = trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));

            ## Таблица для хранения наших звонков
            ## обязательно должна быть глобально объявлена перменная $wpdb
            $this->tbl_adv_callbacks = $wpdb->prefix . 'adv_callbacks';

            ## Функция которая исполняется при активации плагина
            register_activation_hook($this->plugin_name, array(&$this, 'activate'));

            ## Функция которая исполняется при деактивации плагина
            register_deactivation_hook($this->plugin_name, array(&$this, 'deactivate'));


            // Если мы в адм. интерфейсе
            if (is_admin()) {

                // Добавляем стили и скрипты
                add_action('wp_print_scripts', array(&$this, 'admin_load_scripts'));
                add_action('wp_print_scripts', array(&$this, 'admin_load_styles'));

                // Добавляем меню для плагина
                add_action('admin_menu', array(&$this, 'admin_generate_menu'));

            } else {
                // Добавляем стили и скрипты
                add_action('wp_print_scripts', array(&$this, 'site_load_scripts'));
                add_action('wp_print_styles', array(&$this, 'site_load_styles'));

                add_shortcode('show_callbacks', array(&$this, 'site_show_callbacks'));
            }
        }

        /**
         * Загрузка необходимых скриптов для страницы управления
         * в панели администрирования
         */
        function admin_load_scripts()
        {
            // Региестрируем скрипты
            wp_register_script('advcallbacksAdminJs', $this->plugin_url . 'js/admin-scripts.js');

            // Добавляем скрипты на страницу
            wp_enqueue_script('advcallbacksAdminJs');
        }

        /**
         * Загрузка необходимых стилей для страницы управления
         * в панели администрирования
         */
        function admin_load_styles()
        {


            // Регистрируем стили
            wp_register_style('advcallbacksAdminCss', $this->plugin_url . 'css/admin-style.css');
            // Подключаем стили
            wp_enqueue_style('advcallbacksAdminCss');

        }

        /**
         * /**
         * Генерируем меню
         */
        function admin_generate_menu()
        {
            // Добавляем основной раздел меню
            add_menu_page('Добро пожаловать в модуль управления Звонками', 'Звонки', 'manage_options', 'edit-callbacks', array(&$this, 'admin_edit_callbacks'));
            // Добавляем дополнительный раздел
            add_submenu_page('edit-callbacks', 'Управление содержимым', 'О плагине', 'manage_options', 'plugin_info', array(&$this, 'admin_plugin_info'));
        }

        /**
         * Выводим список звонков для редактирования
         */
        public function admin_edit_callbacks()
        {
            global $wpdb;

            $action = isset($_GET['action']) ? $_GET['action'] : null;

            switch ($action) {


                case 'delete':

                    // Удаляем существующую запись
                    $wpdb->query("DELETE FROM `" . $this->tbl_adv_callbacks . "` WHERE `ID` = '" . (int)$_GET['id'] . "'");

                    // Показываем список звонков
                    $this->admin_show_callbacks();
                    break;

                case 'order_status':

                    // Меняем статус

                    $wpdb->query(" UPDATE `" . $this->tbl_adv_callbacks . "` SET callback_order_status=1 WHERE `ID` = '" . (int)$_GET['id'] . "' ");

                    $this->admin_show_callbacks();
                    break;

                case 'order_back':

                    // Отменить статус
                    $wpdb->query(" UPDATE `" . $this->tbl_adv_callbacks . "` SET callback_order_status=0 WHERE `ID` = '" . (int)$_GET['id'] . "' ");

                    $this->admin_show_callbacks();
                    break;

                default:
                    $this->admin_show_callbacks();
            }

        }

        /**
         * Функция для отображения списка звонков в адм. панели
         */
        private function admin_show_callbacks()
        {
            global $wpdb;

            // Получаем данные из БД
            $this->data['callbacks'] = $wpdb->get_results("SELECT * FROM `" . $this->tbl_adv_callbacks . "`", ARRAY_A);

            // Подключаем страницу для отображения результатов
            include_once('view_callbacks.php');
        }

        /**
         * Показываем статическую страницу
         */
        public function admin_plugin_info()
        {
            include_once('plugin_info.php');
        }

        function site_load_scripts()
        {

            wp_register_script('advcallbacksJs', $this->plugin_url . 'js/site-scripts.js');
            wp_enqueue_script('advcallbacksJs');
        }

        function site_load_styles()
        {

            wp_register_style('advcallbacksCss', $this->plugin_url . 'css/site-style.css');
            wp_enqueue_style('advcallbacksCss');
        }

        /**
         * Список звонков на сайте
         */
        public function site_show_callbacks($atts, $content = null)
        {
            global $wpdb;

            if (isset($_POST['action']) && $_POST['action'] == 'add-callback') {
                $this->add_user_callback();
            }

            ## Включаем буферизацию вывода
            ob_start();
            include_once('site_callbacks.php');
            ## Получаем данные
            $output = ob_get_contents();
            ## Отключаем буферизацию
            ob_end_clean();

            return $output;
        }

        private function add_user_callback()
        {
            global $wpdb;


            $inputData = array(
                'callback_user_name' => strip_tags($_POST['callback_user_name']),
                'callback_user_phone' => strip_tags($_POST['callback_user_phone']),
            );


            // Добавляем новый звонок на сайт
            $wpdb->insert($this->tbl_adv_callbacks, $inputData);
        }


        /**
         * Активация плагина
         */
        function activate()
        {
            global $wpdb;

            require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

            $table = $this->tbl_adv_callbacks;

            ## Определение версии mysql
            if (version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
                if (!empty($wpdb->charset))
                    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
                if (!empty($wpdb->collate))
                    $charset_collate .= " COLLATE $wpdb->collate";
            }

            ## Структура нашей таблицы для звонков
            $sql_table_adv_callbacks = "
				CREATE TABLE `" . $wpdb->prefix . "adv_callbacks` (
					`ID` INT(10) UNSIGNED NULL AUTO_INCREMENT,
					`callback_user_name` VARCHAR(200) NULL,
					`callback_user_phone` VARCHAR(200) NULL,
					`callback_order_status` TINYINT NOT NULL default 0,
					`callback_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`ID`)
				)" . $charset_collate . ";";

            ## Проверка на существование таблицы
            if ($wpdb->get_var("show tables like '" . $table . "'") != $table) {
                dbDelta($sql_table_adv_callbacks);
            }

        }

        function deactivate()
        {
            return true;
        }


    }
}

global $callbacks;
$callbacks = new AdvUserCallback();