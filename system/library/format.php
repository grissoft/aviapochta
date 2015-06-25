<?php
class Format {
    
        private $nums = array('0','1','2','3','4','5','6','7','8','9');
        private $full_nums = array('0','1','2','3','4','5','6','7','8','9', '-', '+', ',', '.');
        private $one_day = 86400;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->currency = $registry->get('currency');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');
		$this->length = $registry->get('length');
	}
        
        public function formatNumbers($value, $only_numbers = false) {
                $res = '';
                if($only_numbers) {
                        $patern = $this->nums;
                } else {
                        $patern = $this->full_nums;
                }
                for($i = 0; $i < utf8_strlen($value); $i++) {
                        $a = utf8_substr($value, $i, 1);
                        if(in_array($a, $patern)) {
                                $res .= $a;
                        }
                }
                return $res;
        }
        
        public function formatCustomer($text, $customer) {
		$find = array(
			'{customer_firstname}',
			'{customer_lastname}',
			'{customer_name}',
			'{customer_id}',
			'{customer_login}',
			'{customer_password}'
		);

		$replace = array(
			'customer_firstname'    => $customer['firstname'],
			'customer_lastname'     => $customer['lastname'],
			'customer_name'         => $customer['firstname'] . ' ' . $customer['lastname'],
            'customer_id'           => $customer['customer_number'],
			'customer_login'        => $customer['email'],
			'customer_password'     => $customer['password']
		);

		$text = trim(str_replace($find, $replace, $text));
                return $text;
        }

        public function formatPack($text, $pack) {
		$find = array(
			'{pack_id}',
			'{pack_product_count}',
			'{pack_total}'
		);

		$replace = array(
			'pack_id'               => $pack['pack_number'],
			'pack_product_count'    => $pack['product_count'],
			'pack_total'            => $this->currency->format($pack['total'], $pack['currency_code'], $pack['currency_value'])
		);

		$text = trim(str_replace($find, $replace, $text));
                return $text;
        }
        
        public function formatDate($text) {
		$find = array(
			'{today}',
			'{next_sunday}',
			'{next_monday}',
			'{next_tueday}',
			'{next_wednesday}',
			'{next_thirthday}',
			'{next_friday}',
			'{next_saturday}'
		);
                
                for($i = 1; $i <= 60; $i++) {
                        $find[] = '{today+' . $i . '}';
			$find[] = '{next_sunday+' . $i . '}';
			$find[] = '{next_monday+' . $i . '}';
			$find[] = '{next_tueday+' . $i . '}';
			$find[] = '{next_wednesday+' . $i . '}';
			$find[] = '{next_thirthday+' . $i . '}';
			$find[] = '{next_friday+' . $i . '}';
			$find[] = '{next_saturday+' . $i . '}';
                }

                $replace = array(
			'today'             => date('d.m.Y'),
			'next_sunday'       => date('d.m.Y', $this->getNextDate('next_sunday')),
			'next_monday'       => date('d.m.Y', $this->getNextDate('next_monday')),
			'next_tueday'       => date('d.m.Y', $this->getNextDate('next_tueday')),
			'next_wednesday'    => date('d.m.Y', $this->getNextDate('next_wednesday')),
			'next_thirthday'    => date('d.m.Y', $this->getNextDate('next_thirthday')),
			'next_friday'       => date('d.m.Y', $this->getNextDate('next_friday')),
			'next_saturday'     => date('d.m.Y', $this->getNextDate('next_saturday'))
		);

                for($i = 1; $i <= 60; $i++) {
			$replace['today+' . $i]             = date('d.m.Y', time() + $i * $this->one_day);
			$replace['next_sunday+' . $i]       = date('d.m.Y', $this->getNextDate('next_sunday', time() + $i * $this->one_day));
			$replace['next_monday+' . $i]       = date('d.m.Y', $this->getNextDate('next_monday', time() + $i * $this->one_day));
			$replace['next_tueday+' . $i]       = date('d.m.Y', $this->getNextDate('next_tueday', time() + $i * $this->one_day));
			$replace['next_wednesday+' . $i]    = date('d.m.Y', $this->getNextDate('next_wednesday', time() + $i * $this->one_day));
			$replace['next_thirthday+' . $i]    = date('d.m.Y', $this->getNextDate('next_thirthday', time() + $i * $this->one_day));
			$replace['next_friday+' . $i]       = date('d.m.Y', $this->getNextDate('next_friday', time() + $i * $this->one_day));
			$replace['next_saturday+' . $i]     = date('d.m.Y', $this->getNextDate('next_saturday', time() + $i * $this->one_day));
                }
                
		$text = trim(str_replace($find, $replace, $text));
                return $text;
        }
        
        public function getNextDate($type, $start = false) {
                if(!$start) {
                        $start = time();
                }
                $dow = array(
                        'next_sunday'       => 0,
                        'next_monday'       => 1,
                        'next_tueday'       => 2,
                        'next_wednesday'    => 3,
                        'next_thirthday'    => 4,
                        'next_friday'       => 5,
                        'next_saturday'     => 6
                );
                $w = isset($dow[$type]) ? $dow[$type] : 0;
                for($i = 0; $i < 7; $i++) {
                        $cw = date('w', $start + $i * $this->one_day);
                        if($cw == $w) {
                                break;
                        }
                }
                return $start + $i * $this->one_day;
        }

}
