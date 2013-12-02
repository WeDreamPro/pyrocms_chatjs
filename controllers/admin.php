<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This is a sample module for PyroCMS
 *
 * @author  Jose Fonseca - We Dream Pro Dev Team
 * @website http://wedreampro.com
 * @package PyroCMS
 * @subpackage ChatJS Module
 */
class Admin extends Admin_Controller {

    protected $section = 'chat';

    public function __construct() {
        parent::__construct();
        $this->lang->load('chatjs');
        $this->template
                ->append_js('module::admin.js')
                ->append_js('module::jquery.cssemoticons.min.js')
                ->append_css('module::admin.css')
                ->append_css('module::jquery.cssemoticons.css');
    }

    public function index() {
        $this->type->add_misc('<script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.0.0/handlebars.min.js"></script>');
        $this->type->add_misc('<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.1.0/jquery.timeago.min.js"></script>');
        $params = array(
            'stream' => 'active_users',
            'namespace' => 'chatjs',
            'where' => "user_id_c = '" . $this->current_user->id . "'"
        );
        $entries = $this->streams->entries->get_entries($params);
        if ($entries['total'] == 0) {
            $entry_data = array(
                'user_id_c' => $this->current_user->id,
                'user_b_ip' => $_SERVER['REMOTE_ADDR'],
                'last_activity_u_a' => strtotime(date('Y-m-d H:i:s')),
            );
            $this->streams->entries->insert_entry($entry_data, 'active_users', 'chatjs');
        }
        $this->template
                ->title($this->module_details['name'])
                ->set('user', $this->current_user)
                ->build('admin/body');
    }

}
