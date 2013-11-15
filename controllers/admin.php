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
                ->append_css('module::admin.css');
    }

    public function index() {
        $this->template
                ->title($this->module_details['name'])
                ->build('admin/body');
    }

}
