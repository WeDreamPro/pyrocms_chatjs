<?php

/**
 * Description of chatjs
 *
 * @author rdsone
 */
class chatjs extends Public_Controller {

    public $namespace = "chatjs";

    public function __construct() {
        parent::__construct();
        $this->lang->load('chatjs');
    }
    /**
     * Get the active users
     */
    public function get_active_users() {
        /** check for invalid users, users that have not been active since 1 hour ago **/
        $datetime = strtotime('-5 minutes',strtotime(date('Y-m-d H:i:s')));
        $params_d = array(
            'stream' => 'active_users',
            'namespace' => $this->namespace,
            'where' => "last_activity_u_a < '" .$datetime."'"
        );
        $entries_d = $this->streams->entries->get_entries($params_d);
        foreach($entries_d['entries'] as $e_d){
            $this->streams->entries->delete_entry($e_d['id'], 'active_users', $this->namespace);
        }
        $params = array(
            'stream' => 'active_users',
            'namespace' => $this->namespace
        );
        $entries = $this->streams->entries->get_entries($params);
        $users = array();
        foreach ($entries['entries'] as $e) {
            $users[] = array(
                'user_id' => $e['created_by']['user_id'],
                'user' => $e['created_by']['display_name'],
                'gest_name' => (!empty($e['user_nick_c'])) ? $e['user_nick_c'] : false,
                'isUser' => (!empty($e['created_by']['user_id'])) ? true : false,
                'last_activity' => date('Y-m-d H:i:s', $e['last_activity_u_a']),
                'user_ip' => $e['user_b_ip']
            );
        }
        echo json_encode($users);
    }
    /**
     * get the messages
     */
    public function get_messages() {
        session_write_close();
        $t = strtotime($this->input->get('last_time'));
        $i = 0;
        while ($i < 10) {
            $params = array(
                'stream' => 'chatjs',
                'namespace' => $this->namespace,
                'where' => "`chat_datetime` > '" . $t . "'",
                'order_by' => 'chat_datetime',
                'sort' => 'ASC',
                'limit' => 200
            );
            $entries = $this->streams->entries->get_entries($params);
            if ($entries['total'] > 0) {
                $dato = array();
                foreach ($entries['entries'] as $e) {
                    if ($e['is_gest']) {
                        $user = $e['guest_name'];
                        $avatar = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAEBAQEBAQEBAQEBAQECAgMCAgICAgQDAwIDBQQFBQUEBAQFBgcGBQUHBgQEBgkGBwgICAgIBQYJCgkICgcICAj/2wBDAQEBAQICAgQCAgQIBQQFCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAj/wAARCAAwADADAREAAhEBAxEB/8QAGwAAAgIDAQAAAAAAAAAAAAAAAAoGCQIEBwX/xAAqEAAABQMDAgYDAQAAAAAAAAABAgMEBQAGEQcIEkFRCRQhMUKBImFicf/EABsBAQACAwEBAAAAAAAAAAAAAAAHCAECBQME/8QALBEAAQMCAwYGAwEAAAAAAAAAAQACAwQRBTFBBgchUWFxEkORscHwMqHRM//aAAwDAQACEQMRAD8Arnq3CouiiLZZMnsk8Qj41k9k5BURBJu2RMsqrj34pkATG+grDnAC5NgtmMLj4Wi5Q9ZPY14vHyTJ7GyCQgCrdyiZFVLPtyTOAGL9hRrgRdpuEe0tPhcLHqtasrVFERRFKLHsq59Sb0tPTyyYpWcvCckUIqMZkHAuHKpwIQuehcjkTfEoCPSvCpqWQxulkNmtFz2C+ikpJJ5WwRC7nEADqU91sZ2HaS7OdNYqAtqNi5vUxwgQ1yXYdAPNyrnGTFSUH8kmpRyCaRRAMABjcjGEarftFtHPiMxe82Zo3QD5PM/CtvspsnT4VAGRi8h/J2pPTkOQ9eK2d8OwPSfeXpnLQVxRsXCanN0Dmty6yIB5uKdYyUqqgfkq1MOCqJGEQ4iJi8TFAabO7Rz4dMHsN2HNuhHweRTarZOnxWAskFpB+LtQfkcx8pD2+rJujTS9bt06veKWgrxgpFxFSjNQci3cpHEhy56hkMgb5FEB61ZCmqWTRtljN2uFx2KqRV0kkEroJRZzSQR1Ci1e6+dFEVx/gk6ZsLp3T3JqZKtyOELMt1Ryy5BkCP3h/LJnD+ipeaEOwiFR3vKrzHQthaf9Dx7Dj72Uq7osNEuIuqHeW3h3dwH6um/YS5ScSFyABUFqyal555I6RgExRyFESd3jp6WRtobsra1Ph2ybdve1tpunvAMAeQZqeWUOP9GSFoI9xAe9Tru1rjJQuhd5Z/R4+91Wve5hrYcRbO3zG8e7eB/VlSrUhqK0URXieCNd8dE37uBtZZUico9h4uQQKI4FRNBwqRTHfAuUx+6ivelA4wwyDIEj1A/imncxUNE9REcyGn0JB9wmQ466Cp4wr6/7UNqf1KE7sFQoFBTOf3REtj4614x8vqJt4tRJUisoxhZWRXKA5FNNdwimnntkWyo/VTJuthcIZpDkSB6A/wBUAb56hpnp4hmGuPqQB7FUPVKihZFEXbdu2uVx7ctYLR1attuEiqwUMk+YGU4Ek2KgcVm5jdORfUDfE5SG6VysbwiOupnU0nC+R5EZH7ou3s7jkmHVjKuLjbMcwcx91sm9dBdedHtx1qsrr0rvKNmynIUXMadQqcjGKCHqk5bZ5kMHtkAEhvcphAc1XDFcGqKKQx1DbddD2Ktpgm0FJiMQmpX35jUdCM/jkvc1v3EaN7aLReXZqteUdCcEzC1jSKlUkZNQA9EmzYB5nMPtkQAhfcxgAM0wnB6itkEdO2/XQdysY5tBSYdEZap9ump6AZn25pPXcnr1c+5jWe8dYLoQLHLSKhEWLAqnMkWwSDig2KbrxL6mN8jmObrVj8EwmOhpm00fG2Z5k5n7oqmbRY5JiNY+rl4eLIcgMh91XC66q4iKIiiLJBRZq4I8ZrrsnhQwVZFQyahQ7AcogIB91o+NrhZwutmPc0+JpseiF1FnTg7x4uu9eGDBlllDKKGDsJzCIiH3RkbWizRZHvc4+Jxueqxrdaooi//Z";
                    } else {
                        $user = $e['created_by']['display_name'];
                        $avatar = gravatar($e['created_by']['email'], 50, 'g', true);
                    }
                    $dato[] = array(
                        'user' => $user,
                        'message' => $e['message'],
                        'date' => date('Y-m-d H:i:s', $e['chat_datetime']),
                        'avatar' => $avatar
                    );
                    $last_date = date('Y-m-d H:i:s', $e['chat_datetime']);
                }
                echo json_encode(array('messages' => $dato, 'last_time' => $last_date));
                die;
            }
            $i++;
            sleep(2);
        }
    }
    /**
     * Post a mesasge
     */
    public function post_message() {
        $is_g = (int) $this->input->post('is_guest');
        if (empty($is_g)) {
            /** check if its a banned user **/
            $params = array(
                'stream' => 'active_users',
                'namespace' => 'chatjs',
                'where' => "user_id_c = '" . $this->current_user->id . "'"
            );
            $entries = $this->streams->entries->get_entries($params);
            if ($entries['total'] > 0) {
                $entry_data = array(
                    'last_activity_u_a' => strtotime(date('Y-m-d H:i:s')),
                );
                $this->streams->entries->update_entry($entries['entries'][0]['id'],$entry_data, 'active_users', 'chatjs');
            }else{
                $entry_data = array(
                    'user_id_c' => $this->current_user->id,
                    'user_b_ip' => $_SERVER['REMOTE_ADDR'],
                    'last_activity_u_a' => strtotime(date('Y-m-d H:i:s')),
                );
                $this->streams->entries->insert_entry($entry_data, 'active_users', 'chatjs');
            }
        }else{
            $params = array(
                'stream' => 'active_users',
                'namespace' => 'chatjs',
                'where' => "user_b_ip = '" . $_SERVER['REMOTE_ADDR'] . "'"
            );
            $entries = $this->streams->entries->get_entries($params);
            if ($entries['total'] > 0) {
                $entry_data = array(
                    'last_activity_u_a' => strtotime(date('Y-m-d H:i:s')),
                );
                $this->streams->entries->update_entry($entries['entries'][0]['id'],$entry_data, 'active_users', 'chatjs');
            }else{
                $entry_data = array(
                    'user_nick_c' => $this->input->post('gest_name'),
                    'user_b_ip' => $_SERVER['REMOTE_ADDR'],
                    'last_activity_u_a' => strtotime(date('Y-m-d H:i:s')),
                );
                $this->streams->entries->insert_entry($entry_data, 'active_users', 'chatjs');
            }
        }
        $entry_data = array(
            'message' => $this->input->post('message'),
            'is_gest' => $is_g,
            'guest_name' => $this->input->post('gest_name'),
            'chat_datetime' => strtotime(date('Y-m-d H:i:s'))
        );
        $this->streams->entries->insert_entry($entry_data, 'chatjs', $this->namespace);
        echo json_encode(array('status' => true));
    }

}