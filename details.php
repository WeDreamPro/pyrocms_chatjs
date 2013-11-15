<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_Chatjs extends Module {

    public $version = '1.0';
    public $st_namespace = "chatjs";
    public $st_name = "chatjs";
    public $st_slug = "chatjs";

    public function info() {
        return array(
            'name' => array(
                'en' => 'Chat JS',
                'es' => 'Chat JS'
            ),
            'description' => array(
                'en' => 'Chat system builded with Javascript',
                'es' => 'Sistema de Chat construido con Javascript'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content',
            'sections' => array(
                'chat' => array(
                    'name' => 'chatjs:chat',
                    'uri' => 'admin/chatjs'
                )
            )
        );
    }

    public function install() {
        /** To install the system we need to create the messages stream * */
        $this->streams->streams->add_stream(
                $this->st_name, $this->st_slug, $this->st_namespace, 'chatjs_'
        );
        /** Lets create some fields for the messages Stream * */
        $fields = array(
            array(
                'name' => 'User',
                'slug' => 'user_chat',
                'namespace' => $this->st_namespace,
                'type' => 'user',
                'assign' => $this->st_slug,
                'required' => false
            ),
            array(
                'name' => 'Message',
                'slug' => 'message',
                'namespace' => $this->st_namespace,
                'type' => 'text',
                'assign' => $this->st_slug,
                'required' => true
            ),
            array(
                'name' => 'is Guest',
                'slug' => 'is_gest',
                'namespace' => $this->st_namespace,
                'type' => 'integer',
                'extra' => array('default_value' => '1'),
                'assign' => $this->st_slug,
                'required' => true
            ),
            array(
                'name' => 'Guest Name',
                'slug' => 'guest_name',
                'namespace' => $this->st_namespace,
                'type' => 'text',
                'assign' => $this->st_slug,
                'required' => false
            )
        );
        $this->streams->fields->add_fields($fields);
        /** Now lets create a banned ips stream to be able to ban ips * */
        $this->streams->streams->add_stream(
                'banned_ips', 'banned_ips', $this->st_namespace, 'chatjs_'
        );
        /** now lets add some fields to the banned ip table * */
        $fields_ip = array(
            array(
                'name' => 'IP',
                'slug' => 'ip_chat',
                'namespace' => $this->st_namespace,
                'type' => 'text',
                'assign' => 'banned_ips',
                'required' => true
            )
        );
        $this->streams->fields->add_fields($fields_ip);
        /** Now lets create a banned users stream to be able to ban users **/
        $this->streams->streams->add_stream(
                'banned_users', 'banned_users', $this->st_namespace, 'chatjs_'
        );
        /** now lets add some fields to the banned user table * */
        $fields_u = array(
            array(
                'name' => 'User',
                'slug' => 'user_b',
                'namespace' => $this->st_namespace,
                'type' => 'user',
                'assign' => 'banned_users',
                'required' => true
            )
        );
        $this->streams->fields->add_fields($fields_u);
        return true;
    }

    public function uninstall() {
        $this->streams->utilities->remove_namespace($this->st_namespace);
        return true;
    }

    public function upgrade($old_version) {
        return true;
    }

    public function help() {
        return "Please see te website documentation";
    }

}