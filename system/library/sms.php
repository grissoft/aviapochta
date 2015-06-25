<?php
class Sms {
    
        private $url = 'http://turbosms.in.ua/api/wsdl.html';
        private $client;
        private $authResult = -1;
        private $test = 0; // Имитация отправки
    
	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');
		$this->length = $registry->get('length');
		$this->format = $registry->get('format');
	        $this->config_sms = $this->config->get('config_sms');
        }
        
        public function send($options = array()) {
                if($this->auth($options)) {
                        if(empty($options['destination'])) {
                                $telephone = isset($options['telephone']) ? $options['telephone'] : '';
                                $telephones = array();
                                if(is_array($telephone)) {
                                        foreach($telephone as $phone) {
                                                $clear_phone = $this->format->formatNumbers($phone, true);
                                                if(utf8_strlen($clear_phone) == 12 && count($telephones) < 3) {
                                                        $telephones[] = '+' . $clear_phone;
                                                }
                                        }
                                } else {
                                        $clear_phone = $this->format->formatNumbers($telephone, true);
                                        if(utf8_strlen($clear_phone) == 12) {
                                                $telephones[] = '+' . $clear_phone;
                                        }
                                }
                                if(!$telephones) {
                                        return false;
                                }
                                $telephones = implode(',', $telephones);
                        } else {
                                $telephones = $options['destination'];
                        }
                        
                        // Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8 
                        $text = isset($options['text']) ? $options['text'] : ''; 

                        $sender = !empty($options['sender']) ? $options['sender'] : $this->config_sms['sender'];
                        $sender = trim($sender) ? $sender : 'JustBuy.ua';
                        if(utf8_strlen($sender) > 11) {
                                $sender = utf8_substr($sender, 0, 11);
                        }
                        
                        // Данные для отправки 
                        $sms = Array ( 
                                'sender' => $sender, 
                                'destination' => $telephones, 
                                'text' => $text 
                        );
                        
                        // Отправляем сообщение. 
                        if($this->test) {
                                $result = new stdClass();
                                $result->SendSMSResult = 'test mode';

                        } else {
                                $result = $this->client->SendSMS ($sms);
                        }
                        if(!empty($options['sms_id'])) {
                                $this->db->query("UPDATE `" . DB_PREFIX . "sms` SET date_send = NOW(), `result` = '" . $this->db->escape(serialize($result->SendSMSResult)) . "', `status` = 1 WHERE sms_id = '" . (int)$options['sms_id'] . "'");
                        } else {
                                $this->db->query("INSERT INTO `" . DB_PREFIX . "sms` SET date_send = NOW(), date_added = NOW(), `params` = '" . $this->db->escape(serialize($sms)) . "', `result` = '" . $this->db->escape(serialize($result->SendSMSResult)) . "', `message` = '" . $this->db->escape($text) . "', `sender` = '" . $this->db->escape($sender) . "', `destination` = '" . $this->db->escape($telephones) . "', `status` = 1");
                        }
                        return true;
                } else {
                        return false;
                }
        }
        
        public function auth($options = array()) {
                if($this->authResult == -1) {
                        // Подключаемся к серверу 
                        $this->client = new SoapClient ($this->url);

                        // Данные авторизации 
                        $auth = Array ( 
                                'login' => isset($options['login']) ? $options['login'] : $this->config_sms['login'], 
                                'password' => isset($options['password']) ? $options['password'] : $this->config_sms['password']
                        ); 

                        // Авторизируемся на сервере 
                        $result = $this->client->Auth ($auth); 

                        $this->authResult = $result->AuthResult == 'Вы успешно авторизировались';
                }
                
                // Результат авторизации 
                return $this->authResult;
                
        }
        
        public function add($options = array()) {
                $telephone = isset($options['telephone']) ? $options['telephone'] : '';
                $telephones = array();
                if(is_array($telephone)) {
                        foreach($telephone as $phone) {
                                $clear_phone = $this->format->formatNumbers($phone, true);
                                if(utf8_strlen($clear_phone) == 12 && count($telephones) < 3) {
                                        $telephones[] = '+' . $clear_phone;
                                }
                        }
                } else {
                        $clear_phone = $this->format->formatNumbers($telephone, true);
                        if(utf8_strlen($clear_phone) == 12) {
                                $telephones[] = '+' . $clear_phone;
                        }
                }
                if(!$telephones) {
                        return false;
                }
                $telephones = implode(',', $telephones);
                        
                // Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8 
                $text = isset($options['text']) ? $options['text'] : ''; 

                $sender = !empty($options['sender']) ? $options['sender'] : $this->config_sms['sender'];
                $sender = trim($sender) ? $sender : 'JustBuy.ua';
                if(utf8_strlen($sender) > 11) {
                        $sender = utf8_substr($sender, 0, 11);
                }
                        
                // Данные для отправки 
                $sms = Array ( 
                        'sender' => $sender, 
                        'destination' => $telephones, 
                        'text' => $text 
                );
                $this->db->query("INSERT INTO `" . DB_PREFIX . "sms` SET date_added = NOW(), `params` = '" . $this->db->escape(serialize($sms)) . "', `message` = '" . $this->db->escape($text) . "', `sender` = '" . $this->db->escape($sender) . "', `destination` = '" . $this->db->escape($telephones) . "'");
        }
        
        public function sendNext($limit = 99) {
                
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sms` WHERE status = 0 AND date_locked < NOW() LIMIT " . (int)$limit);
                foreach($query->rows as $row) {
                        if($this->tryLock($row['sms_id'])) {
                                $data = array(
                                    'sender'        => $row['sender'],
                                    'text'          => $row['message'],
                                    'destination'   => $row['destination'],
                                    'sms_id'        => $row['sms_id']
                                );
                                $this->send($data);
                        }
                }
            
        }
        
        private function tryLock($sms_id) {
                $this->db->query("UPDATE `" . DB_PREFIX . "sms` SET date_locked = NOW() + 60 * 15 WHERE status = 0 AND date_locked < NOW() AND sms_id = '" . (int)$sms_id . "'");
                return $this->db->countAffected();
        }
    
}
?>