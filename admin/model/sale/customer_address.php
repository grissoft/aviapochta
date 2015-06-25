<?php
class ModelSaleCustomerAddress extends Model {
	public function addCustomerAddress($data) {
        
       if (isset($data['address'])) {
            $address = $data['address'];
            $this->db->query("INSERT INTO " . DB_PREFIX . "address SET 
                sandbox = ".$this->user->getSandbox().",
                address_id = '" . (int)$address['address_id'] . "',
                customer_id = '0',
                firstname = '" . $this->db->escape($address['firstname']) . "',
                lastname = '" . $this->db->escape($address['lastname']) . "', 
                company = '', 
                address_1 = '" . $this->db->escape($address['address_1']) . "', 
                region = '" . $this->db->escape($address['region']) . "', 
                dom = '" . $this->db->escape($address['dom']) . "',
                kv = '" . $this->db->escape($address['kv']) . "',
                city = '" . $this->db->escape($address['city']) . "',
                postcode = '', country_id = '',
                 zone_id = '".(int)$address['zone_id']."', 
                custom_field = '" . $this->db->escape(isset($address['custom_field']) ? serialize($address['custom_field']) : '') . "'");
        }
	}

	public function editCustomerAddress($customer_address_id, $data) {
        
        if (isset($data['address'])) {
            $address = $data['address'];
            $this->db->query("UPDATE " . DB_PREFIX . "address SET 
                customer_id = '0',
                firstname = '" . $this->db->escape($address['firstname']) . "',
                lastname = '" . $this->db->escape($address['lastname']) . "', 
                company = '', 
                address_1 = '" . $this->db->escape($address['address_1']) . "', 
                region = '" . $this->db->escape($address['region']) . "', 
                dom = '" . $this->db->escape($address['dom']) . "',
                kv = '" . $this->db->escape($address['kv']) . "',
                city = '" . $this->db->escape($address['city']) . "',
                postcode = '', country_id = '',
                 zone_id = '".(int)$address['zone_id']."', 
                custom_field = '" . $this->db->escape(isset($address['custom_field']) ? serialize($address['custom_field']) : '') . "'
                WHERE  address_id = '" . (int)$customer_address_id . "'");
        }
		
	}

	public function deleteCustomerAddress($customer_address_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$customer_address_id . "'");
	}

	public function getCustomerAddress($customer_address_id) {
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$customer_address_id . "'");

		return $query->row;
	}

	public function getCustomerAddresses($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "address a WHERE customer_id = 0 AND sandbox = ".$this->user->getSandbox()." ";

		$sort_data = array(
			'a.firstname'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY a.city";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}


	public function getTotalCustomerAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address  WHERE customer_id = 0 AND sandbox = ".$this->user->getSandbox());

		return $query->row['total'];
	}
}